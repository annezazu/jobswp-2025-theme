<?php
/**
 * Renders the "People Open to Work" candidates section.
 *
 * Data comes from the jobswp plugin's `jobswp_get_open_to_work_candidates()`
 * wrapper around profiles.wordpress.org's /wp-json/wporg-profiles/v1/jobs/
 * open-to-work endpoint (cached for an hour per page). Ported from the
 * classic theme's content-home.php `.candidates-section` block.
 *
 * Pagination is server-side via the `cp` query arg so the section is fully
 * functional without JavaScript and search engines can index every page.
 *
 * @package jobswp-2025
 */

if ( ! function_exists( 'jobswp_get_open_to_work_candidates' ) ) {
	return;
}

$per_page         = max( 1, (int) ( $attributes['perPage'] ?? 10 ) );
$current_page     = isset( $_GET['cp'] ) ? max( 1, absint( $_GET['cp'] ) ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$result           = jobswp_get_open_to_work_candidates( $current_page, $per_page );
$candidates       = $result['candidates'];
$total_candidates = (int) $result['total'];
$total_pages      = (int) $result['pages'];

if ( empty( $candidates ) ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'candidates-section',
		'id'    => 'candidates',
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="candidates-section__inner">

		<div class="candidates-section__header">
			<div>
				<h2><?php esc_html_e( 'People Open to Work', 'jobswp-2025' ); ?></h2>
				<p><?php esc_html_e( 'WordPress community members who are looking for their next opportunity.', 'jobswp-2025' ); ?></p>
			</div>
			<span class="candidates-section__count">
				<?php
				printf(
					/* translators: %s: number of candidates */
					esc_html( _n( '%s person', '%s people', $total_candidates, 'jobswp-2025' ) ),
					esc_html( number_format_i18n( $total_candidates ) )
				);
				?>
			</span>
		</div>

		<div class="candidates-grid">
			<?php foreach ( $candidates as $candidate ) : ?>
				<?php
				$current_role    = '';
				$current_company = '';
				if ( ! empty( $candidate->positions ) ) {
					$latest          = $candidate->positions[0];
					$current_role    = isset( $latest->role ) ? $latest->role : '';
					$current_company = isset( $latest->company ) ? $latest->company : '';
				}
				$avatar_url = 'https://wordpress.org/grav-redirect.php?user=' . rawurlencode( $candidate->user_login ) . '&s=80';
				?>
				<a href="<?php echo esc_url( $candidate->profile_url ); ?>" class="candidate-card" target="_blank" rel="noopener noreferrer">
					<div class="candidate-card__avatar">
						<img
							src="<?php echo esc_url( $avatar_url ); ?>"
							alt=""
							width="80"
							height="80"
							loading="lazy"
						/>
					</div>
					<div class="candidate-card__info">
						<span class="candidate-card__badge"><?php esc_html_e( 'Open to Work', 'jobswp-2025' ); ?></span>
						<h3 class="candidate-card__name"><?php echo esc_html( $candidate->display_name ); ?></h3>
						<?php if ( $current_role || $current_company ) : ?>
							<p class="candidate-card__role">
								<?php echo esc_html( $current_role ); ?>
								<?php if ( $current_company ) : ?>
									<span class="candidate-card__company"><?php echo esc_html( $current_company ); ?></span>
								<?php endif; ?>
							</p>
						<?php endif; ?>
					</div>
					<span class="candidate-card__arrow" aria-hidden="true">&#8594;</span>
				</a>
			<?php endforeach; ?>
		</div>

		<?php if ( $total_pages > 1 ) : ?>
			<nav class="candidates-pagination" aria-label="<?php esc_attr_e( 'Candidates pagination', 'jobswp-2025' ); ?>">
				<?php if ( $current_page > 1 ) : ?>
					<a href="<?php echo esc_url( add_query_arg( 'cp', $current_page - 1, home_url( '/' ) ) . '#candidates' ); ?>" class="candidates-pagination__link">&larr; <?php esc_html_e( 'Previous', 'jobswp-2025' ); ?></a>
				<?php else : ?>
					<span class="candidates-pagination__link candidates-pagination__link--disabled">&larr; <?php esc_html_e( 'Previous', 'jobswp-2025' ); ?></span>
				<?php endif; ?>

				<span class="candidates-pagination__info">
					<?php
					printf(
						/* translators: 1: current page, 2: total pages */
						esc_html__( 'Page %1$s of %2$s', 'jobswp-2025' ),
						esc_html( number_format_i18n( $current_page ) ),
						esc_html( number_format_i18n( $total_pages ) )
					);
					?>
				</span>

				<?php if ( $current_page < $total_pages ) : ?>
					<a href="<?php echo esc_url( add_query_arg( 'cp', $current_page + 1, home_url( '/' ) ) . '#candidates' ); ?>" class="candidates-pagination__link"><?php esc_html_e( 'Next', 'jobswp-2025' ); ?> &rarr;</a>
				<?php else : ?>
					<span class="candidates-pagination__link candidates-pagination__link--disabled"><?php esc_html_e( 'Next', 'jobswp-2025' ); ?> &rarr;</span>
				<?php endif; ?>
			</nav>
		<?php endif; ?>

		<p class="candidates-section__cta">
			<?php
			echo wp_kses(
				sprintf(
					/* translators: %s: URL to update WordPress.org profile */
					__( 'Want to appear here? <a href="%s">Update your WordPress.org profile</a> and toggle "Open to Work" in the Jobs section.', 'jobswp-2025' ),
					'https://profiles.wordpress.org/me/profile/edit/'
				),
				array( 'a' => array( 'href' => array() ) )
			);
			?>
		</p>

	</div>
</section>
