<?php
/**
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2018 BigBlueButton Inc. and by respective authors (see below).
 *
 * This program is free software; you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the Free Software
 * Foundation; either version 3.0 of the License, or (at your option) any later
 * version.
 *
 * BigBlueButton is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along
 * with BigBlueButton; if not, see <http://www.gnu.org/licenses/>.
 */

namespace BigBlueButton\Responses;

/**
 * Class BaseResponse.
 */
abstract class BaseResponse
{
    public const SUCCESS = 'SUCCESS';
    public const FAILED = 'FAILED';
    public const CHECKSUM_ERROR = 'checksumError';

    /**
     * @var \SimpleXMLElement
     */
    protected $rawXml;

    /**
     * BaseResponse constructor.
     */
    public function __construct(\SimpleXMLElement $xml)
    {
        $this->rawXml = $xml;
    }

    /**
     * @return \SimpleXMLElement
     */
    public function getRawXml()
    {
        return $this->rawXml;
    }

    /**
     * @return string
     */
    public function getReturnCode()
    {
        return $this->rawXml->returncode->__toString();
    }

    /**
     * @return string
     */
    public function getMessageKey()
    {
        return $this->rawXml->messageKey->__toString();
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->rawXml->message->__toString();
    }

    public function success()
    {
        return $this->getReturnCode() === self::SUCCESS;
    }

    public function failed()
    {
        return $this->getReturnCode() === self::FAILED;
    }

    /**
     * Check is response is checksum error.
     */
    public function hasChecksumError(): bool
    {
        return $this->failed() && $this->getMessageKey() == self::CHECKSUM_ERROR;
    }
}
