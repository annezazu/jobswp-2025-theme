<?php
/**
 * Renders the Position Types sidebar — "All Openings" plus a list of all
 * job categories via the plugin's custom walker. Ported from sidebar.php.
 *
 * @package jobswp-2025
 */

if ( ! class_exists( 'Jobs_Dot_WP' ) ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'widget-area',
		'id'    => 'secondary',
		'role'  => 'complementary',
	)
);
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php do_action( 'before_sidebar' ); ?>

	<aside id="cats" class="widget">
		<h3 class="widget-title"><?php esc_html_e( 'Position Types', 'jobswp-2025' ); ?></h3>
		<a href="#" class="menu-jobs-toggle" aria-hidden="true"></a>
		<ul class="menu-jobs">
			<li class="job-cat-item job-cat-item-all">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'View all job openings', 'jobswp-2025' ); ?>">
					<?php esc_html_e( 'All Openings', 'jobswp-2025' ); ?>
				</a>
			</li>
			<?php Jobs_Dot_WP::list_job_categories(); ?>
		</ul>
	</aside>
</div>
