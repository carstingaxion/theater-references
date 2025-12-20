/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/block.json"
/*!************************!*\
  !*** ./src/block.json ***!
  \************************/
(module) {

module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"gatherpress/references","version":"0.1.0","title":"GatherPress References","category":"widgets","icon":"awards","description":"Display event references including clients, festivals, and awards.","example":{},"attributes":{"productionId":{"type":"number","default":0},"year":{"type":"string","default":""},"referenceType":{"type":"string","default":"all","enum":["all","ref_client","ref_festival","ref_award"]},"headingLevel":{"type":"number","default":2},"yearSortOrder":{"type":"string","default":"desc","enum":["asc","desc"]},"metadata":{"type":"object","default":{"name":"GatherPress References"}}},"supports":{"html":false,"color":{"background":true,"text":true,"link":true,"gradients":true,"__experimentalDefaultControls":{"background":true,"text":true}},"spacing":{"margin":true,"padding":true,"blockGap":true,"__experimentalDefaultControls":{"margin":true,"padding":true,"blockGap":true}},"typography":{"fontSize":true,"lineHeight":true,"fontFamily":true,"fontWeight":true,"fontStyle":true,"textTransform":true,"letterSpacing":true,"__experimentalDefaultControls":{"fontSize":true,"fontFamily":true}},"__experimentalBorder":{"color":true,"radius":true,"style":true,"width":true,"__experimentalDefaultControls":{"color":true,"radius":true}}},"style":"file:./style-index.css","textdomain":"gatherpress-references","editorScript":"file:./index.js","editorStyle":"file:./index.css","render":"file:./render.php"}');

/***/ },

/***/ "./src/edit.js"
/*!*********************!*\
  !*** ./src/edit.js ***!
  \*********************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Edit)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./editor.scss */ "./src/editor.scss");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__);
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






/**
 * Editor styles
 */


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

