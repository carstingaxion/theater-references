/**
 * Reference Preview Component
 *
 * Renders the block preview in the editor with placeholder data,
 * including year headings, type headings with reorder controls, and item lists.
 *
 * @since 0.1.0
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button, ButtonGroup } from '@wordpress/components';
import { chevronUp, chevronDown } from '@wordpress/icons';

/**
 * Reference Preview component
 *
 * @param {Object}   props                 Component properties.
 * @param {Object}   props.filteredData    Filtered placeholder data keyed by year.
 * @param {Array}    props.sortedYears     Sorted year keys.
 * @param {Array}    props.orderedTypeKeys Ordered type taxonomy slugs.
 * @param {Object}   props.typeLabels      Type slug to label mapping.
 * @param {number}   props.headingLevel    Primary heading level (1-5).
 * @param {string}   props.referenceType   Reference type filter.
 * @param {Function} props.moveTypeUp      Callback to move a type up.
 * @param {Function} props.moveTypeDown    Callback to move a type down.
 * @return {Element|null} Preview element or null if no data.
 */
export default function ReferencePreview( {
	filteredData,
	sortedYears,
	orderedTypeKeys,
	typeLabels,
	headingLevel,
	referenceType,
	moveTypeUp,
	moveTypeDown,
} ) {
	if ( Object.keys( filteredData ).length === 0 ) {
		return null;
	}

	const secondaryHeadingLevel = Math.min( headingLevel + 1, 6 );
	const YearHeading = `h${ headingLevel }`;
	const TypeHeading = `h${ secondaryHeadingLevel }`;

	const showTypeHeadings = referenceType === 'all';
	const showTypeReorderControls =
		referenceType === 'all' && orderedTypeKeys.length > 1;

	return (
		<>
			{ sortedYears.map( ( yearKey ) => {
				const yearData = filteredData[ yearKey ];
				return (
					<div key={ yearKey }>
						<YearHeading className="references-year">
							{ yearKey }
						</YearHeading>

						{ orderedTypeKeys.map( ( typeKey ) => {
							const items = yearData[ typeKey ];
							if ( ! items || items.length === 0 ) {
								return null;
							}

							const currentIndex =
								orderedTypeKeys.indexOf( typeKey );
							const isFirstType = currentIndex === 0;
							const isLastType =
								currentIndex === orderedTypeKeys.length - 1;

							return (
								<div
									key={ typeKey }
									className="reference-type-container"
								>
									{ showTypeHeadings && (
										<div className="references-type-header">
											<TypeHeading className="references-type">
												{ typeLabels[ typeKey ] }
											</TypeHeading>
											{ showTypeReorderControls && (
												<ButtonGroup className="references-type-movers">
													<Button
														icon={ chevronUp }
														onClick={ () =>
															moveTypeUp(
																typeKey
															)
														}
														label={ __(
															'Move up',
															'gatherpress-references'
														) }
														disabled={ isFirstType }
														size="small"
													/>
													<Button
														icon={ chevronDown }
														onClick={ () =>
															moveTypeDown(
																typeKey
															)
														}
														label={ __(
															'Move down',
															'gatherpress-references'
														) }
														disabled={ isLastType }
														size="small"
													/>
												</ButtonGroup>
											) }
										</div>
									) }

									<ul className="references-list">
										{ items.map( ( item, index ) => (
											<li key={ index }>{ item }</li>
										) ) }
									</ul>
								</div>
							);
						} ) }
					</div>
				);
			} ) }
		</>
	);
}
