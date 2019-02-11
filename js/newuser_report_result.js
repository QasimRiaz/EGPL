var resultuserdatatable;
var newrowsdata;
var newcolumsheader;
var newcolumnsheaderarrayfortable = [];
var visiblestatus;
var months = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", 
               "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];
jQuery(document).ready(function () {

 if ( window.location.href.indexOf("user-report-result") > -1)
    {
       jQuery("body").css({'cursor': 'wait'}); 
        var data = new FormData();
        var curdate = new Date();
        var usertimezone = curdate.getTimezoneOffset()/60;
         data.append('usertimezone', usertimezone);
         var url = currentsiteurl+'/';
       if ( window.location.href.indexOf("user-report-result/?report") > -1){
           
           
       
     
       var showcollist = JSON.parse(jQuery('#selectedcolumnslebel-hiddenfield').val());
       
       var ordercolname = jQuery('#userbycolname-hiddenfield').val();
       var orderby = jQuery('#userbytype-hiddenfield').val();
       
      
      
       var filterdata   = jQuery('#filterdata-hiddenfield').val();
       var selectedcolumnslebel   = jQuery('#selectedcolumnslebel-hiddenfield').val();
       var selectedcolumnskeys   = jQuery('#selectedcolumnskeys-hiddenfield').val();
       var userbytype   = jQuery('#userbytype-hiddenfield').val();
       var userbycolname   = jQuery('#userbycolname-hiddenfield').val();
       var loadreportname   = jQuery('#loadreportname-hiddenfield').val();
       
      // console.log(filterdata)
     //  console.log(orderby)
       
       data.append('filterdata', filterdata);
       data.append('selectedcolumnslebel', selectedcolumnslebel);
       data.append('selectedcolumnskeys', selectedcolumnskeys);
       data.append('userbytype', userbytype);
       data.append('userbycolname', userbycolname);
       data.append('loadreportname', loadreportname);
       
       }else{
           
        var showcollist = JSON.parse('["Action","Company Name","Last login","First Name","Last Name","Email","Level"]');   
        var ordercolname = 'Company Name';
        var orderby = 'asc';
       }
       
      console.log(showcollist);
       var hideFromExport = [0,1];
       var urlnew = url + 'wp-content/plugins/EGPL/userreport.php?contentManagerRequest=userreportresultdraw';
       jQuery.ajax({
        url: urlnew,
        data:data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function (data) {
            
            data = data.split('//');
            newrowsdata = JSON.parse(data[0]);
            newcolumsheader = JSON.parse(data[1]);
            
            
            //console.log(columsheader);
            var showcolumnrows = [];
           
            newcolumnsheaderarrayfortable.push({class:'noExport',type:'html',data:'<input name="select_all" value="1" type="checkbox">',title:'<input name="select_all" value="1" type="checkbox">'});
            
            jQuery.each(newcolumsheader, function (nkey, value) {
               
              if(jQuery.inArray( newcolumsheader[nkey].title, showcollist )!= -1){
                 
                   visiblestatus = true;
              }else{
                   visiblestatus = false;
              }
                if (newcolumsheader[nkey].type == 'num' || newcolumsheader[nkey].type == 'num-fmt') {

                    newcolumnsheaderarrayfortable.push({visible:visiblestatus,type:'num',sTitle:newcolumsheader[nkey].title,title: newcolumsheader[nkey].key, data: newcolumsheader[key].title, render: jQuery.fn.dataTable.render.number(',', '.', 2, '$')});
                 
                }else if(newcolumsheader[nkey].type == 'date'){
                    
                    newcolumnsheaderarrayfortable.push({visible:visiblestatus,sTitle:newcolumsheader[nkey].title,title: newcolumsheader[nkey].key, data: newcolumsheader[nkey].title, type: newcolumsheader[nkey].type, render: function (data) {if (data !== null && data !== "") {var javascriptDate = new Date(data);javascriptDate = javascriptDate.getDate() + "/" + months[javascriptDate.getMonth()] + "/" + javascriptDate.getFullYear() +" "+javascriptDate.getHours()+":"+javascriptDate.getMinutes()+":"+javascriptDate.getSeconds();return javascriptDate;} else {return "";} }});
                }else {
                    if(newcolumsheader[nkey].title == 'Action' ){
                        newcolumnsheaderarrayfortable.push({class:'noExport noclick',visible:visiblestatus,sTitle:newcolumsheader[nkey].title,title: newcolumsheader[nkey].key, data: newcolumsheader[nkey].title, type: newcolumsheader[nkey].type});
                    }else{
                        newcolumnsheaderarrayfortable.push({visible:visiblestatus,sTitle:newcolumsheader[nkey].title,title: newcolumsheader[nkey].key, data: newcolumsheader[nkey].title, type: newcolumsheader[nkey].type});
                    }
                }
            
            });
            
             resultuserdatatable = jQuery('#example').DataTable({
                                        data: newrowsdata,
                                        columns: newcolumnsheaderarrayfortable,
                                        aLengthMenu : [[100, 150, 200, -1], [100, 150, 200, "All"]],
                                        'columnDefs': [{
                                                         'targets': 0,
                                                         'searchable': false,
                                                         'orderable': false,
                                                         'className': 'dt-body-center',
                                                         'render': function (data, type, full, meta) {
                                                             return '<input type="checkbox" class="checkcheckedstatus" name="id[]" value="' + jQuery('<div/>').text(data).html() + '">';
                                                         }
                                                     }],
                                                
                                                 dom: 'fBrlptrfBrlp',
                                                 
                                                    buttons: [
                                                        {
                                                            extend: 'excelHtml5',
                                                            title: 'userreport_' + jQuery.now(),
                                                            exportOptions: {
                                                                columns: "thead th:not(.noExport)"
                                                            },
                                                        },
                                                        {
                                                            extend: 'csvHtml5',
                                                            title: 'userreport_' + jQuery.now(),
                                                            exportOptions: {
                                                                columns: "thead th:not(.noExport)"
                                                            },
                                                        },

                                                        {
                                                            extend: 'print',
                                                            exportOptions: {
                                                                columns: "thead th:not(.noExport)"
                                                            }
                                                        }
                                                    ]
           
           
           
       });
       
      jQuery('body').css('cursor', 'default');
      resultuserdatatable.column(':contains(' + ordercolname + ')').order(orderby).draw();
      var rows_selected = [];
      jQuery('#example tbody').on('click', 'input[type="checkbox"]', function(e){
          
      var $row = jQuery(this).closest('tr');

      // Get row data
      var data = resultuserdatatable.row($row).data();

      // Get row ID
      var rowId = data[0];

      // Determine whether row ID is in the list of selected row IDs 
      var index = jQuery.inArray(rowId, rows_selected);

      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         rows_selected.push(rowId);

      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         rows_selected.splice(index, 1);
      }

      if(this.checked){
         $row.addClass('selected');
      } else {
         $row.removeClass('selected');
      }

      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(resultuserdatatable);

      // Prevent click event from propagating to parent
      e.stopPropagation();
   });
   
   // Handle click on table cells with checkboxes
//   jQuery('#example').on('click', 'tbody td, thead th:first-child', function(e){
//      jQuery(this).parent().find('input[type="checkbox"]').trigger('click');
//   });

   // Handle click on "Select all" control
   jQuery('thead input[name="select_all"]', resultuserdatatable.table().container()).on('click', function(e){
      if(this.checked){
         jQuery('#example tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         jQuery('#example tbody input[type="checkbox"]:checked').trigger('click');
      }

      // Prevent click event from propagating to parent
      e.stopPropagation();
   });
   jQuery('.filtersarraytooltip').empty();
if ( window.location.href.indexOf("user-report-result/?report=run") > -1)
    {
    var jsondatauser = JSON.parse(jQuery("#querybuilderfilter").val());
     
    var filteroutput = '';
    
    var tablesettings = jQuery('#example').DataTable().settings();
    jQuery.each(jsondatauser.rules, function (key, value) {
            
            for (var i = 0, iLen = tablesettings[0].aoColumns.length; i < iLen; i++)
             {
               if(tablesettings[0].aoColumns[i].title == value.id){
                 
                 //console.log(' <strong>' + value.operator + '</strong> ' + value.value) 
                 filteroutput += tablesettings[0].aoColumns[i].sTitle + ' <strong>' + value.operator + '</strong> ' + value.value + '</br>';
                }
            }
            
            
            

        });
    }else{
        
        var filteroutput="";
    }
        if (filteroutput == "") {
            filteroutput = 'No Filters Applied';
        }
        
        var filterrowscount = resultuserdatatable.data().count() ;
        var tooltiphtml = ' <div class="faq-page-cat" id="filterapplied" title="' + filteroutput + '" style="cursor: pointer;" ><div class="faq-page-cat-icon"><i style="color:#00a8ff !important;" class="reporticon font-icon fa fa fa-filter fa-2x"></i></div><div class="faq-page-cat-title" style="color:#00a8ff"> Filters applied </div><div class="faq-page-cat-txt" id="filteredusercount" >' + filterrowscount + '</div></div>';
        

    jQuery('.filtersarraytooltip').append(tooltiphtml);
    jQuery('#filterapplied').tooltip({html: true, placement: 'bottom'});       
            
        }
    });
      
        
        
    }
    });
    
    function new_userview_profile(elem){
    
     var data = resultuserdatatable.row( jQuery(elem).parents('tr') ).data();
     var tablesettings = jQuery('#example').DataTable().settings();
     // console.log(data)
     var curr_dat ='';
     var tablehtml='';
     var monthnames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
     tablehtml  = '<table class="table table-striped table-bordered table-condensed" width="100%"><tbody>'; 
   // console.log(tablesettings[0].aoColumns);
     jQuery.each( data, function( i, l ){
         
       for (var counter = 0, iLen = tablesettings[0].aoColumns.length; counter < iLen; counter++)
            {
               
     if(tablesettings[0].aoColumns[counter].sTitle == i){      
      if(i != 'Action'){
       if(tablesettings[0].aoColumns[counter].type == 'date'){
          // console.log(l)
            if(l !="" && l != null ){
                 var d = new Date(l);
                var curr_date = d.getDate();
                var curr_month = d.getMonth();
                var curr_year = d.getFullYear();
                var time = d.getHours() + "" + d.getMinutes();
                curr_dat = d.getDate() + "-" + monthnames[curr_month] + "-" + d.getFullYear();
            }else{
                curr_dat ="";
            }
         tablehtml  +=  '<tr><td style="text-align:right;width:50%;"><b>'+i+'</b></td><td style="width:50%;">'+curr_dat+'</td></tr>';
           
       }else{
        if(l == null){
            l="";
        }
         tablehtml  +=  '<tr><td style="text-align:right;width:50%;"><b>'+i+'</b></td><td style="width:50%;">'+l+'</td></tr>';
       }
         }
     }
     }    
       
         
     });
     
     tablehtml  +='</tbody></table>';
     //console.log(tablehtml);
     
     jQuery.confirm({
            title: '<p style="text-align:center;">View</p>',
            content: tablehtml,
            confirmButtonClass: 'mycustomwidth',
            cancelButtonClass: 'customeclasshide',
            animation: 'rotateY',
            closeIcon: true,
            columnClass: 'jconfirm-box-container-special'
            
         });
                                    

    
}

