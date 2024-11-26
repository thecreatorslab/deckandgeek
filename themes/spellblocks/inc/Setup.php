<?php

namespace TheCreators\SpellBlocks;

class Setup {

	const THEME_VERSION = '1.0.0';

	private static $instance = null;

	private function __construct() {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueueScripts' ] );
	}

	public static function getInstance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function setup() {
		load_theme_textdomain( 'spellblocks', get_template_directory() . '/languages' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		register_nav_menus( [
			'menu-1' => esc_html__( 'Primary', 'spellblocks' ),
		] );
		add_theme_support(
			'html5',
			[ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ]
		);
		add_theme_support(
			'custom-background',
			apply_filters(
				'spellblocks_custom_background_args',
				[
					'default-color' => 'ffffff',
					'default-image' => '',
				]
			)
		);
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support(
			'custom-logo',
			[
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			]
		);
	}

	public function enqueueScripts() {
		wp_enqueue_style( 'spellblocks-style', get_stylesheet_uri(), [], self::THEME_VERSION );
		wp_style_add_data( 'spellblocks-style', 'rtl', 'replace' );
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		wp_register_script(
			'spellblocks-banner',
			get_template_directory_uri() . '/blocks/banner/build/index.js',
			[ 'wp-blocks', 'wp-element', 'wp-editor' ],
			'1.0.0'
		);
	
		wp_register_style(
			'spellblocks-banner-editor',
			get_template_directory_uri() . '/blocks/banner/editor.css',
			[],
			'1.0.0'
		);
	}
}
