( function () {
	'use strict';

	if ( typeof tinymce === 'undefined' || window.fplcButtonRegistered ) {
		return;
	}

	window.fplcButtonRegistered = true;

	tinymce.PluginManager.add( 'fplc_button', function ( editor ) {
		editor.addButton( 'fplc_button', {
			title: 'Insert Featured Post Link',
			icon: 'icon dashicons-admin-links',
			onclick: function () {
				const postOptions = ( typeof FPLC_POSTS !== 'undefined' )
					? FPLC_POSTS.map( function ( post ) {
						return {
							text: post.title + ' (ID: ' + post.id + ')',
							value: String( post.id )
						};
					} )
					: [ { text: 'No posts found', value: '0' } ];

				editor.windowManager.open( {
					title: 'Insert Featured Post Link',
					body: [ {
						type: 'listbox',
						name: 'postid',
						label: 'Select a Post',
						values: postOptions
					} ],
					onsubmit: function ( event ) {
						if ( event.data.postid && event.data.postid !== '0' ) {
							editor.insertContent( '[featured_post_link id="' + event.data.postid + '"]' );
						} else {
							window.alert( 'Please select a post.' );
						}
					}
				} );
			}
		} );
	} );
} )();