function updateDataTableSelectAllCtrl(resultuserdatatable){
    
    var datatable = jQuery('#example').DataTable();
    
  
   var $table             = resultuserdatatable.table().node();
   var $chkbox_all        = jQuery('tbody input[type="checkbox"]', $table);
   var $chkbox_checked    = jQuery('tbody input[type="checkbox"]:checked', $table);
   var chkbox_select_all  = jQuery('thead input[name="select_all"]', $table).get(0);
   var selectedcount =  +(jQuery("#ntableselectedstatscount").html());
   
   jQuery(".selectedusericon").removeClass('filteractivecolor');
   jQuery(".selecteduserbox").removeClass('filteractivecolor');
   jQuery(".bulkbtuton").removeClass('filteractivecolor');
   jQuery("#ntableselectedstatscount").empty();
   jQuery("#newbulkemailcounter").empty();
   jQuery("#selectedstatscountforbulk").empty();
  
 
   // If none of the checkboxes are checked
   if($chkbox_checked.length === 0){
      chkbox_select_all.checked = false;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = false;
      }

   // If all of the checkboxes are checked
   } else if ($chkbox_checked.length === $chkbox_all.length){
      chkbox_select_all.checked = true;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = false;
      }

   // If some of the checkboxes are checked
   } else {
      chkbox_select_all.checked = true;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = true;
      }
   }
   if($chkbox_checked.length > 0){
       jQuery(".selectedusericon").addClass('filteractivecolor');
       jQuery(".selecteduserbox").addClass('filteractivecolor');
       jQuery(".bulkbtuton").addClass('filteractivecolor');
       jQuery('#newsendbulkemailstatus').prop('disabled', false);
   }else{
       jQuery('#newsendbulkemailstatus').prop('disabled', true);
   }
  
   jQuery("#ntableselectedstatscount").append(datatable.rows( '.selected' ).count());
   jQuery("#newbulkemailcounter").append(datatable.rows( '.selected' ).count());
   jQuery("#selectedstatscountforbulk").append(datatable.rows( '.selected' ).count());
   
    if(datatable.rows( '.selected' ).count() > 0){
            
            jQuery('#reportbulkdownload').removeAttr('disabled');
        }else{
            
           jQuery('#reportbulkdownload').attr('disabled','disabled');
        }
}

