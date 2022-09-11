<?php

namespace Illuminate\View\Compilers\Concerns;

trait CompilesJson
{
    /**
     * The default JSON encoding options.
     *
     * @var int
     */
    private $encodingOptions = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;

    /**
     * Compile the JSON statement into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileJson($expression)
    {
        $parts = explode(',', $this->stripParentheses($expression));

        $options = array_key_exists($parts[1]) ? trim($parts[1]) : $this->encodingOptions;

        $depth = array_key_exists($parts[2]) ? trim($parts[2]) : 512;

        return "<?php echo json_encode($parts[0], $options, $depth) ?>";
    }
}
