=== Theater References ===

Contributors:      WordPress Telex
Tags:              block, references, theater, events
Tested up to:      6.8
Stable tag:        0.1.0
License:           GPLv2 or later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Display theater production references including guest performances, festivals, and awards in a structured, chronological format.

== Description ==

The Theater References block displays a curated list of references from past theater events. It automatically organizes references by year and type (guest performances/clients, festivals, awards).

Key features:
- Uses custom taxonomies for efficient data management and querying
- Automatic context detection when used within a production page
- Filter by specific production (by ID)
- Filter by year for annual reviews
- Filter by reference type (venues/clients, festivals, awards, or all)
- Nested list output organized by year and reference type
- Native WordPress term management UI
- Better performance through taxonomy-based queries

Perfect for creating dynamic reference pages, production portfolios, and annual summaries of achievements.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/theater-references` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. The plugin will create three custom taxonomies: Venues & Clients, Festivals, and Awards
1. Add terms to these taxonomies via the Events admin menu
1. Assign taxonomy terms to event posts
1. Insert the block into any page, post, or production content
1. Use the block settings to filter by production, year, or reference type

== Usage Examples ==

### Example 1: Show All References for a Specific Production

**Use Case:** Production detail page showing all achievements

**Settings:**
- Production: Select specific production (e.g., "Hamlet")
- Year: Leave empty (all years)
- Reference Type: All Types

**Result:** Displays all venues, festivals, and awards for that production across all years, organized chronologically.

---

### Example 2: Annual Report Page

**Use Case:** Yearly summary of all activities

**Settings:**
- Production: Auto-detect (or all)
- Year: 2024
- Reference Type: All Types

**Result:** Shows all references from 2024 regardless of production, grouped by type.

---

### Example 3: Awards Page

**Use Case:** Dedicated page highlighting awards won

**Settings:**
- Production: Auto-detect (or all)
- Year: Leave empty (all years)
- Reference Type: Awards

**Result:** Lists all awards received across all productions and years, organized by year.

---

### Example 4: Production Awards for Specific Year

**Use Case:** Show what a production achieved in a particular year

**Settings:**
- Production: Select specific production
- Year: 2023
- Reference Type: Awards

**Result:** Displays only awards that specific production received in 2023.

---

### Example 5: Festival Participation History

**Use Case:** Portfolio page showing festival presence

**Settings:**
- Production: Auto-detect (or all)
- Year: Leave empty
- Reference Type: Festivals

**Result:** Complete chronological list of festival participations across all productions.

== Filter Combinations Matrix ==

This matrix shows all possible filter combinations and their expected behaviors:

| # | Production | Type     | Year     | Expected Result                                      |
|---|------------|----------|----------|-----------------------------------------------------|
| 1 | Any/All    | All      | All      | Show all events, all types (no filters)             |
| 2 | Specific   | All      | All      | Show events for production, all types               |
| 3 | Specific   | Specific | All      | Show events for production with specific type       |
| 4 | Any/All    | Specific | All      | Show all events with specific type                  |
| 5 | Any/All    | All      | Specific | Show all events from year, all types                |
| 6 | Specific   | All      | Specific | Show production events from year, all types         |
| 7 | Specific   | Specific | Specific | Show production events from year with specific type |
| 8 | Any/All    | Specific | Specific | Show all events from year with specific type        |

**Filter Logic:**
- All filters use AND logic when combined
- "Any/All" for Production means no production filter is applied
- "All" for Type means no type filter is applied
- "All" for Year means no year filter is applied

== Frequently Asked Questions ==

= What taxonomies does this plugin create? =

The plugin creates three custom taxonomies associated with the 'events' post type:
- theater-venues: Guest performance venues or clients
- theater-festivals: Festival participations
- theater-awards: Awards received

= Why use taxonomies instead of post meta? =

Taxonomies offer several advantages:
- Better query performance for filtering
- Reusable terms across multiple events
- Native WordPress UI for term management
- More semantic data structure
- Better for faceted search and filtering

= Can I show only awards for a specific production? =

Yes! Use the block settings to select a specific production and set the type filter to "Awards".

= Does it work automatically on production pages? =

Yes, when placed on a production archive or single page, the block automatically detects the production context and shows only its references.

= How do I customize the block's appearance? =

The block supports WordPress theme.json for comprehensive styling:
- Color settings (background, text, links)
- Typography (font family, size, line height, etc.)
- Spacing (margin, padding, block gap)
- Border settings (color, radius, width)

You can also write custom CSS targeting these classes:
- `.wp-block-telex-theater-references` - Main container
- `.references-year` - Year headings
- `.references-type` - Type headings
- `.references-list` - Reference lists
- `.no-references` - Empty state message

= Can I change the heading levels? =

Yes! The block includes a "Year Heading Level" control in the block settings. You can set year headings to H1-H5, and type headings will automatically be one level smaller.

= How do I add demo data for testing? =

Go to Events → Demo Data in the WordPress admin. Click "Generate Demo Data" to create:
- 5 theater productions
- 20 event posts
- 8 venue/client terms
- 6 festival terms
- 6 award terms

All demo data is marked for easy cleanup via the "Delete Demo Data" button.

== Screenshots ==

1. Block editor view with inspector controls
2. Frontend display showing references grouped by year
3. Filtered view showing only awards
4. Term management interface for venues, festivals, and awards

== Developer Documentation ==

### Architecture Overview

The plugin uses a modern WordPress block architecture with:

**PHP Components:**
- `Theater_References_Manager`: Singleton class managing post types, taxonomies, caching
- `Theater_References_Renderer`: Handles server-side rendering with optimized queries

**JavaScript Components:**
- React-based block editor component with live preview
- Inspector controls for filtering
- Dynamic block labeling in list view

**Data Structure:**
- Custom post type: `events`
- Taxonomies: `theater-productions`, `theater-venues`, `theater-festivals`, `theater-awards`
- Hierarchical structure for productions, flat structure for references

### Custom Taxonomies

```php
// Production taxonomy (hierarchical)
register_taxonomy('theater-productions', 'events', [
    'hierarchical' => true,
    'show_in_rest' => true,
]);

