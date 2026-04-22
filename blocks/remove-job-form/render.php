<?php
/**
 * Renders the Remove a Job form. Ported from page-remove-a-job.php.
 *
 * Submission is handled by the jobswp plugin's maybe_remove_job() action,
 * which is already hooked to `wp`. The plugin sets $_POST['errors'] on
 * validation failure and redirects to ?removedjob=1 on success.
 *
 * @package jobswp-2025
 */

if ( ! function_exists( 'jobswp_text_field' ) ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'remove-job' ) );
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

	<?php if ( isset( $_POST['errors'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>
		<div class="notice notice-error">
			<?php
			if ( is_string( $_POST['errors'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
				printf(
					/* translators: %s: error message */
					wp_kses_post( __( '<strong>ERROR:</strong> %s', 'jobswp-2025' ) ),
					esc_html( sanitize_text_field( wp_unslash( $_POST['errors'] ) ) ) // phpcs:ignore WordPress.Security.NonceVerification.Missing
				);
			} else {
				echo wp_kses_post( __( '<strong>ERROR:</strong> One or more required fields are missing a value.', 'jobswp-2025' ) );
			}
			do_action( 'jobswp_notice', 'error' );
			?>
		</div>
	<?php elseif ( isset( $_GET['removedjob'] ) && '1' === $_GET['removedjob'] ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
		<div class="notice notice-success">
			<strong><?php esc_html_e( 'Your job posting has been successfully removed.', 'jobswp-2025' ); ?></strong>
		</div>
	<?php endif; ?>

	<form class="post-job" method="post" action="">
		<?php jobswp_text_field( 'job_token', __( 'Job Token:', 'jobswp-2025' ) ); ?>

		<input type="hidden" name="removejob" value="1" />
		<?php wp_nonce_field( 'jobswpremovejob' ); ?>
		<?php do_action( 'jobswp_remove_job_form' ); ?>

		<input
			class="btn btn-primary submit-job"
			type="submit"
			name="submitjob"
			value="<?php esc_attr_e( 'Remove job', 'jobswp-2025' ); ?>"
		/>
	</form>
</div>
