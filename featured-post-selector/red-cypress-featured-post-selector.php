<?php
/**
 * Plugin Name: Redcypress Featured Post Selector
 * Description: Adds Gutenberg post-selector blocks for featured post links, with or without an excerpt.
 * Version: 1.2.0
 * Author: Redcypress Designs
 * License: GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register editor assets and both dynamic blocks.
 */
function rcd_register_featured_post_selector_blocks() {
	$plugin_dir = plugin_dir_path( __FILE__ );

	wp_register_script(
		'rcd-featured-post-selector-editor',
		plugins_url( 'featured-post-selector.js', __FILE__ ),
		array(
			'wp-api-fetch',
			'wp-block-editor',
			'wp-blocks',
			'wp-components',
			'wp-element',
			'wp-i18n',
		),
		filemtime( $plugin_dir . 'featured-post-selector.js' ),
		true
	);

	wp_register_style(
		'rcd-featured-post-selector-editor',
		plugins_url( 'editor.css', __FILE__ ),
		array(),
		filemtime( $plugin_dir . 'editor.css' )
	);

	wp_register_style(
		'rcd-featured-post-selector-style',
		plugins_url( 'style.css', __FILE__ ),
		array(),
		filemtime( $plugin_dir . 'style.css' )
	);

	$shared_settings = array(
		'api_version'   => 2,
		'editor_script' => 'rcd-featured-post-selector-editor',
		'editor_style'  => 'rcd-featured-post-selector-editor',
		'style'         => 'rcd-featured-post-selector-style',
		'attributes'    => array(
			'postId' => array(
				'type'    => 'integer',
				'default' => 0,
			),
			'postTitle' => array(
				'type'    => 'string',
				'default' => '',
			),
		),
		'supports'      => array(
			'html' => false,
		),
	);

	register_block_type(
		'red-cypress/featured-post-selector',
		array_merge(
			$shared_settings,
			array(
				'render_callback' => 'rcd_render_featured_post_selector_block',
			)
		)
	);

	register_block_type(
		'red-cypress/featured-post-excerpt-selector',
		array_merge(
			$shared_settings,
			array(
				'render_callback' => 'rcd_render_featured_post_excerpt_selector_block',
			)
		)
	);
}
add_action( 'init', 'rcd_register_featured_post_selector_blocks' );

/**
 * Render the original selector block using the companion shortcode.
 */
function rcd_render_featured_post_selector_block( $attributes ) {
	$post_id = isset( $attributes['postId'] ) ? absint( $attributes['postId'] ) : 0;

	if ( ! $post_id ) {
		return '';
	}

	if ( ! shortcode_exists( 'featured_post_link' ) ) {
		return current_user_can( 'edit_posts' )
			? '<p><strong>Redcypress Featured Post Selector:</strong> The <code>[featured_post_link]</code> shortcode is not registered.</p>'
			: '';
	}

	return do_shortcode( sprintf( '[featured_post_link id="%d"]', $post_id ) );
}

/**
 * Render a featured post card containing the image, title, and excerpt.
 */
function rcd_render_featured_post_excerpt_selector_block( $attributes ) {
	$post_id = isset( $attributes['postId'] ) ? absint( $attributes['postId'] ) : 0;

	if ( ! $post_id || 'publish' !== get_post_status( $post_id ) ) {
		return '';
	}

	$title = get_the_title( $post_id );
	$url   = get_permalink( $post_id );
	$image = get_the_post_thumbnail(
		$post_id,
		'thumbnail',
		array(
			'class'   => 'featured-post-excerpt-image',
			'loading' => 'lazy',
		)
	);

	$manual_excerpt = get_post_field( 'post_excerpt', $post_id );
	$excerpt_source = $manual_excerpt ? $manual_excerpt : get_post_field( 'post_content', $post_id );
	$excerpt        = wp_trim_words( wp_strip_all_tags( strip_shortcodes( $excerpt_source ) ), 55, '&hellip;' );

	return sprintf(
		'<a href="%1$s" class="featured-post-excerpt-link">%2$s<div class="featured-post-excerpt-content"><span class="featured-post-excerpt-title">%3$s</span><span id="postexcerpt">%4$s</span></div></a>',
		esc_url( $url ),
		$image,
		esc_html( $title ),
		$excerpt ? '<p class="featured-post-excerpt-text">' . esc_html( $excerpt ) . '</p>' : ''
	);
}
