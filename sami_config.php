<?php

use Sami\Sami;
use Sami\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;


$iterator = Finder::create()
    ->files()
    ->exclude('Vendor')
    ->exclude('tests')
    ->in($dir = __DIR__.'/src')
;

$versions = GitVersionCollection::create($dir)
    ->addFromTags('*')
    ->add('master','master')
;

return new Sami($iterator,array(
    'theme'                => 'default',
    'title'                => 'HTTPClient API',
    'versions'             => $versions,
    'build_dir'            => 'Y:/Public/laravel-packages/www/doc/padosoft/HTTPClient/build/%version%',
    'cache_dir'            => 'Y:/Public/laravel-packages/www/doc/padosoft/HTTPClient/cache/%version%',
    'default_opened_level' => 2,
));

