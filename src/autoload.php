<?php
require_once dirname(__FILE__).'/Core/Loader/AutoLoader.php';
require_once dirname(__FILE__).'/Seaf.php';

/**
 * Seaf Auto Load
 */
Seaf\Core\Loader\AutoLoader::init()->addNamespace(
    'Seaf\\Core\\',
    null,
    dirname(__FILE__).'/Core'
);