function Edit({
  attributes,
  setAttributes
}) {
  // Destructure attributes for easier access
  const {
    productionId,
    year,
    referenceType,
    headingLevel,
    yearSortOrder
  } = attributes;

  /**
   * Fetch productions from WordPress data store
   *
   * Uses the core data store to fetch all production terms.
   * Returns empty array while loading to prevent errors.
   */
  const productions = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.useSelect)(select => {
    const terms = select('core').getEntityRecords('taxonomy', 'gatherpress-productions', {
      per_page: 99 // Large number to get all, but avoid -1. More than 99 is not supported by WordPress.
    });
    return terms || [];
  }, []);

  /**
   * Update block metadata with dynamic label
   *
   * This effect runs whenever the attributes change that affect the label.
   * It updates the block's metadata attribute so the label appears in the list view.
   */
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.useEffect)(() => {
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
      if (productionId > 0) {
        const production = productions.find(p => p.id === productionId);
        if (production) {
          parts.push(production.name);
        }
      }

      // Add year if specified
      if (year) {
        parts.push(year);
      }

      // Add reference type if not "all"
      if (referenceType !== 'all') {
        const typeLabels = {
          ref_client: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Clients', 'gatherpress-references'),
          ref_festival: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Festivals', 'gatherpress-references'),
          ref_award: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Awards', 'gatherpress-references')
        };
        parts.push(typeLabels[referenceType] || referenceType);
      }

      // Construct final label
      if (parts.length > 0) {
        return (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('References:', 'gatherpress-references') + parts.join(' • ');
      }

      // Default label when no filters applied
      return (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('References', 'gatherpress-references');
    };
    const label = getBlockLabel();

    // Update the metadata attribute with the new name
    setAttributes({
      metadata: {
        ...attributes.metadata,
        name: label
      }
    });
  }, [setAttributes, productionId, year, referenceType, productions]);

  /**
   * Calculate secondary heading level
   *
   * Type headings are always one level smaller than year headings,
   * but capped at H6 (no H7 or higher).
   */
  const secondaryHeadingLevel = Math.min(headingLevel + 1, 6);

  // Create dynamic heading tag components
  const YearHeading = `h${headingLevel}`;
  const TypeHeading = `h${secondaryHeadingLevel}`;

  /**
   * Type labels mapping
   *
   * Maps internal taxonomy slugs to user-facing labels.
   * Used for displaying type headings in preview.
   */
  const typeLabels = {
    ref_client: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Clients', 'gatherpress-references'),
    ref_festival: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Festivals', 'gatherpress-references'),
    ref_award: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Awards', 'gatherpress-references')
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
    const displayYear = year ? parseInt(year) : currentYear;

    // If year is specified, show only that year
    if (year) {
      return {
        [displayYear]: {
          ref_client: [(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Royal Theater London', 'gatherpress-references'), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Vienna Burgtheater', 'gatherpress-references')].sort(),
          ref_festival: [(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Edinburgh International Festival', 'gatherpress-references')].sort(),
          ref_award: [(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Best Director Award', 'gatherpress-references')].sort()
        }
      };
    }

    // If no year specified, show two years of data
    return {
      // Cast as string to prevent a default ordering by integer keys.
      [currentYear + ' ']: {
        ref_client: [(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Royal Theater London', 'gatherpress-references'), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Vienna Burgtheater', 'gatherpress-references')].sort(),
        ref_festival: [(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Edinburgh International Festival', 'gatherpress-references')].sort(),
        ref_award: [(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Best Director Award', 'gatherpress-references')].sort()
      },
      // Cast as string to prevent a default ordering by integer keys.
      [currentYear - 1 + ' ']: {
        ref_client: [(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Berlin Staatstheater', 'gatherpress-references')].sort(),
        ref_festival: [(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Avignon Festival', 'gatherpress-references'), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Salzburg Festival', 'gatherpress-references')].sort(),
        ref_award: []
      }
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
    if (referenceType === 'all') {
      return placeholderData;
    }

    // Filter to show only selected type
    const filtered = {};
    Object.keys(placeholderData).forEach(yearKey => {
      const yearData = placeholderData[yearKey];
      // Only include year if it has data for the selected type
      if (yearData[referenceType] && yearData[referenceType].length > 0) {
        filtered[yearKey] = {
          [referenceType]: yearData[referenceType]
        };
      }
    });
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
    const years = Object.keys(filteredData);

    // Don't sort if a specific year is selected
    if (year) {
      return years;
    }

    // Sort based on yearSortOrder attribute
    return years.sort((a, b) => {
      const yearA = parseInt(a);
      const yearB = parseInt(b);
      if (yearSortOrder === 'asc') {
        return yearA - yearB; // Oldest first
      }
      return yearB - yearA; // Newest first (default)
    });
  };
  const sortedYears = getSortedYears();

  // Determine if year sort control should be shown
  const showYearSortControl = !year; // Only show when no specific year selected

  // Determine if we should show type headings (only when showing all types)
  const showTypeHeadings = referenceType === 'all';
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.Fragment, {
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, {
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Reference Settings', 'gatherpress-references'),
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Production', 'gatherpress-references'),
          value: productionId,
          options: [
          // Default option for auto-detection
          {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Auto-detect (or all)', 'gatherpress-references'),
            value: 0
          },
          // Map production terms to options
          ...productions.map(production => ({
            label: production.name,
            value: production.id
          }))],
          onChange: value => setAttributes({
            productionId: parseInt(value)
          }),
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Select a specific production or leave as auto-detect', 'gatherpress-references')
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Year', 'gatherpress-references'),
          value: year,
          onChange: value => setAttributes({
            year: value
          }),
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Filter by specific year (e.g., 2017). Leave empty for all years.', 'gatherpress-references'),
          type: "number"
        }), showYearSortControl && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)(yearSortOrder === 'asc' ? 'Sort Years Oldest First' : 'Sort Years Newest First', 'gatherpress-references'),
          checked: yearSortOrder === 'asc',
          onChange: value => setAttributes({
            yearSortOrder: value ? 'asc' : 'desc'
          }),
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Toggle to sort years from oldest to newest. Default is newest first.', 'gatherpress-references')
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Reference Type', 'gatherpress-references'),
          value: referenceType,
          options: [{
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('All Types', 'gatherpress-references'),
            value: 'all'
          }, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Clients', 'gatherpress-references'),
            value: 'ref_client'
          }, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Festivals', 'gatherpress-references'),
            value: 'ref_festival'
          }, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Awards', 'gatherpress-references'),
            value: 'ref_award'
          }],
          onChange: value => setAttributes({
            referenceType: value
          }),
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Choose which type of references to display', 'gatherpress-references')
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Year Heading Level', 'gatherpress-references'),
          value: headingLevel,
          onChange: value => setAttributes({
            headingLevel: value
          }),
          min: 1,
          max: 5 // Max H5 so secondary can be H6
          ,
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Choose the heading level for year headings (H1-H5). Type headings will be one level smaller.', 'gatherpress-references')
        })]
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
      ...(0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)(),
      children: Object.keys(filteredData).length > 0 && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.Fragment, {
        children: sortedYears.map(yearKey => {
          const yearData = filteredData[yearKey];
          return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(YearHeading, {
              className: "references-year",
              children: yearKey
            }), Object.keys(yearData).map(typeKey => {
              const items = yearData[typeKey];
              if (items.length === 0) {
                return null;
              }
              return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
                children: [showTypeHeadings && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(TypeHeading, {
                  className: "references-type",
                  children: typeLabels[typeKey]
                }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("ul", {
                  className: "references-list",
                  children: items.map((item, index) => /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("li", {
                    children: item
                  }, index))
                })]
              }, typeKey);
            })]
          }, yearKey);
        })
      })
    })]
  });
}

