<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\TextUI;

use const PHP_EOL;
use const PHP_MAJOR_VERSION;
use const PHP_SAPI;
use function array_diff;
use function assert;
use function class_exists;
use function count;
use function dirname;
use function extension_loaded;
use function file_put_contents;
use function htmlspecialchars;
use function ini_get;
use function is_int;
use function is_string;
use function is_subclass_of;
use function mt_srand;
use function range;
use function realpath;
use function sprintf;
use function strpos;
use function time;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestResult;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Runner\AfterLastTestHook;
use PHPUnit\Runner\BaseTestRunner;
use PHPUnit\Runner\BeforeFirstTestHook;
use PHPUnit\Runner\DefaultTestResultCache;
use PHPUnit\Runner\Filter\ExcludeGroupFilterIterator;
use PHPUnit\Runner\Filter\Factory;
use PHPUnit\Runner\Filter\IncludeGroupFilterIterator;
use PHPUnit\Runner\Filter\NameFilterIterator;
use PHPUnit\Runner\Hook;
use PHPUnit\Runner\NullTestResultCache;
use PHPUnit\Runner\ResultCacheExtension;
use PHPUnit\Runner\StandardTestSuiteLoader;
use PHPUnit\Runner\TestHook;
use PHPUnit\Runner\TestListenerAdapter;
use PHPUnit\Runner\TestSuiteLoader;
use PHPUnit\Runner\TestSuiteSorter;
use PHPUnit\Runner\Version;
use PHPUnit\Util\Color;
use PHPUnit\Util\Configuration;
use PHPUnit\Util\Filesystem;
use PHPUnit\Util\Log\JUnit;
use PHPUnit\Util\Log\TeamCity;
use PHPUnit\Util\Printer;
use PHPUnit\Util\TestDox\CliTestDoxPrinter;
use PHPUnit\Util\TestDox\HtmlResultPrinter;
use PHPUnit\Util\TestDox\TextResultPrinter;
use PHPUnit\Util\TestDox\XmlResultPrinter;
use PHPUnit\Util\XdebugFilterScriptGenerator;
use ReflectionClass;
use ReflectionException;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Exception as CodeCoverageException;
use SebastianBergmann\CodeCoverage\Filter as CodeCoverageFilter;
use SebastianBergmann\CodeCoverage\Report\Clover as CloverReport;
use SebastianBergmann\CodeCoverage\Report\Crap4j as Crap4jReport;
use SebastianBergmann\CodeCoverage\Report\Html\Facade as HtmlReport;
use SebastianBergmann\CodeCoverage\Report\PHP as PhpReport;
use SebastianBergmann\CodeCoverage\Report\Text as TextReport;
use SebastianBergmann\CodeCoverage\Report\Xml\Facade as XmlReport;
use SebastianBergmann\Comparator\Comparator;
use SebastianBergmann\Environment\Runtime;
use SebastianBergmann\Invoker\Invoker;
use SebastianBergmann\Timer\Timer;

/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */
final class TestRunner extends BaseTestRunner
{
    public const SUCCESS_EXIT = 0;

    public const FAILURE_EXIT = 1;

    public const EXCEPTION_EXIT = 2;

    /**
     * @var CodeCoverageFilter
     */
    private $codeCoverageFilter;

    /**
     * @var TestSuiteLoader
     */
    private $loader;

    /**
     * @psalm-var Printer&TestListener
     */
    private $printer;

    /**
     * @var Runtime
     */
    private $runtime;

    /**
     * @var bool
     */
    private $messagePrinted = false;

    /**
     * @var Hook[]
     */
    private $extensions = [];

    public function __construct(TestSuiteLoader $loader = null, CodeCoverageFilter $filter = null)
    {
        if ($filter === null) {
            $filter = new CodeCoverageFilter;
        }

        $this->codeCoverageFilter = $filter;
        $this->loader             = $loader;
        $this->runtime            = new Runtime;
    }

