<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\HTTPClient;

use Psr\Http\Message\StreamInterface;


/**
 * Class RequestHelper
 * Simple Class to easy contruct you request.
 * @package Padosoft\HTTPClient
 */
class RequestHelper
{
    /**
     * @var array
     */
    protected $options  = array();

    /**
     * RequestHelper constructor.
     */
    public function __construct()
    {
        //set defaults
        $this->setDefaults();
    }

    /**
     * allow_redirects: (bool|array) Controls redirect behavior. Pass false
     * to disable redirects, pass true to enable redirects, pass an
     * associative to provide custom redirect settings. Defaults to "false".
     * This option only works if your handler has the RedirectMiddleware. When
     * passing an associative array, you can provide the following key value
     * pairs:
     *
     * - max: (int, default=5) maximum number of allowed redirects.
     * - strict: (bool, default=false) Set to true to use strict redirects
     *   meaning redirect POST requests with POST requests vs. doing what most
     *   browsers do which is redirect POST requests with GET requests
     * - referer: (bool, default=true) Set to false to disable the Referer
     *   header.
     * - protocols: (array, default=['http', 'https']) Allowed redirect
     *   protocols.
     * - on_redirect: (callable) PHP callable that is invoked when a redirect
     *   is encountered. The callable is invoked with the request, the redirect
     *   response that was received, and the effective URI. Any return value
     *   from the on_redirect function is ignored.
     * @param int $max
     * @param bool $strict
     * @param bool $referer
     * @param array $protocolsArray
     * @param callable|null $onRedirect
     * @return $this
     */
    public function setAllowRedirects($max=5,$strict=false,$referer=true,$protocolsArray=['http', 'https'],callable $onRedirect=null)
    {
        if(!isset($max) || !preg_match("/^(0)$|^([1-9][0-9]*)$")) {
            $max=5;
        }
        if(!isset($strict) || !is_bool($strict)) {
            $strict=false;
        }
        if(!isset($referer) || !is_bool($referer)) {
            $referer=true;
        }

        if(!isset($protocolsArray) || !is_array($protocolsArray)) {
            $protocolsArray=['http', 'https'];
        }

        if(!is_callable($onRedirect)){
            $onRedirect = null;
        }

        $this->options['allow_redirects'] = ['max' => $max
                                                , 'strict' => $strict
                                                , 'referer' => $referer
                                                , 'protocolsArray' => $protocolsArray
                                                , 'onRedirect' => $onRedirect
                                            ];
        return $this;
    }

