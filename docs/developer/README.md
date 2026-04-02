# Developer documentation

## Filter Hooks Reference

The plugin provides several filter hooks for customization, documented in [`docs/developer/hooks/Hooks.md`](hooks/Hooks.md)

## GatherPress Integration

The plugin extends GatherPress by registering taxonomies and using post type support for configuration:

```php
// Configure which taxonomies to use via post type support
$config = array(
    'ref_tax'   => 'gatherpress-production',  // The main reference taxonomy
    'ref_types' => array( '_gatherpress-client', '_gatherpress-festival', '_gatherpress-award' ),
);
add_post_type_support( 'gatherpress_event', 'gatherpress_references', $config );
```

## Block Attributes

```json
{
  "refTermId": {
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

## Caching Strategy

The plugin uses WordPress transients for performance:

```php
// Cache key based on filter parameters
$cache_key = 'gatherpress_refs_' . md5(serialize([
    $ref_term_id,
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

## Data Organization

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


## Performance Considerations

1. **Batch Queries**: Use `get_post_dates()` and `get_post_terms()` to minimize database calls
2. **Field Limitation**: Query only post IDs with `'fields' => 'ids'`
3. **Cache Management**: Automatic transient caching with smart invalidation
4. **No Found Rows**: Set `'no_found_rows' => true` to skip pagination count
5. **Term Cache**: Enable term caching with `'update_post_term_cache' => true`

## Theme.json Integration

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
