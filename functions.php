<?php
/**
 * Jobs Theme 2026 functions and definitions.
 *
 * @package jobswp-2025
 */

define( 'JOBSWP_2025_VERSION', '1.0.0' );

/**
 * Template tag helpers ported from the jobswp classic theme.
 *
 * Required because the jobswp plugin's the_content filter for the
 * /post-a-job/ page calls get_template_part( 'content', 'post-job' ) and
 * get_template_part( 'content', 'single' ) against the active theme, and
 * those template parts call helpers like jobswp_posted_on().
 */
require_once get_template_directory() . '/inc/template-tags.php';

/**
 * Theme setup.
 */
function jobswp_2025_setup() {
	load_theme_textdomain( 'jobswp-2025', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'style.css' );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'jobswp-2025' ),
	) );

	// Jobs don't have comments.
	add_filter( 'feed_links_show_comments_feed', '__return_false' );
}
add_action( 'after_setup_theme', 'jobswp_2025_setup' );

/**
 * Enqueue frontend stylesheet.
 */
function jobswp_2025_enqueue_assets() {
	wp_enqueue_style(
		'jobswp-2025-style',
		get_stylesheet_uri(),
		array(),
		filemtime( get_stylesheet_directory() . '/style.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'jobswp_2025_enqueue_assets' );

/**
 * Google Tag Manager snippet in <head>.
 *
 * Ported from the jobswp classic theme's header.php. In a block theme the
 * <head> is rendered by wp_head() rather than a header template, so this
 * hooks into wp_head at high priority.
 */
function jobswp_2025_gtm_head() {
	?>
	<!-- Google Tag Manager -->
	<link rel="dns-prefetch" href="//www.googletagmanager.com"/>
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-P24PF4B');</script>
	<!-- End Google Tag Manager -->
	<?php
}
add_action( 'wp_head', 'jobswp_2025_gtm_head', 1 );

/**
 * GTM noscript iframe immediately after <body>.
 */
function jobswp_2025_gtm_body() {
	?>
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P24PF4B" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<?php
}
add_action( 'wp_body_open', 'jobswp_2025_gtm_body' );

/**
 * RSS auto-discovery links for each job category on the homepage.
 */
function jobswp_2025_category_feed_links() {
	if ( ! is_front_page() || ! class_exists( 'Jobs_Dot_WP' ) ) {
		return;
	}

	$categories = Jobs_Dot_WP::get_job_categories();
	if ( ! $categories ) {
		return;
	}

	foreach ( $categories as $cat ) {
		printf(
			'<link rel="alternate" type="application/rss+xml" title="%s" href="%s" />' . "\n",
			esc_attr( sprintf( '%s &raquo; %s Feed', get_bloginfo( 'name' ), $cat->name ) ),
			esc_url( get_term_feed_link( $cat->term_id, 'job_category' ) )
		);
	}
}
add_action( 'wp_head', 'jobswp_2025_category_feed_links' );

/**
 * 404 response for author archive requests.
 */
function jobswp_2025_author_archives_404() {
	if ( is_author() ) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
	}
}
add_action( 'wp', 'jobswp_2025_author_archives_404' );

/**
 * noindex,nofollow for empty job category archives and search results.
 */
function jobswp_2025_noindex( $robots ) {
	global $wp_query;

	if (
		is_search()
	||
		( is_tax( 'job_category' ) && 0 === $wp_query->found_posts )
	) {
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
	}

	return $robots;
}
add_filter( 'wp_robots', 'jobswp_2025_noindex' );

/**
 * Add page slug to body_class for page-specific styling.
 */
function jobswp_2025_add_page_slug_to_body_class( $classes ) {
	if ( is_page() ) {
		$page = get_post();
		if ( $page ) {
			$classes[] = 'page-' . $page->post_name;
		}
	}
	return $classes;
}
add_filter( 'body_class', 'jobswp_2025_add_page_slug_to_body_class' );

/**
 * Mark the nav link that matches the current request with aria-current="page".
 *
 * core/navigation-link does not add an active-state marker of its own, so the
 * classic theme's FAQ/Feedback highlighting disappears when ported as-is to a
 * block theme. Compare the link's resolved URL to the current request URL and
 * inject aria-current on exact matches; style.css targets the attribute.
 */
function jobswp_2025_mark_current_nav_link( $block_content, $block ) {
	if ( empty( $block['attrs']['url'] ) || false === strpos( $block_content, '<a ' ) ) {
		return $block_content;
	}

	// Compare path components only — query strings (?preview=1, ?paged=2, …)
	// and fragments should not affect the active-state match.
	$link_path    = trailingslashit( (string) wp_parse_url( $block['attrs']['url'], PHP_URL_PATH ) );
	$request_path = trailingslashit( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ) );

	// Subtract the site's path prefix so subdirectory installs
	// (home_url() = https://example.com/jobs) don't double-count it.
	$home_path = trailingslashit( (string) wp_parse_url( home_url(), PHP_URL_PATH ) );
	if ( '/' !== $home_path && 0 === strpos( $request_path, $home_path ) ) {
		$request_path = '/' . ltrim( substr( $request_path, strlen( $home_path ) ), '/' );
		$request_path = trailingslashit( $request_path );
	}

	if ( $link_path === $request_path ) {
		$block_content = preg_replace( '/<a\b/', '<a aria-current="page"', $block_content, 1 );
	}

	return $block_content;
}
add_filter( 'render_block_core/navigation-link', 'jobswp_2025_mark_current_nav_link', 10, 2 );

/**
 * Register theme-provided dynamic blocks.
 */
function jobswp_2025_register_blocks() {
	$blocks_dir = __DIR__ . '/blocks';
	if ( ! is_dir( $blocks_dir ) ) {
		return;
	}

	foreach ( glob( $blocks_dir . '/*/block.json' ) as $block_json ) {
		register_block_type_from_metadata( dirname( $block_json ) );
	}
}
add_action( 'init', 'jobswp_2025_register_blocks' );
