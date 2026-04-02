/**
 * Placeholder Data Utilities
 *
 * Generates, filters, and sorts placeholder data for the editor preview.
 *
 * @since 0.1.0
 */

/**
 * Build placeholder data for a single year
 *
 * @param {Array}  orderedTypeKeys Ordered taxonomy slugs.
 * @param {Object} typeLabels      Type slug to label mapping.
 * @return {Object} Year data with example items per type.
 */
function buildYearData( orderedTypeKeys, typeLabels ) {
	const yearData = {};
	orderedTypeKeys.forEach( ( taxSlug ) => {
		const taxLabel = typeLabels[ taxSlug ] || taxSlug;
		yearData[ taxSlug ] = [
			`${ taxLabel } Example 1`,
			`${ taxLabel } Example 2`,
		].sort();
	} );
	return yearData;
}

/**
 * Generate placeholder data for editor preview
 *
 * Creates one or two years of example data based on current filter settings.
 *
 * @param {Object}  params                 Generation parameters.
 * @param {boolean} params.isConfigured    Whether block is configured.
 * @param {Object}  params.config          Block configuration.
 * @param {number}  params.year            Year filter value (0 = all years).
 * @param {Array}   params.orderedTypeKeys Ordered taxonomy slugs.
 * @param {Object}  params.typeLabels      Type slug to label mapping.
 * @return {Object} Placeholder data keyed by year.
 */
export function getPlaceholderData( {
	isConfigured,
	config,
	year,
	orderedTypeKeys,
	typeLabels,
} ) {
	if ( ! isConfigured || ! config?.ref_types ) {
		return {};
	}

	const currentYear = new Date().getFullYear();
	const displayYear = year > 0 ? year : currentYear;

	if ( year > 0 ) {
		return {
			[ displayYear ]: buildYearData( orderedTypeKeys, typeLabels ),
		};
	}

	return {
		[ currentYear + ' ' ]: buildYearData( orderedTypeKeys, typeLabels ),
		[ currentYear - 1 + ' ' ]: buildYearData( orderedTypeKeys, typeLabels ),
	};
}

/**
 * Filter placeholder data by reference type
 *
 * If a specific type is selected, only includes data for that type.
 * Removes years with no matching data after filtering.
 *
 * @param {Object} placeholderData Placeholder data keyed by year.
 * @param {string} referenceType   Reference type filter ('all' or specific slug).
 * @return {Object} Filtered placeholder data.
 */
export function filterPlaceholderData( placeholderData, referenceType ) {
	if ( referenceType === 'all' ) {
		return placeholderData;
	}

	const filtered = {};
	Object.keys( placeholderData ).forEach( ( yearKey ) => {
		const yearData = placeholderData[ yearKey ];
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
}

/**
 * Sort year keys based on sort order
 *
 * @param {Object} filteredData  Filtered placeholder data.
 * @param {number} year          Year filter value (0 = all years).
 * @param {string} yearSortOrder Sort order ('asc' or 'desc').
 * @return {Array} Sorted array of year keys.
 */
export function getSortedYears( filteredData, year, yearSortOrder ) {
	const years = Object.keys( filteredData );

	if ( year > 0 ) {
		return years;
	}

	return years.sort( ( a, b ) => {
		const yearA = parseInt( a );
		const yearB = parseInt( b );

		if ( yearSortOrder === 'asc' ) {
			return yearA - yearB;
		}
		return yearB - yearA;
	} );
}
