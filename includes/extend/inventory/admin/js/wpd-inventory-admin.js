jQuery(document).ready(function ($) {
	$('input').change(function(){
		var metavalue = this.value;
		var metakey =  $(this).attr('id');
		var wpd_item_id =  $(this).attr('name');
		$.post(WPaAjax.ajaxurl,{
			action : 'wpd_inventory_settings',
            wpd_item_id : wpd_item_id,
            metavalue : metavalue,
            metakey : metakey
        //},
		//function(data, status){
            //alert("Data: " + data + "\nStatus: " + status);
		});
    });
});
