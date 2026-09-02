# Redcypress Featured Post Selector 1.2.0

This version contains two Gutenberg blocks:

1. **Featured Post Selector** — renders the selected post through the companion `[featured_post_link]` shortcode.
2. **Featured Post with Excerpt** — shows the featured image, title, and excerpt.

The excerpt block uses the manually written WordPress excerpt when available. Otherwise, it creates a 55-word excerpt from the post content.

## Installation

Upload the ZIP through **Plugins → Add New Plugin → Upload Plugin**. WordPress should replace the earlier version of the plugin.

## Blocks

### Featured Post Selector

Search for a published post in the block editor and render it through `[featured_post_link]`. Install and activate the companion [Featured Post Link (Classic Editor)](../featured-post-link-classic-editor/) plugin to register that shortcode. With the companion plugin's current output, the block displays the featured image, linked title, and excerpt.

### Featured Post with Excerpt

Search for a published post and display its featured image, title, and excerpt. This block works independently of `[featured_post_link]`.

## Styling hooks

- `.featured-post-excerpt-link`
- `.featured-post-excerpt-image`
- `.featured-post-excerpt-content`
- `.featured-post-excerpt-title`
- `.featured-post-excerpt-text`