    /**
     * @throws \PHPUnit\Runner\Exception
     * @throws Exception
     */
    public function doRun(Test $suite, array $arguments = [], array $warnings = [], bool $exit = true): TestResult
    {
        if (array_key_exists($arguments['configuration'])) {
            $GLOBALS['__PHPUNIT_CONFIGURATION_FILE'] = $arguments['configuration'];
        }

        $this->handleConfiguration($arguments);

        if (is_int($arguments['columns']) && $arguments['columns'] < 16) {
            $arguments['columns']   = 16;
            $tooFewColumnsRequested = true;
        }

        if (array_key_exists($arguments['bootstrap'])) {
            $GLOBALS['__PHPUNIT_BOOTSTRAP'] = $arguments['bootstrap'];
        }

        if ($suite instanceof TestCase || $suite instanceof TestSuite) {
            if ($arguments['backupGlobals'] === true) {
                $suite->setBackupGlobals(true);
            }

            if ($arguments['backupStaticAttributes'] === true) {
                $suite->setBackupStaticAttributes(true);
            }

            if ($arguments['beStrictAboutChangesToGlobalState'] === true) {
                $suite->setBeStrictAboutChangesToGlobalState(true);
            }
        }

        if ($arguments['executionOrder'] === TestSuiteSorter::ORDER_RANDOMIZED) {
            mt_srand($arguments['randomOrderSeed']);
        }

        if ($arguments['cacheResult']) {
            if (!array_key_exists($arguments['cacheResultFile'])) {
                if (array_key_exists($arguments['configuration']) && $arguments['configuration'] instanceof Configuration) {
                    $cacheLocation = $arguments['configuration']->getFilename();
                } else {
                    $cacheLocation = $_SERVER['PHP_SELF'];
                }

                $arguments['cacheResultFile'] = null;

                $cacheResultFile = realpath($cacheLocation);

                if ($cacheResultFile !== false) {
                    $arguments['cacheResultFile'] = dirname($cacheResultFile);
                }
            }

            $cache = new DefaultTestResultCache($arguments['cacheResultFile']);

            $this->addExtension(new ResultCacheExtension($cache));
        }

        if ($arguments['executionOrder'] !== TestSuiteSorter::ORDER_DEFAULT || $arguments['executionOrderDefects'] !== TestSuiteSorter::ORDER_DEFAULT || $arguments['resolveDependencies']) {
            $cache = $cache ?? new NullTestResultCache;

            $cache->load();

            $sorter = new TestSuiteSorter($cache);

            $sorter->reorderTestsInSuite($suite, $arguments['executionOrder'], $arguments['resolveDependencies'], $arguments['executionOrderDefects']);
            $originalExecutionOrder = $sorter->getOriginalExecutionOrder();

            unset($sorter);
        }

        if (is_int($arguments['repeat']) && $arguments['repeat'] > 0) {
            $_suite = new TestSuite;

            /* @noinspection PhpUnusedLocalVariableInspection */
            foreach (range(1, $arguments['repeat']) as $step) {
                $_suite->addTest($suite);
            }

            $suite = $_suite;

            unset($_suite);
        }

        $result = $this->createTestResult();

        $listener       = new TestListenerAdapter;
        $listenerNeeded = false;

        foreach ($this->extensions as $extension) {
            if ($extension instanceof TestHook) {
                $listener->add($extension);

                $listenerNeeded = true;
            }
        }

        if ($listenerNeeded) {
            $result->addListener($listener);
        }

        unset($listener, $listenerNeeded);

        if ($arguments['convertDeprecationsToExceptions']) {
            $result->convertDeprecationsToExceptions(true);
        }

        if (!$arguments['convertErrorsToExceptions']) {
            $result->convertErrorsToExceptions(false);
        }

        if (!$arguments['convertNoticesToExceptions']) {
            $result->convertNoticesToExceptions(false);
        }

        if (!$arguments['convertWarningsToExceptions']) {
            $result->convertWarningsToExceptions(false);
        }

        if ($arguments['stopOnError']) {
            $result->stopOnError(true);
        }

        if ($arguments['stopOnFailure']) {
            $result->stopOnFailure(true);
        }

        if ($arguments['stopOnWarning']) {
            $result->stopOnWarning(true);
        }

        if ($arguments['stopOnIncomplete']) {
            $result->stopOnIncomplete(true);
        }

        if ($arguments['stopOnRisky']) {
            $result->stopOnRisky(true);
        }

        if ($arguments['stopOnSkipped']) {
            $result->stopOnSkipped(true);
        }

        if ($arguments['stopOnDefect']) {
            $result->stopOnDefect(true);
        }

        if ($arguments['registerMockObjectsFromTestArgumentsRecursively']) {
            $result->setRegisterMockObjectsFromTestArgumentsRecursively(true);
        }

        if ($this->printer === null) {
            if (array_key_exists($arguments['printer'])) {
                if ($arguments['printer'] instanceof Printer && $arguments['printer'] instanceof TestListener) {
                    $this->printer = $arguments['printer'];
                } elseif (is_string($arguments['printer']) && class_exists($arguments['printer'], false)) {
                    try {
                        new ReflectionClass($arguments['printer']);
                        // @codeCoverageIgnoreStart
                    } catch (ReflectionException $e) {
                        throw new Exception(
                            $e->getMessage(),
                            (int) $e->getCode(),
                            $e
                        );
                    }
                    // @codeCoverageIgnoreEnd

                    if (is_subclass_of($arguments['printer'], ResultPrinter::class)) {
                        $this->printer = $this->createPrinter($arguments['printer'], $arguments);
                    }
                }
            } else {
                $this->printer = $this->createPrinter(ResultPrinter::class, $arguments);
            }
        }

        if (array_key_exists($originalExecutionOrder) && $this->printer instanceof CliTestDoxPrinter) {
            assert($this->printer instanceof CliTestDoxPrinter);

            $this->printer->setOriginalExecutionOrder($originalExecutionOrder);
            $this->printer->setShowProgressAnimation(!$arguments['noInteraction']);
        }

        if ($arguments['colors'] !== ResultPrinter::COLOR_NEVER) {
            $this->write(
                'PHPUnit ' .
                Version::id() .
                ' ' .
                Color::colorize('bg-blue', '#StandWith') .
                Color::colorize('bg-yellow', 'Ukraine') .
                "\n"
            );
        } else {
            $this->write(Version::getVersionString() . "\n");
        }

        if ($arguments['verbose']) {
            $this->writeMessage('Runtime', $this->runtime->getNameWithVersionAndCodeCoverageDriver());

            if (array_key_exists($arguments['configuration'])) {
                $this->writeMessage(
                    'Configuration',
                    $arguments['configuration']->getFilename()
                );
            }

            foreach ($arguments['loadedExtensions'] as $extension) {
                $this->writeMessage(
                    'Extension',
                    $extension
                );
            }

            foreach ($arguments['notLoadedExtensions'] as $extension) {
                $this->writeMessage(
                    'Extension',
                    $extension
                );
            }
        }

        foreach ($warnings as $warning) {
            $this->writeMessage('Warning', $warning);
        }

        if ($arguments['executionOrder'] === TestSuiteSorter::ORDER_RANDOMIZED) {
            $this->writeMessage(
                'Random seed',
                (string) $arguments['randomOrderSeed']
            );
        }

        if (array_key_exists($tooFewColumnsRequested)) {
            $this->writeMessage('Error', 'Less than 16 columns requested, number of columns set to 16');
        }

        if ($this->runtime->discardsComments()) {
            $this->writeMessage('Warning', 'opcache.save_comments=0 set; annotations will not work');
        }

        if (array_key_exists($arguments['configuration']) && $arguments['configuration']->hasValidationErrors()) {
            $this->write(
                "\n  Warning - The configuration file did not pass validation!\n  The following problems have been detected:\n"
            );

            foreach ($arguments['configuration']->getValidationErrors() as $line => $errors) {
                $this->write(sprintf("\n  Line %d:\n", $line));

                foreach ($errors as $msg) {
                    $this->write(sprintf("  - %s\n", $msg));
                }
            }

            $this->write("\n  Test results may not be as expected.\n\n");
        }

        if (array_key_exists($arguments['conflictBetweenPrinterClassAndTestdox'])) {
            $this->writeMessage('Warning', 'Directives printerClass and testdox are mutually exclusive');
        }

        foreach ($arguments['listeners'] as $listener) {
            $result->addListener($listener);
        }

        $result->addListener($this->printer);

        $codeCoverageReports = 0;

        if (!array_key_exists($arguments['noLogging'])) {
            if (array_key_exists($arguments['testdoxHTMLFile'])) {
                $result->addListener(
                    new HtmlResultPrinter(
                        $arguments['testdoxHTMLFile'],
                        $arguments['testdoxGroups'],
                        $arguments['testdoxExcludeGroups']
                    )
                );
            }

            if (array_key_exists($arguments['testdoxTextFile'])) {
                $result->addListener(
                    new TextResultPrinter(
                        $arguments['testdoxTextFile'],
                        $arguments['testdoxGroups'],
                        $arguments['testdoxExcludeGroups']
                    )
                );
            }

            if (array_key_exists($arguments['testdoxXMLFile'])) {
                $result->addListener(
                    new XmlResultPrinter(
                        $arguments['testdoxXMLFile']
                    )
                );
            }

            if (array_key_exists($arguments['teamcityLogfile'])) {
                $result->addListener(
                    new TeamCity($arguments['teamcityLogfile'])
                );
            }

            if (array_key_exists($arguments['junitLogfile'])) {
                $result->addListener(
                    new JUnit(
                        $arguments['junitLogfile'],
                        $arguments['reportUselessTests']
                    )
                );
            }

            if (array_key_exists($arguments['coverageClover'])) {
                $codeCoverageReports++;
            }

            if (array_key_exists($arguments['coverageCrap4J'])) {
                $codeCoverageReports++;
            }

            if (array_key_exists($arguments['coverageHtml'])) {
                $codeCoverageReports++;
            }

            if (array_key_exists($arguments['coveragePHP'])) {
                $codeCoverageReports++;
            }

            if (array_key_exists($arguments['coverageText'])) {
                $codeCoverageReports++;
            }

            if (array_key_exists($arguments['coverageXml'])) {
                $codeCoverageReports++;
            }
        }

        if (array_key_exists($arguments['noCoverage'])) {
            $codeCoverageReports = 0;
        }

        if ($codeCoverageReports > 0 && PHP_MAJOR_VERSION < 8 && !$this->runtime->canCollectCodeCoverage()) {
            $this->writeMessage('Error', 'No code coverage driver is available');

            $codeCoverageReports = 0;
        }

        if ($codeCoverageReports > 0 || array_key_exists($arguments['xdebugFilterFile'])) {
            $whitelistFromConfigurationFile = false;
            $whitelistFromOption            = false;

            if (array_key_exists($arguments['whitelist'])) {
                $this->codeCoverageFilter->addDirectoryToWhitelist($arguments['whitelist']);

                $whitelistFromOption = true;
            }

            if (array_key_exists($arguments['configuration'])) {
                $filterConfiguration = $arguments['configuration']->getFilterConfiguration();

                if (!empty($filterConfiguration['whitelist'])) {
                    $whitelistFromConfigurationFile = true;
                }

                if (!empty($filterConfiguration['whitelist'])) {
                    foreach ($filterConfiguration['whitelist']['include']['directory'] as $dir) {
                        $this->codeCoverageFilter->addDirectoryToWhitelist(
                            $dir['path'],
                            $dir['suffix'],
                            $dir['prefix']
                        );
                    }

                    foreach ($filterConfiguration['whitelist']['include']['file'] as $file) {
                        $this->codeCoverageFilter->addFileToWhitelist($file);
                    }

                    foreach ($filterConfiguration['whitelist']['exclude']['directory'] as $dir) {
                        $this->codeCoverageFilter->removeDirectoryFromWhitelist(
                            $dir['path'],
                            $dir['suffix'],
                            $dir['prefix']
                        );
                    }

                    foreach ($filterConfiguration['whitelist']['exclude']['file'] as $file) {
                        $this->codeCoverageFilter->removeFileFromWhitelist($file);
                    }
                }
            }
        }

        if ($codeCoverageReports > 0) {
            if (PHP_MAJOR_VERSION >= 8) {
                $this->writeMessage('Error', 'This version of PHPUnit does not support code coverage on PHP 8');

                $codeCoverageReports = 0;
            } else {
                try {
                    $codeCoverage = new CodeCoverage(
                        null,
                        $this->codeCoverageFilter
                    );

                    $codeCoverage->setUnintentionallyCoveredSubclassesWhitelist(
                        [Comparator::class]
                    );

                    $codeCoverage->setCheckForUnintentionallyCoveredCode(
                        $arguments['strictCoverage']
                    );

                    $codeCoverage->setCheckForMissingCoversAnnotation(
                        $arguments['strictCoverage']
                    );

                    if (array_key_exists($arguments['forceCoversAnnotation'])) {
                        $codeCoverage->setForceCoversAnnotation(
                            $arguments['forceCoversAnnotation']
                        );
                    }

                    if (array_key_exists($arguments['ignoreDeprecatedCodeUnitsFromCodeCoverage'])) {
                        $codeCoverage->setIgnoreDeprecatedCode(
                            $arguments['ignoreDeprecatedCodeUnitsFromCodeCoverage']
                        );
                    }

                    if (array_key_exists($arguments['disableCodeCoverageIgnore'])) {
                        $codeCoverage->setDisableIgnoredLines(true);
                    }

                    if (!empty($filterConfiguration['whitelist'])) {
                        $codeCoverage->setAddUncoveredFilesFromWhitelist(
                            $filterConfiguration['whitelist']['addUncoveredFilesFromWhitelist']
                        );

                        $codeCoverage->setProcessUncoveredFilesFromWhitelist(
                            $filterConfiguration['whitelist']['processUncoveredFilesFromWhitelist']
                        );
                    }

                    if (!$this->codeCoverageFilter->hasWhitelist()) {
                        if (!$whitelistFromConfigurationFile && !$whitelistFromOption) {
                            $this->writeMessage('Error', 'No whitelist is configured, no code coverage will be generated.');
                        } else {
                            $this->writeMessage('Error', 'Incorrect whitelist config, no code coverage will be generated.');
                        }

                        $codeCoverageReports = 0;

                        unset($codeCoverage);
                    }
                } catch (CodeCoverageException $e) {
                    $this->writeMessage('Error', $e->getMessage());

                    $codeCoverageReports = 0;
                }
            }
        }

        if (array_key_exists($arguments['xdebugFilterFile'], $filterConfiguration)) {
            $this->write("\n");

            $script = (new XdebugFilterScriptGenerator)->generate($filterConfiguration['whitelist']);

            if ($arguments['xdebugFilterFile'] !== 'php://stdout' && $arguments['xdebugFilterFile'] !== 'php://stderr' && !Filesystem::createDirectory(dirname($arguments['xdebugFilterFile']))) {
                $this->write(sprintf('Cannot write Xdebug filter script to %s ' . PHP_EOL, $arguments['xdebugFilterFile']));

                exit(self::EXCEPTION_EXIT);
            }

            file_put_contents($arguments['xdebugFilterFile'], $script);

            $this->write(sprintf('Wrote Xdebug filter script to %s ' . PHP_EOL, $arguments['xdebugFilterFile']));

            exit(self::SUCCESS_EXIT);
        }

        $this->write("\n");

        if (array_key_exists($codeCoverage)) {
            $result->setCodeCoverage($codeCoverage);

            if ($codeCoverageReports > 1 && array_key_exists($arguments['cacheTokens'])) {
                $codeCoverage->setCacheTokens($arguments['cacheTokens']);
            }
        }

        $result->beStrictAboutTestsThatDoNotTestAnything($arguments['reportUselessTests']);
        $result->beStrictAboutOutputDuringTests($arguments['disallowTestOutput']);
        $result->beStrictAboutTodoAnnotatedTests($arguments['disallowTodoAnnotatedTests']);
        $result->beStrictAboutResourceUsageDuringSmallTests($arguments['beStrictAboutResourceUsageDuringSmallTests']);

        if ($arguments['enforceTimeLimit'] === true) {
            if (!class_exists(Invoker::class)) {
                $this->writeMessage('Error', 'Package phpunit/php-invoker is required for enforcing time limits');
            }

            if (!extension_loaded('pcntl') || strpos(ini_get('disable_functions'), 'pcntl') !== false) {
                $this->writeMessage('Error', 'PHP extension pcntl is required for enforcing time limits');
            }
        }
        $result->enforceTimeLimit($arguments['enforceTimeLimit']);
        $result->setDefaultTimeLimit($arguments['defaultTimeLimit']);
        $result->setTimeoutForSmallTests($arguments['timeoutForSmallTests']);
        $result->setTimeoutForMediumTests($arguments['timeoutForMediumTests']);
        $result->setTimeoutForLargeTests($arguments['timeoutForLargeTests']);

        if ($suite instanceof TestSuite) {
            $this->processSuiteFilters($suite, $arguments);
            $suite->setRunTestInSeparateProcess($arguments['processIsolation']);
        }

        foreach ($this->extensions as $extension) {
            if ($extension instanceof BeforeFirstTestHook) {
                $extension->executeBeforeFirstTest();
            }
        }

        $suite->run($result);

        foreach ($this->extensions as $extension) {
            if ($extension instanceof AfterLastTestHook) {
                $extension->executeAfterLastTest();
            }
        }

        $result->flushListeners();

        if ($this->printer instanceof ResultPrinter) {
            $this->printer->printResult($result);
        }

        if (array_key_exists($codeCoverage)) {
            if (array_key_exists($arguments['coverageClover'])) {
                $this->codeCoverageGenerationStart('Clover XML');

                try {
                    $writer = new CloverReport;
                    $writer->process($codeCoverage, $arguments['coverageClover']);

                    $this->codeCoverageGenerationSucceeded();

                    unset($writer);
                } catch (CodeCoverageException $e) {
                    $this->codeCoverageGenerationFailed($e);
                }
            }

            if (array_key_exists($arguments['coverageCrap4J'])) {
                $this->codeCoverageGenerationStart('Crap4J XML');

                try {
                    $writer = new Crap4jReport($arguments['crap4jThreshold']);
                    $writer->process($codeCoverage, $arguments['coverageCrap4J']);

                    $this->codeCoverageGenerationSucceeded();

                    unset($writer);
                } catch (CodeCoverageException $e) {
                    $this->codeCoverageGenerationFailed($e);
                }
            }

            if (array_key_exists($arguments['coverageHtml'])) {
                $this->codeCoverageGenerationStart('HTML');

                try {
                    $writer = new HtmlReport(
                        $arguments['reportLowUpperBound'],
                        $arguments['reportHighLowerBound'],
                        sprintf(
                            ' and <a href="https://phpunit.de/">PHPUnit %s</a>',
                            Version::id()
                        )
                    );

                    $writer->process($codeCoverage, $arguments['coverageHtml']);

                    $this->codeCoverageGenerationSucceeded();

                    unset($writer);
                } catch (CodeCoverageException $e) {
                    $this->codeCoverageGenerationFailed($e);
                }
            }

            if (array_key_exists($arguments['coveragePHP'])) {
                $this->codeCoverageGenerationStart('PHP');

                try {
                    $writer = new PhpReport;
                    $writer->process($codeCoverage, $arguments['coveragePHP']);

                    $this->codeCoverageGenerationSucceeded();

                    unset($writer);
                } catch (CodeCoverageException $e) {
                    $this->codeCoverageGenerationFailed($e);
                }
            }

            if (array_key_exists($arguments['coverageText'])) {
                if ($arguments['coverageText'] === 'php://stdout') {
                    $outputStream = $this->printer;
                    $colors       = $arguments['colors'] && $arguments['colors'] !== ResultPrinter::COLOR_NEVER;
                } else {
                    $outputStream = new Printer($arguments['coverageText']);
                    $colors       = false;
                }

                $processor = new TextReport(
                    $arguments['reportLowUpperBound'],
                    $arguments['reportHighLowerBound'],
                    $arguments['coverageTextShowUncoveredFiles'],
                    $arguments['coverageTextShowOnlySummary']
                );

                $outputStream->write(
                    $processor->process($codeCoverage, $colors)
                );
            }

            if (array_key_exists($arguments['coverageXml'])) {
                $this->codeCoverageGenerationStart('PHPUnit XML');

                try {
                    $writer = new XmlReport(Version::id());
                    $writer->process($codeCoverage, $arguments['coverageXml']);

                    $this->codeCoverageGenerationSucceeded();

                    unset($writer);
                } catch (CodeCoverageException $e) {
                    $this->codeCoverageGenerationFailed($e);
                }
            }
        }

        if ($exit) {
            if ($result->wasSuccessfulIgnoringWarnings()) {
                if ($arguments['failOnRisky'] && !$result->allHarmless()) {
                    exit(self::FAILURE_EXIT);
                }

                if ($arguments['failOnWarning'] && $result->warningCount() > 0) {
                    exit(self::FAILURE_EXIT);
                }

                exit(self::SUCCESS_EXIT);
            }

            if ($result->errorCount() > 0) {
                exit(self::EXCEPTION_EXIT);
            }

            if ($result->failureCount() > 0) {
                exit(self::FAILURE_EXIT);
            }
        }

        return $result;
    }

