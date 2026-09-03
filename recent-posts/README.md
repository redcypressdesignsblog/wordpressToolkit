# Redcypress Recent Posts 1.0.0

Adds a configurable `[recent_posts]` shortcode that displays published posts in order of their most recent update.

Each result can include the post's featured image, linked title, and excerpt. A final link points to the selected category or tag archive, or to the blog page when no filter is supplied.

## Installation

1. Download the installable ZIP from the repository release.
2. In WordPress, go to **Plugins → Add New Plugin → Upload Plugin**.
3. Upload the ZIP and activate **Redcypress Recent Posts**.
4. Add the shortcode to a post, page, widget, or pattern that processes WordPress shortcodes.

If this shortcode currently lives in a theme, child theme, or code-snippet plugin, remove or disable that original copy after activating this plugin.

## Usage

Display the five most recently modified posts:

```text
[recent_posts]
```

Display three posts from a category:

```text
[recent_posts category="design-code" count="3"]
```

Display posts with a particular tag:

```text
[recent_posts tag="wordpress" count="6"]
```

Add one or more custom CSS classes to the wrapper:

```text
[recent_posts class="sidebar-list compact-list"]
```

Category and tag values should use their WordPress slugs. If both are supplied, a post must match both filters.

## Attributes

| Attribute | Default | Description |
| --- | --- | --- |
| `category` | empty | Limits results to a category slug. |
| `tag` | empty | Limits results to a tag slug. |
| `count` | `5` | Number of posts to show, up to 50. |
| `class` | empty | Adds custom CSS classes to the outer wrapper. |

## Styling hooks

- `.rcd-recent-posts`
- `.rcd-post`
- `.rcd-read-more`

The original Redcypress Designs spacing and thumbnail layout are retained as inline styles. The classes above can be used for additional theme-specific styling.

## Notes

- Results are ordered by the WordPress modified date, newest first.
- Sticky posts do not receive special placement.
- If no matching posts are found, the shortcode returns no markup.
- URLs, titles, excerpts, and custom class names are sanitized or escaped before output.
