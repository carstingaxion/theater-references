/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/@wordpress/icons/build-module/library/chevron-down.mjs"
/*!*****************************************************************************!*\
  !*** ./node_modules/@wordpress/icons/build-module/library/chevron-down.mjs ***!
  \*****************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ chevron_down_default)
/* harmony export */ });
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/primitives */ "@wordpress/primitives");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
// packages/icons/src/library/chevron-down.tsx


var chevron_down_default = /* @__PURE__ */ (0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__.SVG, { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", children: /* @__PURE__ */ (0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__.Path, { d: "M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z" }) });

//# sourceMappingURL=chevron-down.mjs.map


/***/ },

/***/ "./node_modules/@wordpress/icons/build-module/library/chevron-up.mjs"
/*!***************************************************************************!*\
  !*** ./node_modules/@wordpress/icons/build-module/library/chevron-up.mjs ***!
  \***************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ chevron_up_default)
/* harmony export */ });
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/primitives */ "@wordpress/primitives");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
// packages/icons/src/library/chevron-up.tsx


var chevron_up_default = /* @__PURE__ */ (0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__.SVG, { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", children: /* @__PURE__ */ (0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__.Path, { d: "M6.5 12.4L12 8l5.5 4.4-.9 1.2L12 10l-4.5 3.6-1-1.2z" }) });

//# sourceMappingURL=chevron-up.mjs.map


/***/ },

/***/ "./src/block.json"
/*!************************!*\
  !*** ./src/block.json ***!
  \************************/
(module) {

module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"gatherpress/references","version":"0.1.0","title":"GatherPress References","category":"gatherpress","icon":"awards","description":"Display references such as clients, festivals and awards in a structured, chronological format.","example":{},"attributes":{"postType":{"type":"string","default":""},"refTermId":{"type":"number","default":0},"year":{"type":"number","default":0},"referenceType":{"type":"string","default":"all"},"headingLevel":{"type":"number","default":2},"yearSortOrder":{"type":"string","default":"desc","enum":["asc","desc"]},"typeOrder":{"type":"array","default":[]},"metadata":{"type":"object","default":{"name":"References"}}},"supports":{"html":false,"align":["wide","full"],"color":{"background":true,"text":true,"link":false,"gradients":true,"__experimentalDefaultControls":{"background":true,"text":true}},"spacing":{"margin":true,"padding":true,"blockGap":false,"__experimentalDefaultControls":{"margin":true,"padding":true,"blockGap":false}},"typography":{"fontSize":true,"lineHeight":true,"fontFamily":true,"fontWeight":true,"fontStyle":true,"textTransform":true,"letterSpacing":true,"__experimentalDefaultControls":{"fontSize":true,"fontFamily":true}},"__experimentalBorder":{"color":true,"radius":true,"style":true,"width":true,"__experimentalDefaultControls":{"color":true,"radius":true}}},"styles":[{"name":"default","label":"Default","isDefault":true},{"name":"classic-serif","label":"Classic Serif"},{"name":"modern-corporate","label":"Modern Corporate"},{"name":"neon-gradient","label":"Neon Gradient"},{"name":"eco-cyberpunk","label":"Eco Cyberpunk"}],"style":"file:./style-index.css","textdomain":"gatherpress-references","editorScript":"file:./index.js","editorStyle":"file:./index.css","render":"file:./render.php"}');

/***/ },

/***/ "./src/components/not-configured.js"
/*!******************************************!*\
  !*** ./src/components/not-configured.js ***!
  \******************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ NotConfigured)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__);
/**
 * Not Configured Component
 *
 * Displays an error state when the block is not properly configured
 * with a post type that supports gatherpress_references.
 *
 * @since 0.1.0
 */

/**
 * WordPress dependencies
 */




/**
 * Not Configured component
 *
 * @param {Object} props                    Component properties.
 * @param {Object} props.blockProps         Block wrapper props from useBlockProps.
 * @param {Array}  props.supportedPostTypes Array of supported post type objects.
 * @return {Element} Error state element.
 */

