<?php
/**
 * Seaf Auto Load
 */
require_once dirname(__FILE__).'/Core/Loader/AutoLoader.php';
require_once dirname(__FILE__).'/Seaf.php';

$loader = Seaf\Core\Loader\AutoLoader::init();

$loader->addNamespace(
    'Seaf\\Core\\',
    null,
    dirname(__FILE__).'/Core'
);