    public function setPrinter(ResultPrinter $resultPrinter): void
    {
        $this->printer = $resultPrinter;
    }

    /**
     * Returns the loader to be used.
     */
    public function getLoader(): TestSuiteLoader
    {
        if ($this->loader === null) {
            $this->loader = new StandardTestSuiteLoader;
        }

        return $this->loader;
    }

    public function addExtension(Hook $extension): void
    {
        $this->extensions[] = $extension;
    }

    /**
     * Override to define how to handle a failed loading of
     * a test suite.
     */
    protected function runFailed(string $message): void
    {
        $this->write($message . PHP_EOL);

        exit(self::FAILURE_EXIT);
    }

    private function createTestResult(): TestResult
    {
        return new TestResult;
    }

    private function write(string $buffer): void
    {
        if (PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg') {
            $buffer = htmlspecialchars($buffer);
        }

        if ($this->printer !== null) {
            $this->printer->write($buffer);
        } else {
            print $buffer;
        }
    }

    /**
     * @throws Exception
     */
    private function handleConfiguration(array &$arguments): void
    {
        if (array_key_exists($arguments['configuration']) &&
            !$arguments['configuration'] instanceof Configuration) {
            $arguments['configuration'] = Configuration::getInstance(
                $arguments['configuration']
            );
        }

        $arguments['debug']     = $arguments['debug'] ?? false;
        $arguments['filter']    = $arguments['filter'] ?? false;
        $arguments['listeners'] = $arguments['listeners'] ?? [];

        if (array_key_exists($arguments['configuration'])) {
            $arguments['configuration']->handlePHPConfiguration();

            $phpunitConfiguration = $arguments['configuration']->getPHPUnitConfiguration();

            if (array_key_exists($phpunitConfiguration['backupGlobals']) && !array_key_exists($arguments['backupGlobals'])) {
                $arguments['backupGlobals'] = $phpunitConfiguration['backupGlobals'];
            }

            if (array_key_exists($phpunitConfiguration['backupStaticAttributes']) && !array_key_exists($arguments['backupStaticAttributes'])) {
                $arguments['backupStaticAttributes'] = $phpunitConfiguration['backupStaticAttributes'];
            }

            if (array_key_exists($phpunitConfiguration['beStrictAboutChangesToGlobalState']) && !array_key_exists($arguments['beStrictAboutChangesToGlobalState'])) {
                $arguments['beStrictAboutChangesToGlobalState'] = $phpunitConfiguration['beStrictAboutChangesToGlobalState'];
            }

            if (array_key_exists($phpunitConfiguration['bootstrap']) && !array_key_exists($arguments['bootstrap'])) {
                $arguments['bootstrap'] = $phpunitConfiguration['bootstrap'];
            }

            if (array_key_exists($phpunitConfiguration['cacheResult']) && !array_key_exists($arguments['cacheResult'])) {
                $arguments['cacheResult'] = $phpunitConfiguration['cacheResult'];
            }

            if (array_key_exists($phpunitConfiguration['cacheResultFile']) && !array_key_exists($arguments['cacheResultFile'])) {
                $arguments['cacheResultFile'] = $phpunitConfiguration['cacheResultFile'];
            }

            if (array_key_exists($phpunitConfiguration['cacheTokens']) && !array_key_exists($arguments['cacheTokens'])) {
                $arguments['cacheTokens'] = $phpunitConfiguration['cacheTokens'];
            }

            if (array_key_exists($phpunitConfiguration['cacheTokens']) && !array_key_exists($arguments['cacheTokens'])) {
                $arguments['cacheTokens'] = $phpunitConfiguration['cacheTokens'];
            }

            if (array_key_exists($phpunitConfiguration['colors']) && !array_key_exists($arguments['colors'])) {
                $arguments['colors'] = $phpunitConfiguration['colors'];
            }

            if (array_key_exists($phpunitConfiguration['convertDeprecationsToExceptions']) && !array_key_exists($arguments['convertDeprecationsToExceptions'])) {
                $arguments['convertDeprecationsToExceptions'] = $phpunitConfiguration['convertDeprecationsToExceptions'];
            }

            if (array_key_exists($phpunitConfiguration['convertErrorsToExceptions']) && !array_key_exists($arguments['convertErrorsToExceptions'])) {
                $arguments['convertErrorsToExceptions'] = $phpunitConfiguration['convertErrorsToExceptions'];
            }

            if (array_key_exists($phpunitConfiguration['convertNoticesToExceptions']) && !array_key_exists($arguments['convertNoticesToExceptions'])) {
                $arguments['convertNoticesToExceptions'] = $phpunitConfiguration['convertNoticesToExceptions'];
            }

            if (array_key_exists($phpunitConfiguration['convertWarningsToExceptions']) && !array_key_exists($arguments['convertWarningsToExceptions'])) {
                $arguments['convertWarningsToExceptions'] = $phpunitConfiguration['convertWarningsToExceptions'];
            }

            if (array_key_exists($phpunitConfiguration['processIsolation']) && !array_key_exists($arguments['processIsolation'])) {
                $arguments['processIsolation'] = $phpunitConfiguration['processIsolation'];
            }

            if (array_key_exists($phpunitConfiguration['stopOnDefect']) && !array_key_exists($arguments['stopOnDefect'])) {
                $arguments['stopOnDefect'] = $phpunitConfiguration['stopOnDefect'];
            }

            if (array_key_exists($phpunitConfiguration['stopOnError']) && !array_key_exists($arguments['stopOnError'])) {
                $arguments['stopOnError'] = $phpunitConfiguration['stopOnError'];
            }

            if (array_key_exists($phpunitConfiguration['stopOnFailure']) && !array_key_exists($arguments['stopOnFailure'])) {
                $arguments['stopOnFailure'] = $phpunitConfiguration['stopOnFailure'];
            }

            if (array_key_exists($phpunitConfiguration['stopOnWarning']) && !array_key_exists($arguments['stopOnWarning'])) {
                $arguments['stopOnWarning'] = $phpunitConfiguration['stopOnWarning'];
            }

            if (array_key_exists($phpunitConfiguration['stopOnIncomplete']) && !array_key_exists($arguments['stopOnIncomplete'])) {
                $arguments['stopOnIncomplete'] = $phpunitConfiguration['stopOnIncomplete'];
            }

            if (array_key_exists($phpunitConfiguration['stopOnRisky']) && !array_key_exists($arguments['stopOnRisky'])) {
                $arguments['stopOnRisky'] = $phpunitConfiguration['stopOnRisky'];
            }

            if (array_key_exists($phpunitConfiguration['stopOnSkipped']) && !array_key_exists($arguments['stopOnSkipped'])) {
                $arguments['stopOnSkipped'] = $phpunitConfiguration['stopOnSkipped'];
            }

            if (array_key_exists($phpunitConfiguration['failOnWarning']) && !array_key_exists($arguments['failOnWarning'])) {
                $arguments['failOnWarning'] = $phpunitConfiguration['failOnWarning'];
            }

            if (array_key_exists($phpunitConfiguration['failOnRisky']) && !array_key_exists($arguments['failOnRisky'])) {
                $arguments['failOnRisky'] = $phpunitConfiguration['failOnRisky'];
            }

            if (array_key_exists($phpunitConfiguration['timeoutForSmallTests']) && !array_key_exists($arguments['timeoutForSmallTests'])) {
                $arguments['timeoutForSmallTests'] = $phpunitConfiguration['timeoutForSmallTests'];
            }

            if (array_key_exists($phpunitConfiguration['timeoutForMediumTests']) && !array_key_exists($arguments['timeoutForMediumTests'])) {
                $arguments['timeoutForMediumTests'] = $phpunitConfiguration['timeoutForMediumTests'];
            }

            if (array_key_exists($phpunitConfiguration['timeoutForLargeTests']) && !array_key_exists($arguments['timeoutForLargeTests'])) {
                $arguments['timeoutForLargeTests'] = $phpunitConfiguration['timeoutForLargeTests'];
            }

            if (array_key_exists($phpunitConfiguration['reportUselessTests']) && !array_key_exists($arguments['reportUselessTests'])) {
                $arguments['reportUselessTests'] = $phpunitConfiguration['reportUselessTests'];
            }

            if (array_key_exists($phpunitConfiguration['strictCoverage']) && !array_key_exists($arguments['strictCoverage'])) {
                $arguments['strictCoverage'] = $phpunitConfiguration['strictCoverage'];
            }

            if (array_key_exists($phpunitConfiguration['ignoreDeprecatedCodeUnitsFromCodeCoverage']) && !array_key_exists($arguments['ignoreDeprecatedCodeUnitsFromCodeCoverage'])) {
                $arguments['ignoreDeprecatedCodeUnitsFromCodeCoverage'] = $phpunitConfiguration['ignoreDeprecatedCodeUnitsFromCodeCoverage'];
            }

            if (array_key_exists($phpunitConfiguration['disallowTestOutput']) && !array_key_exists($arguments['disallowTestOutput'])) {
                $arguments['disallowTestOutput'] = $phpunitConfiguration['disallowTestOutput'];
            }

            if (array_key_exists($phpunitConfiguration['defaultTimeLimit']) && !array_key_exists($arguments['defaultTimeLimit'])) {
                $arguments['defaultTimeLimit'] = $phpunitConfiguration['defaultTimeLimit'];
            }

            if (array_key_exists($phpunitConfiguration['enforceTimeLimit']) && !array_key_exists($arguments['enforceTimeLimit'])) {
                $arguments['enforceTimeLimit'] = $phpunitConfiguration['enforceTimeLimit'];
            }

            if (array_key_exists($phpunitConfiguration['disallowTodoAnnotatedTests']) && !array_key_exists($arguments['disallowTodoAnnotatedTests'])) {
                $arguments['disallowTodoAnnotatedTests'] = $phpunitConfiguration['disallowTodoAnnotatedTests'];
            }

            if (array_key_exists($phpunitConfiguration['beStrictAboutResourceUsageDuringSmallTests']) && !array_key_exists($arguments['beStrictAboutResourceUsageDuringSmallTests'])) {
                $arguments['beStrictAboutResourceUsageDuringSmallTests'] = $phpunitConfiguration['beStrictAboutResourceUsageDuringSmallTests'];
            }

            if (array_key_exists($phpunitConfiguration['verbose']) && !array_key_exists($arguments['verbose'])) {
                $arguments['verbose'] = $phpunitConfiguration['verbose'];
            }

            if (array_key_exists($phpunitConfiguration['reverseDefectList']) && !array_key_exists($arguments['reverseList'])) {
                $arguments['reverseList'] = $phpunitConfiguration['reverseDefectList'];
            }

            if (array_key_exists($phpunitConfiguration['forceCoversAnnotation']) && !array_key_exists($arguments['forceCoversAnnotation'])) {
                $arguments['forceCoversAnnotation'] = $phpunitConfiguration['forceCoversAnnotation'];
            }

            if (array_key_exists($phpunitConfiguration['disableCodeCoverageIgnore']) && !array_key_exists($arguments['disableCodeCoverageIgnore'])) {
                $arguments['disableCodeCoverageIgnore'] = $phpunitConfiguration['disableCodeCoverageIgnore'];
            }

            if (array_key_exists($phpunitConfiguration['registerMockObjectsFromTestArgumentsRecursively']) && !array_key_exists($arguments['registerMockObjectsFromTestArgumentsRecursively'])) {
                $arguments['registerMockObjectsFromTestArgumentsRecursively'] = $phpunitConfiguration['registerMockObjectsFromTestArgumentsRecursively'];
            }

            if (array_key_exists($phpunitConfiguration['executionOrder']) && !array_key_exists($arguments['executionOrder'])) {
                $arguments['executionOrder'] = $phpunitConfiguration['executionOrder'];
            }

            if (array_key_exists($phpunitConfiguration['executionOrderDefects']) && !array_key_exists($arguments['executionOrderDefects'])) {
                $arguments['executionOrderDefects'] = $phpunitConfiguration['executionOrderDefects'];
            }

            if (array_key_exists($phpunitConfiguration['resolveDependencies']) && !array_key_exists($arguments['resolveDependencies'])) {
                $arguments['resolveDependencies'] = $phpunitConfiguration['resolveDependencies'];
            }

            if (array_key_exists($phpunitConfiguration['noInteraction']) && !array_key_exists($arguments['noInteraction'])) {
                $arguments['noInteraction'] = $phpunitConfiguration['noInteraction'];
            }

            if (array_key_exists($phpunitConfiguration['conflictBetweenPrinterClassAndTestdox'])) {
                $arguments['conflictBetweenPrinterClassAndTestdox'] = true;
            }

            $groupCliArgs = [];

            if (!empty($arguments['groups'])) {
                $groupCliArgs = $arguments['groups'];
            }

            $groupConfiguration = $arguments['configuration']->getGroupConfiguration();

            if (!empty($groupConfiguration['include']) && !array_key_exists($arguments['groups'])) {
                $arguments['groups'] = $groupConfiguration['include'];
            }

            if (!empty($groupConfiguration['exclude']) && !array_key_exists($arguments['excludeGroups'])) {
                $arguments['excludeGroups'] = array_diff($groupConfiguration['exclude'], $groupCliArgs);
            }

            foreach ($arguments['configuration']->getExtensionConfiguration() as $extension) {
                if ($extension['file'] !== '' && !class_exists($extension['class'], false)) {
                    require_once $extension['file'];
                }

                if (!class_exists($extension['class'])) {
                    throw new Exception(
                        sprintf(
                            'Class "%s" does not exist',
                            $extension['class']
                        )
                    );
                }

                try {
                    $extensionClass = new ReflectionClass($extension['class']);
                    // @codeCoverageIgnoreStart
                } catch (ReflectionException $e) {
                    throw new Exception(
                        $e->getMessage(),
                        (int) $e->getCode(),
                        $e
                    );
                }
                // @codeCoverageIgnoreEnd

                if (!$extensionClass->implementsInterface(Hook::class)) {
                    throw new Exception(
                        sprintf(
                            'Class "%s" does not implement a PHPUnit\Runner\Hook interface',
                            $extension['class']
                        )
                    );
                }

                if (count($extension['arguments']) === 0) {
                    $extensionObject = $extensionClass->newInstance();
                } else {
                    $extensionObject = $extensionClass->newInstanceArgs(
                        $extension['arguments']
                    );
                }

                assert($extensionObject instanceof Hook);

                $this->addExtension($extensionObject);
            }

            foreach ($arguments['configuration']->getListenerConfiguration() as $listener) {
                if ($listener['file'] !== '' && !class_exists($listener['class'], false)) {
                    require_once $listener['file'];
                }

                if (!class_exists($listener['class'])) {
                    throw new Exception(
                        sprintf(
                            'Class "%s" does not exist',
                            $listener['class']
                        )
                    );
                }

                try {
                    $listenerClass = new ReflectionClass($listener['class']);
                    // @codeCoverageIgnoreStart
                } catch (ReflectionException $e) {
                    throw new Exception(
                        $e->getMessage(),
                        (int) $e->getCode(),
                        $e
                    );
                }
                // @codeCoverageIgnoreEnd

                if (!$listenerClass->implementsInterface(TestListener::class)) {
                    throw new Exception(
                        sprintf(
                            'Class "%s" does not implement the PHPUnit\Framework\TestListener interface',
                            $listener['class']
                        )
                    );
                }

                if (count($listener['arguments']) === 0) {
                    $listener = new $listener['class'];
                } else {
                    $listener = $listenerClass->newInstanceArgs(
                        $listener['arguments']
                    );
                }

                $arguments['listeners'][] = $listener;
            }

            $loggingConfiguration = $arguments['configuration']->getLoggingConfiguration();

            if (array_key_exists($loggingConfiguration['coverage-clover']) && !array_key_exists($arguments['coverageClover'])) {
                $arguments['coverageClover'] = $loggingConfiguration['coverage-clover'];
            }

            if (array_key_exists($loggingConfiguration['coverage-crap4j']) && !array_key_exists($arguments['coverageCrap4J'])) {
                $arguments['coverageCrap4J'] = $loggingConfiguration['coverage-crap4j'];

                if (array_key_exists($loggingConfiguration['crap4jThreshold']) && !array_key_exists($arguments['crap4jThreshold'])) {
                    $arguments['crap4jThreshold'] = $loggingConfiguration['crap4jThreshold'];
                }
            }

            if (array_key_exists($loggingConfiguration['coverage-html']) && !array_key_exists($arguments['coverageHtml'])) {
                if (array_key_exists($loggingConfiguration['lowUpperBound']) && !array_key_exists($arguments['reportLowUpperBound'])) {
                    $arguments['reportLowUpperBound'] = $loggingConfiguration['lowUpperBound'];
                }

                if (array_key_exists($loggingConfiguration['highLowerBound']) && !array_key_exists($arguments['reportHighLowerBound'])) {
                    $arguments['reportHighLowerBound'] = $loggingConfiguration['highLowerBound'];
                }

                $arguments['coverageHtml'] = $loggingConfiguration['coverage-html'];
            }

            if (array_key_exists($loggingConfiguration['coverage-php']) && !array_key_exists($arguments['coveragePHP'])) {
                $arguments['coveragePHP'] = $loggingConfiguration['coverage-php'];
            }

            if (array_key_exists($loggingConfiguration['coverage-text']) && !array_key_exists($arguments['coverageText'])) {
                $arguments['coverageText']                   = $loggingConfiguration['coverage-text'];
                $arguments['coverageTextShowUncoveredFiles'] = $loggingConfiguration['coverageTextShowUncoveredFiles'] ?? false;
                $arguments['coverageTextShowOnlySummary']    = $loggingConfiguration['coverageTextShowOnlySummary'] ?? false;
            }

            if (array_key_exists($loggingConfiguration['coverage-xml']) && !array_key_exists($arguments['coverageXml'])) {
                $arguments['coverageXml'] = $loggingConfiguration['coverage-xml'];
            }

            if (array_key_exists($loggingConfiguration['plain'])) {
                $arguments['listeners'][] = new ResultPrinter(
                    $loggingConfiguration['plain'],
                    true
                );
            }

            if (array_key_exists($loggingConfiguration['teamcity']) && !array_key_exists($arguments['teamcityLogfile'])) {
                $arguments['teamcityLogfile'] = $loggingConfiguration['teamcity'];
            }

            if (array_key_exists($loggingConfiguration['junit']) && !array_key_exists($arguments['junitLogfile'])) {
                $arguments['junitLogfile'] = $loggingConfiguration['junit'];
            }

            if (array_key_exists($loggingConfiguration['testdox-html']) && !array_key_exists($arguments['testdoxHTMLFile'])) {
                $arguments['testdoxHTMLFile'] = $loggingConfiguration['testdox-html'];
            }

            if (array_key_exists($loggingConfiguration['testdox-text']) && !array_key_exists($arguments['testdoxTextFile'])) {
                $arguments['testdoxTextFile'] = $loggingConfiguration['testdox-text'];
            }

            if (array_key_exists($loggingConfiguration['testdox-xml']) && !array_key_exists($arguments['testdoxXMLFile'])) {
                $arguments['testdoxXMLFile'] = $loggingConfiguration['testdox-xml'];
            }

            $testdoxGroupConfiguration = $arguments['configuration']->getTestdoxGroupConfiguration();

            if (array_key_exists($testdoxGroupConfiguration['include']) &&
                !array_key_exists($arguments['testdoxGroups'])) {
                $arguments['testdoxGroups'] = $testdoxGroupConfiguration['include'];
            }

            if (array_key_exists($testdoxGroupConfiguration['exclude']) &&
                !array_key_exists($arguments['testdoxExcludeGroups'])) {
                $arguments['testdoxExcludeGroups'] = $testdoxGroupConfiguration['exclude'];
            }
        }

        $arguments['addUncoveredFilesFromWhitelist']                  = $arguments['addUncoveredFilesFromWhitelist'] ?? true;
        $arguments['backupGlobals']                                   = $arguments['backupGlobals'] ?? null;
        $arguments['backupStaticAttributes']                          = $arguments['backupStaticAttributes'] ?? null;
        $arguments['beStrictAboutChangesToGlobalState']               = $arguments['beStrictAboutChangesToGlobalState'] ?? null;
        $arguments['beStrictAboutResourceUsageDuringSmallTests']      = $arguments['beStrictAboutResourceUsageDuringSmallTests'] ?? false;
        $arguments['cacheResult']                                     = $arguments['cacheResult'] ?? true;
        $arguments['cacheTokens']                                     = $arguments['cacheTokens'] ?? false;
        $arguments['colors']                                          = $arguments['colors'] ?? ResultPrinter::COLOR_DEFAULT;
        $arguments['columns']                                         = $arguments['columns'] ?? 80;
        $arguments['convertDeprecationsToExceptions']                 = $arguments['convertDeprecationsToExceptions'] ?? false;
        $arguments['convertErrorsToExceptions']                       = $arguments['convertErrorsToExceptions'] ?? true;
        $arguments['convertNoticesToExceptions']                      = $arguments['convertNoticesToExceptions'] ?? true;
        $arguments['convertWarningsToExceptions']                     = $arguments['convertWarningsToExceptions'] ?? true;
        $arguments['crap4jThreshold']                                 = $arguments['crap4jThreshold'] ?? 30;
        $arguments['disallowTestOutput']                              = $arguments['disallowTestOutput'] ?? false;
        $arguments['disallowTodoAnnotatedTests']                      = $arguments['disallowTodoAnnotatedTests'] ?? false;
        $arguments['defaultTimeLimit']                                = $arguments['defaultTimeLimit'] ?? 0;
        $arguments['enforceTimeLimit']                                = $arguments['enforceTimeLimit'] ?? false;
        $arguments['excludeGroups']                                   = $arguments['excludeGroups'] ?? [];
        $arguments['executionOrder']                                  = $arguments['executionOrder'] ?? TestSuiteSorter::ORDER_DEFAULT;
        $arguments['executionOrderDefects']                           = $arguments['executionOrderDefects'] ?? TestSuiteSorter::ORDER_DEFAULT;
        $arguments['failOnRisky']                                     = $arguments['failOnRisky'] ?? false;
        $arguments['failOnWarning']                                   = $arguments['failOnWarning'] ?? false;
        $arguments['groups']                                          = $arguments['groups'] ?? [];
        $arguments['noInteraction']                                   = $arguments['noInteraction'] ?? false;
        $arguments['processIsolation']                                = $arguments['processIsolation'] ?? false;
        $arguments['processUncoveredFilesFromWhitelist']              = $arguments['processUncoveredFilesFromWhitelist'] ?? false;
        $arguments['randomOrderSeed']                                 = $arguments['randomOrderSeed'] ?? time();
        $arguments['registerMockObjectsFromTestArgumentsRecursively'] = $arguments['registerMockObjectsFromTestArgumentsRecursively'] ?? false;
        $arguments['repeat']                                          = $arguments['repeat'] ?? false;
        $arguments['reportHighLowerBound']                            = $arguments['reportHighLowerBound'] ?? 90;
        $arguments['reportLowUpperBound']                             = $arguments['reportLowUpperBound'] ?? 50;
        $arguments['reportUselessTests']                              = $arguments['reportUselessTests'] ?? true;
        $arguments['reverseList']                                     = $arguments['reverseList'] ?? false;
        $arguments['resolveDependencies']                             = $arguments['resolveDependencies'] ?? true;
        $arguments['stopOnError']                                     = $arguments['stopOnError'] ?? false;
        $arguments['stopOnFailure']                                   = $arguments['stopOnFailure'] ?? false;
        $arguments['stopOnIncomplete']                                = $arguments['stopOnIncomplete'] ?? false;
        $arguments['stopOnRisky']                                     = $arguments['stopOnRisky'] ?? false;
        $arguments['stopOnSkipped']                                   = $arguments['stopOnSkipped'] ?? false;
        $arguments['stopOnWarning']                                   = $arguments['stopOnWarning'] ?? false;
        $arguments['stopOnDefect']                                    = $arguments['stopOnDefect'] ?? false;
        $arguments['strictCoverage']                                  = $arguments['strictCoverage'] ?? false;
        $arguments['testdoxExcludeGroups']                            = $arguments['testdoxExcludeGroups'] ?? [];
        $arguments['testdoxGroups']                                   = $arguments['testdoxGroups'] ?? [];
        $arguments['timeoutForLargeTests']                            = $arguments['timeoutForLargeTests'] ?? 60;
        $arguments['timeoutForMediumTests']                           = $arguments['timeoutForMediumTests'] ?? 10;
        $arguments['timeoutForSmallTests']                            = $arguments['timeoutForSmallTests'] ?? 1;
        $arguments['verbose']                                         = $arguments['verbose'] ?? false;

        if ($arguments['reportLowUpperBound'] > $arguments['reportHighLowerBound']) {
            $arguments['reportLowUpperBound']  = 50;
            $arguments['reportHighLowerBound'] = 90;
        }
    }