function NotConfigured({
  blockProps,
  supportedPostTypes
}) {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.Fragment, {
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, {
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Reference Settings', 'gatherpress-references'),
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Notice, {
          status: "warning",
          isDismissible: false,
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('References block requires a post type with gatherpress_references support.', 'gatherpress-references')
        })
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("div", {
      ...blockProps,
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Notice, {
        status: "warning",
        isDismissible: false,
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("p", {
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('This block requires a post type with gatherpress_references support configured.', 'gatherpress-references')
        }), supportedPostTypes.length > 0 && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("p", {
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("strong", {
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Supported post types:', 'gatherpress-references')
          }), ' ', supportedPostTypes.map(type => type.labels?.name || type.name).join(', ')]
        })]
      })
    })]
  });
}

/***/ },

/***/ "./src/components/reference-inspector.js"
/*!***********************************************!*\
  !*** ./src/components/reference-inspector.js ***!
  \***********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ ReferenceInspector)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__);
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

function ReferenceInspector({
  attributes,
  setAttributes,
  supportedPostTypes,
  refTaxonomy,
  refTerms,
  taxonomies
}) {
  const {
    postType,
    refTermId,
    year,
    referenceType,
    headingLevel,
    yearSortOrder
  } = attributes;
  const showYearSortControl = year === 0;
  const yearSortLabel = yearSortOrder === 'asc' ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Sort Years Oldest First', 'gatherpress-references') : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Sort Years Newest First', 'gatherpress-references');
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, {
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Reference Settings', 'gatherpress-references'),
      children: [supportedPostTypes.length > 1 && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Post Type', 'gatherpress-references'),
        value: postType || '',
        options: [{
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Select post type', 'gatherpress-references'),
          value: ''
        }, ...supportedPostTypes.map(type => ({
          label: type.labels?.name || type.name,
          value: type.slug
        }))],
        onChange: value => setAttributes({
          postType: value
        }),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Select which post type to query for references', 'gatherpress-references')
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
        label: refTaxonomy?.labels?.singular_name || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Reference Term', 'gatherpress-references'),
        value: refTermId,
        options: [{
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('All (or auto-detect)', 'gatherpress-references'),
          value: 0
        }, ...refTerms.map(refTerm => ({
          label: refTerm.name,
          value: refTerm.id
        }))],
        onChange: value => setAttributes({
          refTermId: parseInt(value)
        }),
        help: refTaxonomy?.labels?.singular_name ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.sprintf)((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Select a specific "%s" or leave as auto-detect', 'gatherpress-references'), refTaxonomy.labels.singular_name) : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Select a specific reference term or leave as auto-detect', 'gatherpress-references')
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Year', 'gatherpress-references'),
        value: year > 0 ? year.toString() : '',
        onChange: value => {
          const numValue = parseInt(value);
          setAttributes({
            year: isNaN(numValue) ? 0 : numValue
          });
        },
        type: "number",
        min: "0",
        max: new Date().getFullYear() + 1,
        placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Leave empty for all years', 'gatherpress-references'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Enter a specific year (e.g., 2024) or leave empty for all years', 'gatherpress-references')
      }), showYearSortControl && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
        label: yearSortLabel,
        checked: yearSortOrder === 'asc',
        onChange: value => setAttributes({
          yearSortOrder: value ? 'asc' : 'desc'
        }),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Toggle to sort years from oldest to newest. Default is newest first.', 'gatherpress-references')
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Reference Type', 'gatherpress-references'),
        value: referenceType,
        options: [{
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('All Types', 'gatherpress-references'),
          value: 'all'
        }, ...taxonomies.map(tax => ({
          label: tax.labels?.name || tax.name,
          value: tax.slug
        }))],
        onChange: value => setAttributes({
          referenceType: value
        }),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Choose which type of references to display', 'gatherpress-references')
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Year Heading Level', 'gatherpress-references'),
        value: headingLevel,
        onChange: value => setAttributes({
          headingLevel: value
        }),
        min: 1,
        max: 5,
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Choose the heading level for year headings (H1-H5). Type headings will be one level smaller.', 'gatherpress-references')
      })]
    })
  });
}

/***/ },

