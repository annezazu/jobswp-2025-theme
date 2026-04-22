<?php
/**
 * Renders the job list table for archive/search pages. Ported from
 * content-list.php.
 *
 * @package jobswp-2025
 */

if ( ! function_exists( 'jobswp_get_job_meta' ) ) {
	return;
}

global $wp_query;

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'job-list' ) );
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( $wp_query->have_posts() ) : ?>
		<?php
		$evenodd = 0;
		while ( $wp_query->have_posts() ) :
			$wp_query->the_post();
			$evenodd = abs( $evenodd - 1 );
			?>
			<div class="row row-<?php echo esc_attr( $evenodd ); ?>">
				<div class="job-date"><?php echo esc_html( get_the_date( 'M j' ) ); ?></div>
				<div class="job-title">
					<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
				</div>
				<div class="job-type"><?php echo esc_html( jobswp_get_job_meta( get_the_ID(), 'jobtype' ) ); ?></div>
				<div class="job-location">
					<?php
					echo wp_kses(
						jobswp_get_job_meta( get_the_ID(), 'location' ),
						array(
							'a' => array(
								'href' => array(),
								'rel'  => array(),
							),
						)
					);
					?>
				</div>
				<div class="clear"></div>
			</div>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>

		<?php
		if ( is_tax( 'job_category' ) ) :
			$term = get_queried_object();
			if ( $term ) :
				$all_link = get_term_link( $term, 'job_category' );
				?>
				<p class="all-job-categories">
					<a
						href="<?php echo esc_url( $all_link ); ?>"
						title="<?php echo esc_attr( sprintf( /* translators: %s: category name */ __( 'View all jobs filed under %s', 'jobswp-2025' ), $term->name ) ); ?>"
					>
						<?php
						printf(
							/* translators: %s: category name */
							esc_html__( 'Show all %s jobs &raquo;', 'jobswp-2025' ),
							esc_html( $term->name )
						);
						?>
					</a>
				</p>
				<?php
			endif;
		endif;
		?>
	<?php else : ?>
		<div class="row row-1">
			<div class="no-job">
				<?php
				echo wp_kses(
					sprintf(
						/* translators: %s: URL to the post-a-job page */
						__( 'There are no jobs here. If you are hiring, you can <a href="%s">post a new job</a>.', 'jobswp-2025' ),
						esc_url( home_url( '/post-a-job/' ) )
					),
					array( 'a' => array( 'href' => array() ) )
				);
				?>
			</div>
		</div>
	<?php endif; ?>

	<div class="clear"></div>
</div>
