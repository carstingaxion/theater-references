# gatherpress_references_type_labels


Customize the human-readable labels for each reference type displayed in headings.

## Example

Override existing label
```php
add_filter( 'gatherpress_references_type_labels', function( $labels ) {
	$labels['_gatherpress-award'] = __( 'Prizes & Honours', 'textdomain' );
	return $labels;
} );
```

## Example

Locale-specific labels
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

## Parameters

- *`array<string,`* `string>` $labels Array of taxonomy slug => label pairs.

## Returns

`array<string,` string> Filtered type labels.

## Files

- [src/render.php:279](https://github.com/carstingaxion/gatherpress-cache-invalidation-hooks/blob/main/src/render.php#L279)
```php
apply_filters( 'gatherpress_references_type_labels', $type_labels )
```



[← All Hooks](Hooks.md)
