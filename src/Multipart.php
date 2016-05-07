<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\HTTPClient;

use Psr\Http\Message\StreamInterface;

/**
 * Class Multipart
 * @package Padosoft\HTTPClient
 */
class Multipart
{
    /**
     * required "name" key mapping to the form field, name
     * @var string
     */
    protected $name = '';

    /**
     * required "contents" key mapping to a StreamInterface|resource|string
     * @var (StreamInterface|resource|string) string
     */
    protected $contents;

    /**
     * optional "headers" associative array of custom headers
     * @var array
     */
    protected $headers;

    /**
     * optional "filename" key mapping to a string to send as the filename in
     * the part. If no "filename" key is present, then no "filename" attribute
     * will be added to the part.
     * @var string
     */
    protected $filename='';

    /**
     * Multipart constructor.
     * @param string $name required "name" key mapping to the form field, name
     * @param (StreamInterface|resource|string) $contents required "contents" key mapping to a StreamInterface|resource|string
     * @param string $filename optional "filename" key mapping to a string to send as the filename in
     * the part. If no "filename" key is present, then no "filename" attribute
     * will be added to the part.
     * @param array $headers optional "headers" associative array of custom headers
     * @throws \Exception
     */
    public function __construct($name, $contents, $filename=null, $headers=[])
    {
        if($name===null || $name==''){
            throw new \Exception("name is mandatory in Multipart!");
        }

        if(!is_resource($contents) && !is_string($contents) && !is_a($contents, StreamInterface::class))
        {
            throw new \Exception("contents is mandatory in Multipart and it's a StreamInterface or resource or string!");
        }

        $this->name = $name;
        $this->contents = $contents;

        if(!is_string($filename))
        {
            $this->filename = null;
        }

        if(!is_array($headers))
        {
            $this->headers = [];
        }
    }

    /**
     * @return resource|string
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return array|null
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Return an associative array for RequestHelper::SetMultipart()
     * @return array
     */
    public function getMultipartArray()
    {
        $arrMultipart = array();

        $arrMultipart[] = ['name' => $this->name,
                            'contents' => $this->contents,
                            'headers' => $this->headers,
                            'filename' => $this->filename
        ];

        return $arrMultipart;
    }

}
