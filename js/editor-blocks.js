/**
 * Client-side registration for the theme's dynamic blocks.
 *
 * The six theme blocks are registered server-side via
 * register_block_type_from_metadata() in functions.php and render via
 * their own render.php. Server registration is enough for the frontend
 * but the Site Editor still needs to find the block in its client-side
 * block registry — otherwise it shows
 *   "Your site doesn't include support for the <name> block"
 * on every saved block comment in every template.
 *
 * WP's auto-registration from block.json metadata is inconsistent across
 * setups (particularly for dynamic `render: file:…` blocks without an
 * editorScript), so we both (a) filter any blocks that DO auto-register
 * to attach an edit component, and (b) explicitly register any that
 * haven't after domReady. ServerSideRender makes the editor preview
 * match the frontend render.
 */
( function ( wp ) {
	if ( ! wp || ! wp.blocks || ! wp.element ) {
		return;
	}

	var createElement    = wp.element.createElement;
	var useBlockProps    = wp.blockEditor && wp.blockEditor.useBlockProps;
	var ServerSideRender = wp.serverSideRender;

	// Minimal metadata fallback for each block — matches block.json. Used
	// only when auto-registration didn't expose the block to the client.
	var FALLBACK_BLOCKS = {
		'jobswp-2025/job-browser': {
			title:    'Job Browser',
			category: 'theme',
		},
		'jobswp-2025/job-list': {
			title:    'Job List',
			category: 'theme',
		},
		'jobswp-2025/archive-header': {
			title:    'Archive Header',
			category: 'theme',
		},
		'jobswp-2025/job-meta-card': {
			title:    'Job Meta Card',
			category: 'theme',
		},
		'jobswp-2025/open-to-work-candidates': {
			title:    'Open to Work Candidates',
			category: 'theme',
		},
		'jobswp-2025/hero-stats': {
			title:    'Hero Stats',
			category: 'theme',
		},
	};

	function renderEdit( name ) {
		return function ( props ) {
			var wrapperProps = useBlockProps ? useBlockProps() : {};

			if ( ServerSideRender ) {
				return createElement(
					'div',
					wrapperProps,
					createElement( ServerSideRender, {
						block:      name,
						attributes: props.attributes,
					} )
				);
			}

			return createElement(
				'div',
				wrapperProps,
				createElement( 'strong', null, FALLBACK_BLOCKS[ name ].title )
			);
		};
	}

	// (a) Filter any auto-registered blocks to attach the edit component
	// before they finalise. This keeps whatever metadata the server
	// emitted (attributes, supports, etc.) intact.
	if ( wp.hooks && wp.hooks.addFilter ) {
		wp.hooks.addFilter(
			'blocks.registerBlockType',
			'jobswp-2025/add-server-side-edit',
			function ( settings, name ) {
				if ( ! FALLBACK_BLOCKS[ name ] || settings.edit ) {
					return settings;
				}
				settings.edit = renderEdit( name );
				settings.save = function () {
					return null;
				};
				return settings;
			}
		);
	}

	// (b) After the editor has loaded, register any blocks that weren't
	// auto-registered. These are full registerBlockType calls with the
	// minimal metadata above — the server render still provides the real
	// attributes; we only need enough for the editor to recognise the
	// block comment.
	function registerMissing() {
		Object.keys( FALLBACK_BLOCKS ).forEach( function ( name ) {
			if ( wp.blocks.getBlockType( name ) ) {
				return;
			}
			wp.blocks.registerBlockType( name, {
				apiVersion: 3,
				title:      FALLBACK_BLOCKS[ name ].title,
				category:   FALLBACK_BLOCKS[ name ].category,
				edit:       renderEdit( name ),
				save:       function () {
					return null;
				},
			} );
		} );
	}

	if ( wp.domReady ) {
		wp.domReady( registerMissing );
	} else if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', registerMissing );
	} else {
		registerMissing();
	}
} )( window.wp );
