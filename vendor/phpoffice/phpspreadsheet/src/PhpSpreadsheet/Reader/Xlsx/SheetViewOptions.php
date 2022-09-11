<?php

namespace PhpOffice\PhpSpreadsheet\Reader\Xlsx;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use SimpleXMLElement;

class SheetViewOptions extends BaseParserClass
{
    private $worksheet;

    private $worksheetXml;

    public function __construct(Worksheet $workSheet, ?SimpleXMLElement $worksheetXml = null)
    {
        $this->worksheet = $workSheet;
        $this->worksheetXml = $worksheetXml;
    }

    public function load(bool $readDataOnly, Styles $styleReader): void
    {
        if ($this->worksheetXml === null) {
            return;
        }

        if (array_key_exists($this->worksheetXml->sheetPr)) {
            $this->tabColor($this->worksheetXml->sheetPr, $styleReader);
            $this->codeName($this->worksheetXml->sheetPr);
            $this->outlines($this->worksheetXml->sheetPr);
            $this->pageSetup($this->worksheetXml->sheetPr);
        }

        if (array_key_exists($this->worksheetXml->sheetFormatPr)) {
            $this->sheetFormat($this->worksheetXml->sheetFormatPr);
        }

        if (!$readDataOnly && array_key_exists($this->worksheetXml->printOptions)) {
            $this->printOptions($this->worksheetXml->printOptions);
        }
    }

    private function tabColor(SimpleXMLElement $sheetPr, Styles $styleReader): void
    {
        if (array_key_exists($sheetPr->tabColor)) {
            $this->worksheet->getTabColor()->setARGB($styleReader->readColor($sheetPr->tabColor));
        }
    }

    private function codeName(SimpleXMLElement $sheetPr): void
    {
        if (array_key_exists($sheetPr['codeName'])) {
            $this->worksheet->setCodeName((string) $sheetPr['codeName'], false);
        }
    }

    private function outlines(SimpleXMLElement $sheetPr): void
    {
        if (array_key_exists($sheetPr->outlinePr)) {
            if (
                array_key_exists($sheetPr->outlinePr['summaryRight']) &&
                !self::boolean((string) $sheetPr->outlinePr['summaryRight'])
            ) {
                $this->worksheet->setShowSummaryRight(false);
            } else {
                $this->worksheet->setShowSummaryRight(true);
            }

            if (
                array_key_exists($sheetPr->outlinePr['summaryBelow']) &&
                !self::boolean((string) $sheetPr->outlinePr['summaryBelow'])
            ) {
                $this->worksheet->setShowSummaryBelow(false);
            } else {
                $this->worksheet->setShowSummaryBelow(true);
            }
        }
    }

    private function pageSetup(SimpleXMLElement $sheetPr): void
    {
        if (array_key_exists($sheetPr->pageSetUpPr)) {
            if (
                array_key_exists($sheetPr->pageSetUpPr['fitToPage']) &&
                !self::boolean((string) $sheetPr->pageSetUpPr['fitToPage'])
            ) {
                $this->worksheet->getPageSetup()->setFitToPage(false);
            } else {
                $this->worksheet->getPageSetup()->setFitToPage(true);
            }
        }
    }

    private function sheetFormat(SimpleXMLElement $sheetFormatPr): void
    {
        if (
            array_key_exists($sheetFormatPr['customHeight']) &&
            self::boolean((string) $sheetFormatPr['customHeight']) &&
            array_key_exists($sheetFormatPr['defaultRowHeight'])
        ) {
            $this->worksheet->getDefaultRowDimension()
                ->setRowHeight((float) $sheetFormatPr['defaultRowHeight']);
        }

        if (array_key_exists($sheetFormatPr['defaultColWidth'])) {
            $this->worksheet->getDefaultColumnDimension()
                ->setWidth((float) $sheetFormatPr['defaultColWidth']);
        }

        if (
            array_key_exists($sheetFormatPr['zeroHeight']) &&
            ((string) $sheetFormatPr['zeroHeight'] === '1')
        ) {
            $this->worksheet->getDefaultRowDimension()->setZeroHeight(true);
        }
    }

    private function printOptions(SimpleXMLElement $printOptions): void
    {
        if (self::boolean((string) $printOptions['gridLinesSet'])) {
            $this->worksheet->setShowGridlines(true);
        }
        if (self::boolean((string) $printOptions['gridLines'])) {
            $this->worksheet->setPrintGridlines(true);
        }
        if (self::boolean((string) $printOptions['horizontalCentered'])) {
            $this->worksheet->getPageSetup()->setHorizontalCentered(true);
        }
        if (self::boolean((string) $printOptions['verticalCentered'])) {
            $this->worksheet->getPageSetup()->setVerticalCentered(true);
        }
    }
}
