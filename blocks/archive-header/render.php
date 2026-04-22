<?php
/**
 * Renders the archive header — category name (or search query), job count,
 * RSS link, and column labels. Ported from jobswp_archive_header() in
 * inc/template-tags.php.
 *
 * @package jobswp-2025
 */

global $wp_query;

$is_search   = is_search();
$is_category = is_tax( 'job_category' );

if ( ! $is_search && ! $is_category ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'jobs-group' ) );
$found_posts        = isset( $wp_query->found_posts ) ? (int) $wp_query->found_posts : 0;

if ( $is_category ) {
	$category = get_queried_object();
	$title    = sprintf(
		'<h2 class="job-cat-item job-cat-item-%1$s">%2$s</h2>',
		esc_attr( $category->slug ),
		esc_html( $category->name )
	);
	$feed_link  = get_term_feed_link( $category->term_id, $category->taxonomy );
	$count_text = sprintf(
		/* translators: %d: number of jobs in the category */
		_n( '%d job', '%d jobs', $found_posts, 'jobswp-2025' ),
		number_format_i18n( $found_posts )
	);
} else {
	$title = sprintf(
		'<h2 class="search-header">%s <span>%s</span></h2>',
		esc_html__( 'Search Results for:', 'jobswp-2025' ),
		esc_html( get_search_query() )
	);
	$feed_link  = get_search_feed_link();
	$count_text = '';
}
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="row row-head">
		<div class="job-list-head">
			<?php echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<span class="posts-count">(<?php echo esc_html( number_format_i18n( $found_posts ) ); ?>)</span>
		</div>
		<div class="jobs-count">
			<?php if ( $feed_link ) : ?>
				<a href="<?php echo esc_url( $feed_link ); ?>"><?php esc_html_e( 'RSS', 'jobswp-2025' ); ?></a>
			<?php endif; ?>
			<?php if ( $count_text ) : ?>
				<span><?php echo esc_html( $count_text ); ?></span>
			<?php endif; ?>
		</div>
	</div>
	<div class="row job-list-col-labels">
		<div class="job-date"><?php esc_html_e( 'Date Posted', 'jobswp-2025' ); ?></div>
		<div class="job-title"><?php esc_html_e( 'Job Title', 'jobswp-2025' ); ?></div>
		<div class="job-type"><?php esc_html_e( 'Job Type', 'jobswp-2025' ); ?></div>
		<div class="job-location"><?php esc_html_e( 'Location', 'jobswp-2025' ); ?></div>
	</div>
</div>