/***/ "./src/components/reference-preview.js"
/*!*********************************************!*\
  !*** ./src/components/reference-preview.js ***!
  \*********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ ReferencePreview)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_icons__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/icons */ "./node_modules/@wordpress/icons/build-module/library/chevron-down.mjs");
/* harmony import */ var _wordpress_icons__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/icons */ "./node_modules/@wordpress/icons/build-module/library/chevron-up.mjs");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__);
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

function ReferencePreview({
  filteredData,
  sortedYears,
  orderedTypeKeys,
  typeLabels,
  headingLevel,
  referenceType,
  moveTypeUp,
  moveTypeDown
}) {
  if (Object.keys(filteredData).length === 0) {
    return null;
  }
  const secondaryHeadingLevel = Math.min(headingLevel + 1, 6);
  const YearHeading = `h${headingLevel}`;
  const TypeHeading = `h${secondaryHeadingLevel}`;
  const showTypeHeadings = referenceType === 'all';
  const showTypeReorderControls = referenceType === 'all' && orderedTypeKeys.length > 1;
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.Fragment, {
    children: sortedYears.map(yearKey => {
      const yearData = filteredData[yearKey];
      return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)("div", {
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(YearHeading, {
          className: "references-year",
          children: yearKey
        }), orderedTypeKeys.map(typeKey => {
          const items = yearData[typeKey];
          if (!items || items.length === 0) {
            return null;
          }
          const currentIndex = orderedTypeKeys.indexOf(typeKey);
          const isFirstType = currentIndex === 0;
          const isLastType = currentIndex === orderedTypeKeys.length - 1;
          return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)("div", {
            className: "reference-type-container",
            children: [showTypeHeadings && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)("div", {
              className: "references-type-header",
              children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(TypeHeading, {
                className: "references-type",
                children: typeLabels[typeKey]
              }), showTypeReorderControls && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.ButtonGroup, {
                className: "references-type-movers",
                children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
                  icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_3__["default"],
                  onClick: () => moveTypeUp(typeKey),
                  label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Move up', 'gatherpress-references'),
                  disabled: isFirstType,
                  size: "small"
                }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
                  icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_2__["default"],
                  onClick: () => moveTypeDown(typeKey),
                  label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Move down', 'gatherpress-references'),
                  disabled: isLastType,
                  size: "small"
                })]
              })]
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("ul", {
              className: "references-list",
              children: items.map((item, index) => /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("li", {
                children: item
              }, index))
            })]
          }, typeKey);
        })]
      }, yearKey);
    })
  });
}

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
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _hooks_use_config__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./hooks/use-config */ "./src/hooks/use-config.js");
/* harmony import */ var _hooks_use_type_order__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./hooks/use-type-order */ "./src/hooks/use-type-order.js");
/* harmony import */ var _hooks_use_block_label__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./hooks/use-block-label */ "./src/hooks/use-block-label.js");
/* harmony import */ var _components_not_configured__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./components/not-configured */ "./src/components/not-configured.js");
/* harmony import */ var _components_reference_inspector__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./components/reference-inspector */ "./src/components/reference-inspector.js");
/* harmony import */ var _components_reference_preview__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./components/reference-preview */ "./src/components/reference-preview.js");
/* harmony import */ var _utils_placeholder_data__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./utils/placeholder-data */ "./src/utils/placeholder-data.js");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./editor.scss */ "./src/editor.scss");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__);
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


/**
 * Internal dependencies - Hooks
 */




/**
 * Internal dependencies - Components
 */




/**
 * Internal dependencies - Utilities
 */


/**
 * Editor styles
 */


/**
 * Edit component for GatherPress References block
 *
 * @param {Object}   props               Block properties from WordPress.
 * @param {Object}   props.attributes    Current block attribute values.
 * @param {Function} props.setAttributes Function to update block attributes.
 * @return {Element} React element to render in editor.
 */

