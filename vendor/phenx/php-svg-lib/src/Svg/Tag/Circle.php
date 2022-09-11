<?php
/**
 * @package php-svg-lib
 * @link    http://github.com/PhenX/php-svg-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license GNU LGPLv3+ http://www.gnu.org/copyleft/lesser.html
 */

namespace Svg\Tag;

class Circle extends Shape
{
    protected $cx = 0;
    protected $cy = 0;
    protected $r;

    public function start($attributes)
    {
        if (array_key_exists($attributes['cx'])) {
            $this->cx = $attributes['cx'];
        }
        if (array_key_exists($attributes['cy'])) {
            $this->cy = $attributes['cy'];
        }
        if (array_key_exists($attributes['r'])) {
            $this->r = $attributes['r'];
        }

        $this->document->getSurface()->circle($this->cx, $this->cy, $this->r);
    }
} 