jQuery('.backtofilter').on('click', function () {

    jQuery("#runreportresult").submit();

})

function customloaduserreport(){
    
    
    var loadreportname = jQuery( "#customloaduserreportss option:selected" ).val();
    var url = currentsiteurl+'/';
    
    if(loadreportname == 'defult'){
         window.location.href = url + "user-report-result/";
   
    }else{
         window.location.href = url + "user-report-result/?report="+ encodeURI(loadreportname);
   
    }
    
    
    
}



function reportbulkdownload(){
    
    
    
     var hiddentemplatelist = jQuery("#hiddenfileuploadtasklist").html();  
    jQuery.confirm({
        title: '<p style="text-align:center;" >Bulk Download Files</p>',
        content: '<p><strong>Select a Task :</strong><select style="margin-left: 14px;border: #cccccc 1px solid;border-radius: 7px;height: 36px;width: 76%;"id="downloadtaskkey">'+hiddentemplatelist+'</select> </p><p>Do you want to download files uploaded by selected users in the selected task?</p>',
        confirmButton: 'Yes, download it!',
        cancelButton: 'No, cancel please!',
       
        confirmButtonClass: 'btn mycustomwidth btn-lg btn-primary',
        cancelButtonClass: 'btn  btn-lg btn-danger',
       
        
        confirm: function () {
           jQuery("body").css({'cursor':'wait'});
           var selectedtaskkey = jQuery("#downloadtaskkey option:selected").val();
           get_all_selected_users_files(selectedtaskkey);
           
           
           
        },
        cancel: function () {
            //  location.reload();
        }

    });
    
    
    
    
    
}

