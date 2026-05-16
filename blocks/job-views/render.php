<?php
/**
 * Renders the Job Views block — all-time view count for a single job
 * listing, sourced from Jetpack Stats. Phrased as "Seen by N candidates"
 * to reframe the metric as candidate interest rather than raw analytics.
 *
 * Result is cached in a transient (1 hour) to avoid hitting Stats on
 * every pageview.
 *
 * @package jobswp-2025
 */

$post_id = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();
if ( ! $post_id ) {
	return;
}

$prefix     = isset( $attributes['prefix'] ) ? (string) $attributes['prefix'] : 'Seen by';
$noun       = isset( $attributes['noun'] ) ? (string) $attributes['noun'] : 'candidates';
$hide_below = isset( $attributes['hideBelow'] ) ? (int) $attributes['hideBelow'] : 0;

$views = jobswp_2025_get_job_views( $post_id );

if ( null === $views || $views < $hide_below ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'job-views' ) );
$count_text         = number_format_i18n( $views );
?>
<p <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( '' !== $prefix ) : ?>
		<span class="job-views__prefix"><?php echo esc_html( $prefix ); ?></span>
	<?php endif; ?>
	<span class="job-views__count"><?php echo esc_html( $count_text ); ?></span>
	<?php if ( '' !== $noun ) : ?>
		<span class="job-views__noun"><?php echo esc_html( $noun ); ?></span>
	<?php endif; ?>
</p>
<?php

/**
 * Fetch all-time view count for a post from Jetpack Stats.
 *
 * Returns null when no Stats source is available (so the block can hide
 * itself rather than render "0 views" on local/dev environments where
 * Jetpack isn't connected).
 *
 * @param int $post_id Post ID.
 * @return int|null
 */
function jobswp_2025_get_job_views( $post_id ) {
	$cache_key = 'jobswp_2025_views_' . $post_id;
	$cached    = get_transient( $cache_key );
	if ( false !== $cached ) {
		return is_numeric( $cached ) ? (int) $cached : null;
	}

	$views = null;

	// Preferred: modern Jetpack Stats package.
	if ( class_exists( '\Automattic\Jetpack\Stats\WPCOM_Stats' ) ) {
		$stats = new \Automattic\Jetpack\Stats\WPCOM_Stats();
		$data  = $stats->get_post_views( $post_id );
		if ( is_array( $data ) && isset( $data['views'] ) ) {
			$views = (int) $data['views'];
		}
	}

	// Fallback: legacy stats_get_csv helper (Jetpack < 9.7 / WP.com).
	if ( null === $views && function_exists( 'stats_get_csv' ) ) {
		$rows = stats_get_csv( 'postviews', array( 'post_id' => $post_id, 'period' => 'year', 'limit' => -1 ) );
		if ( is_array( $rows ) && ! empty( $rows[0]['views'] ) ) {
			$views = (int) $rows[0]['views'];
		}
	}

	// Cache for 1 hour. Cache "null" as empty string so we don't refetch
	// every pageview on environments without Stats.
	set_transient( $cache_key, null === $views ? '' : (string) $views, HOUR_IN_SECONDS );

	return $views;
}
