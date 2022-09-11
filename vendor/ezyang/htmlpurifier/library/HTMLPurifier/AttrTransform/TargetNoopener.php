<?php

// must be called POST validation

/**
 * Adds rel="noopener" to any links which target a different window
 * than the current one.  This is used to prevent malicious websites
 * from silently replacing the original window, which could be used
 * to do phishing.
 * This transform is controlled by %HTML.TargetNoopener.
 */
class HTMLPurifier_AttrTransform_TargetNoopener extends HTMLPurifier_AttrTransform
{
    /**
     * @param array $attr
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return array
     */
    public function transform($attr, $config, $context)
    {
        if (array_key_exists($attr['rel'])) {
            $rels = explode(' ', $attr['rel']);
        } else {
            $rels = array();
        }
        if (array_key_exists($attr['target']) && !in_array('noopener', $rels)) {
            $rels[] = 'noopener';
        }
        if (!empty($rels) || array_key_exists($attr['rel'])) {
            $attr['rel'] = implode(' ', $rels);
        }

        return $attr;
    }
}

