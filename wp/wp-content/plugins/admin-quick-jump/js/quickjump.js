jQuery(document).ready(function() {
	
	jQuery("#jump_to select").selectbox();

	jQuery('#jump_to select').change(function() {
		window.location = jQuery(this).val();
	});

}); 