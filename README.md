# GatherPress References

**Contributors:** carstenbach & WordPress Telex  
**Tags:** block, references, theater, events  
**Tested up to:** 6.8  
**Stable tag:** 0.1.0  
**Requires Plugins:**  gatherpress  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html  

[![Playground Demo Link](https://img.shields.io/badge/WordPress_Playground-blue?logo=wordpress&logoColor=%23fff&labelColor=%233858e9&color=%233858e9)](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/carstingaxion/gatherpress-references/main/.wordpress-org/blueprints/blueprint.json)

Display production references including clients, festivals and awards in a structured, chronological format.

## Description

The **GatherPress References** block displays a generated list of references from past events. It automatically organizes references by year and type (clients, festivals, awards).

This plugin requires the GatherPress plugin to be installed and activated. It works with GatherPress events (`gatherpress_event` post type) to provide specialized reference management for e.g. theater productions.

Perfect for creating dynamic reference pages, production portfolios, and annual summaries of achievements.

---

## Usage Examples

### Example 1: Show All References for a Specific Production

**Use Case:** Production detail page showing all achievements  

**Settings:**
- Production: Select specific production (e.g., *Hamlet*)
- Year: Leave empty (all years)
- Reference Type: All Types  

**Result:** Displays all clients, festivals, and awards for that production across all years, organized chronologically.

![Show the editing Interface for the block for: All References for a Specific Production](assets/screenshot-1.png)

---

### Example 2: Annual Report Page

**Use Case:** Yearly summary of all activities  

**Settings:**
- Production: Auto-detect (or all)
- Year: Enter specific year (e.g., 2024)
- Reference Type: All Types

**Result:** Shows all references from 2024 regardless of production, grouped by type.

![Shows the editing Interface for the block for: All references from 2024 regardless of production, grouped by type.](assets/screenshot-2.png)

---

### Example 3: Awards Page

**Use Case:** Dedicated page highlighting awards won  

**Settings:**
- Production: Auto-detect (or all)
- Year: Leave empty (all years)
- Reference Type: Awards  

**Result:** Lists all awards received across all productions and years, organized by year.

![Shows the editing Interface for the block for: All awards received across all productions and years, organized by year.](assets/screenshot-3.png)

---

### Example 4: Production Awards for Specific Year

**Use Case:** Show what a production achieved in a particular year  

**Settings:**
- Production: Select specific production (e.g., *Macbeth*)
- Year: Enter specific year (e.g., 2023)
- Reference Type: Awards

**Result:** Displays only awards that Macbeth received in 2023.

![Shows the editing Interface for the block for: Displays only awards that Macbeth received in 2023.](assets/screenshot-4.png)

---

### Example 5: Festival Participation History

**Use Case:** Portfolio page showing festival presence  

**Settings:**
- Production: Auto-detect (or all)
- Year: Leave empty
- Reference Type: Festivals  

**Result:** Complete chronological list of festival participations across all productions.

![Shows the editing Interface for the block for: A Complete chronological list of festival participations across all productions.](assets/screenshot-5.png)

---

## Filter Combinations Matrix

This matrix shows all possible filter combinations and their expected behaviors:

| # | Production | Year     | Type     | Expected Result                                      |
|---|------------|----------|----------|-----------------------------------------------------|
| 1 | Any/All    | All      | All      | Show all events, all types (no filters)             |
| 2 | Specific   | All      | All      | Show events for production, all types               |
| 3 | Specific   | All      | Specific | Show events for production with specific type       |
| 4 | Any/All    | All      | Specific | Show all events with specific type                  |
| 5 | Any/All    | Specific | All      | Show all events from year, all types                |
| 6 | Specific   | Specific | All      | Show production events from year, all types         |
| 7 | Specific   | Specific | Specific | Show production events from year with specific type |
| 8 | Any/All    | Specific | Specific | Show all events from year with specific type        |

**Filter Logic:**

- All filters use **AND** logic when combined  
- **Any/All** for Production means no production filter is applied  
- **All** for Type means no type filter is applied  
- **All** for Year means no year filter is applied  

---

## Installation

1. **Install and activate GatherPress plugin first** (required dependency)
2. Upload the plugin files to the `/wp-content/plugins/gatherpress-references` directory.
3. Activate the plugin through the **Plugins** screen in WordPress.
4. The plugin will register three custom taxonomies: **Clients**, **Festivals**, and **Awards**.
5. Add terms to these taxonomies via the **Events** admin menu.
6. Assign taxonomy terms to event posts.
7. Insert the block into any post or template.
8. Use the block settings to filter by production, year, or reference type.

---

## Frequently Asked Questions

### Does this plugin work without GatherPress?

No, this plugin is specifically designed as a GatherPress add-on and requires GatherPress to be installed and activated. It extends GatherPress functionality to add reference management.

### What taxonomies does this plugin create?

The plugin registers four custom taxonomies associated with the GatherPress `gatherpress_event` post type:
- `gatherpress-productions`: Hierarchical taxonomy for productions
- `_gatherpress-client`: Clients
- `_gatherpress-festival`: Festival participations
- `_gatherpress-award`: Awards received

These work alongside GatherPress's existing event taxonomies.

### Can I show only awards for a specific production? =

Yes. Use the block settings to select a specific production and set the type filter to **Awards**.

### Does it work automatically on production pages?

Yes. When placed on a production term archive, the block automatically detects the production context and shows only its references.

### How do I customize the block's appearance?

The block supports **theme.json** styling:

- Colors (background, text, links)  
- Typography (font family, size, line height)  
- Spacing (margin, padding, block gap)  
- Borders (color, radius, width)  

You can also use custom CSS:

- `.wp-block-gatherpress-references` - Main container
- `.references-year` - Year headings
- `.references-type` - Type headings
- `.references-list` - Reference lists

### Can I change the heading levels?

Yes. The block includes a **Year Heading Level** control in the block settings. You can set year headings to **H1–H5**, and type headings will automatically be one level smaller.

### Can I change the year sort order?

Yes. The block includes a **Sort Years** toggle control. By default, years are sorted newest first (descending). Toggle it on to sort from oldest to newest (ascending). This control only appears when showing all years (no specific year filter).

= How do I add demo data for testing? =

Go to **Events → Demo Data** in the WordPress admin and click **Generate Demo Data** to create:

- 5 theater productions  
- 20 GatherPress event posts  
- 8 client terms  
- 6 festival terms  
- 6 award terms  

All demo data is marked for easy cleanup via the **Delete Demo Data** button.


## Does this affect my existing GatherPress events?

No, this plugin only adds optional taxonomies to GatherPress events. Your existing events remain unchanged unless you explicitly assign the new taxonomy terms to them.


---

## Screenshots

1. Show All References for a Specific Production
2. Annual Report Page
3. Awards Page
4. Production Awards for Specific Year
5. Festival Participation History

---

## Developer Documentation

### GatherPress Integration

The plugin extends GatherPress by:

```php
// Registers taxonomies for gatherpress_event post type
register_taxonomy('gatherpress-productions', 'gatherpress_event', [...]);
register_taxonomy('_gatherpress-client', 'gatherpress_event', [...]);
register_taxonomy('_gatherpress-festival', 'gatherpress_event', [...]);
register_taxonomy('_gatherpress-award', 'gatherpress_event', [...]);
```

### Block Attributes

```json
{
  "productionId": {
    "type": "number",
    "default": 0
  },
  "year": {
    "type": "number",
    "default": 0
  },
  "referenceType": {
    "type": "string",
    "default": "all",
    "enum": ["all", "ref_client", "ref_festival", "ref_award"]
  },
  "headingLevel": {
    "type": "number",
    "default": 2
  },
  "yearSortOrder": {
    "type": "string",
    "default": "desc",
    "enum": ["asc", "desc"]
  }
}
```

### Caching Strategy

The plugin uses WordPress transients for performance:

```php
// Cache key based on filter parameters
$cache_key = 'gatherpress_refs_' . md5(serialize([
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

Results are organized and cached in a nested structure:

```php
[
    '2024' => [
        '_gatherpress-client' => ['Client 1', 'Client 2'],
        '_gatherpress-festival' => ['Festival 1'],
        '_gatherpress-award' => ['Award 1', 'Award 2']
    ],
    '2023' => [
        '_gatherpress-client' => ['Client 3'],
        '_gatherpress-festival' => [],
        '_gatherpress-award' => ['Award 3']
    ]
]
```

### Filter Hooks Reference

The plugin provides several filter hooks for customization:

#### `gatherpress_references_cache_expiration`

**Description:** Modify the cache expiration time in seconds.

**Default:** 3600 (1 hour)

**Parameters:**
- `$seconds` (int) - Cache expiration time in seconds

**Example - Increase cache to 2 hours:**
```php
add_filter( 'gatherpress_references_cache_expiration', function( $seconds ) {
    return 7200; // 2 hours
} );
```

**Example - Disable caching:**
```php
add_filter( 'gatherpress_references_cache_expiration', '__return_zero' );
```

---

#### `gatherpress_references_query_args`

**Description:** Modify WP_Query arguments before the query is executed.

**Parameters:**
- `$args` (array) - WP_Query arguments array
- `$production_id` (int) - Production term ID filter
- `$year` (string) - Year filter
- `$type` (string) - Reference type filter

**Example - Limit results to 50 posts:**
```php
add_filter( 'gatherpress_references_query_args', function( $args ) {
    $args['posts_per_page'] = 50;
    return $args;
} );
```

**Example - Add custom meta query:**
```php
add_filter( 'gatherpress_references_query_args', function( $args ) {
    $args['meta_query'] = array(
        array(
            'key'     => 'featured',
            'value'   => '1',
            'compare' => '='
        )
    );
    return $args;
} );
```

**Example - Exclude specific post IDs:**
```php
add_filter( 'gatherpress_references_query_args', function( $args ) {
    $args['post__not_in'] = array( 123, 456, 789 );
    return $args;
} );
```

**Example - Conditional ordering:**
```php
add_filter( 'gatherpress_references_query_args', function( $args, $production_id, $year, $type ) {
    // Order by title alphabetically for award references
    if ( $type === '_gatherpress-award' ) {
        $args['orderby'] = 'title';
        $args['order'] = 'ASC';
    }
    return $args;
}, 10, 4 );
```

---


#### `gatherpress_references_type_labels`

**Description:** Customize the human-readable labels for each reference type displayed in headings.

**Default:**
```php
array(
    '_gatherpress-client'    => __( 'Clients', 'gatherpress-references' ),
    '_gatherpress-festival' => __( 'Festivals', 'gatherpress-references' ),
    '_gatherpress-award'    => __( 'Awards', 'gatherpress-references' ),
)
```

**Parameters:**
- `$labels` (array) - Array of taxonomy slug => label pairs

**Example - Override existing label:**
```php
add_filter( 'gatherpress_references_type_labels', function( $labels ) {
    $labels['_gatherpress-award'] = __( 'Prizes & Honours', 'textdomain' );
    return $labels;
} );
```

**Example - Locale-specific labels:**
```php
add_filter( 'gatherpress_references_type_labels', function( $labels ) {
    $locale = get_locale();
    
    if ( $locale === 'de_DE' ) {
        $labels['_gatherpress-client'] = 'Gastspiele & Kunden';
        $labels['_gatherpress-festival'] = 'Festivals';
        $labels['_gatherpress-award'] = 'Auszeichnungen';
    }
    
    return $labels;
} );
```


### Performance Considerations

1. **Batch Queries**: Use `get_post_dates()` and `get_post_terms()` to minimize database calls
2. **Field Limitation**: Query only post IDs with `'fields' => 'ids'`
3. **Cache Management**: Automatic transient caching with smart invalidation
4. **No Found Rows**: Set `'no_found_rows' => true` to skip pagination count
5. **Term Cache**: Enable term caching with `'update_post_term_cache' => true`


### Theme.json Integration

The block fully supports WordPress theme.json:

```json
{
    "settings": {
        "blocks": {
            "gatherpress/references": {
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

## Changelog

### 0.1.0
* Initial release
* Custom taxonomies for clients, festivals and awards
* Support for production filtering
* Support for year filtering
* Support for reference type filtering
* Year sort order control (ascending/descending)
* Automatic production context detection
* Demo data generator
* Comprehensive theme.json support
* Optimized caching system
* Dynamic heading levels
* Filter hooks for extensibility
