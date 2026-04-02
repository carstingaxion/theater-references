# gatherpress_references_query_args


Modify WP_Query arguments before the posts, that affect the reference-display, are queried.

## Example

Add custom meta query
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

## Parameters

- *`array<mixed>`* `$args` WP_Query arguments.
- *`string`* `$post_type` Post type slug.
- *`int`* `$ref_term_id` Reference term ID.
- *`int`* `$year` Year filter.
- *`string`* `$type` Reference type filter.

## Returns

`array<mixed>` Modified WP_Query arguments.

## Files

- [includes/classes/class-query-builder.php:98](https://github.com/carstingaxion/gatherpress-cache-invalidation-hooks/blob/main/includes/classes/class-query-builder.php#L98)
```php
apply_filters( 'gatherpress_references_query_args', $args, $post_type, $ref_term_id, $year, $type )
```



[← All Hooks](Hooks.md)
