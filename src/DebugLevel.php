<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\HTTPClient;

use Padosoft\HTTPClient\traits\Enumerable;

/**
 * Class DebugLevel
 * @package Padosoft\HTTPClient
 */
class DebugLevel
{
    use Enumerable;

    const NONE    = 0;
    const ERROR   = 1;
    const VERBOSE = 2;
}
