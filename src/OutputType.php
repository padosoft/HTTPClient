<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\HTTPClient;


use Padosoft\HTTPClient\traits\Enumerable;

class OutputType
{
    use Enumerable;

    const SCREEN = 0;
    const FILE   = 1;
    const ALL    = 2;
}