function get_all_selected_users_files(selectedtaskkey) {

    var datatable = jQuery('#example').DataTable();
   // console.log(selectedtaskkey);
    var listofids = [];
    
    var selectedrowsdatalist = datatable.rows( '.selected' ).data();
    
    jQuery.each(selectedrowsdatalist, function (key, value) {
       
         jQuery.each(value, function (secondkey, secondvalue) {
             if(secondkey == 'User ID'){
                
                 listofids.push(secondvalue);
             }
             
         });
         
    });
    
    
    var data = new FormData();
    var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/userreport.php?contentManagerRequest=get_all_selected_users_files';
    data.append('selectedtaskkey', selectedtaskkey);
    data.append('selecteduserids',  JSON.stringify(listofids));
     jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                jQuery("body").css({'cursor':'default'});
               jQuery('#hiddenform').empty();
               if(jQuery.parseJSON(data) !=null){
               var hiddenformhtml ="";
                 hiddenformhtml += '<form id="myform" action="'+url+'wp-content/plugins/EGPL/bulkdownload.php" method="post"><input type="hidden" name="zipfoldername" value="'+selectedtaskkey+'">';
                
                 jQuery.each(jQuery.parseJSON(data), function(key, value) {
                   
                     hiddenformhtml += '<input type="hidden" name="result[]" value="'+ value+ '">';
                 });
                hiddenformhtml += '</form>' ;
                
                
                jQuery( "#hiddenform" ).append(hiddenformhtml);
                
                 document.getElementById('myform').submit();
             }else{
                 swal({
                        title: "Error",
			text: "There are no files uploaded by selected users.",
			type: "error",
			confirmButtonClass: "btn-danger"
		});
             }
            }
        });
 
}