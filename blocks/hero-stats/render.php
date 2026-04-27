<?php
/**
 * Renders the hero's three live stats (open positions, categories, remote %).
 *
 * Extracted from the home-hero pattern so the editor doesn't see a wp:html
 * block whose inner markup is dynamic — wp:html round-trips poorly through
 * the parser and triggered "Block contains unexpected or invalid content"
 * warnings on every Site Editor load. As a real dynamic block this renders
 * via ServerSideRender in the editor and PHP on the front end.
 *
 * @package jobswp-2025
 */

$snapshot = function_exists( 'jobswp_2025_homepage_snapshot' ) ? jobswp_2025_homepage_snapshot() : null;

if ( ! $snapshot ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'hero__stats' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="hero__stat">
		<span class="hero__stat-number"><?php echo esc_html( $snapshot['total_jobs'] ); ?></span>
		<span class="hero__stat-label"><?php esc_html_e( 'Open Positions', 'jobswp-2025' ); ?></span>
	</div>
	<div class="hero__stat">
		<span class="hero__stat-number"><?php echo esc_html( $snapshot['categories_with_jobs'] ); ?></span>
		<span class="hero__stat-label"><?php esc_html_e( 'Categories', 'jobswp-2025' ); ?></span>
	</div>
	<div class="hero__stat">
		<span class="hero__stat-number"><?php echo esc_html( $snapshot['remote_pct'] . '%' ); ?></span>
		<span class="hero__stat-label"><?php esc_html_e( 'Remote Friendly', 'jobswp-2025' ); ?></span>
	</div>
</section>
