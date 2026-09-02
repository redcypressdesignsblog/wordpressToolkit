<?php
/**
 * Plugin Name: Featured Post Link (Classic Editor)
 * Description: Adds a shortcode and Classic Editor button for linking to a published post with its featured image and excerpt.
 * Version: 1.0.0
 * Author: Redcypress Designs
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render a featured-post link with its thumbnail, title, and excerpt.
 *
 * Usage: [featured_post_link id="123" image_size="thumbnail"]
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function fplc_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'id'         => 0,
			'image_size' => 'thumbnail',
		),
		$atts,
		'featured_post_link'
	);

	$post_id = absint( $atts['id'] );
	$post    = get_post( $post_id );

	if ( ! $post_id || ! $post || 'publish' !== $post->post_status ) {
		return '';
	}

	$image_size = sanitize_key( $atts['image_size'] );
	$image_size = $image_size ? $image_size : 'thumbnail';
	$title      = get_the_title( $post_id );
	$url        = get_permalink( $post_id );
	$excerpt    = get_the_excerpt( $post_id );
	$image      = get_the_post_thumbnail(
		$post_id,
		$image_size,
		array(
			'class'   => 'featured-post-image',
			'loading' => 'lazy',
		)
	);

	$output  = '<a href="' . esc_url( $url ) . '" class="featured-post-link" style="display:inline-flex;align-items:center;gap:10px;text-decoration:none;">';
	$output .= $image;
	$output .= '<span style="font-weight:bold;color:inherit;">' . esc_html( $title ) . '</span>';
	$output .= '</a>';

	if ( $excerpt ) {
		$output .= '<p class="featured-post-link-excerpt">' . wp_kses_post( $excerpt ) . '</p>';
	}

	return $output;
}
add_shortcode( 'featured_post_link', 'fplc_shortcode' );

/**
 * Supply published posts to the Classic Editor selector.
 *
 * @return array
 */
function fplc_get_post_list() {
	$posts = get_posts(
		array(
			'numberposts' => -1,
			'post_status' => 'publish',
			'orderby'     => 'date',
			'order'       => 'DESC',
		)
	);

	return array_map(
		static function ( $post ) {
			return array(
				'id'    => $post->ID,
				'title' => $post->post_title,
			);
		},
		$posts
	);
}

/**
 * Register the TinyMCE toolbar button for Classic Editor users.
 */
function fplc_add_mce_button() {
	if ( ! current_user_can( 'edit_posts' ) || 'true' !== get_user_option( 'rich_editing' ) ) {
		return;
	}

	wp_register_script(
		'fplc-editor-js',
		plugins_url( 'fplc-button.js', __FILE__ ),
		array( 'jquery' ),
		'1.0.0',
		true
	);

	wp_localize_script( 'fplc-editor-js', 'FPLC_POSTS', fplc_get_post_list() );
	wp_enqueue_script( 'fplc-editor-js' );

	add_filter(
		'mce_external_plugins',
		static function ( $plugin_array ) {
			$plugin_array['fplc_button'] = plugins_url( 'fplc-button.js', __FILE__ );
			return $plugin_array;
		}
	);

	add_filter(
		'mce_buttons',
		static function ( $buttons ) {
			$buttons[] = 'fplc_button';
			return $buttons;
		}
	);
}
add_action( 'admin_head', 'fplc_add_mce_button' );
