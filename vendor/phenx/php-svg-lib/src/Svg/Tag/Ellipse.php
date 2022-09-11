<?php
/**
 * @package php-svg-lib
 * @link    http://github.com/PhenX/php-svg-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license GNU LGPLv3+ http://www.gnu.org/copyleft/lesser.html
 */

namespace Svg\Tag;

class Ellipse extends Shape
{
    protected $cx = 0;
    protected $cy = 0;
    protected $rx = 0;
    protected $ry = 0;

    public function start($attributes)
    {
        parent::start($attributes);

        if (array_key_exists($attributes['cx'])) {
            $this->cx = $attributes['cx'];
        }
        if (array_key_exists($attributes['cy'])) {
            $this->cy = $attributes['cy'];
        }
        if (array_key_exists($attributes['rx'])) {
            $this->rx = $attributes['rx'];
        }
        if (array_key_exists($attributes['ry'])) {
            $this->ry = $attributes['ry'];
        }

        $this->document->getSurface()->ellipse($this->cx, $this->cy, $this->rx, $this->ry, 0, 0, 360, false);
    }
} 
