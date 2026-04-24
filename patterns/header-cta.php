<?php
/**
 * Title: Header CTA
 * Slug: jobswp-2025/header-cta
 * Description: "Post a Job" call-to-action shown in the site header and mobile overlay.
 * Categories: featured
 *
 * Pattern (PHP) rather than inline markup inside the template part so the
 * label stays translatable via __(). Uses core wp:buttons + wp:button so
 * the Site Editor shows a proper block preview instead of raw HTML.
 */
?>
<!-- wp:buttons {"className":"site-header__cta"} -->
<div class="wp-block-buttons site-header__cta">
	<!-- wp:button -->
	<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/post-a-job/' ) ); ?>"><?php esc_html_e( 'Post a Job', 'jobswp-2025' ); ?></a></div>
	<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
