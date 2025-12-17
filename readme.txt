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

Yes! Use the block settings to select a specific production and set the type filter to "Awards Only".

= Does it work automatically on production pages? =

Yes, when placed on a production archive or single page, the block automatically detects the production context and shows only its references.

== Screenshots ==

1. Block editor view with inspector controls
2. Frontend display showing references grouped by year
3. Filtered view showing only awards
4. Term management interface for venues, festivals, and awards

== Changelog ==

= 0.1.0 =
* Initial release
* Custom taxonomies for venues, festivals, and awards
* Support for production filtering
* Support for year filtering
* Support for reference type filtering
* Automatic production context detection
* Demo data generator