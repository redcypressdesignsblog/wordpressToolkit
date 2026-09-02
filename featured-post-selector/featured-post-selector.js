( function ( blocks, element, components, apiFetch, blockEditor, i18n ) {
	'use strict';

	const { registerBlockType } = blocks;
	const { createElement: el, useEffect, useMemo, useState } = element;
	const { ComboboxControl, Notice, Placeholder, Spinner } = components;
	const { useBlockProps } = blockEditor;
	const { __ } = i18n;

	function PostSelectorEdit( props, settings ) {
		const { attributes, setAttributes } = props;
		const { postId, postTitle } = attributes;
		const [ searchTerm, setSearchTerm ] = useState( '' );
		const [ posts, setPosts ] = useState( [] );
		const [ isLoading, setIsLoading ] = useState( false );
		const [ error, setError ] = useState( '' );

		useEffect( function () {
			let isCurrent = true;
			const controller = typeof AbortController !== 'undefined' ? new AbortController() : null;
			const timer = window.setTimeout( function () {
				setIsLoading( true );
				setError( '' );

				const query = new URLSearchParams( {
					status: 'publish',
					per_page: '50',
					orderby: searchTerm ? 'relevance' : 'date',
					order: 'desc',
					_fields: 'id,title'
				} );

				if ( searchTerm ) {
					query.set( 'search', searchTerm );
				}

				const request = { path: '/wp/v2/posts?' + query.toString() };
				if ( controller ) request.signal = controller.signal;

				apiFetch( request )
					.then( function ( response ) {
						if ( ! isCurrent ) return;
						setPosts( Array.isArray( response ) ? response : [] );
						setIsLoading( false );
					} )
					.catch( function ( requestError ) {
						if ( ! isCurrent || ( requestError && requestError.name === 'AbortError' ) ) return;
						setError( requestError && requestError.message ? requestError.message : __( 'The posts could not be loaded.', 'red-cypress' ) );
						setIsLoading( false );
					} );
			}, 250 );

			return function () {
				isCurrent = false;
				window.clearTimeout( timer );
				if ( controller ) controller.abort();
			};
		}, [ searchTerm ] );

		const options = useMemo( function () {
			const mappedPosts = posts.map( function ( post ) {
				return {
					value: String( post.id ),
					label: post.title && post.title.rendered
						? post.title.rendered.replace( /<[^>]+>/g, '' )
						: __( '(Untitled)', 'red-cypress' )
				};
			} );

			if ( postId && postTitle && ! mappedPosts.some( function ( option ) {
				return option.value === String( postId );
			} ) ) {
				mappedPosts.unshift( { value: String( postId ), label: postTitle } );
			}

			return mappedPosts;
		}, [ posts, postId, postTitle ] );

		return el(
			'div',
			useBlockProps( { className: 'rcd-featured-post-selector' } ),
			el(
				Placeholder,
				{
					icon: settings.icon,
					label: settings.label,
					instructions: settings.instructions
				},
				error ? el( Notice, { status: 'error', isDismissible: false }, error ) : null,
				el( ComboboxControl, {
					label: __( 'Select a published post', 'red-cypress' ),
					value: postId ? String( postId ) : '',
					options: options,
					onFilterValueChange: function ( value ) {
						setSearchTerm( value || '' );
					},
					onChange: function ( value ) {
						const selected = options.find( function ( option ) {
							return option.value === value;
						} );

						setAttributes( {
							postId: value ? parseInt( value, 10 ) : 0,
							postTitle: selected ? selected.label : ''
						} );
					},
					help: postId
						? __( 'Selected post: ', 'red-cypress' ) + ( postTitle || __( '(Untitled)', 'red-cypress' ) ) + ' (' + postId + ')'
						: __( 'Start typing to search by title.', 'red-cypress' )
				} ),
				isLoading ? el( Spinner ) : null
			)
		);
	}

	const shared = {
		apiVersion: 2,
		category: 'widgets',
		attributes: {
			postId: { type: 'integer', default: 0 },
			postTitle: { type: 'string', default: '' }
		},
		supports: { html: false },
		save: function () { return null; }
	};

	registerBlockType( 'red-cypress/featured-post-selector', Object.assign( {}, shared, {
		title: __( 'Featured Post Selector', 'red-cypress' ),
		description: __( 'Choose a post and show its image and title.', 'red-cypress' ),
		icon: 'admin-links',
		keywords: [ __( 'featured post', 'red-cypress' ), __( 'related post', 'red-cypress' ) ],
		edit: function ( props ) {
			return PostSelectorEdit( props, {
				icon: 'admin-links',
				label: __( 'Featured Post', 'red-cypress' ),
				instructions: __( 'Search for and select the post you want to feature.', 'red-cypress' )
			} );
		}
	} ) );

	registerBlockType( 'red-cypress/featured-post-excerpt-selector', Object.assign( {}, shared, {
		title: __( 'Featured Post with Excerpt', 'red-cypress' ),
		description: __( 'Choose a post and show its image, title, and excerpt.', 'red-cypress' ),
		icon: 'excerpt-view',
		keywords: [ __( 'excerpt', 'red-cypress' ), __( 'featured post', 'red-cypress' ), __( 'related post', 'red-cypress' ) ],
		edit: function ( props ) {
			return PostSelectorEdit( props, {
				icon: 'excerpt-view',
				label: __( 'Featured Post with Excerpt', 'red-cypress' ),
				instructions: __( 'Search for and select a post. Its featured image, title, and excerpt will appear on the published page.', 'red-cypress' )
			} );
		}
	} ) );
} )(
	window.wp.blocks,
	window.wp.element,
	window.wp.components,
	window.wp.apiFetch,
	window.wp.blockEditor,
	window.wp.i18n
);
