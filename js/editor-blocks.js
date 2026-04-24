/**
 * Client-side edit component for the theme's dynamic blocks.
 *
 * Each block is registered server-side via register_block_type_from_metadata()
 * in functions.php and renders via its own render.php. That's enough for the
 * frontend, but the Site Editor needs a client-side edit() function to render
 * the block in the canvas — otherwise it shows "Your site doesn't include
 * support for the <name> block" for each saved block comment.
 *
 * We hook blocks.registerBlockType so our edit component is attached as the
 * block's metadata auto-registers from the server — no duplicate
 * registerBlockType call, no duplicated metadata to keep in sync with
 * block.json. ServerSideRender means the editor preview matches render.php.
 */
( function ( wp ) {
	if ( ! wp || ! wp.hooks || ! wp.element ) {
		return;
	}

	wp.hooks.addFilter(
		'blocks.registerBlockType',
		'jobswp-2025/add-server-side-edit',
		function ( settings, name ) {
			if ( 'string' !== typeof name || 0 !== name.indexOf( 'jobswp-2025/' ) ) {
				return settings;
			}

			// No further filter work needed — any block under the
			// `jobswp-2025/` namespace gets the same server-render edit
			// component. (job-browser, job-list, archive-header,
			// job-meta-card, sidebar-category-list, remove-job-form,
			// open-to-work-candidates, menu-toggle.)

			// Don't clobber an edit component the block already ships.
			if ( settings.edit ) {
				return settings;
			}

			var createElement    = wp.element.createElement;
			var useBlockProps    = wp.blockEditor && wp.blockEditor.useBlockProps;
			var ServerSideRender = wp.serverSideRender;

			settings.edit = function ( props ) {
				var wrapperProps = useBlockProps ? useBlockProps() : {};

				// Prefer a live ServerSideRender preview so editor output
				// matches the frontend. Fall back to a labelled placeholder
				// when the component isn't available (e.g. Gutenberg plugin
				// versions that package it differently).
				var inner;
				if ( ServerSideRender ) {
					inner = createElement( ServerSideRender, {
						block:      name,
						attributes: props.attributes,
					} );
				} else {
					inner = createElement(
						'div',
						{ className: 'jobswp-2025-block-placeholder' },
						createElement( 'strong', null, settings.title || name ),
						settings.description
							? createElement( 'p', null, settings.description )
							: null
					);
				}

				return createElement( 'div', wrapperProps, inner );
			};

			settings.save = function () {
				// All seven blocks are server-rendered; the saved content is
				// just the block comment.
				return null;
			};

			return settings;
		}
	);
} )( window.wp );
