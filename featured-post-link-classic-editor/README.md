# Featured Post Link (Classic Editor) 1.0.0

Adds the `[featured_post_link]` shortcode and a TinyMCE toolbar button for selecting a published post in the WordPress Classic Editor.

## What it displays

The shortcode outputs:

- The selected post's featured image
- The post title linked to the post
- The post excerpt

## Usage

```text
[featured_post_link id="123"]
```

To request a registered WordPress image size:

```text
[featured_post_link id="123" image_size="medium"]
```

Replace `123` with the published post's ID.

## Classic Editor button

When the WordPress visual editor is enabled, the plugin adds an **Insert Featured Post Link** button to TinyMCE. Select a published post from the list and the plugin inserts the shortcode automatically.

The toolbar button requires the Classic Editor/TinyMCE interface. The shortcode itself can be rendered anywhere WordPress processes shortcodes.

## Relationship to Featured Post Selector

The companion [Featured Post Selector](../featured-post-selector/) Gutenberg plugin uses this plugin's `[featured_post_link]` shortcode for its original selector block.

Activate this plugin when using that block. The companion plugin's **Featured Post with Excerpt** block has its own renderer and does not require this shortcode.

## Installation

1. Place this folder in `wp-content/plugins/`, or package it as a ZIP and upload it through **Plugins → Add New Plugin → Upload Plugin**.
2. Activate **Featured Post Link (Classic Editor)**.
3. Insert the shortcode manually or use the Classic Editor toolbar button.

## Styling hooks

- `.featured-post-link`
- `.featured-post-image`
- `.featured-post-link-excerpt`

The original link layout uses inline styles to preserve the appearance of the existing Redcypress Designs implementation.

## Notes

- Only published posts are displayed.
- The post ID is validated before output.
- URLs and titles are escaped, and excerpt HTML is filtered through WordPress's allowed-post HTML rules.
