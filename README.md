# GatherPress References

**Contributors:** carstenbach & WordPress Telex  
**Tags:** block, references, theater, events  
**Tested up to:** 6.8  
**Stable tag:** 0.1.0  
**Requires Plugins:**  gatherpress  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html  

Display production references including clients, festivals, and awards in a structured, chronological format.

![](assets/screenshot-1.png)

## Description

The **GatherPress References** block displays a generated list of references from past events. It automatically organizes references by year and type (clients, festivals, awards).

This plugin requires the GatherPress plugin to be installed and activated. It works with GatherPress events (`gatherpress_event` post type) to provide specialized reference management for e.g. theater productions.

### Key features

- Seamlessly integrates with GatherPress events
- Uses custom taxonomies for efficient data management and querying
- Automatic context detection when used within a production page
- Filter by specific production (by ID)
- Filter by year for annual reviews
- Filter by reference type (clients, festivals, awards, or all)
- Nested list output organized by year and reference type
- Native WordPress term management UI
- Better performance through taxonomy-based queries

Perfect for creating dynamic reference pages, production portfolios, and annual summaries of achievements.

---

## Installation

1. **Install and activate GatherPress plugin first** (required dependency)
2. Upload the plugin files to the `/wp-content/plugins/gatherpress-references` directory.
3. Activate the plugin through the **Plugins** screen in WordPress.
4. The plugin will create three custom taxonomies: **Clients**, **Festivals**, and **Awards**.
5. Add terms to these taxonomies via the **Events** admin menu.
6. Assign taxonomy terms to event posts.
7. Insert the block into any page, post, or production content.
8. Use the block settings to filter by production, year, or reference type.

---

## Usage Examples

### Example 1: Show All References for a Specific Production

**Use Case:** Production detail page showing all achievements  

**Settings:**
- Production: Select specific production (e.g., *Hamlet*)
- Year: Leave empty (all years)
- Reference Type: All Types  

**Result:** Displays all clients, festivals, and awards for that production across all years, organized chronologically.

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

## Frequently Asked Questions

### Does this plugin work without GatherPress?

No, this plugin is specifically designed as a GatherPress add-on and requires GatherPress to be installed and activated. It extends GatherPress functionality to add reference management.

### What taxonomies does this plugin create?

The plugin creates three custom taxonomies associated with the GatherPress `gatherpress_event` post type:
- `_gatherpress-client`: Clients
- `_gatherpress-festival`: Festival participations
- `_gatherpress-award`: Awards received

These work alongside GatherPress's existing event taxonomies.

### Why use taxonomies instead of post meta?

Taxonomies offer several advantages:
- Better query performance for filtering
- Reusable terms across multiple events
- Native WordPress UI for term management
- More semantic data structure
- Better for faceted search and filtering
- Seamless integration with GatherPress event queries

### Can I show only awards for a specific production? =

Yes. Use the block settings to select a specific production and set the type filter to **Awards**.

### Does it work automatically on production pages?

Yes. When placed on a production archive or single page, the block automatically detects the production context and shows only its references.

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
- `.no-references` - Empty state message

### Can I change the heading levels?

Yes. The block includes a **Year Heading Level** control in the block settings. You can set year headings to **H1–H5**, and type headings will automatically be one level smaller.

### How do I add demo data for testing?

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

1. Block editor view with inspector controls
2. Frontend display showing references grouped by year
3. Filtered view showing only awards
4. Term management interface for clients, festivals, and awards

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
    "type": "string",
    "default": ""
  },
  "referenceType": {
    "type": "string",
    "default": "all",
    "enum": ["all", "_gatherpress-client", "_gatherpress-festival", "_gatherpress-award"]
  },
  "headingLevel": {
    "type": "number",
    "default": 2
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
* Custom taxonomies for clients, festivals, and awards
* Support for production filtering
* Support for year filtering
* Support for reference type filtering
* Automatic production context detection
* Demo data generator
* Comprehensive theme.json support
* Optimized caching system
* Debug mode for development
* Dynamic heading levels
* Filter hooks for extensibility
