(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 
		/* Code by Marc-Antoine Minville */

		jQuery(document).ready(function() {
			 
			 /* Admin Panel Controls styling */
			
			/* Parent rows are different from childs rows. */
			jQuery('tr.parent').css('border-bottom', '1px solid #bbb').css('border-top', '1px solid #ccc').css('background', 'linear-gradient(to bottom, #E6E6E6, #C4C4C4) #dfdfdf').css('text-shadow', '1px 1px 1px rgba(255,255,255, 0.1)');
			
			/* Each row will be evaluated to color it or hide it. */
			jQuery('tr.parent input[type="checkbox"]').each(function(){
				if (!this.checked) {
					jQuery('tr.child.'+this.name+'').hide();
					jQuery(this).parent().parent().parent().css('opacity', '0.3').css('background', 'linear-gradient(to bottom, #E6E6E6, #E6E6E6) #E6E6E6');
				} else {
					jQuery('tr.child.'+this.name+'').css('background-color', '#DCEDF2');
					jQuery(this).parent().parent().parent().css('opacity', '1').css('background', 'linear-gradient(to bottom, #E6E6E6, #C4C4C4) #dfdfdf');
				}
				//alert(this.checked);
			});
			
			/* On click of a parent checkbox, whole childs are hidden. */
			jQuery('tr.parent input[type="checkbox"]').click(function(){
				var name = this.name; 
				var checked = this.checked;
				var color = checked ? '#D3E9F0': '#FFD3C9';
				
				if (checked) {
					jQuery(this).parent().parent().parent().css('opacity', '1').css('background', 'linear-gradient(to bottom, #E6E6E6, #C4C4C4) #dfdfdf');
				} else {
					jQuery(this).parent().parent().parent().css('opacity', '0.3').css('background', 'linear-gradient(to bottom, #E6E6E6, #E6E6E6) #E6E6E6');
				}
				
				jQuery('tr.child.'+name+'').css('background-color', color).fadeToggle(500, "swing", function(){
					
					if (checked) {
						jQuery(this).css('background-color', '#DCEDF2');
						//jQuery(this).animate({'background-color': 'transparent'}, 200);
					} else {
						
					}
				});
				
			});

		});
		
		
		// Plugin-specific functions.
	 
		jQuery(document).ready(function($){
			//jQuery('.kjm-dismiss-notice button.notice-dismiss').click(function(){
			$( '.notice.is-dismissible' ).on('click', '.notice-dismiss', function ( event ) {
					event.preventDefault();
				var nfe = $(this).parents('div:first');
				if ($(this).hasClass("kjm-notice-dismiss") == true) nfe.fadeOut();
				var nfn = nfe.attr('data-notice-id');
				$.ajax({
					type: 'POST',
					url: ajaxurl,
					async: false,
					data: { action: 'kjm_dismiss_notice_ajax', notice: nfn, _wpnonce: kjm_admin_notices_ajax.ajax_nonce }
				});
				//console.log(nfn);
				
			});
		});
		
		
		// Check All / None Button.
		jQuery(document).ready(function($){
			
			$('#kjm_admin_notices_show_notice_to_all').change(function () {
				$('#kjm_admin_notices_show_notice_to .others').prop("checked", this.checked);
				if ($('#kjm_admin_notices_show_notice_to_all').prop("checked") === true) {
					$('#kjm_admin_notices_show_notice_to .others').prop("disabled", true);
				} else {
					$('#kjm_admin_notices_show_notice_to .others').prop("disabled", false);
				}
				//console.log($('#kjm_admin_notices_show_notice_to .others').prop("disabled"));
			});
			
		});

})( jQuery );
