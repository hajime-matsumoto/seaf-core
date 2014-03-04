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

/**
 * Components
 */
$loader->addNamespace(
    'Seaf\\Component\\',
    null,
    dirname(__FILE__).'/components'
);

/**
 * bundle
 */
foreach ( glob(dirname(__FILE__).'/bundle/*/src/autoload.php') as $file ) {
    require_once $file;
}
