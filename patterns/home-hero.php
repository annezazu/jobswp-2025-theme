<?php
/**
 * Title: Home Hero
 * Slug: jobswp-2025/home-hero
 * Categories: featured, banner
 * Description: Homepage hero with title, subtitle, call-to-action buttons, and live stats.
 * Keywords: hero, homepage, landing
 * Inserter: yes
 */
?>
<!-- wp:group {"align":"full","className":"hero","layout":{"type":"default"}} -->
<section class="wp-block-group alignfull hero">
	<!-- wp:group {"className":"hero__inner","layout":{"type":"default"}} -->
	<div class="wp-block-group hero__inner">
		<!-- wp:heading {"level":1} -->
		<h1 class="wp-block-heading"><?php
			printf(
				/* translators: %s: highlighted word "Opportunity" */
				esc_html__( 'Find Your Next WordPress %s', 'jobswp-2025' ),
				'<span class="hero__highlight">' . esc_html__( 'Opportunity', 'jobswp-2025' ) . '</span>'
			);
		?></h1>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p><?php esc_html_e( 'Browse open positions across the WordPress ecosystem — from development to design, support to community.', 'jobswp-2025' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"className":"hero__actions"} -->
		<div class="wp-block-buttons hero__actions">
			<!-- wp:button -->
			<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#jobs"><?php esc_html_e( 'Browse Jobs', 'jobswp-2025' ); ?></a></div>
			<!-- /wp:button -->

			<!-- wp:button {"className":"is-style-outline"} -->
			<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/post-a-job/"><?php esc_html_e( 'Post a Job', 'jobswp-2025' ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->

		<?php
		$snapshot = function_exists( 'jobswp_2025_homepage_snapshot' ) ? jobswp_2025_homepage_snapshot() : null;
		if ( $snapshot ) :
			?>
			<!-- wp:html -->
			<section class="hero__stats">
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
			<!-- /wp:html -->
		<?php endif; ?>
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
