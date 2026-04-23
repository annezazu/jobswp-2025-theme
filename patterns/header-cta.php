<?php
/**
 * Title: Header CTA
 * Slug: jobswp-2025/header-cta
 * Description: "Post a Job" call-to-action button shown in the site header.
 * Categories: featured
 *
 * Rendered as a bare <a> rather than a wp:button so the .btn/.btn-primary/
 * .btn-sm CSS (designed for the classic theme's anchor-based buttons) applies
 * directly to the styleable element. Placed in a pattern rather than inline
 * in parts/header.html so the label can be localised via __(), which static
 * template parts cannot do.
 */
?>
<!-- wp:html -->
<a class="btn btn-primary btn-sm" href="<?php echo esc_url( home_url( '/post-a-job/' ) ); ?>"><?php esc_html_e( 'Post a Job', 'jobswp-2025' ); ?></a>
<!-- /wp:html -->
