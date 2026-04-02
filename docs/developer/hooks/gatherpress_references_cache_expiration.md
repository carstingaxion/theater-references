# gatherpress_references_cache_expiration


Allows filtering the cache expiration time in seconds.

Defaults to: 3600s = 1h

## Example

Increase cache to 2 hours:
```php
add_filter( 'gatherpress_references_cache_expiration', function( $expiration ) {
    return 7200; // Set cache expiration to 2 hours
} );
```

## Example

Disable caching by setting expiration to 0:
```php
add_filter( 'gatherpress_references_cache_expiration', '__return_zero' );
```

## Parameters

- *`int`* `$cache_expiration` Cache expiration time in seconds.

## Returns

`int` Filtered cache expiration time.

## Files

- [includes/classes/class-cache-manager.php:78](https://github.com/carstingaxion/gatherpress-cache-invalidation-hooks/blob/main/includes/classes/class-cache-manager.php#L78)
```php
apply_filters( 'gatherpress_references_cache_expiration', $this->cache_expiration )
```



[← All Hooks](Hooks.md)
