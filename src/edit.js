/**
 * GatherPress References Block - Editor Component
 *
 * Slim orchestrator that composes hooks and components
 * for the block editor experience.
 *
 * @since 0.1.0
 */

/**
 * WordPress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Internal dependencies - Hooks
 */
import useConfig from './hooks/use-config';
import useTypeOrder from './hooks/use-type-order';
import useBlockLabel from './hooks/use-block-label';

/**
 * Internal dependencies - Components
 */
import NotConfigured from './components/not-configured';
import ReferenceInspector from './components/reference-inspector';
import ReferencePreview from './components/reference-preview';

/**
 * Internal dependencies - Utilities
 */
import {
	getPlaceholderData,
	filterPlaceholderData,
	getSortedYears,
} from './utils/placeholder-data';

/**
 * Editor styles
 */
import './editor.scss';

/**
 * Edit component for GatherPress References block
 *
 * @param {Object}   props               Block properties from WordPress.
 * @param {Object}   props.attributes    Current block attribute values.
 * @param {Function} props.setAttributes Function to update block attributes.
 * @return {Element} React element to render in editor.
 */
export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();

	const {
		postType,
		refTermId,
		year,
		referenceType,
		headingLevel,
		yearSortOrder,
		typeOrder,
	} = attributes;

	// Load configuration, taxonomies, and labels.
	const {
		supportedPostTypes,
		activePostType,
		config,
		refTaxonomy,
		refTerms,
		taxonomies,
		typeLabels,
		isConfigured,
	} = useConfig( { postType, setAttributes } );

	// Manage type ordering.
	const { orderedTypeKeys, moveTypeUp, moveTypeDown } = useTypeOrder( {
		config,
		typeOrder,
		setAttributes,
	} );

	// Update block label in list view.
	useBlockLabel( {
		refTermId,
		year,
		referenceType,
		refTerms,
		typeLabels,
		isConfigured,
		setAttributes,
	} );

	// Show error state if not configured.
	if ( ! isConfigured || ! activePostType ) {
		return (
			<NotConfigured
				blockProps={ blockProps }
				supportedPostTypes={ supportedPostTypes }
			/>
		);
	}

	// Generate and process preview data.
	const placeholderData = getPlaceholderData( {
		isConfigured,
		config,
		year,
		orderedTypeKeys,
		typeLabels,
	} );
	const filteredData = filterPlaceholderData(
		placeholderData,
		referenceType
	);
	const sortedYears = getSortedYears( filteredData, year, yearSortOrder );

	return (
		<>
			<ReferenceInspector
				attributes={ attributes }
				setAttributes={ setAttributes }
				supportedPostTypes={ supportedPostTypes }
				refTaxonomy={ refTaxonomy }
				refTerms={ refTerms }
				taxonomies={ taxonomies }
			/>

			<div { ...blockProps }>
				<ReferencePreview
					filteredData={ filteredData }
					sortedYears={ sortedYears }
					orderedTypeKeys={ orderedTypeKeys }
					typeLabels={ typeLabels }
					headingLevel={ headingLevel }
					referenceType={ referenceType }
					moveTypeUp={ moveTypeUp }
					moveTypeDown={ moveTypeDown }
				/>
			</div>
		</>
	);
}
