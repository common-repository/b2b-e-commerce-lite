(function($){
	$(
		function(){

			var $tbdsTable = $( '.codup-ecommerce-payment-method-mode' ).eq( 0 ).closest( 'table.form-table' );
			// add another tier button
			var $addMethodButton = $( '.b2be-add-method-button' );
			$addMethodButton.on( 'click', addMethodRow );

			/**
			 * Add another input row for adding tiers.
			 *
			 * @returns {undefined}
			 */
			function addMethodRow() {
				alert( "You can only add a maximum of 1 payment method. Purchase B2B Ecommerce For WooCommerce Pro To Add More." );
			}

			var table = $( ".form-table" );
			if ( $( "input[id='b2be_rfq_enable_has_terms']" ).prop( 'checked' ) == true ) {
				table.find( "tr:last" ).show( 'slow' );
				table.find( ".b2be_payment_method" ).show( 'slow' );
				table.find( ".codup-ecommerce-payment-method-mode" ).attr( "required", true );
				table.find( '.b2be-payment-template-row' ).hide();
				table.find( '.b2be-payment-template-row .codup-ecommerce-payment-method-mode' ).attr( "required", false );
			} else {
				table.find( "tr:last" ).hide( 'slow' );
				table.find( ".b2be_payment_method" ).hide( 'slow' );
				table.find( ".codup-ecommerce-payment-method-mode" ).attr( "required", false );
				table.find( '.b2be-payment-template-row' ).hide();
				table.find( '.b2be-payment-template-row .codup-ecommerce-payment-method-mode' ).attr( "required", false );
			}

			$( "input[id='b2be_rfq_enable_has_terms']" ).on(
				"change",
				function(){
					var table = $( ".form-table" );
					if ( $( this ).prop( 'checked' ) == true ) {
						table.find( "tr:last" ).show( 'slow' );
						table.find( ".b2be_payment_method" ).show( 'slow' );
						table.find( ".codup-ecommerce-payment-method-mode" ).attr( "required", true );
						table.find( '.b2be-payment-template-row' ).hide();
						table.find( '.b2be-payment-template-row .codup-ecommerce-payment-method-mode' ).attr( "required", false );
					} else {
						table.find( "tr:last" ).hide( 'slow' );
						table.find( ".b2be_payment_method" ).hide( 'slow' );
						table.find( ".codup-ecommerce-payment-method-mode" ).attr( "required", false );
						table.find( '.b2be-payment-template-row' ).hide();
						table.find( '.b2be-payment-template-row .codup-ecommerce-payment-method-mode' ).attr( "required", false );
					}
				}
			)
		}
	);

})( jQuery );