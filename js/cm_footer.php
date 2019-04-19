    
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<!--        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/highstock.js?v=2.95"></script>-->
     
      
	<script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/tether/tether.min.js?v=2.95"></script>
	<script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/bootstrap/bootstrap.min.js?v=2.95"></script>
	<script src="/wp-content/plugins/EGPL/cmtemplate/js/plugins.js?v=2.95"></script>
        <script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/bootstrap-sweetalert/sweetalert.js?v=2.95"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js?v=2.95"></script>
	<script type="text/javascript" src="/wp-content/plugins/EGPL/cmtemplate/js/lib/lobipanel/lobipanel.min.js?v=2.95"></script>
	<script type="text/javascript" src="/wp-content/plugins/EGPL/cmtemplate/js/lib/match-height/jquery.matchHeight.min.js?v=2.95"></script>
	
        <script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/bootstrap-select/bootstrap-select.min.js?v=2.95"></script>
	<script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/select2/select2.full.js?v=2.95"></script>
        <script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/datatables-net/datatables.min.js?v=2.95"></script>
        <script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/clockpicker/bootstrap-clockpicker.min.js?v=2.95"></script>
	<script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/clockpicker/bootstrap-clockpicker-init.js?v=2.95"></script>
	<script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/daterangepicker/daterangepicker.js?v=2.95"></script>
        <script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/tether/tether.min.js"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/cmtemplate/js/lib/blockUI/jquery.blockUI.js"></script>
        <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<!--       content manager js files -->
        
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/reportsfield.js?v=2.95"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/lodash.js?v=2.95"></script>
        
        
        
<!--        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/exporting.js?v=2.95"></script>-->
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/contentmanager.js?v=2.97"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/jquery.watable.js?v=2.95"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/bootstrap-datepicker.min.js?v=2.95"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/bootstrap-multiselect.js?v=2.95"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/bulk-email.js?v=2.003"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/jquery-confirm.js?v=2.95"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/passwordstrength.js?v=2.95"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/taskmanager.js?v=2.95"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/role.js?v=2.95"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/jquery.fileDownload.js?v=2.95"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/admin-modernizr.custom.js?v=2.95"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/welcomeemail-content.js?v=2.95"></script>
        
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/highcharts-more.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
      
        <script src='/wp-content/plugins/EGPL/js/randomColor.js?v=2.95'></script>
        <script src='/wp-content/plugins/EGPL/js/jquery.ajax-progress.js?v=2.95'></script>
         <script type="text/javascript" src="/wp-content/plugins/EGPL/js/bulk_edit_task.js?v=2.95"></script>
         <script type="text/javascript" src="/wp-content/plugins/EGPL/js/moment.min.js?v=2.95"></script>
         
	 <?php
         $outside_jsfiels = 'EGPL_include_custome_js_css_files';
         $include_js_files = get_option($outside_jsfiels);
         if (!empty($include_js_files['js'])) {
             foreach ($include_js_files['js'] as $key => $url) {
                 ?>
                 <script type="text/javascript" src="<?php echo $include_js_files['js'][$key]['url']; ?> "></script>

             <? }
         }
         ?>
            

	<script type="text/javascript" language="javascript" src="/wp-content/plugins/EGPL/js/dataTables.buttons.min.js?v=2.95"></script>
        <script type="text/javascript" language="javascript" src="/wp-content/plugins/EGPL/js/jszip.min.js?v=2.95"></script>
        <script type="text/javascript" language="javascript" src="/wp-content/plugins/EGPL/js/buttons.html5.min.js?v=2.95"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/dashboardrequest.js?v=2.95"></script>
  
           <script type="text/javascript" src="/wp-content/plugins/EGPL/js/jquery.alerts.js?v=2.95"></script>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>
jQuery(document).ready(function() {
    
    jQuery('[data-toggle="tooltip"]').tooltip(); 
jQuery('.panel').lobiPanel({
    reload: false,
    close: false,
    editTitle: false,
    expand:false,
    Unpin:false,
    state :'collapsed'
   
			});
                    });
   
tinymce.init({
  selector: '#mycustomeditor',
  height: 400,
  branding: false,
  plugins: [
    'table code link hr paste'
  ],table_default_attributes: {
    
    
            class:'table'
  },
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  convert_urls: false,
        content_css: [
    '/wp-content/plugins/EGPL/css/editorstyle.css'
  ]
});
tinymce.init({
  selector: '#bodytext',
  height: 400,
  branding: false,
  plugins: [
    'table code link hr paste'
  ],table_default_attributes: {
    
    
             border:1, class:'table'
  },
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  convert_urls: false,
  content_css: [
    '/wp-content/plugins/EGPL/css/editorstyle.css'
  ]
});
tinymce.init({
  selector: '#welcomebodytext',
  height: 400,
  branding: false,
  plugins: [
    'table code link hr paste'
  ],table_default_attributes: {
    
    
           border:1, class:'table'
  },
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  convert_urls: false,
        content_css: [
    '/wp-content/plugins/EGPL/css/editorstyle.css'
  ]
});
tinymce.init({
  selector: '#taskdescrpition',
  height: 400,
  branding: false,
  plugins: [
    'table code link hr paste'
  ],table_default_attributes: {
    
    
           border:1
  },
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  convert_urls: false,
        content_css: [
    '/wp-content/plugins/EGPL/css/editorstyle.css'
  ]
});

</script>
        
	<script>
          
          console.log(window.location.protocol + "//" + window.location.host + this.location.pathname);
          jQuery('a[href="' + window.location.protocol + "//" + window.location.host + this.location.pathname + '"]').parents('li').addClass('active');
          jQuery('a[href="' + window.location.protocol + "//" + window.location.host + this.location.pathname + '"]').parents('li').parent('ul').parent('li').removeClass('active');
          if(this.location.pathname != '/dashboard/'){
          jQuery('.opened').removeClass('opened');
          jQuery('a[href="' + window.location.protocol + "//" + window.location.host + this.location.pathname + '"]').parents('li').parent('ul').parent('li').addClass('opened');
          }
            jQuery(".mynav li a").on("click", function(){
                    jQuery('.opened').addClass('opened');
                    jQuery('.active').removeClass('active');
                    jQuery('a[href="' + window.location.protocol + "//" + window.location.host + this.location.pathname + '"]').parent('li').addClass("active");
            });
jQuery('#daterange3').daterangepicker({
				singleDatePicker: true,
				showDropdowns: true,
                                locale: {
                                    format: 'DD-MMM-YYYY'
                                }
                                
			});
 jQuery('.datepicker').daterangepicker({
				singleDatePicker: true,
				showDropdowns: true,
                                locale: {
                                    format: 'DD-MMM-YYYY'
                                }
                                
			});                       
 var dt = new Date();
 var hours = dt.getHours()+1;
 var time =hours + ":" + dt.getMinutes();
 var timezone = 'GMT' + getTimezoneName();
 jQuery("#timezonetext").append(timezone);
 jQuery("#picktime").val(time);
 
</script>
<script src="/wp-content/plugins/EGPL/cmtemplate/js/app.js"></script>
</body>
</html>