<?php
/**
 * Seaf Auto Load
 */
Seaf::di('autoLoader')->addNamespace(
    'Seaf\\Component\\Config',
    null,
    dirname(__FILE__).'/Config'
);
