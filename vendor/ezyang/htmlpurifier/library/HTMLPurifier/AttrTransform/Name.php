<?php

/**
 * Pre-transform that changes deprecated name attribute to ID if necessary
 */
class HTMLPurifier_AttrTransform_Name extends HTMLPurifier_AttrTransform
{

    /**
     * @param array $attr
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return array
     */
    public function transform($attr, $config, $context)
    {
        // Abort early if we're using relaxed definition of name
        if ($config->get('HTML.Attr.Name.UseCDATA')) {
            return $attr;
        }
        if (!array_key_exists($attr['name'])) {
            return $attr;
        }
        $id = $this->confiscateAttr($attr, 'name');
        if (array_key_exists($attr['id'])) {
            return $attr;
        }
        $attr['id'] = $id;
        return $attr;
    }
}

// vim: et sw=4 sts=4
