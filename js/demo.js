jQuery(document).ready(function() {
								
	jQuery('.mytable').dataTable( {
       "paging":   false,
	   
	   "info":     false,
	   
	    "dom": '<"top"i><"clear">' ,
		
		"columnDefs": [
    	{ "orderable": false, "targets": [ 1 , 2 , 3 , 4 ] }
  		],
    } );
	
} );


///*global SyntaxHighlighter*/
//SyntaxHighlighter.config.tagName = 'code';
//
//jQuery(document).ready( function () {
//	if ( ! jQuery.fn.dataTable ) {
//		return;
//	}
//	var dt110 = jQuery.fn.dataTable.Api ? true : false;
//
//	// Work around for WebKit bug 55740
//	var info = jQuery('div.info');
//
//	if ( info.height() < 115 ) {
//		info.css( 'min-height', '8em' );
//	}
//
//	var escapeHtml = function ( str ) {
//		return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
//	};
//
//	// css
//	var cssContainer = jQuery('div.tabs div.css');
//	if ( jQuery.trim( cssContainer.find('code').text() ) === '' ) {
//		cssContainer.find('code, p:eq(0), div').css('display', 'none');
//	}
//
//	// init html
//	var table = jQuery('<p/>').append( jQuery('table').clone() ).html();
//	jQuery('div.tabs div.table').append(
//		'<code class="multiline language-html">\t\t\t'+
//			escapeHtml( table )+
//		'</code>'
//	);
//	//SyntaxHighlighter.highlight({}, jQuery('#display-init-html')[0]);
//
//	// Allow the demo code to run if DT 1.9 is used
//	if ( dt110 ) {
//		// json
//		var ajaxTab = jQuery('ul.tabs li').eq(3).css('display', 'none');
//
//		jQuery(document).on( 'init.dt', function ( e, settings ) {
//			if ( e.namespace !== 'dt' ) {
//				return;
//			}
//
//			var api = new jQuery.fn.dataTable.Api( settings );
//
//			var show = function ( str ) {
//				ajaxTab.css( 'display', 'block' );
//				jQuery('div.tabs div.ajax code').remove();
//				jQuery('div.tabs div.ajax div.syntaxhighlighter').remove();
//
//				// Old IE :-|
//				try {
//					str = JSON.stringify( str, null, 2 );
//				} catch ( e ) {}
//
//				jQuery('div.tabs div.ajax').append(
//					'<code class="multiline language-js">'+str+'</code>'
//				);
//				SyntaxHighlighter.highlight( {}, jQuery('div.tabs div.ajax code')[0] );
//			};
//
//			// First draw
//			var json = api.ajax.json();
//			if ( json ) {
//				show( json );
//			}
//
//			// Subsequent draws
//			api.on( 'xhr.dt', function ( e, settings, json ) {
//				show( json );
//			} );
//		} );
//
//		// php
//		var phpTab = jQuery('ul.tabs li').eq(4).css('display', 'none');
//
//		jQuery(document).on( 'init.dt.demoSSP', function ( e, settings ) {
//			if ( e.namespace !== 'dt' ) {
//				return;
//			}
//
//			if ( settings.oFeatures.bServerSide ) {
//				if ( jQuery.isFunction( settings.ajax ) ) {
//					return;
//				}
//				jQuery.ajax( {
//					url: '../resources/examples.php',
//					data: {
//						src: settings.sAjaxSource || settings.ajax.url || settings.ajax
//					},
//					dataType: 'text',
//					type: 'post',
//					success: function ( txt ) {
//						phpTab.css( 'display', 'block' );
//						jQuery('div.tabs div.php').append(
//							'<code class="multiline language-php">'+txt+'</code>'
//						);
//						SyntaxHighlighter.highlight( {}, jQuery('div.tabs div.php code')[0] );
//					}
//				} );
//			}
//		} );
//	}
//	else {
//		jQuery('ul.tabs li').eq(3).css('display', 'none');
//		jQuery('ul.tabs li').eq(4).css('display', 'none');
//	}
//
//	// Tabs
//	jQuery('ul.tabs').on( 'click', 'li', function () {
//		jQuery('ul.tabs li.active').removeClass('active');
//		jQuery(this).addClass('active');
//
//		jQuery('div.tabs>div')
//			.css('display', 'none')
//			.eq( jQuery(this).index() ).css('display', 'block');
//	} );
//	jQuery('ul.tabs li.active').click();
//} );
//
//jQuery("input").change(function(event) {
//       var id = jQuery(this).attr('id');
//       var value = this.value;
//      jQuery("#display_"+id).val(value);
//    });
//   jQuery( ".remove_upload" ).click(function() {
//         var id = jQuery(this).attr('id');
//         myString = id.replace('remove_','');
//         jQuery( "input[name='"+myString+"']" ).val("");
//         var myClass = jQuery("#"+id).attr("class");
//         var myArray = myClass.split(' ');
//         jQuery( "input[name$='"+myArray[0]+"']" ).val("");
//         jQuery("#hd_"+myArray[0]).val("");
//         jQuery("."+id).hide();
//         jQuery("."+myArray[0]).show();
//   });
//   
//  jQuery("#login_temp").contents().filter(function () {
//     return this.nodeType === 3; 
//}).remove();
//
//jQuery('textarea').keyup(function() {
//
//  
//  
// 
//    
// 
//   
//  var maxLength = jQuery(this).attr('maxlength');
//  var textareaid= jQuery(this).attr('id');
//  var length = jQuery(this).val().length;
//  var length = maxLength-length;
//  jQuery('#chars_'+textareaid).text(length);
//  if(length == 0){
//     // alert('.');
//   jAlert('You have exceeded the character limit. The extra text has been removed', 'Character limit exceeded'); 
////jQuery( "#dialog" ).dialog();
//  }
//});
//
//jQuery( document ).ready(function() {
//    
//   
//    jQuery('select').each(function(){
//     
//        var id = jQuery(this).attr('id');
//        var slectvalue =  jQuery("#"+id+" option:selected" ).text();
//       
//        if(slectvalue == 'Complete'){
//           //jQuery("."+id).css( "background-color:#FFF" );
//           jQuery("."+id).removeClass('duedate');
//        }
//        
//});
//});
//jQuery( document ).ready(function() {
//    
//    jQuery( ".sf-sub-indicator" ).addClass( "icon-chevron-right" ); 
//    jQuery('textarea').each(function(){
//      
//        var maxLength = jQuery(this).attr('maxlength');
//        var textareaid= jQuery(this).attr('id');
//        var length = jQuery(this).val().length;
//        var remininglength=maxLength-length;
//        jQuery('#chars_'+textareaid).text(remininglength);
//    
//       
//})
//});
//
//
//
