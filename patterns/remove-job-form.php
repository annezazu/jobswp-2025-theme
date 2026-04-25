<?php
/**
 * Title: Remove a Job Form
 * Slug: jobswp-2025/remove-job-form
 * Categories: featured
 * Description: Token-input form for /remove-a-job/. Replaces the legacy jobswp-2025/remove-job-form custom block — submission is handled by the jobswp plugin's maybe_remove_job() action, so the only theme-side responsibilities are markup, the nonce, and rendering success/error notices set by the plugin.
 * Inserter: no
 */

if ( ! function_exists( 'jobswp_text_field' ) ) {
	return;
}
?>
<!-- wp:group {"className":"remove-job","layout":{"type":"default"}} -->
<div class="wp-block-group remove-job">

	<?php if ( isset( $_POST['errors'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>
		<!-- wp:html -->
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
		<!-- /wp:html -->
	<?php elseif ( isset( $_GET['removedjob'] ) && '1' === $_GET['removedjob'] ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
		<!-- wp:html -->
		<div class="notice notice-success">
			<strong><?php esc_html_e( 'Your job posting has been successfully removed.', 'jobswp-2025' ); ?></strong>
		</div>
		<!-- /wp:html -->
	<?php endif; ?>

	<!-- wp:html -->
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
	<!-- /wp:html -->

</div>
<!-- /wp:group -->
