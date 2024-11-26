<?php

// Carrega o autoload do Composer
require_once __DIR__ . '/vendor/autoload.php';

use TheCreators\SpellBlocks\Setup;

Setup::getInstance();


function test(){
    var_dump(register_block_type(get_template_directory() . '/blocks/banner/build/block.json'));
}
add_action('init', 'test', 1000);
