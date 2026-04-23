/**
 * Client-side registration for the theme's dynamic blocks.
 *
 * Each block is registered server-side via register_block_type_from_metadata()
 * in functions.php and renders via its own render.php. That's enough to paint
 * correctly on the frontend, but the Site Editor needs a client-side block
 * registration to recognise the block comments in saved content — otherwise
 * it shows "Your site doesn't include support for the <name> block".
 *
 * We use ServerSideRender as the edit component so the editor previews match
 * what the user will see on the frontend (the actual render.php output) with
 * no second implementation to keep in sync.
 */
( function ( wp ) {
	if ( ! wp || ! wp.blocks || ! wp.element ) {
		return;
	}

	var registerBlockType = wp.blocks.registerBlockType;
	var getBlockType      = wp.blocks.getBlockType;
	var createElement     = wp.element.createElement;
	var useBlockProps     = wp.blockEditor && wp.blockEditor.useBlockProps;
	var ServerSideRender  = wp.serverSideRender;

	if ( ! ServerSideRender || ! useBlockProps ) {
		return;
	}

	var blocks = [
		'jobswp-2025/job-browser',
		'jobswp-2025/job-list',
		'jobswp-2025/archive-header',
		'jobswp-2025/job-meta-card',
		'jobswp-2025/sidebar-category-list',
		'jobswp-2025/remove-job-form',
		'jobswp-2025/open-to-work-candidates',
	];

	blocks.forEach( function ( name ) {
		// Skip blocks that already have a registered edit component
		// (defensive — in case someone adds one later).
		var existing = getBlockType( name );
		if ( existing && existing.edit ) {
			return;
		}

		registerBlockType( name, {
			edit: function ( props ) {
				return createElement(
					'div',
					useBlockProps(),
					createElement( ServerSideRender, {
						block: name,
						attributes: props.attributes,
					} )
				);
			},
			save: function () {
				// Server-rendered — saved content is just the block comment.
				return null;
			},
		} );
	} );
} )( window.wp );
