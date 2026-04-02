/**
 * Reference Inspector Controls
 *
 * Sidebar inspector controls for filtering and customizing the references block.
 *
 * @since 0.1.0
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	TextControl,
	ToggleControl,
	RangeControl,
} from '@wordpress/components';

/**
 * Reference Inspector component
 *
 * Renders all sidebar controls for the references block.
 *
 * @param {Object}   props                    Component properties.
 * @param {Object}   props.attributes         Block attributes.
 * @param {Function} props.setAttributes      Function to update attributes.
 * @param {Array}    props.supportedPostTypes Supported post type objects.
 * @param {Object}   props.refTaxonomy        Reference taxonomy object.
 * @param {Array}    props.refTerms           Reference term objects.
 * @param {Array}    props.taxonomies         Taxonomy objects for types.
 * @return {Element} Inspector controls element.
 */
export default function ReferenceInspector( {
	attributes,
	setAttributes,
	supportedPostTypes,
	refTaxonomy,
	refTerms,
	taxonomies,
} ) {
	const {
		postType,
		refTermId,
		year,
		referenceType,
		headingLevel,
		yearSortOrder,
	} = attributes;

	const showYearSortControl = year === 0;

	const yearSortLabel =
		yearSortOrder === 'asc'
			? __( 'Sort Years Oldest First', 'gatherpress-references' )
			: __( 'Sort Years Newest First', 'gatherpress-references' );

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'Reference Settings', 'gatherpress-references' ) }
			>
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

				<SelectControl
					label={
						refTaxonomy?.labels?.singular_name ||
						__( 'Reference Term', 'gatherpress-references' )
					}
					value={ refTermId }
					options={ [
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

				{ showYearSortControl && (
					<ToggleControl
						label={ yearSortLabel }
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

				<SelectControl
					label={ __( 'Reference Type', 'gatherpress-references' ) }
					value={ referenceType }
					options={ [
						{
							label: __( 'All Types', 'gatherpress-references' ),
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
					max={ 5 }
					help={ __(
						'Choose the heading level for year headings (H1-H5). Type headings will be one level smaller.',
						'gatherpress-references'
					) }
				/>
			</PanelBody>
		</InspectorControls>
	);
}
