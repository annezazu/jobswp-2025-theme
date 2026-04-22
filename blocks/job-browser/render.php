<?php
/**
 * Renders the homepage job browser — hero stats, filter pills, job cards grid.
 *
 * Ported from content-home.php. Filtering is handled client-side by view.js
 * via the Interactivity API.
 *
 * @package jobswp-2025
 */

if ( ! class_exists( 'Jobs_Dot_WP' ) || ! function_exists( 'jobswp_get_job_meta' ) ) {
	return;
}

$job_categories = Jobs_Dot_WP::get_job_categories();

$all_jobs             = array();
$category_map         = array();
$total_jobs           = 0;
$remote_count         = 0;
$categories_with_jobs = 0;
$counts               = array( 'all' => 0 );

if ( $job_categories ) {
	foreach ( $job_categories as $cat ) {
		$jobs                 = Jobs_Dot_WP::get_jobs_for_category( $cat );
		$counts[ $cat->slug ] = count( $jobs );

		if ( ! empty( $jobs ) ) {
			$categories_with_jobs++;

			foreach ( $jobs as $job ) {
				if ( isset( $category_map[ $job->ID ] ) ) {
					continue;
				}
				$all_jobs[]               = $job;
				$category_map[ $job->ID ] = $cat->slug;
				$total_jobs++;

				$location = get_post_meta( $job->ID, 'location', true );
				if (
					empty( $location ) ||
					'N/A' === $location ||
					false !== stripos( $location, 'remote' ) ||
					false !== stripos( $location, 'anywhere' )
				) {
					$remote_count++;
				}
			}
		}
	}
	$counts['all'] = $total_jobs;
}

$remote_pct         = $total_jobs > 0 ? round( ( $remote_count / $total_jobs ) * 100 ) : 0;
$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'job-browser' ) );
$interactivity_ctx  = array( 'counts' => $counts );
?>
<div
	<?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	data-wp-interactive="jobswp/jobBrowser"
	<?php echo wp_interactivity_data_wp_context( $interactivity_ctx ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
>

	<section class="hero__stats">
		<div class="hero__stat">
			<span class="hero__stat-number"><?php echo esc_html( $total_jobs ); ?></span>
			<span class="hero__stat-label"><?php esc_html_e( 'Open Positions', 'jobswp-2025' ); ?></span>
		</div>
		<div class="hero__stat">
			<span class="hero__stat-number"><?php echo esc_html( $categories_with_jobs ); ?></span>
			<span class="hero__stat-label"><?php esc_html_e( 'Categories', 'jobswp-2025' ); ?></span>
		</div>
		<div class="hero__stat">
			<span class="hero__stat-number"><?php echo esc_html( $remote_pct . '%' ); ?></span>
			<span class="hero__stat-label"><?php esc_html_e( 'Remote Friendly', 'jobswp-2025' ); ?></span>
		</div>
	</section>

	<section class="filters" id="jobs">
		<p class="filters__label"><?php esc_html_e( 'Filter by category', 'jobswp-2025' ); ?></p>
		<div class="filters__pills">
			<button
				type="button"
				class="filter-pill active"
				data-wp-context='{"slug":"all"}'
				data-wp-on--click="actions.setCategory"
				data-wp-class--active="state.isActive"
			><?php esc_html_e( 'All', 'jobswp-2025' ); ?></button>

			<?php if ( $job_categories ) : ?>
				<?php foreach ( $job_categories as $cat ) : ?>
					<button
						type="button"
						class="filter-pill"
						data-wp-context='<?php echo esc_attr( wp_json_encode( array( 'slug' => $cat->slug ) ) ); ?>'
						data-wp-on--click="actions.setCategory"
						data-wp-class--active="state.isActive"
					><?php echo esc_html( $cat->name ); ?></button>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</section>

	<section class="jobs-section">
		<div class="jobs-grid">
			<?php if ( ! empty( $all_jobs ) ) : ?>
				<?php foreach ( $all_jobs as $job ) :
					$cat_slug         = isset( $category_map[ $job->ID ] ) ? $category_map[ $job->ID ] : '';
					$cat_term         = $cat_slug ? get_term_by( 'slug', $cat_slug, 'job_category' ) : null;
					$cat_name         = $cat_term ? $cat_term->name : '';
					$company          = get_post_meta( $job->ID, 'company', true );
					$location_raw     = get_post_meta( $job->ID, 'location', true );
					$jobtype          = jobswp_get_job_meta( $job->ID, 'jobtype' );
					$is_remote        = empty( $location_raw ) || 'N/A' === $location_raw || false !== stripos( $location_raw, 'remote' ) || false !== stripos( $location_raw, 'anywhere' );
					$location_display = $is_remote ? __( 'Remote', 'jobswp-2025' ) : $location_raw;
					$location_icon    = $is_remote ? '&#127758;' : '&#128205;';
					$type_icon        = '&#128188;';
					if ( 'Contract' === $jobtype || 'Project' === $jobtype ) {
						$type_icon = '&#128196;';
					} elseif ( 'Part Time' === $jobtype ) {
						$type_icon = '&#9201;';
					}
					?>
					<a
						href="<?php echo esc_url( get_permalink( $job->ID ) ); ?>"
						class="job-card"
						data-wp-context='<?php echo esc_attr( wp_json_encode( array( 'slug' => $cat_slug ) ) ); ?>'
						data-wp-bind--hidden="state.isHidden"
					>
						<?php if ( $cat_name ) : ?>
							<span class="job-card__badge"><?php echo esc_html( $cat_name ); ?></span>
						<?php endif; ?>
						<h2 class="job-card__title"><?php echo esc_html( get_the_title( $job->ID ) ); ?></h2>
						<?php if ( $company ) : ?>
							<p class="job-card__company"><?php echo esc_html( $company ); ?></p>
						<?php endif; ?>
						<div class="job-card__meta">
							<span><?php echo $location_icon . ' ' . esc_html( $location_display ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<?php if ( $jobtype && 'N/A' !== $jobtype ) : ?>
								<span><?php echo $type_icon . ' ' . esc_html( $jobtype ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<?php endif; ?>
						</div>
						<p class="job-card__date">
							<?php
							printf(
								/* translators: %s: date the job was posted */
								esc_html__( 'Posted %s', 'jobswp-2025' ),
								esc_html( get_the_date( 'F j, Y', $job->ID ) )
							);
							?>
						</p>
					</a>
				<?php endforeach; ?>
			<?php endif; ?>

			<div
				class="jobs-empty"
				<?php echo ! empty( $all_jobs ) ? 'hidden' : ''; ?>
				data-wp-bind--hidden="state.hasResults"
			>
				<p>
					<?php
					echo wp_kses(
						sprintf(
							/* translators: %s: URL to the post-a-job page */
							__( 'There are no jobs in this category. If you are hiring, you can <a href="%s">post a new job</a>.', 'jobswp-2025' ),
							esc_url( home_url( '/post-a-job/' ) )
						),
						array( 'a' => array( 'href' => array() ) )
					);
					?>
				</p>
			</div>
		</div>
	</section>
</div>