/***/ },

/***/ "./src/editor.scss"
/*!*************************!*\
  !*** ./src/editor.scss ***!
  \*************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ },

/***/ "./src/index.js"
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./style.scss */ "./src/style.scss");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./edit */ "./src/edit.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./block.json */ "./src/block.json");
/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */


/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */


/**
 * Internal dependencies
 */



/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)(_block_json__WEBPACK_IMPORTED_MODULE_3__.name, {
  /**
   * @see ./edit.js
   */
  edit: _edit__WEBPACK_IMPORTED_MODULE_2__["default"]
});

/***/ },

/***/ "./src/style.scss"
/*!************************!*\
  !*** ./src/style.scss ***!
  \************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ },

/***/ "@wordpress/block-editor"
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
(module) {

module.exports = window["wp"]["blockEditor"];

/***/ },

/***/ "@wordpress/blocks"
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
(module) {

module.exports = window["wp"]["blocks"];

/***/ },

/***/ "@wordpress/components"
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
(module) {

module.exports = window["wp"]["components"];

/***/ },

/***/ "@wordpress/data"
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
(module) {

module.exports = window["wp"]["data"];

/***/ },

/***/ "@wordpress/element"
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
(module) {

module.exports = window["wp"]["element"];

/***/ },

/***/ "@wordpress/i18n"
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
(module) {

module.exports = window["wp"]["i18n"];

/***/ },

/***/ "react/jsx-runtime"
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
(module) {

module.exports = window["ReactJSXRuntime"];

/***/ }

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Check if module exists (development only)
/******/ 		if (__webpack_modules__[moduleId] === undefined) {
/******/ 			var e = new Error("Cannot find module '" + moduleId + "'");
/******/ 			e.code = 'MODULE_NOT_FOUND';
/******/ 			throw e;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"index": 0,
/******/ 			"./style-index": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = globalThis["webpackChunkgatherpress_references"] = globalThis["webpackChunkgatherpress_references"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["./style-index"], () => (__webpack_require__("./src/index.js")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
//# sourceMappingURL=index.js.map