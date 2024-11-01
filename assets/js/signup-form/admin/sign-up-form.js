(function($){
	$(
		function(){

			var $tbdsTable = $( '.codup-ecommerce-signup-field-mode' ).eq( 0 ).closest( 'table.form-table' );

			// add another tier button
			var $addTierButton = $( '.sfg-add-field-button' );
			$addTierButton.on( 'click', addTierRow );

			/**
			 * Add another input row for adding tiers.
			 *
			 * @returns {undefined}
			 */
			function addTierRow(){
				
				alert( 'Purchase B2B Ecommerce For WooCommerce Pro To Get This Feature.' );

			}

			$( "input[id='codup-role-baseddiscount_type_global']" ).on(
				"change",
				function(){
					var table = $( ".form-table" );
					if ( $( this ).prop( 'checked' ) == true ) {

						table.find( ".cwl-tier-row" ).find( "td:last" ).prev().show();
						table.find( ".role-discount-title" ).show();

					} else {

						table.find( ".cwl-tier-row" ).find( "td:last" ).prev().hide();
						table.find( ".role-discount-title" ).hide();

					}

				}
			)
			$( "#product-type" ).on(
				"change",
				function(){

					if ( $( this ).val() == 'simple' ) {
						$( '.codup-role-based-discount_tab' ).show();
					} else {
						$( '.codup-role-based-discount_tab' ).hide();
					}
				}
			)

			$( ".sfg_request" ).on(
				"click",
				function() {

					var sfg_request = $( this ).attr( "id" );
					if ( $( this ).attr( "disabled" ) != "disabled" ) {

						var result = confirm( "Are you sure you want to perform this action? An email will be sent to users regarding their status.." );
						if ( result ) {
							var user_id = $( "#user_id" ).val();

							$.ajax(
								{
									url: sign_up_settings.ajaxurl,
									method: "POST",
									data: {
										action: "sfg_request_action",
										"sfg_request": sfg_request,
										"user_id": user_id,
									},
									success: function(response) {

										$( "#signup_success_message" ).text( response ).css( "color", "green" );
										location.reload();
									}

									}
							);

						}
					}
				}
			);

			$( ".form-table" ).children( ".email_field" ).attr( 'disabled', true )

		}
	);

})( jQuery );
