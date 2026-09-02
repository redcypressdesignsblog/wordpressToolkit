# Red Cypress Featured Post Selector 1.2.0

This version contains two Gutenberg blocks:

1. **Featured Post Selector** — uses the existing `[featured_post_link]` shortcode and shows the featured image and title.
2. **Featured Post with Excerpt** — shows the featured image, title, and excerpt.

The excerpt block uses the manually written WordPress excerpt when available. Otherwise, it creates a 55-word excerpt from the post content.

## Installation

Upload the ZIP through **Plugins → Add New Plugin → Upload Plugin**. WordPress should replace the earlier version of the plugin.

## Blocks

### Featured Post Selector

Search for a published post in the block editor and display its featured image and title on the front end. This block requires the existing `[featured_post_link]` shortcode to be registered on the site.

### Featured Post with Excerpt

Search for a published post and display its featured image, title, and excerpt. This block works independently of `[featured_post_link]`.

## Styling hooks

- `.featured-post-excerpt-link`
- `.featured-post-excerpt-image`
- `.featured-post-excerpt-content`
- `.featured-post-excerpt-title`
- `.featured-post-excerpt-text`
