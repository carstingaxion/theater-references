/**
 * GatherPress References Block - Editor Component
 *
 * Renders the block in the WordPress block editor with live preview
 * and inspector controls for filtering and customization.
 *
 * @since 0.1.0
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	TextControl,
	ToggleControl,
	RangeControl,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useEffect } from '@wordpress/element';

/**
 * Editor styles
 */
import './editor.scss';

/**
 * Edit component for GatherPress References block
 *
 * Displays a preview of the block output with inspector controls
 * for customization. Shows placeholder data for better UX.
 *
 * @param {Object}   props               Block properties from WordPress
 * @param {Object}   props.attributes    Current block attribute values
 * @param {Function} props.setAttributes Function to update block attributes
 * @return {Element} React element to render in editor
 */
export default function Edit( { attributes, setAttributes } ) {
	// Destructure attributes for easier access
	const { productionId, year, referenceType, headingLevel, yearSortOrder } =
		attributes;

	/**
	 * Fetch productions from WordPress data store
	 *
	 * Uses the core data store to fetch all production terms.
	 * Returns empty array while loading to prevent errors.
	 */
	const productions = useSelect( ( select ) => {
		const terms = select( 'core' ).getEntityRecords(
			'taxonomy',
			'gatherpress-productions',
			{
				per_page: 99, // Large number to get all, but avoid -1. More than 99 is not supported by WordPress.
			}
		);
		return terms || [];
	}, [] );

	/**
	 * Update block metadata with dynamic label
	 *
	 * This effect runs whenever the attributes change that affect the label.
	 * It updates the block's metadata attribute so the label appears in the list view.
	 */
	useEffect( () => {
		/**
		 * Generate dynamic block label based on attributes
		 *
		 * Creates a human-readable label that reflects current filters:
		 * - Production name (if specific production selected)
		 * - Year (if specified)
		 * - Reference type (if not "all")
		 *
		 * @return {string} Dynamic label for block
		 */
		const getBlockLabel = () => {
			const parts = [];

			// Add production name if specific production selected
			if ( productionId > 0 ) {
				const production = productions.find(
					( p ) => p.id === productionId
				);
				if ( production ) {
					parts.push( production.name );
				}
			}

			// Add year if specified
			if ( year > 0 ) {
				parts.push( year.toString() );
			}

			// Add reference type if not "all"
			if ( referenceType !== 'all' ) {
				const typeLabels = {
					ref_client: __( 'Clients', 'gatherpress-references' ),
					ref_festival: __( 'Festivals', 'gatherpress-references' ),
					ref_award: __( 'Awards', 'gatherpress-references' ),
				};
				parts.push( typeLabels[ referenceType ] || referenceType );
			}

			// Construct final label
			if ( parts.length > 0 ) {
				return (
					__( 'References:', 'gatherpress-references' ) +
					parts.join( ' • ' )
				);
			}

			// Default label when no filters applied
			return __( 'References', 'gatherpress-references' );
		};
		const label = getBlockLabel();

		// Update the metadata attribute with the new name
		setAttributes( {
			metadata: {
				...attributes.metadata,
				name: label,
			},
		} );
	}, [
		setAttributes,
		productionId,
		year,
		referenceType,
		productions,
	] );

	/**
	 * Calculate secondary heading level
	 *
	 * Type headings are always one level smaller than year headings,
	 * but capped at H6 (no H7 or higher).
	 */
	const secondaryHeadingLevel = Math.min( headingLevel + 1, 6 );

	// Create dynamic heading tag components
	const YearHeading = `h${ headingLevel }`;
	const TypeHeading = `h${ secondaryHeadingLevel }`;

	/**
	 * Type labels mapping
	 *
	 * Maps internal taxonomy slugs to user-facing labels.
	 * Used for displaying type headings in preview.
	 */
	const typeLabels = {
		ref_client: __( 'Clients', 'gatherpress-references' ),
		ref_festival: __( 'Festivals', 'gatherpress-references' ),
		ref_award: __( 'Awards', 'gatherpress-references' ),
	};

	/**
	 * Placeholder data for editor preview
	 *
	 * Provides realistic sample data to show users what the block
	 * will look like with actual content. Organized by year and type.
	 *
	 * Structure matches the output from render.php:
	 * {
	 *   '2024': {
	 *     'ref_client': ['Client 1', 'Client 2'],
	 *     'ref_festival': ['Festival 1'],
	 *     'ref_award': ['Award 1']
	 *   }
	 * }
	 */
	const getPlaceholderData = () => {
		// Determine which year(s) to show in preview
		const currentYear = new Date().getFullYear();
		const displayYear = year > 0 ? year : currentYear;

		// If year is specified, show only that year
		if ( year > 0 ) {
			return {
				[ displayYear ]: {
					ref_client: [
						__( 'Royal Theater London', 'gatherpress-references' ),
						__( 'Vienna Burgtheater', 'gatherpress-references' ),
					].sort(),
					ref_festival: [
						__(
							'Edinburgh International Festival',
							'gatherpress-references'
						),
					].sort(),
					ref_award: [
						__( 'Best Director Award', 'gatherpress-references' ),
					].sort(),
				},
			};
		}

		// If no year specified, show two years of data
		return {
			// Cast as string to prevent a default ordering by integer keys.
			[ currentYear + ' ' ]: {
				ref_client: [
					__( 'Royal Theater London', 'gatherpress-references' ),
					__( 'Vienna Burgtheater', 'gatherpress-references' ),
				].sort(),
				ref_festival: [
					__(
						'Edinburgh International Festival',
						'gatherpress-references'
					),
				].sort(),
				ref_award: [
					__( 'Best Director Award', 'gatherpress-references' ),
				].sort(),
			},
			// Cast as string to prevent a default ordering by integer keys.
			[ currentYear - 1 + ' ' ]: {
				ref_client: [
					__( 'Berlin Staatstheater', 'gatherpress-references' ),
				].sort(),
				ref_festival: [
					__( 'Avignon Festival', 'gatherpress-references' ),
					__( 'Salzburg Festival', 'gatherpress-references' ),
				].sort(),
				ref_award: [],
			},
		};
	};

	const placeholderData = getPlaceholderData();

	/**
	 * Filter placeholder data based on reference type
	 *
	 * If a specific type is selected, only show data for that type.
	 * Otherwise show all types. Removes empty years after filtering.
	 *
	 * @return {Object} Filtered placeholder data structure
	 */
	const getFilteredPlaceholderData = () => {
		// Show all types if 'all' is selected
		if ( referenceType === 'all' ) {
			return placeholderData;
		}

		// Filter to show only selected type
		const filtered = {};
		Object.keys( placeholderData ).forEach( ( yearKey ) => {
			const yearData = placeholderData[ yearKey ];
			// Only include year if it has data for the selected type
			if (
				yearData[ referenceType ] &&
				yearData[ referenceType ].length > 0
			) {
				filtered[ yearKey ] = {
					[ referenceType ]: yearData[ referenceType ],
				};
			}
		} );
		return filtered;
	};

	const filteredData = getFilteredPlaceholderData();

	/**
	 * Sort years based on yearSortOrder
	 *
	 * Only sorts when no specific year is selected.
	 * Preserves child arrays order (taxonomy data).
	 *
	 * @return {Array} Sorted array of year keys
	 */
	const getSortedYears = () => {
		const years = Object.keys( filteredData );

		// Don't sort if a specific year is selected
		if ( year > 0 ) {
			return years;
		}

		// Sort based on yearSortOrder attribute
		return years.sort( ( a, b ) => {
			const yearA = parseInt( a );
			const yearB = parseInt( b );

			if ( yearSortOrder === 'asc' ) {
				return yearA - yearB; // Oldest first
			}
			return yearB - yearA; // Newest first (default)
		} );
	};

	const sortedYears = getSortedYears();

	// Determine if year sort control should be shown
	const showYearSortControl = year === 0; // Only show when no specific year selected

	// Determine if we should show type headings (only when showing all types)
	const showTypeHeadings = referenceType === 'all';

	return (
		<>
			{ /* Inspector Controls - Sidebar settings panel */ }
			<InspectorControls>
				<PanelBody
					title={ __(
						'Reference Settings',
						'gatherpress-references'
					) }
				>
					{ /* Production filter dropdown */ }
					<SelectControl
						label={ __( 'Production', 'gatherpress-references' ) }
						value={ productionId }
						options={ [
							// Default option for auto-detection
							{
								label: __(
									'Auto-detect (or all)',
									'gatherpress-references'
								),
								value: 0,
							},
							// Map production terms to options
							...productions.map( ( production ) => ( {
								label: production.name,
								value: production.id,
							} ) ),
						] }
						onChange={ ( value ) =>
							setAttributes( { productionId: parseInt( value ) } )
						}
						help={ __(
							'Select a specific production or leave as auto-detect',
							'gatherpress-references'
						) }
					/>

					{ /* Year filter text input */ }
					<TextControl
						label={ __( 'Year', 'gatherpress-references' ) }
						value={ year > 0 ? year.toString() : '' }
						onChange={ ( value ) => {
							const numValue = parseInt( value );
							setAttributes( {
								year: isNaN( numValue ) ? 0 : numValue,
							} );
						} }
						type="number"
						min="0"
						max={ new Date().getFullYear() + 1 }
						placeholder={ __(
							'Leave empty for all years',
							'gatherpress-references'
						) }
						help={ __(
							'Enter a specific year (e.g., 2024) or leave empty for all years',
							'gatherpress-references'
						) }
					/>

					{ /* Year sort order toggle (only when no specific year) */ }
					{ showYearSortControl && (
						<ToggleControl
							label={ __(
								( yearSortOrder === 'asc' ) ? 'Sort Years Oldest First' : 'Sort Years Newest First',
								'gatherpress-references'
							) }
							checked={ yearSortOrder === 'asc' }
							onChange={ ( value ) =>
								setAttributes( {
									yearSortOrder: value ? 'asc' : 'desc',
								} )
							}
							help={ __(
								'Toggle to sort years from oldest to newest. Default is newest first.',
								'gatherpress-references'
							) }
						/>
					) }

					{ /* Reference type filter dropdown */ }
					<SelectControl
						label={ __(
							'Reference Type',
							'gatherpress-references'
						) }
						value={ referenceType }
						options={ [
							{
								label: __(
									'All Types',
									'gatherpress-references'
								),
								value: 'all',
							},
							{
								label: __(
									'Clients',
									'gatherpress-references'
								),
								value: 'ref_client',
							},
							{
								label: __(
									'Festivals',
									'gatherpress-references'
								),
								value: 'ref_festival',
							},
							{
								label: __( 'Awards', 'gatherpress-references' ),
								value: 'ref_award',
							},
						] }
						onChange={ ( value ) =>
							setAttributes( { referenceType: value } )
						}
						help={ __(
							'Choose which type of references to display',
							'gatherpress-references'
						) }
					/>

					{ /* Heading level slider */ }
					<RangeControl
						label={ __(
							'Year Heading Level',
							'gatherpress-references'
						) }
						value={ headingLevel }
						onChange={ ( value ) =>
							setAttributes( { headingLevel: value } )
						}
						min={ 1 }
						max={ 5 } // Max H5 so secondary can be H6
						help={ __(
							'Choose the heading level for year headings (H1-H5). Type headings will be one level smaller.',
							'gatherpress-references'
						) }
					/>
				</PanelBody>
			</InspectorControls>

			{ /* Block content preview */ }
			<div { ...useBlockProps() }>
				{ Object.keys( filteredData ).length > 0 && (
					<>
						{ /* Loop through sorted years */ }
						{ sortedYears.map( ( yearKey ) => {
							const yearData = filteredData[ yearKey ];
							return (
								<div key={ yearKey }>
									{ /* Year heading */ }
									<YearHeading className="references-year">
										{ yearKey }
									</YearHeading>

									{ /* Loop through types within year */ }
									{ Object.keys( yearData ).map(
										( typeKey ) => {
											const items = yearData[ typeKey ];
											if ( items.length === 0 ) {
												return null;
											}

											return (
												<div key={ typeKey }>
													{ /* Type heading - only show when displaying all types */ }
													{ showTypeHeadings && (
														<TypeHeading className="references-type">
															{
																typeLabels[
																	typeKey
																]
															}
														</TypeHeading>
													) }

													{ /* Reference list */ }
													<ul className="references-list">
														{ items.map(
															( item, index ) => (
																<li
																	key={
																		index
																	}
																>
																	{ item }
																</li>
															)
														) }
													</ul>
												</div>
											);
										}
									) }
								</div>
							);
						} ) }
					</>
				) }
			</div>
		</>
	);
}
