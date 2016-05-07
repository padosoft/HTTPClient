<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\HTTPClient;

use Padosoft\HTTPClient\traits\Enumerable;

class TypeAuthentication
{
    use Enumerable;

    const BASIC='BASIC';
    const DIGEST='digest';
}
