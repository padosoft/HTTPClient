<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\HTTPClient;

use GuzzleHttp\Psr7\Response as GuzzleResponse;

class Response
{
    protected $status_code = -1;
    protected $body = '';
    protected $psr7response = null;


    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }
    
    
}