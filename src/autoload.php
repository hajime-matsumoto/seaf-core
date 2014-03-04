<?php
/**
 * Seaf Auto Load
 */

require_once dirname(__FILE__).'/Loader/AutoLoader.php';

$loader = Seaf\Core\Loader\AutoLoader::init();

$loader->addNamespace(
    'Seaf\\Core\\',
    null,
    dirname(__FILE__)
);

