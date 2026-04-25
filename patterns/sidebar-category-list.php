<?php
/**
 * Title: Position Types Sidebar
 * Slug: jobswp-2025/sidebar-category-list
 * Categories: featured
 * Description: Sidebar widget listing the "All Openings" link and every job_category term. Replaces the legacy jobswp-2025/sidebar-category-list custom block — uses core/categories pointed at the job_category taxonomy so editors get the standard category-list UI.
 * Inserter: yes
 */
?>
<!-- wp:group {"tagName":"aside","className":"widget-area","layout":{"type":"default"}} -->
<aside class="wp-block-group widget-area" id="secondary" role="complementary">

	<!-- wp:heading {"level":3,"className":"widget-title"} -->
	<h3 class="wp-block-heading widget-title"><?php esc_html_e( 'Position Types', 'jobswp-2025' ); ?></h3>
	<!-- /wp:heading -->

	<!-- wp:list {"className":"job-cat-list job-cat-list--all"} -->
	<ul class="wp-block-list job-cat-list job-cat-list--all">
		<!-- wp:list-item -->
		<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'View all job openings', 'jobswp-2025' ); ?>"><?php esc_html_e( 'All Openings', 'jobswp-2025' ); ?></a></li>
		<!-- /wp:list-item -->
	</ul>
	<!-- /wp:list -->

	<!-- wp:categories {"taxonomy":"job_category","className":"job-cat-list"} /-->

</aside>
<!-- /wp:group -->
