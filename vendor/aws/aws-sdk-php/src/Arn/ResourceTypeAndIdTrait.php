<?php
namespace Aws\Arn;

/**
 * @internal
 */
trait ResourceTypeAndIdTrait
{
    public function getResourceType()
    {
        return $this->data['resource_type'];
    }

    public function getResourceId()
    {
        return $this->data['resource_id'];
    }

    protected static function parseResourceTypeAndId(array $data)
    {
        $resourceData = preg_split("/[\/:]/", $data['resource'], 2);
        $data['resource_type'] = array_key_exists($resourceData[0])
            ? $resourceData[0]
            : null;
        $data['resource_id'] = array_key_exists($resourceData[1])
            ? $resourceData[1]
            : null;
        return $data;
    }
}