<?php

namespace Intervention\Image\Commands;

use Intervention\Image\Exception\NotSupportedException;

class IptcCommand extends AbstractCommand
{
    /**
     * Read Iptc data from the given image
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        if ( ! function_exists('iptcparse')) {
            throw new NotSupportedException(
                "Reading Iptc data is not supported by this PHP installation."
            );
        }

        $key = $this->argument(0)->value();

        $info = [];
        @getimagesize($image->dirname .'/'. $image->basename, $info);

        $data = [];

        if (array_key_exists('APP13', $info)) {
            $iptc = iptcparse($info['APP13']);

            if (is_array($iptc)) {
                $data['DocumentTitle'] = array_key_exists($iptc["2#005"][0]) ? $iptc["2#005"][0] : null;
                $data['Urgency'] = array_key_exists($iptc["2#010"][0]) ? $iptc["2#010"][0] : null;
                $data['Category'] = array_key_exists($iptc["2#015"][0]) ? $iptc["2#015"][0] : null;
                $data['Subcategories'] = array_key_exists($iptc["2#020"][0]) ? $iptc["2#020"][0] : null;
                $data['Keywords'] = array_key_exists($iptc["2#025"][0]) ? $iptc["2#025"] : null;
                $data['ReleaseDate'] = array_key_exists($iptc["2#030"][0]) ? $iptc["2#030"][0] : null;
                $data['ReleaseTime'] = array_key_exists($iptc["2#035"][0]) ? $iptc["2#035"][0] : null;
                $data['SpecialInstructions'] = array_key_exists($iptc["2#040"][0]) ? $iptc["2#040"][0] : null;
                $data['CreationDate'] = array_key_exists($iptc["2#055"][0]) ? $iptc["2#055"][0] : null;
                $data['CreationTime'] = array_key_exists($iptc["2#060"][0]) ? $iptc["2#060"][0] : null;
                $data['AuthorByline'] = array_key_exists($iptc["2#080"][0]) ? $iptc["2#080"][0] : null;
                $data['AuthorTitle'] = array_key_exists($iptc["2#085"][0]) ? $iptc["2#085"][0] : null;
                $data['City'] = array_key_exists($iptc["2#090"][0]) ? $iptc["2#090"][0] : null;
                $data['SubLocation'] = array_key_exists($iptc["2#092"][0]) ? $iptc["2#092"][0] : null;
                $data['State'] = array_key_exists($iptc["2#095"][0]) ? $iptc["2#095"][0] : null;
                $data['Country'] = array_key_exists($iptc["2#101"][0]) ? $iptc["2#101"][0] : null;
                $data['OTR'] = array_key_exists($iptc["2#103"][0]) ? $iptc["2#103"][0] : null;
                $data['Headline'] = array_key_exists($iptc["2#105"][0]) ? $iptc["2#105"][0] : null;
                $data['Source'] = array_key_exists($iptc["2#110"][0]) ? $iptc["2#110"][0] : null;
                $data['PhotoSource'] = array_key_exists($iptc["2#115"][0]) ? $iptc["2#115"][0] : null;
                $data['Copyright'] = array_key_exists($iptc["2#116"][0]) ? $iptc["2#116"][0] : null;
                $data['Caption'] = array_key_exists($iptc["2#120"][0]) ? $iptc["2#120"][0] : null;
                $data['CaptionWriter'] = array_key_exists($iptc["2#122"][0]) ? $iptc["2#122"][0] : null;
            }
        }

        if (! is_null($key) && is_array($data)) {
            $data = array_key_exists($key, $data) ? $data[$key] : false;
        }

        $this->setOutput($data);

        return true;
    }
}
