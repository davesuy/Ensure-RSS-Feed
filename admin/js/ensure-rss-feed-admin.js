jQuery( document ).ready(function($) {
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

	  jQuery('#feedUrlCon').repeater({
		      btnAddClass: 'r-btnAdd',
		      btnRemoveClass: 'r-btnRemove',
		      groupClass: 'r-group',
		      minItems: 0,
		      maxItems: 0,
		      startingIndex: 0,
		      showMinItemsOnLoad: true,
		      reindexOnDelete: true,
		      repeatMode: 'append',
		      animation: 'fade',
		      animationSpeed: 400,
		      animationEasing: 'swing',
		      clearValues: true
  	});


 

	  $(".loop-feed-input-remove").click(function(){

	  		$(this).hide();
	  		$(this).siblings(".loop-feed-input").hide();
	  		$(this).siblings(".loop-feed-input").attr("value", "");
	  });

});