// Reference taxonomies (flat, like tags)
register_taxonomy('theater-venues', 'events', [
    'hierarchical' => false,
    'show_in_rest' => true,
]);
```

### Block Attributes

```json
{
    "productionId": {
        "type": "number",
        "default": 0
    },
    "year": {
        "type": "string",
        "default": ""
    },
    "referenceType": {
        "type": "string",
        "default": "all",
        "enum": ["all", "theater-venues", "theater-festivals", "theater-awards"]
    },
    "headingLevel": {
        "type": "number",
        "default": 2
    }
}
```

### Query Logic

The renderer builds optimized WP_Query arguments:

```php
// Base query
$args = [
    'post_type' => 'events',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
    'fields' => 'ids', // Performance optimization
];

// Add production filter
if ($production_id > 0) {
    $tax_query[] = [
        'taxonomy' => 'theater-productions',
        'field' => 'term_id',
        'terms' => $production_id,
    ];
}

// Add type filter (if not 'all')
if ($type !== 'all') {
    $tax_query[] = [
        'taxonomy' => $type,
        'operator' => 'EXISTS',
    ];
}

// Add year filter
if (!empty($year)) {
    $args['date_query'] = [
        ['year' => intval($year)]
    ];
}
```

### Caching Strategy

The plugin uses WordPress transients for performance:

```php
// Cache key based on filter parameters
$cache_key = 'theater_refs_' . md5(serialize([
    $production_id,
    $year,
    $type
]));

// Try to get cached data
$cached = get_transient($cache_key);
if (false !== $cached) {
    return $cached;
}

// ... perform query ...

