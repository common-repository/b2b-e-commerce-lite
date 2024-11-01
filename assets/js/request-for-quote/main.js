jQuery(document).ready(function($){
	if(a_vars.page_slug === 'rfq'){
		$('.woocommerce-message').remove();
	}
	$('.wc-forward.rfq-btn').remove();
	$(document).on('click', '.cwpt_rfq', function() {
		$('.wc-forward.rfq-btn').remove();
		$('.woocommerce-message').remove();
		var add_to_rfq = $(this).attr('value');
		var quantity = $(this).siblings('.product-quantity').val();
		var ajax_button = $(this);
		var is_shop = jQuery.inArray( 'shop', window.location.href.split('/') );

		jQuery.ajax(
			{
				url: a_vars.url,
				type: "post",
				data: {action: "add_to_rfq_ajax", add_to_rfq: add_to_rfq,quantity: quantity},
				success: function (response) {
					json_resp = JSON.parse(response);
					
					if ( '-1' !== is_shop ) {
						ajax_button.after('<a style="margin-left: 10px" href="'+ a_vars.rfq_cart_url +'" class="wc-forward rfq-btn">View RFQ</a>');
					}
					else {
						$('.entry-title').before('<div class="woocommerce-message rfq" role="alert"><a href="'+ a_vars.rfq_cart_url + '" class="button wc-forward rfq-btn">View RFQ</a> “' + json_resp.name + '” has been added to your RFQ.  </div>');
						$('html, body').animate({scrollTop: $(".woocommerce-breadcrumb").offset().top}, 1000);
						jQuery('.woocommerce-message.rfq').delay(4500).fadeOut(300);
						
						setTimeout(function(){
							
							jQuery('.woocommerce-message .rfq').remove();
							
						}, 2000);
					}
				}
			}
		);
		}
	);
			
	
	$( "#rfq_customer_comment_submit_button" ).click(function() {
		
		var wpnonce = $("#admin-comment-meta-action").val();
		
		var currentdate = new Date();
		var datetime = currentdate.getDate() + "/"
				+ (currentdate.getMonth()+1)  + "/" 
				+ currentdate.getFullYear() + " "  
				+ currentdate.getHours() + ":"  
				+ currentdate.getMinutes() + ":" 
				+ currentdate.getSeconds();
		var rfq_customer_comment_quote_id = $( "#rfq_customer_comment_quote_id" ).val();
		var rfq_customer_comment_textarea = $( "#rfq_customer_comment_textarea" ).val();
		var rfq_customer_comment_author   = $( "#rfq_customer_comment_author" ).val();

		if(rfq_customer_comment_textarea != ''){
			$.ajax({
				url: a_vars.url,
				type:"POST",
				data: {
					action: 'save_customer_comment',
					'rfq_customer_comment_quote_id': rfq_customer_comment_quote_id,
					'rfq_customer_comment_textarea': rfq_customer_comment_textarea,
					'rfq_customer_comment_author'  : rfq_customer_comment_author,
					wpnonce: wpnonce,
				},
				success: function(data) {
					$('#rfq_admin_comment_box').append(
						
						"<tr style='height: 65px;width: 100%;'>"+
							"<td style='vertical-align: top;padding: 8px 10px;width:25%;word-wrap: break-word;'>"+
								"<div style='font-size: 13px;line-height: 1.5em;color:#555'>"+
									"<strong>"+ 
										rfq_customer_comment_author + 
									"</strong>"+
								"</div>"+
							"</td>"+
							"<td style='vertical-align: top;padding: 8px 10px;width:75%;word-wrap: break-word;'>"+
								"<div style='font-size: 13px;line-height: 1.5em;'>"+
									rfq_customer_comment_textarea +
								"</div>"+
								"<div style='font-size: 13px;color: rosybrown;width:30%;float:right'>"+
								datetime +
								"</div>"+
							"</td>"+
						"</tr>"
					);
				},
				error: function(e){
					alert('Error Sending Comment!!');
				}
			});
			$( "#rfq_customer_comment_textarea" ).val('');
		}
	});
	
	$('#view_rfq_order').DataTable({
		"lengthMenu": [[10, 20,50,100, -1], [10, 20,50,100, "All"]],
		"ordering": false,
		"searching": false,
	});


	function scrollBottom(transVal){
		var height = 0;
		jQuery('#rfq_customer_comments_box tr').each(function(i, value){
			height += parseInt(jQuery(this).height());
		});
		height += '';
		jQuery('#rfq_customer_comments_box').animate({scrollTop: height},transVal); 
	}
	scrollBottom(0);
	jQuery('#rfq_customer_comment_submit_button').click(function(){
		scrollBottom(3000);
	})
		
});
