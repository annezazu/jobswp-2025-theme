<?php
/**
 * Title: Home Hero
 * Slug: jobswp-2025/home-hero
 * Categories: featured, banner
 * Description: Homepage hero with title, subtitle, and call-to-action buttons.
 * Keywords: hero, homepage, landing
 * Inserter: yes
 */
?>
<!-- wp:group {"className":"hero","layout":{"type":"default"}} -->
<section class="wp-block-group hero">
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
			<!-- wp:button {"className":"btn btn-primary is-style-fill"} -->
			<div class="wp-block-button btn btn-primary is-style-fill"><a class="wp-block-button__link wp-element-button" href="#jobs"><?php esc_html_e( 'Browse Jobs', 'jobswp-2025' ); ?></a></div>
			<!-- /wp:button -->

			<!-- wp:button {"className":"btn btn-outline is-style-outline"} -->
			<div class="wp-block-button btn btn-outline is-style-outline"><a class="wp-block-button__link wp-element-button" href="/post-a-job/"><?php esc_html_e( 'Post a Job', 'jobswp-2025' ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