function Edit({
  attributes,
  setAttributes
}) {
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__.useBlockProps)();
  const {
    postType,
    refTermId,
    year,
    referenceType,
    headingLevel,
    yearSortOrder,
    typeOrder
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
    isConfigured
  } = (0,_hooks_use_config__WEBPACK_IMPORTED_MODULE_1__["default"])({
    postType,
    setAttributes
  });

  // Manage type ordering.
  const {
    orderedTypeKeys,
    moveTypeUp,
    moveTypeDown
  } = (0,_hooks_use_type_order__WEBPACK_IMPORTED_MODULE_2__["default"])({
    config,
    typeOrder,
    setAttributes
  });

  // Update block label in list view.
  (0,_hooks_use_block_label__WEBPACK_IMPORTED_MODULE_3__["default"])({
    refTermId,
    year,
    referenceType,
    refTerms,
    typeLabels,
    isConfigured,
    setAttributes
  });

  // Show error state if not configured.
  if (!isConfigured || !activePostType) {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_components_not_configured__WEBPACK_IMPORTED_MODULE_4__["default"], {
      blockProps: blockProps,
      supportedPostTypes: supportedPostTypes
    });
  }

  // Generate and process preview data.
  const placeholderData = (0,_utils_placeholder_data__WEBPACK_IMPORTED_MODULE_7__.getPlaceholderData)({
    isConfigured,
    config,
    year,
    orderedTypeKeys,
    typeLabels
  });
  const filteredData = (0,_utils_placeholder_data__WEBPACK_IMPORTED_MODULE_7__.filterPlaceholderData)(placeholderData, referenceType);
  const sortedYears = (0,_utils_placeholder_data__WEBPACK_IMPORTED_MODULE_7__.getSortedYears)(filteredData, year, yearSortOrder);
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.Fragment, {
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_components_reference_inspector__WEBPACK_IMPORTED_MODULE_5__["default"], {
      attributes: attributes,
      setAttributes: setAttributes,
      supportedPostTypes: supportedPostTypes,
      refTaxonomy: refTaxonomy,
      refTerms: refTerms,
      taxonomies: taxonomies
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("div", {
      ...blockProps,
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_components_reference_preview__WEBPACK_IMPORTED_MODULE_6__["default"], {
        filteredData: filteredData,
        sortedYears: sortedYears,
        orderedTypeKeys: orderedTypeKeys,
        typeLabels: typeLabels,
        headingLevel: headingLevel,
        referenceType: referenceType,
        moveTypeUp: moveTypeUp,
        moveTypeDown: moveTypeDown
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

/***/ "./src/hooks/use-block-label.js"
/*!**************************************!*\
  !*** ./src/hooks/use-block-label.js ***!
  \**************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ useBlockLabel)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
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
function useBlockLabel({
  refTermId,
  year,
  referenceType,
  refTerms,
  typeLabels,
  isConfigured,
  setAttributes
}) {
  const previousLabelRef = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useRef)('');
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    if (!isConfigured) {
      return;
    }
    const parts = [];
    if (refTermId > 0) {
      const refTerm = refTerms.find(p => p.id === refTermId);
      if (refTerm) {
        parts.push(refTerm.name);
      }
    }
    if (year > 0) {
      parts.push(year.toString());
    }
    if (referenceType !== 'all') {
      parts.push(typeLabels[referenceType] || referenceType);
    }
    let newLabel;
    if (parts.length > 0) {
      newLabel = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('References', 'gatherpress-references') + ': ' + parts.join(' \u2022 ');
    } else {
      newLabel = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('References', 'gatherpress-references');
    }
    if (newLabel !== previousLabelRef.current) {
      previousLabelRef.current = newLabel;
      setAttributes({
        metadata: {
          name: newLabel
        }
      });
    }
  }, [setAttributes, refTermId, year, referenceType, refTerms, typeLabels, isConfigured]);
}

/***/ },

/***/ "./src/hooks/use-config.js"
/*!*********************************!*\
  !*** ./src/hooks/use-config.js ***!
  \*********************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ useConfig)
/* harmony export */ });
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/**
 * Configuration Hook
 *
 * Handles post type detection, configuration loading, and taxonomy data retrieval.
 *
 * @since 0.1.0
 */

/**
 * WordPress dependencies
 */



/**
 * Custom hook for block configuration
 *
 * Fetches supported post types, active configuration, taxonomy terms,
 * and type labels from the WordPress data store.
 *
 * @param {Object}   params               Hook parameters.
 * @param {string}   params.postType      Current post type attribute.
 * @param {Function} params.setAttributes Function to update block attributes.
 * @return {Object} Configuration data including supportedPostTypes, activePostType, config, refTaxonomy, refTerms, taxonomies, typeLabels, and isConfigured.
 */
function useConfig({
  postType,
  setAttributes
}) {
  /**
   * Fetch all post types with gatherpress_references support
   */
  const supportedPostTypes = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.useSelect)(select => {
    const postTypes = select('core').getPostTypes({
      per_page: -1
    });
    if (!postTypes) {
      return [];
    }
    return postTypes.filter(type => {
      return type.supports && type.supports.gatherpress_references;
    });
  }, []);

  /**
   * Auto-assign post type on block insertion if only one supported type exists
   */
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    if (!postType && supportedPostTypes.length === 1) {
      setAttributes({
        postType: supportedPostTypes[0].slug
      });
    }
  }, [postType, supportedPostTypes, setAttributes]);

  /**
   * Determine active post type for configuration lookup
   */
  const activePostType = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useMemo)(() => {
    if (postType) {
      return postType;
    }
    return supportedPostTypes.length > 0 ? supportedPostTypes[0].slug : null;
  }, [postType, supportedPostTypes]);

  /**
   * Fetch block configuration from the active post type
   */
  const config = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.useSelect)(select => {
    if (!activePostType) {
      return null;
    }
    const postTypeObject = select('core').getPostType(activePostType);
    if (!postTypeObject || !postTypeObject.supports) {
      return null;
    }
    const referencesSupport = postTypeObject.supports.gatherpress_references;
    if (!referencesSupport) {
      return null;
    }
    if (Array.isArray(referencesSupport) && referencesSupport.length > 0) {
      return referencesSupport[0];
    }
    if (typeof referencesSupport === 'object' && referencesSupport !== null) {
      return referencesSupport;
    }
    return null;
  }, [activePostType]);

  /**
   * Fetch reference taxonomy object and terms
   */
  const {
    refTaxonomy,
    refTerms
  } = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.useSelect)(select => {
    if (!config || !config.ref_tax) {
      return {
        refTaxonomy: null,
        refTerms: []
      };
    }
    const taxonomy = select('core').getTaxonomy(config.ref_tax);
    const terms = select('core').getEntityRecords('taxonomy', config.ref_tax, {
      per_page: 99
    });
    return {
      refTaxonomy: taxonomy || null,
      refTerms: terms || []
    };
  }, [config]);

  /**
   * Fetch taxonomy objects for reference types
   */
  const taxonomies = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.useSelect)(select => {
    if (!config || !config.ref_types || !Array.isArray(config.ref_types)) {
      return [];
    }
    return config.ref_types.map(slug => select('core').getTaxonomy(slug)).filter(tax => tax !== null && tax !== undefined);
  }, [config]);

  /**
   * Build type labels mapping
   */
  const typeLabels = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useMemo)(() => {
    const labels = {};
    taxonomies.forEach(tax => {
      labels[tax.slug] = tax.labels?.name || tax.name;
    });
    return labels;
  }, [taxonomies]);

  /**
   * Check if block is properly configured
   */
  const isConfigured = config && typeof config === 'object' && config.ref_tax && config.ref_types && Array.isArray(config.ref_types) && config.ref_types.length > 0;
  return {
    supportedPostTypes,
    activePostType,
    config,
    refTaxonomy,
    refTerms,
    taxonomies,
    typeLabels,
    isConfigured
  };
}

