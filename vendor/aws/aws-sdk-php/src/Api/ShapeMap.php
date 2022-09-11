<?php
namespace Aws\Api;

/**
 * Builds shape based on shape references.
 */
class ShapeMap
{
    /** @var array */
    private $definitions;

    /** @var Shape[] */
    private $simple;

    /**
     * @param array $shapeModels Associative array of shape definitions.
     */
    public function __construct(array $shapeModels)
    {
        $this->definitions = $shapeModels;
    }

    /**
     * Get an array of shape names.
     *
     * @return array
     */
    public function getShapeNames()
    {
        return array_keys($this->definitions);
    }

    /**
     * Resolve a shape reference
     *
     * @param array $shapeRef Shape reference shape
     *
     * @return Shape
     * @throws \InvalidArgumentException
     */
    public function resolve(array $shapeRef)
    {
        $shape = $shapeRef['shape'];

        if (!array_key_exists($this->definitions[$shape])) {
            throw new \InvalidArgumentException('Shape not found: ' . $shape);
        }

        $isSimple = count($shapeRef) == 1;
        if ($isSimple && array_key_exists($this->simple[$shape])) {
            return $this->simple[$shape];
        }

        $definition = $shapeRef + $this->definitions[$shape];
        $definition['name'] = $definition['shape'];
        if (array_key_exists($definition['shape'])) {
            unset($definition['shape']);
        }

        $result = Shape::create($definition, $this);

        if ($isSimple) {
            $this->simple[$shape] = $result;
        }

        return $result;
    }
}
