<?php

use Brain\Monkey\Functions\expect;
use SpellBlocks\ThemeSetup;

beforeEach(function () {
    // Set up Brain Monkey para poder mockar funções do WordPress
    \Brain\Monkey\setUp();
});

afterEach(function () {
    // Finaliza a configuração do Brain Monkey após cada teste
    \Brain\Monkey\tearDown();
});

it('registers the setup action hook', function () {
    // Testa se a função 'setup' foi registrada corretamente no hook 'after_setup_theme'
    Brain\Monkey\Actions\expectAdded('after_setup_theme')
        ->once()
        ->with([ThemeSetup::getInstance(), 'setup']);
});

it('registers the enqueueScripts action hook', function () {
    // Testa se a função 'enqueueScripts' foi registrada corretamente no hook 'wp_enqueue_scripts'
    Brain\Monkey\Actions\expectAdded('wp_enqueue_scripts')
        ->once()
        ->with([ThemeSetup::getInstance(), 'enqueueScripts']);
});

it('calls setup and registers necessary theme features', function () {
    // Mocker o 'add_theme_support' e 'register_nav_menus'
    expect('add_theme_support')->once()->with('automatic-feed-links');
    expect('add_theme_support')->once()->with('title-tag');
    expect('add_theme_support')->once()->with('post-thumbnails');
    expect('register_nav_menus')->once()->with([
        'menu-1' => 'Primary',
    ]);

    // Chama o método 'setup' diretamente
    $themeSetup = ThemeSetup::getInstance();
    $themeSetup->setup();
});

it('enqueues the theme stylesheet', function () {
    // Mocker as funções do WordPress 'wp_enqueue_style' e 'wp_style_add_data'
    expect('wp_enqueue_style')->once()->with('spellblocks-style', get_stylesheet_uri(), [], '1.0.0');
    expect('wp_style_add_data')->once()->with('spellblocks-style', 'rtl', 'replace');
    
    // Chama o método 'enqueueScripts' diretamente
    $themeSetup = ThemeSetup::getInstance();
    $themeSetup->enqueueScripts();
});

it('enqueues comment-reply script on single post with comments open', function () {
    // Mockando o comportamento do WordPress para uma página singular com comentários abertos
    expect('is_singular')->once()->andReturn(true);
    expect('comments_open')->once()->andReturn(true);
    expect('get_option')->once()->with('thread_comments')->andReturn(true);
    
    // Mockando o 'wp_enqueue_script'
    expect('wp_enqueue_script')->once()->with('comment-reply');
    
    // Chama o método 'enqueueScripts' diretamente
    $themeSetup = ThemeSetup::getInstance();
    $themeSetup->enqueueScripts();
});