    /**
     * auth: Pass HTTP authentication parameters to use
     * with the request.
     *
     * @param string $username
     * @param string $password
     * @param string $typeAuthentication
     * @return $this
     */
    public function setAuth($username, $password, $typeAuthentication=TypeAuthentication::BASIC)
    {
        if(!TypeAuthentication::isValidValue($typeAuthentication)) {
            $typeAuthentication=TypeAuthentication::BASIC;
        }
        $this->options['auth'] = [$username, $password, $typeAuthentication];
        return $this;
    }
    /**
     * body: (string|null|callable|iterator|object) Body to send in the
     * request.
     * @param (string|null|callable|iterator|object) $body
     * @return $this
     */
    public function setBody($body)
    {
        if(null!==$body && !is_object($body) && !is_string($body) && !is_callable($body) && !is_a($body, \Iterator::class))
        {
            return $this;
        }

        $this->options['body'] = $body;
        return $this;
    }
    /**
     * cert: (string|array) Set to a string to specify the path to a file
     * containing a PEM formatted SSL client side certificate. If a password
     * is required, then set cert to an array containing the path to the PEM
     * file in the first array element followed by the certificate password
     * in the second array element.
     *
     * @param (string|array) $certificate
     * @return $this
     */
    public function setCert($certificate)
    {
        if(!is_array($certificate) && !is_string($certificate))
        {
            return $this;
        }

        $this->options['cert'] = $certificate;
        return $this;
    }
    /**
     * cookies: (bool|GuzzleHttp\Cookie\CookieJarInterface)
     * Specifies whether or not cookies are used in a request or what cookie
     * jar to use or what cookies to send. This option only works if your
     * handler has the `cookie` middleware. Valid values are `false` and
     * an instance of {@see GuzzleHttp\Cookie\CookieJarInterface}.
     *
     * @param (bool|GuzzleHttp\Cookie\CookieJarInterface) $cookies
     * @return $this
     */
    public function setCookies($cookies)
    {
        if(!is_bool($$cookies) && !is_a($cookies, \GuzzleHttp\Cookie\CookieJarInterface::class))
        {
            $cookies = false;
        }

        $this->options['cookies'] = $cookies;
        return $this;
    }
    /**
     * connect_timeout: (float, default=0) Float describing the number of
     * seconds to wait while trying to connect to a server. Use 0 to wait
     * indefinitely (the default behavior).
     * @param int $secondConnectTimeout
     * @return $this
     */
    public function setConnectTimeout($secondConnectTimeout=0)
    {
        $this->options['connect_timeout'] = (float) $secondConnectTimeout;
        return $this;
    }
    /**
     * debug: (bool|resource) Set to true or set to a PHP stream returned by
     * fopen()  enable debug output with the HTTP handler used to send a
     * request.
     * @param $debug
     * @return $this
     */
    public function setDebug($debug)
    {
        if(!is_resource($debug) && !is_bool($debug))
        {
            return $this;
        }

        $this->options['debug'] = $debug;
        return $this;
    }
    /**
     * decode_content: (bool, default=true) Specify whether or not
     * Content-Encoding responses (gzip, deflate, etc.) are automatically
     * decoded.
     * @return $this
     */
    public function setDecodeContentTrue()
    {
        $this->options['decode_content']= true;
        return $this;
    }
    /**
     * decode_content: (bool, default=true) Specify whether or not
     * Content-Encoding responses (gzip, deflate, etc.) are automatically
     * decoded.
     * @return $this
     */
    public function setDecodeContentFalse()
    {
        $this->options['decode_content']= false;
        return $this;
    }
    /**
     * delay: (int) The amount of time to delay before sending in milliseconds.
     * @param int $millisecondDelay
     * @return $this
     */
    public function setDelay($millisecondDelay)
    {
        $this->options['delay'] = (int) $millisecondDelay;
        return $this;
    }
    /**
     * expect: (bool|integer) Controls the behavior of the
     * "Expect: 100-Continue" header.
     *
     * Set to `true` to enable the "Expect: 100-Continue" header for all
     * requests that sends a body. Set to `false` to disable the
     * "Expect: 100-Continue" header for all requests. Set to a number so that
     * the size of the payload must be greater than the number in order to send
     * the Expect header. Setting to a number will send the Expect header for
     * all requests in which the size of the payload cannot be determined or
     * where the body is not rewindable.
     *
     * By default, Guzzle will add the "Expect: 100-Continue" header when the
     * size of the body of a request is greater than 1 MB and a request is
     * using HTTP/1.1.
     *
     * @param (bool|integer) $expect
     * @return $this
     */
    public function setExpect($expect)
    {
        if(!is_bool($expect) && !is_int($expect)){
            return $this;
        }

        $this->options['expect']=$expect;
        return $this;
    }
    /**
     * form_params: (array) Associative array of form field names to values
     * where each value is a string or array of strings. Sets the Content-Type
     * header to application/x-www-form-urlencoded when no Content-Type header
     * is already present.
     *
     * @param array $form_params
     * @return $this
     */
    public function setFormParams($form_params)
    {
        if(!is_array($form_params)){
            return $this;
        }

        $this->options['form_params']=$form_params;
        return $this;
    }
    /**
     * headers: (array) Associative array of HTTP headers. Each value MUST be
     * a string or array of strings.
     *
     * @param array $headers
     * @return $this
     */
    public function setHeaders($headers)
    {
        if(!is_array($headers)){
            return $this;
        }

        $this->options['headers'] = $headers;
        return $this;
    }
    /**
     * http_errors: (bool, default=true) Set to false to disable exceptions
     * when a non- successful HTTP response is received. By default,
     * exceptions will be thrown for 4xx and 5xx responses. This option only
     * works if your handler has the `httpErrors` middleware.
     *
     * @return $this
     */
    public function setHttpErrorsTrue()
    {
        $this->options['http_errors'] =true;
        return $this;
    }
    /**
     * http_errors: (bool, default=true) Set to false to disable exceptions
     * when a non- successful HTTP response is received. By default,
     * exceptions will be thrown for 4xx and 5xx responses. This option only
     * works if your handler has the `httpErrors` middleware.
     *
     * @return $this
     */
    public function setHttpErrorsFalse()
    {
        $this->options['http_errors'] =false;
        return $this;
    }
    /**
     * json: (mixed) Adds JSON data to a request.
     * The provided value it will be JSON encoded
     * and a Content-Type header of application/json will be added to
     * the request if no Content-Type header is already present.
     *
     * @param $json
     * @return $this
     */
    public function setJson($json)
    {
        $this->options['json'] = $json;
        return $this;
    }
    /**
     * @param Multipart $multipart
     * @return $this
     */
    public function setMultipart(Multipart $multipart)
    {
        if($multipart===null){
            return $this;
        }

        $this->options['multipart']= $multipart->getMultipartArray();
        return $this;
    }
    /**
     * on_headers: (callable) A callable that is invoked when the HTTP headers
     * of the response have been received but the body has not yet begun to
     * download.
     *
     * @param callable $on_headers
     * @return $this
     */
    public function setOnHeaders(callable $on_headers)
    {
        if(!is_callable($on_headers)){
            return $this;
        }

        $this->options['on_headers']=$on_headers;
        return $this;
    }
    /**
     * on_stats: (callable) allows you to get access to transfer statistics of
     * a request and access the lower level transfer details of the handler
     * associated with your client. ``on_stats`` is a callable that is invoked
     * when a handler has finished sending a request. The callback is invoked
     * with transfer statistics about the request, the response received, or
     * the error encountered. Included in the data is the total amount of time
     * taken to send the request.
     *
     * @param callable $on_stats
     * @return $this
     */
    public function setOnStats(callable $on_stats)
    {
        if(!is_callable($on_stats)){
            return $this;
        }

        $this->options['on_stats'] = $on_stats;
        return $this;
    }
    /**
     * progress: (callable) Defines a function to invoke when transfer
     * progress is made. The function accepts the following positional
     * arguments: the total number of bytes expected to be downloaded, the
     * number of bytes downloaded so far, the number of bytes expected to be
     * uploaded, the number of bytes uploaded so far.
     *
     * @param callable $progress
     * @return $this
     */
    public function setProgress(callable $progress)
    {
        if(!is_callable($progress)){
            return $this;
        }

        $this->options['progress']= $progress;
        return $this;
    }
    /**
     * proxy: (string|array) Pass a string to specify an HTTP proxy, or an
     * array to specify different proxies for different protocols (where the
     * key is the protocol and the value is a proxy string).
     *
     * @param (string|array) $proxy
     * @return $this
     */
    public function setProxy($proxy)
    {
        if(!is_array($proxy) && !is_string($proxy)){
            return $this;
        }

        $this->options['proxy']= $proxy;
        return $this;
    }
    /**
     * query: (array|string) Associative array of query string values to add
     * to the request. This option uses PHP's http_build_query() to create
     * the string representation. Pass a string value if you need more
     * control than what this method provides
     *
     * @param (array|string) $query
     * @return $this
     */
    public function setQuery($query)
    {
        if(!is_array($query) && !is_string($query)) {
            return $this;
        }

        $this->options['query']=$query;
        return $this;
    }
    /**
     * sink: (resource|string|StreamInterface) Where the data of the
     * response is written to. Defaults to a PHP temp stream. Providing a
     * string will write data to a file by the given name.
     *
     * @param (resource|string|StreamInterface) $sink
     * @return $this
     */
    public function setSink($sink)
    {
        if(!is_resource($sink) && !is_string($sink) && !is_a($sink, StreamInterface::class))
        {
            return $this;
        }

        $this->options['sink']=$sink;
        return $this;
    }
    /**
     * synchronous: (bool) Set to true to inform HTTP handlers that you intend
     * on waiting on the response. This can be useful for optimizations. Note
     * that a promise is still returned if you are using one of the async
     * client methods.
     *
     * @return $this
     */
    public function setSynchronousTrue()
    {
        $this->options['synchronous'] = true;
        return $this;
    }
    /**
     * synchronous: (bool) Set to true to inform HTTP handlers that you intend
     * on waiting on the response. This can be useful for optimizations. Note
     * that a promise is still returned if you are using one of the async
     * client methods.
     *
     * @return $this
     */
    public function setSynchronousFalse()
    {
        $this->options['synchronous'] = false;
        return $this;
    }
    /**
     * ssl_key: (array|string) Specify the path to a file containing a private
     * SSL key in PEM format. If a password is required, then set to an array
     * containing the path to the SSL key in the first array element followed
     * by the password required for the certificate in the second element.
     *
     * @param (array|string) $ssl_key
     * @return $this
     */
    public function setSslKey($ssl_key)
    {
        if(!is_array($ssl_key) && !is_string($ssl_key))
        {
            return $this;
        }

        $this->options['ssl_key']=$ssl_key;
        return $this;
    }
    /**
     * stream: Set to true to attempt to stream a response rather than
     * download it all up-front.
     *
     * @param $stream
     * @return $this
     */
    public function setStream($stream)
    {
        $this->options['stream']=$stream;
        return $this;
    }
    /**
     * verify: (bool|string, default=true) Describes the SSL certificate
     * verification behavior of a request. Set to true to enable SSL
     * certificate verification using the system CA bundle when available
     * (the default). Set to false to disable certificate verification (this
     * is insecure!). Set to a string to provide the path to a CA bundle on
     * disk to enable verification using a custom certificate.
     *
     * @param (bool|string) $verify
     * @return $this
     */
    public function setVerify($verify)
    {
        if(!is_bool($verify) && !is_string($verify))
        {
            $verify = false;
        }

        $this->options['verify']=$verify;
        return $this;
    }
    /**
     * timeout: (float, default=0) Float describing the timeout of the
     * request in seconds. Use 0 to wait indefinitely (the default behavior).
     *
     * @param float $secondRequestTimeout
     * @return $this
     */
    public function setTimeout($secondRequestTimeout=0.0)
    {
        if(!is_float($secondRequestTimeout)){
            $secondRequestTimeout = (float) 0.0;
        }

        $this->options['timeout']=(float) $secondRequestTimeout;
        return $this;
    }
    /**
     * version: (float, default=1.1) Specifies the HTTP protocol version to attempt to use.
     *
     * @param float $version
     * @return $this
     */
    public function setVersion($version)
    {
        if(!is_float($version)){
            $version = (float) 1.1;
        }

        $this->options['version']=$version;
        return $this;
    }

    /**
     * @param $property
     * @return mixed
     */
    public function __get($property)
    {
        if (!property_exists($this, $property)) {
            return null;
        }

        return $this->$property;
    }

    /**
     * @param $property
     * @param $value
     * @return $this
     */
    public function __set($property, $value)
    {
        if (!property_exists($this, $property)) {
            return;
        }

        $this->$property = $value;
    }

    /**
     * Set the default Request headers.
     */
    public function setDefaults()
    {
        $this->setHttpErrorsFalse();
    }
}
