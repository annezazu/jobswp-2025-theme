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
		<h1 class="wp-block-heading">Find Your Next WordPress <span class="hero__highlight">Opportunity</span></h1>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p>Browse open positions across the WordPress ecosystem — from development to design, support to community.</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"className":"hero__actions"} -->
		<div class="wp-block-buttons hero__actions">
			<!-- wp:button -->
			<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#jobs">Browse Jobs</a></div>
			<!-- /wp:button -->

			<!-- wp:button {"className":"is-style-outline"} -->
			<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/post-a-job/">Post a Job</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->

		<!-- wp:jobswp-2025/hero-stats /-->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