    private function processSuiteFilters(TestSuite $suite, array $arguments): void
    {
        if (!$arguments['filter'] &&
            empty($arguments['groups']) &&
            empty($arguments['excludeGroups'])) {
            return;
        }

        $filterFactory = new Factory;

        if (!empty($arguments['excludeGroups'])) {
            $filterFactory->addFilter(
                new ReflectionClass(ExcludeGroupFilterIterator::class),
                $arguments['excludeGroups']
            );
        }

        if (!empty($arguments['groups'])) {
            $filterFactory->addFilter(
                new ReflectionClass(IncludeGroupFilterIterator::class),
                $arguments['groups']
            );
        }

        if ($arguments['filter']) {
            $filterFactory->addFilter(
                new ReflectionClass(NameFilterIterator::class),
                $arguments['filter']
            );
        }

        $suite->injectFilter($filterFactory);
    }

    private function writeMessage(string $type, string $message): void
    {
        if (!$this->messagePrinted) {
            $this->write("\n");
        }

        $this->write(
            sprintf(
                "%-15s%s\n",
                $type . ':',
                $message
            )
        );

        $this->messagePrinted = true;
    }

    /**
     * @template T as Printer
     *
     * @param class-string<T> $class
     *
     * @return T
     */
    private function createPrinter(string $class, array $arguments): Printer
    {
        return new $class(
            (array_key_exists($arguments['stderr']) && $arguments['stderr'] === true) ? 'php://stderr' : null,
            $arguments['verbose'],
            $arguments['colors'],
            $arguments['debug'],
            $arguments['columns'],
            $arguments['reverseList']
        );
    }

    private function codeCoverageGenerationStart(string $format): void
    {
        $this->write(
            sprintf(
                "\nGenerating code coverage report in %s format ... ",
                $format
            )
        );

        Timer::start();
    }

    private function codeCoverageGenerationSucceeded(): void
    {
        $this->write(
            sprintf(
                "done [%s]\n",
                Timer::secondsToTimeString(Timer::stop())
            )
        );
    }

    private function codeCoverageGenerationFailed(\Exception $e): void
    {
        $this->write(
            sprintf(
                "failed [%s]\n%s\n",
                Timer::secondsToTimeString(Timer::stop()),
                $e->getMessage()
            )
        );
    }
}
