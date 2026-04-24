<?php
/**
 * Renders the hamburger ("open") or close ("close") button for the mobile
 * navigation overlay.
 *
 * Paired with js/mobile-overlay.js, which looks up elements via the
 * data-mobile-overlay-open / data-mobile-overlay-close attributes emitted
 * here. The block encapsulates the SVG + button markup so header.html and
 * the mobile-overlay template part can stay pure-block.
 *
 * @package jobswp-2025
 */

$direction = isset( $attributes['direction'] ) && in_array( $attributes['direction'], array( 'open', 'close' ), true )
	? $attributes['direction']
	: 'open';
$target    = isset( $attributes['target'] ) && $attributes['target']
	? (string) $attributes['target']
	: 'mobile-overlay';

if ( 'close' === $direction ) {
	$label    = __( 'Close menu', 'jobswp-2025' );
	$classes  = 'mobile-overlay__close';
	$data_key = 'data-mobile-overlay-close';
	$svg_body = '<line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>';
} else {
	$label    = __( 'Open menu', 'jobswp-2025' );
	$classes  = 'mobile-menu-toggle';
	$data_key = 'data-mobile-overlay-open';
	$svg_body = '<line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line>';
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $classes ) );
?>
<button
	type="button"
	<?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	aria-label="<?php echo esc_attr( $label ); ?>"
	aria-controls="<?php echo esc_attr( $target ); ?>"
	<?php echo 'open' === $direction ? 'aria-expanded="false"' : ''; ?>
	<?php echo esc_attr( $data_key ); ?>
>
	<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
		<?php echo $svg_body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG markup. ?>
	</svg>
</button>
