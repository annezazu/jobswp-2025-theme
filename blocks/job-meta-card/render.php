<?php
/**
 * Renders the Job Meta Card block — the sidebar card on single job pages.
 *
 * Ported from content-single.php's aside.job-sidebar markup.
 *
 * @package jobswp-2025
 */

if ( ! function_exists( 'jobswp_get_job_meta' ) ) {
	return;
}

$post_id = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();
if ( ! $post_id ) {
	return;
}

$fields = array(
	'company'    => __( 'Company', 'jobswp-2025' ),
	'jobtype'    => __( 'Job Type', 'jobswp-2025' ),
	'location'   => __( 'Location', 'jobswp-2025' ),
	'budget'     => __( 'Budget', 'jobswp-2025' ),
	'howtoapply' => __( 'How to Apply', 'jobswp-2025' ),
);

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'job-sidebar' ) );
$allowed_link       = array(
	'a' => array(
		'href' => array(),
		'rel'  => array(),
	),
);
?>
<aside <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="job-sidebar__card">
		<h3><?php esc_html_e( 'Job Details', 'jobswp-2025' ); ?></h3>
		<?php foreach ( $fields as $field_key => $field_label ) :
			$value = jobswp_get_job_meta( $post_id, $field_key );
			if ( ! $value ) {
				continue;
			}
			?>
			<div class="job-sidebar__detail">
				<span class="job-sidebar__detail-label"><?php echo esc_html( $field_label ); ?></span>
				<span class="job-sidebar__detail-value"><?php echo wp_kses( $value, $allowed_link ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
</aside>
