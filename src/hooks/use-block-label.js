/**
 * Block Label Hook
 *
 * Manages the dynamic block metadata label that appears in the
 * editor's list view, reflecting the current filter configuration.
 *
 * @since 0.1.0
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useEffect, useRef } from '@wordpress/element';

/**
 * Custom hook for dynamic block label management
 *
 * Updates the block's metadata.name attribute to reflect current filters
 * in the editor's list view. Uses a ref to prevent unnecessary updates.
 *
 * @param {Object}   params               Hook parameters.
 * @param {number}   params.refTermId     Reference term ID.
 * @param {number}   params.year          Year filter.
 * @param {string}   params.referenceType Reference type filter.
 * @param {Array}    params.refTerms      Available reference terms.
 * @param {Object}   params.typeLabels    Type slug to label mapping.
 * @param {boolean}  params.isConfigured  Whether block is configured.
 * @param {Function} params.setAttributes Function to update block attributes.
 */
export default function useBlockLabel( {
	refTermId,
	year,
	referenceType,
	refTerms,
	typeLabels,
	isConfigured,
	setAttributes,
} ) {
	const previousLabelRef = useRef( '' );

	useEffect( () => {
		if ( ! isConfigured ) {
			return;
		}

		const parts = [];

		if ( refTermId > 0 ) {
			const refTerm = refTerms.find( ( p ) => p.id === refTermId );
			if ( refTerm ) {
				parts.push( refTerm.name );
			}
		}

		if ( year > 0 ) {
			parts.push( year.toString() );
		}

		if ( referenceType !== 'all' ) {
			parts.push( typeLabels[ referenceType ] || referenceType );
		}

		let newLabel;
		if ( parts.length > 0 ) {
			newLabel =
				__( 'References', 'gatherpress-references' ) +
				': ' +
				parts.join( ' \u2022 ' );
		} else {
			newLabel = __( 'References', 'gatherpress-references' );
		}

		if ( newLabel !== previousLabelRef.current ) {
			previousLabelRef.current = newLabel;
			setAttributes( {
				metadata: {
					name: newLabel,
				},
			} );
		}
	}, [
		setAttributes,
		refTermId,
		year,
		referenceType,
		refTerms,
		typeLabels,
		isConfigured,
	] );
}
