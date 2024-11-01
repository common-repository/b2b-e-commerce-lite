jQuery( document ).ready(
	function($){

		$( "#codup_hide_by_product" ).select2(
			{
				closeOnSelect : false,
				placeholder : "Select Products",
				allowHtml: true,
				allowClear: true,
				tags: false
			}
		);

		$( "#codup_hide_by_category" ).select2(
			{
				closeOnSelect : false,
				placeholder : "Select Categories",
				allowHtml: true,
				allowClear: true,
				tags: false
			}
		);

		$( "#codup_hide_by_pages" ).select2(
			{
				closeOnSelect : false,
				placeholder : "Select Pages",
				allowHtml: true,
				allowClear: true,
				tags: false
			}
		);

	}
)
