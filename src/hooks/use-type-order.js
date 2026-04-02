/**
 * Type Order Hook
 *
 * Manages the ordering of reference type taxonomies within the block,
 * including initialization and move up/down operations.
 *
 * @since 0.1.0
 */

/**
 * WordPress dependencies
 */
import { useEffect, useMemo } from '@wordpress/element';

/**
 * Custom hook for type ordering
 *
 * Handles initialization and reordering of reference type taxonomies.
 *
 * @param {Object}      params               Hook parameters.
 * @param {Object|null} params.config        Block configuration object.
 * @param {Array}       params.typeOrder     Current type order attribute.
 * @param {Function}    params.setAttributes Function to update block attributes.
 * @return {Object} Type order operations including orderedTypeKeys, moveTypeUp, and moveTypeDown.
 */
export default function useTypeOrder( { config, typeOrder, setAttributes } ) {
	/**
	 * Get ordered type keys based on typeOrder attribute or default config order
	 */
	const orderedTypeKeys = useMemo( () => {
		if ( ! config || ! config.ref_types ) {
			return [];
		}

		if ( typeOrder && Array.isArray( typeOrder ) && typeOrder.length > 0 ) {
			const validOrder = typeOrder.filter( ( type ) =>
				config.ref_types.includes( type )
			);

			const missingTypes = config.ref_types.filter(
				( type ) => ! validOrder.includes( type )
			);

			return [ ...validOrder, ...missingTypes ];
		}

		return config.ref_types;
	}, [ config, typeOrder ] );

	/**
	 * Initialize typeOrder attribute if not set
	 */
	useEffect( () => {
		if ( config && config.ref_types && ! typeOrder ) {
			setAttributes( { typeOrder: config.ref_types } );
		}
	}, [ config, typeOrder, setAttributes ] );

	/**
	 * Move type up in order
	 *
	 * @param {string} typeKey The key of the type to move up.
	 */
	const moveTypeUp = ( typeKey ) => {
		const currentIndex = orderedTypeKeys.indexOf( typeKey );
		if ( currentIndex <= 0 ) {
			return;
		}

		const newOrder = [ ...orderedTypeKeys ];
		const temp = newOrder[ currentIndex - 1 ];
		newOrder[ currentIndex - 1 ] = newOrder[ currentIndex ];
		newOrder[ currentIndex ] = temp;

		setAttributes( { typeOrder: newOrder } );
	};

	/**
	 * Move type down in order
	 *
	 * @param {string} typeKey The key of the type to move down.
	 */
	const moveTypeDown = ( typeKey ) => {
		const currentIndex = orderedTypeKeys.indexOf( typeKey );
		if (
			currentIndex === -1 ||
			currentIndex >= orderedTypeKeys.length - 1
		) {
			return;
		}

		const newOrder = [ ...orderedTypeKeys ];
		const temp = newOrder[ currentIndex + 1 ];
		newOrder[ currentIndex + 1 ] = newOrder[ currentIndex ];
		newOrder[ currentIndex ] = temp;

		setAttributes( { typeOrder: newOrder } );
	};

	return {
		orderedTypeKeys,
		moveTypeUp,
		moveTypeDown,
	};
}
