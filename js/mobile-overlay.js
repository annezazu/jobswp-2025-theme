/**
 * Mobile navigation overlay toggle.
 *
 * Drives the template part at parts/mobile-overlay.html. Keeps markup from
 * parts/header.html (hamburger button) and the overlay template part in sync
 * via aria-expanded / hidden attributes, locks body scroll while open, and
 * closes on Esc or backdrop click. Mirrors the behaviour of core/navigation's
 * overlay without its layout constraints (the CTA lives inside the overlay
 * instead of beside the hamburger).
 */
( function () {
	'use strict';

	function init() {
		var openers  = document.querySelectorAll( '[data-mobile-overlay-open]' );
		var closers  = document.querySelectorAll( '[data-mobile-overlay-close]' );
		var overlay  = document.getElementById( 'mobile-overlay' );

		if ( ! overlay || ! openers.length ) {
			return;
		}

		// Apply dialog semantics here rather than in the template part so the
		// wp:group block's saved markup matches what the Site Editor expects
		// to parse (role/aria attrs aren't wp:group block attributes).
		// Visibility is handled by CSS via the `.is-open` class — toggling a
		// class avoids the flash-of-visible-content the `hidden` attribute
		// would cause before JS runs.
		overlay.setAttribute( 'role', 'dialog' );
		overlay.setAttribute( 'aria-modal', 'true' );
		if ( ! overlay.hasAttribute( 'aria-label' ) ) {
			overlay.setAttribute( 'aria-label', 'Site menu' );
		}

		function open() {
			overlay.classList.add( 'is-open' );
			document.body.classList.add( 'has-mobile-overlay-open' );
			openers.forEach( function ( btn ) {
				btn.setAttribute( 'aria-expanded', 'true' );
			} );
			// Move focus into the overlay for keyboard users.
			var firstFocusable = overlay.querySelector( 'button, a, [tabindex]' );
			if ( firstFocusable ) {
				firstFocusable.focus();
			}
		}

		function close() {
			overlay.classList.remove( 'is-open' );
			document.body.classList.remove( 'has-mobile-overlay-open' );
			openers.forEach( function ( btn ) {
				btn.setAttribute( 'aria-expanded', 'false' );
				btn.focus();
			} );
		}

		openers.forEach( function ( btn ) {
			btn.addEventListener( 'click', open );
		} );
		closers.forEach( function ( btn ) {
			btn.addEventListener( 'click', close );
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' && overlay.classList.contains( 'is-open' ) ) {
				close();
			}
		} );

		// Clicking a nav link inside the overlay also closes it so the user
		// lands on the destination page without a stuck overlay.
		overlay.addEventListener( 'click', function ( event ) {
			if ( event.target.closest( 'a[href]' ) ) {
				close();
			}
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
