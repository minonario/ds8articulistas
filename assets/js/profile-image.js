/**
  * SAB
  * (c) WebFactory Ltd, 2016 - 2021
  */

(function( $ ) {

	'use strict';
	var DS8Box = {};

	var mediaControl = {

		// Initializes a new media manager or returns an existing frame.
		// @see wp.media.featuredImage.frame()
		selector: null,
		size: null,
		container: null,
		frame: function() {
			if ( this._frame ) {
				return this._frame;

			}

			this._frame = wp.media( {
				title: 'Media',
				button: {
					text: 'Update'
				},
				multiple: false
			} );

			this._frame.on( 'open', this.updateFrame ).state( 'library' ).on( 'select', this.select );

			return this._frame;

		},

		select: function() {
			var context = $( '#ds8box-custom-profile-image' ),
				input = context.find( '#ds8box-custom-image' ),
				image = context.find( 'img' ),
				attachment = mediaControl.frame().state().get( 'selection' ).first().toJSON();

			image.attr( 'src', attachment.url );
			input.val( attachment.url );

		},

		init: function() {
			var context = $( '#ds8box-custom-profile-image' );
			context.on( 'click', '#ds8box-add-image', function( e ) {
				e.preventDefault();
				mediaControl.frame().open();
			} );

			context.on( 'click', '#ds8box-remove-image', function( e ) {
				var context = $( '#ds8box-custom-profile-image' ),
					input = context.find( '#ds8box-custom-image' ),
					image = context.find( 'img' );

				e.preventDefault();

				input.val( '' );
				image.attr( 'src', image.data( 'default' ) );
			} );

		}

	};

	$( document ).ready( function() {

		mediaControl.init();

	} );

})( jQuery );
