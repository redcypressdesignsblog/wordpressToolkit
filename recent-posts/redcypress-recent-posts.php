<?php
/**
 * Plugin Name: Redcypress Recent Posts
 * Description: Adds a configurable recent-posts shortcode with category, tag, count, and CSS class options.
 * Version: 1.0.0
 * Author: Redcypress Designs
 * License: GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render a list of recently modified posts.
 *
 * Usage: [recent_posts category="design-code" tag="wordpress" count="5" class="custom-class"]
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function rcdrp_render_recent_posts_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'category' => '',
			'tag'      => '',
			'count'    => 5,
			'class'    => '',
		),
		$atts,
		'recent_posts'
	);

	$category_slug = sanitize_title( $atts['category'] );
	$tag_slug      = sanitize_title( $atts['tag'] );
	$count         = absint( $atts['count'] );
	$count         = $count ? min( $count, 50 ) : 5;

	$custom_classes = preg_split( '/\\s+/', trim( (string) $atts['class'] ) );
	$custom_classes = array_filter( array_map( 'sanitize_html_class', $custom_classes ) );
	$wrapper_classes = array_merge( array( 'rcd-recent-posts' ), $custom_classes );

	$args = array(
		'post_type'           => 'post',
		'posts_per_page'      => $count,
		'post_status'         => 'publish',
		'orderby'             => 'modified',
		'order'               => 'DESC',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	if ( $category_slug ) {
		$args['category_name'] = $category_slug;
	}

	if ( $tag_slug ) {
		$args['tag'] = $tag_slug;
	}

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		return '';
	}

	$read_more_url  = '';
	$read_more_text = 'Read more →';

	if ( $category_slug ) {
		$category = get_category_by_slug( $category_slug );

		if ( $category ) {
			$category_link = get_category_link( $category->term_id );

			if ( ! is_wp_error( $category_link ) ) {
				$read_more_url  = $category_link;
				$read_more_text = 'More in ' . $category->name . ' →';
			}
		}
	} elseif ( $tag_slug ) {
		$tag = get_term_by( 'slug', $tag_slug, 'post_tag' );

		if ( $tag && ! is_wp_error( $tag ) ) {
			$tag_link = get_tag_link( $tag->term_id );

			if ( ! is_wp_error( $tag_link ) ) {
				$read_more_url  = $tag_link;
				$read_more_text = 'More tagged ' . $tag->name . ' →';
			}
		}
	}

	if ( ! $read_more_url ) {
		$posts_page_id = absint( get_option( 'page_for_posts' ) );
		$posts_page_url = $posts_page_id ? get_permalink( $posts_page_id ) : '';
		$read_more_url = $posts_page_url ? $posts_page_url : home_url( '/' );
	}

	$output = '<div class="' . esc_attr( implode( ' ', $wrapper_classes ) ) . '">';

	while ( $query->have_posts() ) {
		$query->the_post();

		$post_url   = get_permalink();
		$post_title = get_the_title();
		$excerpt    = get_the_excerpt();

		$output .= '<div class="rcd-post" style="margin-bottom:40px;clear:both;box-sizing:content-box;display:block;">';

		if ( has_post_thumbnail() ) {
			$output .= '<a href="' . esc_url( $post_url ) . '" style="margin-right:10px;float:left;">';
			$output .= get_the_post_thumbnail( get_the_ID(), 'thumbnail' );
			$output .= '</a>';
		}

		$output .= '<span style="font-weight:bold;color:inherit;"><a href="' . esc_url( $post_url ) . '">' . esc_html( $post_title ) . '</a></span>';
		$output .= '<p>' . esc_html( $excerpt ) . '</p>';
		$output .= '</div>';
	}

	wp_reset_postdata();

	$output .= '<div class="rcd-read-more" style="clear:both;margin-top:10px;">';
	$output .= '<a href="' . esc_url( $read_more_url ) . '" style="font-weight:bold;display:inline-block;">' . esc_html( $read_more_text ) . '</a>';
	$output .= '</div>';
	$output .= '</div>';

	return $output;
}
add_shortcode( 'recent_posts', 'rcdrp_render_recent_posts_shortcode' );
