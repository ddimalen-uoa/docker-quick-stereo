// JavaScript Document
//--------------------------------------------------------------------------------------------------
function showPackage(change_delivery_speed,checkPromocode) {
	var billingCurrency = jQuery('#billingCurrency').val() || '';
	
	jQuery.post("shadow_post.php", 
		{ 
			mouseX: mouseX,			
			mouseY: mouseY
		},
		function(data) {
			//alert(data);
			var empty_value = '...';
			jsonObj = jQuery.evalJSON(data);
			

			jQuery("#mouseX").html(jsonObj['mouseX']);

		}
	);
}