/***/ },

/***/ "./src/hooks/use-type-order.js"
/*!*************************************!*\
  !*** ./src/hooks/use-type-order.js ***!
  \*************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ useTypeOrder)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
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
function useTypeOrder({
  config,
  typeOrder,
  setAttributes
}) {
  /**
   * Get ordered type keys based on typeOrder attribute or default config order
   */
  const orderedTypeKeys = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useMemo)(() => {
    if (!config || !config.ref_types) {
      return [];
    }
    if (typeOrder && Array.isArray(typeOrder) && typeOrder.length > 0) {
      const validOrder = typeOrder.filter(type => config.ref_types.includes(type));
      const missingTypes = config.ref_types.filter(type => !validOrder.includes(type));
      return [...validOrder, ...missingTypes];
    }
    return config.ref_types;
  }, [config, typeOrder]);

  /**
   * Initialize typeOrder attribute if not set
   */
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (config && config.ref_types && !typeOrder) {
      setAttributes({
        typeOrder: config.ref_types
      });
    }
  }, [config, typeOrder, setAttributes]);

  /**
   * Move type up in order
   *
   * @param {string} typeKey The key of the type to move up.
   */
  const moveTypeUp = typeKey => {
    const currentIndex = orderedTypeKeys.indexOf(typeKey);
    if (currentIndex <= 0) {
      return;
    }
    const newOrder = [...orderedTypeKeys];
    const temp = newOrder[currentIndex - 1];
    newOrder[currentIndex - 1] = newOrder[currentIndex];
    newOrder[currentIndex] = temp;
    setAttributes({
      typeOrder: newOrder
    });
  };

  /**
   * Move type down in order
   *
   * @param {string} typeKey The key of the type to move down.
   */
  const moveTypeDown = typeKey => {
    const currentIndex = orderedTypeKeys.indexOf(typeKey);
    if (currentIndex === -1 || currentIndex >= orderedTypeKeys.length - 1) {
      return;
    }
    const newOrder = [...orderedTypeKeys];
    const temp = newOrder[currentIndex + 1];
    newOrder[currentIndex + 1] = newOrder[currentIndex];
    newOrder[currentIndex] = temp;
    setAttributes({
      typeOrder: newOrder
    });
  };
  return {
    orderedTypeKeys,
    moveTypeUp,
    moveTypeDown
  };
}

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

