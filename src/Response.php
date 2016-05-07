<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\HTTPClient;

use GuzzleHttp\Psr7\Response as GuzzleResponse;

/**
 * Class Response
 * Wrap psr7 Response object wit body and status code.
 * @package Padosoft\HTTPClient
 */
class Response
{
    /**
     * @var int
     */
    protected $status_code = -1;
    /**
     * @var string
     */
    protected $body = '';
    /**
     * @var \GuzzleHttp\Psr7\Response $psr7response
     */
    protected $psr7response;

    /**
     * @param $property
     * @return mixed
     */
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    /**
     * @param $property
     * @param $value
     */
    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }


}
