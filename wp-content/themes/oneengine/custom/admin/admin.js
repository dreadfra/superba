( function( $ ) {

	/**
	 * Upload/caricamento di un'immagine da Media Library.
	 */
	$( document ).on( "click", ".thb-image-upload button", function() {
		var self = $( this ),
			field = self.parents( ".thb-image-upload" ).first(),
			img = $( "img", field ),
			input = $( "input", field );

		if ( input.length ) {
			var media = new THB_MediaSelector( {
			    select: function( selected_images ) {
			    	img.attr( "src", selected_images.sizes.thumbnail.url );
			    	input.val( selected_images.id );
			    }
			} );

			media.open();
		}

		return false;
	} );

	/**
	 * Upload/caricamento di un documento da Media Library.
	 */
	$( document ).on( "click", ".thb-document-upload button", function() {
		var self = $( this ),
			field = self.parents( ".thb-document-upload" ).first(),
			input = $( "input", field );

		if ( input.length ) {
			var media = new THB_MediaSelector( {
				type: "",
			    select: function( selected_images ) {
			    	input.val( selected_images.url );
			    }
			} );

			media.open();
		}

		return false;
	} );

} )( jQuery );