# WordPress Toolkit

A collection of small WordPress tools, shortcodes, and custom helpers created for [Redcypress Designs](https://redcypressdesigns.com).

These tools started as practical fixes for things I wanted my own site to do more easily. Some are simple shortcodes; others add small workflow improvements inside WordPress.

## Included tools

### [Featured Post Selector](featured-post-selector/)

Two dynamic Gutenberg blocks for selecting a published post:

- **Featured Post Selector** — displays the selected post's featured image and title through the site's existing `[featured_post_link]` shortcode.
- **Featured Post with Excerpt** — displays the selected post's featured image, title, and either its manual excerpt or a generated 55-word excerpt.

Current version: **1.2.0**

## What belongs here

This repository is for small, reusable WordPress tools such as:

- Recent-post display shortcodes
- Featured-post selectors
- Featured-post and excerpt displays
- Other reusable editorial and affiliate-workflow helpers

Client-specific projects and site-specific credentials do not belong in this repository.

## Repository structure

Each tool lives in its own folder and includes:

- The plugin source
- Installation instructions
- Shortcode or block usage
- Notes about styling or theme-specific behavior

## Installation

Installation details are documented inside each tool's folder. In general:

1. Download the tool's folder or a packaged ZIP file.
2. Upload it through **WordPress Admin → Plugins → Add New Plugin → Upload Plugin**.
3. Activate the plugin.
4. Add the documented block or shortcode where you want the output to appear.

## Important notes

These tools were originally built for Redcypress Designs. They may need styling or compatibility adjustments for a different theme, plugin stack, or WordPress configuration.

Before installing custom code:

- Back up your site.
- Test on a staging site when possible.
- Review the source for compatibility with your setup.
- Never place API keys, passwords, or private site credentials in a public repository.

## Licensing

Check each tool's source header for its license. A repository-wide license has not been selected.