// Cache results for 1 hour
set_transient($cache_key, $references, 3600);
```

**Cache Invalidation:**
Cache is automatically cleared when:
- Event posts are saved or deleted
- Taxonomy terms are edited or deleted
- Demo data is generated or deleted

### Data Organization

Results are organized in a nested structure:

```php
[
    '2024' => [
        'theater-venues' => ['Venue 1', 'Venue 2'],
        'theater-festivals' => ['Festival 1'],
        'theater-awards' => ['Award 1', 'Award 2']
    ],
    '2023' => [
        'theater-venues' => ['Venue 3'],
        'theater-festivals' => [],
        'theater-awards' => ['Award 3']
    ]
]
```

### Extending the Block

**Add a new reference type:**

1. Register new taxonomy in `theater-references.php`:
```php
private function register_custom_taxonomy(): void {
    register_taxonomy('theater-custom', 'events', [
        'hierarchical' => false,
        'public' => true,
        'show_in_rest' => true,
        'labels' => [
            'name' => __('Custom References', 'theater-references'),
            // ... more labels
        ]
    ]);
}
```

2. Add to type labels in `render.php`:
```php
public function get_type_labels(): array {
    return [
        // ... existing types
        'theater-custom' => __('Custom References', 'theater-references'),
    ];
}
```

3. Update block.json to include new type in enum:
```json
"referenceType": {
    "enum": ["all", "theater-venues", "theater-festivals", "theater-awards", "theater-custom"]
}
```

4. Add option to SelectControl in `edit.js`:
```javascript
{
    label: __('Custom References', 'theater-references'),
    value: 'theater-custom'
}
```

**Customize rendering:**

Override CSS classes in your theme:

```css
/* Customize year headings */
.wp-block-telex-theater-references .references-year {
    color: #your-color;
    font-size: 2rem;
}

/* Customize list bullets */
.wp-block-telex-theater-references .references-list li::before {
    content: "★"; /* Custom bullet */
    color: gold;
}
```

**Filter hook examples:**

```php
// Modify cache expiration time
add_filter('theater_references_cache_expiration', function($seconds) {
    return 7200; // 2 hours instead of 1
});

// Modify query arguments before execution
add_filter('theater_references_query_args', function($args) {
    $args['posts_per_page'] = 50; // Limit results
    return $args;
});
```

### Performance Considerations

1. **Batch Queries**: Use `get_post_dates()` and `get_post_terms()` to minimize database calls
2. **Field Limitation**: Query only post IDs with `'fields' => 'ids'`
3. **Cache Management**: Automatic transient caching with smart invalidation
4. **No Found Rows**: Set `'no_found_rows' => true` to skip pagination count
5. **Term Cache**: Enable term caching with `'update_post_term_cache' => true`

### Debug Mode

Enable debug output in `render.php`:

```php
private bool $debug = true; // Set to true
```

This will output:
- Filter parameters received
- Query arguments before execution
- SQL query generated
- Number of posts found
- Term assignment details
- Final data structure

### Theme.json Integration

The block fully supports WordPress theme.json:

```json
{
    "settings": {
        "blocks": {
            "telex/theater-references": {
                "color": {
                    "palette": [
                        {
                            "name": "Primary",
                            "slug": "primary",
                            "color": "#your-color"
                        }
                    ]
                },
                "typography": {
                    "fontSizes": [
                        {
                            "name": "Year Heading",
                            "slug": "year-heading",
                            "size": "2.5rem"
                        }
                    ]
                },
                "spacing": {
                    "padding": true,
                    "margin": true,
                    "blockGap": true
                }
            }
        }
    }
}
```

== Changelog ==

= 0.1.0 =
* Initial release
* Custom taxonomies for venues, festivals, and awards
* Support for production filtering
* Support for year filtering
* Support for reference type filtering
* Automatic production context detection
* Demo data generator
* Comprehensive theme.json support
* Optimized caching system
* Debug mode for development
* Dynamic heading levels
* Block metadata labeling
* Responsive design
* Accessibility features

== Upgrade Notice ==

= 0.1.0 =
Initial release. Please ensure you have WordPress 6.1 or higher and PHP 7.4 or higher.