/***/ "./src/utils/placeholder-data.js"
/*!***************************************!*\
  !*** ./src/utils/placeholder-data.js ***!
  \***************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   filterPlaceholderData: () => (/* binding */ filterPlaceholderData),
/* harmony export */   getPlaceholderData: () => (/* binding */ getPlaceholderData),
/* harmony export */   getSortedYears: () => (/* binding */ getSortedYears)
/* harmony export */ });
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
function buildYearData(orderedTypeKeys, typeLabels) {
  const yearData = {};
  orderedTypeKeys.forEach(taxSlug => {
    const taxLabel = typeLabels[taxSlug] || taxSlug;
    yearData[taxSlug] = [`${taxLabel} Example 1`, `${taxLabel} Example 2`].sort();
  });
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
function getPlaceholderData({
  isConfigured,
  config,
  year,
  orderedTypeKeys,
  typeLabels
}) {
  if (!isConfigured || !config?.ref_types) {
    return {};
  }
  const currentYear = new Date().getFullYear();
  const displayYear = year > 0 ? year : currentYear;
  if (year > 0) {
    return {
      [displayYear]: buildYearData(orderedTypeKeys, typeLabels)
    };
  }
  return {
    [currentYear + ' ']: buildYearData(orderedTypeKeys, typeLabels),
    [currentYear - 1 + ' ']: buildYearData(orderedTypeKeys, typeLabels)
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
function filterPlaceholderData(placeholderData, referenceType) {
  if (referenceType === 'all') {
    return placeholderData;
  }
  const filtered = {};
  Object.keys(placeholderData).forEach(yearKey => {
    const yearData = placeholderData[yearKey];
    if (yearData[referenceType] && yearData[referenceType].length > 0) {
      filtered[yearKey] = {
        [referenceType]: yearData[referenceType]
      };
    }
  });
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
function getSortedYears(filteredData, year, yearSortOrder) {
  const years = Object.keys(filteredData);
  if (year > 0) {
    return years;
  }
  return years.sort((a, b) => {
    const yearA = parseInt(a);
    const yearB = parseInt(b);
    if (yearSortOrder === 'asc') {
      return yearA - yearB;
    }
    return yearB - yearA;
  });
}

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

/***/ "@wordpress/primitives"
/*!************************************!*\
  !*** external ["wp","primitives"] ***!
  \************************************/
(module) {

module.exports = window["wp"]["primitives"];

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