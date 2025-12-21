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
	Notice,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useEffect, useMemo } from '@wordpress/element';

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
	const { postType, refTermId, year, referenceType, headingLevel, yearSortOrder } =
		attributes;

	/**
	 * Fetch all post types with gatherpress_references support
	 */
	const supportedPostTypes = useSelect( ( select ) => {
		const postTypes = select( 'core' ).getPostTypes( { per_page: -1 } );
		
		if ( ! postTypes ) {
			return [];
		}
		
		return postTypes.filter( ( type ) => {
			return type.supports && type.supports.gatherpress_references;
		} );
	}, [] );

	/**
	 * Auto-assign post type on block insertion if only one supported type exists
	 */
	useEffect( () => {
		if ( ! postType && supportedPostTypes.length === 1 ) {
			setAttributes( { postType: supportedPostTypes[0].slug } );
		}
	}, [ postType, supportedPostTypes, setAttributes ] );

	/**
	 * Determine active post type for configuration lookup
	 */
	const activePostType = useMemo( () => {
		// Use explicitly set post type if available
		if ( postType ) {
			return postType;
		}
		
		// Fall back to first supported post type
		return supportedPostTypes.length > 0 ? supportedPostTypes[0].slug : null;
	}, [ postType, supportedPostTypes ] );

	/**
	 * Fetch block configuration from the active post type
	 */
	const config = useSelect(
		( select ) => {
			if ( ! activePostType ) {
				return null;
			}
			
			const postTypeObject = select( 'core' ).getPostType( activePostType );
			
			if ( ! postTypeObject || ! postTypeObject.supports ) {
				return null;
			}
			
			// Extract the configuration from the supports object
			const referencesSupport = postTypeObject.supports.gatherpress_references;
			
			if ( ! referencesSupport ) {
				return null;
			}
			
			// If it's an array, take the first element (WordPress stores support args in arrays)
			if ( Array.isArray( referencesSupport ) && referencesSupport.length > 0 ) {
				return referencesSupport[0];
			}
			
			// If it's an object, use it directly
			if ( typeof referencesSupport === 'object' && referencesSupport !== null ) {
				return referencesSupport;
			}
			
			// If it's just true, we need to handle this case
			if ( referencesSupport === true ) {
				// Return null as we need actual configuration
				return null;
			}
			
			return null;
		},
		[ activePostType ]
	);

	/**
	 * Fetch reference taxonomy object and terms
	 */
	const { refTaxonomy, refTerms } = useSelect(
		( select ) => {
			if ( ! config || ! config.ref_tax ) {
				return { refTaxonomy: null, refTerms: [] };
			}

			const taxonomy = select( 'core' ).getTaxonomy( config.ref_tax );
			const terms = select( 'core' ).getEntityRecords(
				'taxonomy',
				config.ref_tax,
				{
					per_page: 99, // Large number to get all, but avoid -1. More than 99 is not supported by WordPress.
				}
			);
			return {
				refTaxonomy: taxonomy || null,
				refTerms: terms || [],
			};
		},
		[ config ]
	);

	/**
	 * Fetch taxonomy objects for reference types
	 */
	const taxonomies = useSelect(
		( select ) => {
			if ( ! config || ! config.ref_types || ! Array.isArray( config.ref_types ) ) {
				return [];
			}

			const taxonomyObjects = config.ref_types
				.map( ( slug ) => select( 'core' ).getTaxonomy( slug ) )
				.filter( ( tax ) => tax !== null && tax !== undefined );

			return taxonomyObjects;
		},
		[ config ]
	);

	/**
	 * Build type labels mapping
	 */
	const typeLabels = useMemo( () => {
		const labels = {};
		taxonomies.forEach( ( tax ) => {
			labels[ tax.slug ] = tax.labels?.name || tax.name;
		} );
		return labels;
	}, [ taxonomies ] );

	/**
	 * Check if block is properly configured
	 */
	const isConfigured =
		config &&
		typeof config === 'object' &&
		config.ref_tax &&
		config.ref_types &&
		Array.isArray( config.ref_types ) &&
		config.ref_types.length > 0;

	/**
	 * Update block metadata with dynamic label
	 *
	 * This effect runs whenever the attributes change that affect the label.
	 * It updates the block's metadata attribute so the label appears in the list view.
	 */
	useEffect( () => {
		if ( ! isConfigured ) {
			return;
		}

		/**
		 * Generate dynamic block label based on attributes
		 *
		 * Creates a human-readable label that reflects current filters:
		 * - Reference term name (if specific term selected)
		 * - Year (if specified)
		 * - Reference type (if not "all")
		 *
		 * @return {string} Dynamic label for block
		 */
		const getBlockLabel = () => {
			const parts = [];

			// Add reference term name if specific term selected
			if ( refTermId > 0 ) {
				const refTerm = refTerms.find(
					( p ) => p.id === refTermId
				);
				if ( refTerm ) {
					parts.push( refTerm.name );
				}
			}

			// Add year if specified
			if ( year > 0 ) {
				parts.push( year.toString() );
			}

			// Add reference type if not "all"
			if ( referenceType !== 'all' ) {
				parts.push( typeLabels[ referenceType ] || referenceType );
			}

			// Construct final label
			if ( parts.length > 0 ) {
				return (
					__( 'References', 'gatherpress-references' ) + ': ' +
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
		refTermId,
		year,
		referenceType,
		refTerms,
		typeLabels,
		isConfigured,
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
	 * Placeholder data for editor preview
	 */
	const getPlaceholderData = () => {
		if ( ! isConfigured || ! config.ref_types ) {
			return {};
		}

		// Determine which year(s) to show in preview
		const currentYear = new Date().getFullYear();
		const displayYear = year > 0 ? year : currentYear;

		// Build placeholder data using configured taxonomies
		const buildYearData = () => {
			const yearData = {};
			config.ref_types.forEach( ( taxSlug ) => {
				const taxLabel = typeLabels[ taxSlug ] || taxSlug;
				yearData[ taxSlug ] = [
					`${ taxLabel } Example 1`,
					`${ taxLabel } Example 2`,
				].sort();
			} );
			return yearData;
		};

		// If year is specified, show only that year
		if ( year > 0 ) {
			return {
				[ displayYear ]: buildYearData(),
			};
		}

		// If no year specified, show two years of data
		return {
			[ currentYear + ' ' ]: buildYearData(),
			[ currentYear - 1 + ' ' ]: buildYearData(),
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
	const showYearSortControl = year === 0;

	// Determine if we should show type headings.
	const showTypeHeadings = referenceType === 'all';

	// Show configuration error if block is not properly configured
	if ( ! isConfigured || ! activePostType ) {
		return (
			<>
				<InspectorControls>
					<PanelBody
						title={ __(
							'Reference Settings',
							'gatherpress-references'
						) }
					>
						<Notice status="warning" isDismissible={ false }>
							{ __(
								'References block requires a post type with gatherpress_references support.',
								'gatherpress-references'
							) }
						</Notice>
					</PanelBody>
				</InspectorControls>
				<div { ...useBlockProps() }>
					<Notice status="warning" isDismissible={ false }>
						<p>
							{ __(
								'This block requires a post type with gatherpress_references support configured.',
								'gatherpress-references'
							) }
						</p>
						{ supportedPostTypes.length > 0 && (
							<p>
								<strong>
									{ __(
										'Supported post types:',
										'gatherpress-references'
									) }
								</strong>{ ' ' }
								{ supportedPostTypes
									.map( ( type ) => type.labels?.name || type.name )
									.join( ', ' ) }
							</p>
						) }
					</Notice>
				</div>
			</>
		);
	}

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __(
						'Reference Settings',
						'gatherpress-references'
					) }
				>
					{ /* Post Type selector - only show if multiple supported post types */ }
					{ supportedPostTypes.length > 1 && (
						<SelectControl
							label={ __( 'Post Type', 'gatherpress-references' ) }
							value={ postType || '' }
							options={ [
								{
									label: __(
										'Select post type',
										'gatherpress-references'
									),
									value: '',
								},
								...supportedPostTypes.map( ( type ) => ( {
									label: type.labels?.name || type.name,
									value: type.slug,
								} ) ),
							] }
							onChange={ ( value ) =>
								setAttributes( { postType: value } )
							}
							help={ __(
								'Select which post type to query for references',
								'gatherpress-references'
							) }
						/>
					) }

					{ /* Reference Term filter dropdown */ }
					<SelectControl
						label={
							refTaxonomy?.labels?.singular_name ||
							__( 'Reference Term', 'gatherpress-references' )
						}
						value={ refTermId }
						options={ [
							// Default option for auto-detection
							{
								label: __(
									'All (or auto-detect)',
									'gatherpress-references'
								),
								value: 0,
							},
							...refTerms.map( ( refTerm ) => ( {
								label: refTerm.name,
								value: refTerm.id,
							} ) ),
						] }
						onChange={ ( value ) =>
							setAttributes( { refTermId: parseInt( value ) } )
						}
						help={
							refTaxonomy?.labels?.singular_name
								? `Select a specific ${ refTaxonomy.labels.singular_name.toLowerCase() } or leave as auto-detect`
								: __(
										'Select a specific reference term or leave as auto-detect',
										'gatherpress-references'
								  )
						}
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
								yearSortOrder === 'asc'
									? 'Sort Years Oldest First'
									: 'Sort Years Newest First',
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
							...taxonomies.map( ( tax ) => ( {
								label: tax.labels?.name || tax.name,
								value: tax.slug,
							} ) ),
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
