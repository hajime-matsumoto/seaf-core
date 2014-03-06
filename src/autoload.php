<?php
/**
 * Seaf Auto Load
 */
Seaf\Core\Loader\AutoLoader::init()->addNamespace(
    'Seaf\\Core\\',
    null,
    dirname(__FILE__).'/Core'
);
