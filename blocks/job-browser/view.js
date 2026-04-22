/**
 * Interactivity store for the Job Browser block.
 *
 * Filters job cards by category when a pill is clicked. Per-pill and per-card
 * context carries the category slug; shared state tracks the active category.
 */
import { store, getContext } from '@wordpress/interactivity';

const { state } = store( 'jobswp/jobBrowser', {
	state: {
		activeCategory: 'all',

		get isActive() {
			const context = getContext();
			return state.activeCategory === context.slug;
		},

		get isHidden() {
			if ( 'all' === state.activeCategory ) {
				return false;
			}
			const context = getContext();
			return state.activeCategory !== context.slug;
		},

		get hasResults() {
			const context = getContext();
			const count = context.counts?.[ state.activeCategory ] ?? 0;
			return count > 0;
		},
	},
	actions: {
		setCategory() {
			const context = getContext();
			state.activeCategory = context.slug;
		},
	},
} );
