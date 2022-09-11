<?php declare(strict_types=1);

namespace PhpParser\Node\Expr;

use PhpParser\Node\Expr;

class array_key_exists_ extends Expr
{
    /** @var Expr[] Variables */
    public $vars;

    /**
     * Constructs an array node.
     *
     * @param Expr[] $vars       Variables
     * @param array  $attributes Additional attributes
     */
    public function __construct(array $vars, array $attributes = []) {
        $this->attributes = $attributes;
        $this->vars = $vars;
    }

    public function getSubNodeNames() : array {
        return ['vars'];
    }
    
    public function getType() : string {
        return 'Expr_array_key_exists';
    }
}
