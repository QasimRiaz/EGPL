
 var t;
 var roleassignmenttable;
 var listview;
 var newfieldtask =0;
 var loadinglightbox;
 var taskuseremaillist = [];
 var removeproductList = [];
jQuery(document).ready(function() {
  
   t = jQuery('.bulkproductedits').DataTable( {
         "order": [[ 0, "desc" ]],
        initComplete: function () {
            
           this.api().columns([1]).every( function () {
                var column = this;
                jQuery(".specialsearchfilter")
                    .on( 'change', function () {
                        var val = jQuery.fn.dataTable.util.escapeRegex(
                            jQuery(this).val()
                            
                        );
                        var  searchvalue = val.replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, ' ');
                       
                        column
                            .search( searchvalue )
                            .draw();
                    } );
                 
               column.data().unique().sort().each( function ( d, j ) {
                    var val = jQuery(d).val();
                  
                  // jQuery(".specialsearchfilter").append( '<option value="'+val+'">'+val+'</option>' );
                   
                } 
                        
             );
                
               
               
                
            } );
            
        },
        "paging": false,
        "info": false,
        "dom": '<"top"i><"clear">',
        columnDefs: [
            { "type": "html-input", "targets": [1] }
        ] 
    } );

  
    
   
    
   
   
  
jQuery(window).load(function() {
   console.log('finshedloading'); 
   //jQuery('#loadingalert').hide();
   if ( window.location.href.indexOf("bulk-edit-task") > -1)
    {
   jQuery('.block-msg-default').remove();
   jQuery('.blockOverlay').remove();
    }
});

    jQuery('.addnewbulkproduct').on( 'click', function () {
        
        jQuery("#customers_select_search").select2({ allowClear: true });
        t.columns().every( function () {
        var that = this;
 
        
           
                that.search(' ').draw();
            
        });
        
        
         var uniquecode  = randomString(5, 'a')+'-addnewporduct';
         var producttaskslist = jQuery('.addnewproductdata-taskslist').html();
         var productlevel = jQuery('.addnewproductdata-level').html();
         
        
        var col1 = '<div class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><i class="hi-icon fa fa-clone saveeverything" id="'+uniquecode+'" title="Create a clone" onclick="clonebulk_product(this)" style="color:#262626;cursor: pointer;" data-toggle="tooltip" aria-hidden="true"></i> <i data-toggle="tooltip" style=" cursor: pointer;margin-left: 10px;" onclick="removebulk_product(this)" title="Remove this task" class="hi-icon fusion-li-icon fa fa-times-circle " style="color:#262626;"></i></div>';
        var col2 = '<input type="text"  id="row-'+uniquecode+'-title" class="form-control marginetopbottom" name="Product Lable" placeholder="Title" data-toggle="tooltip" title="Title" value="" required>  ';
        var col3 = '<div class="input-group marginetopbottom" ><span class="input-group-addon">$</span><input  type="number" id="row-'+uniquecode+'-price" value="" class="form-control currency" required></div>';
        var col4 = '<div class="marginetopbottom"><select   class="select2 "  data-placeholder="Stock Status" title="Stock Status" id="row-'+uniquecode+'-stockstatus" data-allow-clear="true" data-toggle="tooltip" required="true"><option value="instock" selected="selected">In Stock</option><option value="outofstock" >Out of Stock</option></select></div>';  
        var col5 = '<input  placeholder="Stock Quantity" data-toggle="tooltip" title="Stock Quantity"  class="form-control marginetopbottom"  type="number" id="row-'+uniquecode+'-quantity" value="" class="form-control currency" required></div>'; 
                                              
        var col6 = '<div class="marginetopbottom"><select  class="select2 marginetopbottom"  data-placeholder="Status" title="Status" id="row-'+uniquecode+'-publishstatus" data-allow-clear="true" data-toggle="tooltip" required="true"><option value="publish" >Published</option><option value="draft" selected="selected" >Draft ( will not be visible in the shop)</option></select></div>';   
        var col7 = '<div class="marginetopbottom"><select   class="select2 "  data-placeholder="Select Levels" title="Select Levels" id="row-'+uniquecode+'-levels" data-allow-clear="true" data-toggle="tooltip" required="true">'+productlevel+'</select></div>';  
       
        var col8 = '<div class="addscrolproducts topmarrginebulkedit"><select class="select2"  data-placeholder="Select Tasks" title="Tasks" id="row-'+uniquecode+'-tasksarray" data-allow-clear="true" data-toggle="tooltip" multiple="true">'+producttaskslist+'</select></div>';   
        var col9 = '<div class="marginetopbottom" style="display:none" id="row-'+uniquecode+'-showimagediv"> <img id="row-'+uniquecode+'-previewimage"  height="100"><input type="hidden"  id="row-'+uniquecode+'-imagepostID"><button class="btn btn-danger btn-info ourcustomebutton imagepreviewbutton" id="row-'+uniquecode+'-removebutton" onclick="removethisimage(this)" name="'+uniquecode+'" >Remove</button></div><div  id="row-'+uniquecode+'-showaddproductimage"> <input  placeholder="Image" data-toggle="tooltip" title="Product Image"  class="form-control marginetopbottom"  onchange="checkfile(this)"  name="'+uniquecode+'" type="file" id="row-'+uniquecode+'-file" class="form-control" > <button class="btn btn-small btn-info ourcustomebutton" id="row-'+uniquecode+'-fileuploadbutton" onclick="uploaduserImage(this)" name="'+uniquecode+'" disabled="true">Upload</button></div>';   
                                                 
                                                     
                                                      
                                                    
                                                    
                                                
                                                
                                           
                                    
        var col10 = '<input   placeholder="Position" data-toggle="tooltip" title="Position"  class="form-control marginetopbottom"  type="number" id="row-'+uniquecode+'-position" value="" class="form-control currency" >';
                                        
        var col11 = '<div class="addscrolproducts topmarrginebulkedit"><div id="row-'+uniquecode+'-shortdescrpition" class="editprodcutshortdiscrpition_'+uniquecode+'"></div><p ><i class="font-icon fa fa-edit" id="prodcutshortdiscrpition_'+uniquecode+'" title="Edit your product short description"style="cursor: pointer;color: #0082ff;"onclick="bulkproduct_short_descripiton(this)"></i><span id="desplaceholder-'+uniquecode+'"style="margin-left: 10px;color:gray;">Short Description</span></p></div></div>';
        var col12 = '<div class="addscrolproducts topmarrginebulkedit"><div id="row-'+uniquecode+'-longdescrpition" class="editprodcutlongdiscrpition_'+uniquecode+'"></div><p ><i class="font-icon fa fa-edit" id="prodcutlongdiscrpition_'+uniquecode+'" title="Edit your product long description"style="cursor: pointer;color: #0082ff;"onclick="bulkproduct_long_descripiton(this)"></i><span id="desplaceholder-'+uniquecode+'"style="margin-left: 10px;color:gray;">Long Description</span></p></div></div>';
        
        
       t.row.add( [
            col1,
            col2,
            col3,
            col4,
            col5,
            col6,
            col7,
            col8,
            col9,
            col10,
            col11,
            col12
          
        ]).draw().nodes().to$().addClass("bulkaddnewprodcut");
        
        t.column(0).order('desc').draw();
        
        jQuery('#row-'+uniquecode+'-tasksarray').select2();
        jQuery('#row-'+uniquecode+'-levels').select2();
        jQuery('#row-'+uniquecode+'-publishstatus').select2();
        jQuery('#row-'+uniquecode+'-stockstatus').select2();
    
      
    } );
    
    
    
   
   
   });
            
 jQuery(document).ready(function() {
    
    
    var url = currentsiteurl+'/';
     var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=getuseremailids';
    var data = new FormData();
    jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
               taskuseremaillist = jQuery.parseJSON(data);
               
               
           }
       });
       var $myneweventSelect = jQuery(".js-example-events");


$myneweventSelect.on("select2:open", function (e) { 
    jQuery("body").css({'cursor':'wait'});
    var selectedemailsids = [];
    var id = jQuery(this).attr('id');
    var lastdata = jQuery(this).select2('data');
    jQuery.each(lastdata, function (optionkey, optionkeyvalue) {

        selectedemailsids.push(optionkeyvalue.text);

    });
    
    //jQuery(this).find('option').not(':selected').remove();
    
    jQuery.each(taskuseremaillist, function (key, value) {

        var newState = new Option(value.text, value.id, false, false);

        if (selectedemailsids.length > 0) {
            if (jQuery.inArray(value.text, selectedemailsids) == -1) {

                jQuery('#' + id).append(newState);
            }

        } else {

            jQuery('#' + id).append(newState);
        }
    });
    jQuery("body").css({'cursor':'default'});
});
   });           
    
            

var $eventSelect = jQuery(".bulktasktypedrop");
//$eventSelect.on("select2:open", function (e) {  console.log('open'); });
//$eventSelect.on("select2:close", function (e) { console.log('close'); });
$eventSelect.on("select2:select", function (e) {
    console.log('1');
     var selectedtype = jQuery(this).val();
     var className = jQuery(this).attr('id');
     
    
     if (selectedtype == 'select-2') {

                jQuery('.d' + className).show();
                jQuery('.' + className).hide();
            } else if (selectedtype == 'link') {
                jQuery('.' + className).show();
                jQuery('.d' + className).hide();
            } else {
                jQuery('.' + className).hide();
                jQuery('.d' + className).hide();
            }

});

function bulkproduct_long_descripiton(e){
    
       
        
         var classname = jQuery(e).attr("id");
         var desplaceholder = jQuery(e).attr("id").split('_');
         var descrpition = jQuery(".edit"+classname).html();
      
      
        var updatedescripiton = jQuery.confirm({
            
        title: 'Description (Shown on Detail Page)',
        content: '<textarea name="taskdescrpition" class="taskdescrpition"  >'+descrpition+'</textarea>',
        confirmButton: 'Update',
        cancelButton: 'Close',
        confirmButtonClass: 'btn mycustomwidth btn-lg btn-primary mysubmitemailbutton',
        cancelButtonClass: 'btn mycustomwidth btn-lg btn-danger',
        columnClass: 'jconfirm-box-container-special',
         closeIcon: true,
        confirm: function () {
            
            
            jQuery(".edit"+classname).empty();
            jQuery(".edit"+classname).append(tinymce.activeEditor.getContent());
            var n = jQuery(".edit"+classname).text().length;
            if(n == 0){
            
                jQuery("#desplaceholder-"+desplaceholder[1]).show();
            
            }else{
                
                jQuery("#desplaceholder-"+desplaceholder[1]).hide();
            }
        }

        });
        
        
  tinymce.init({
  selector: '.taskdescrpition',
  height: 300,
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

}                     


function bulkproduct_short_descripiton(e){
    
       
        
         var classname = jQuery(e).attr("id");
         var desplaceholder = jQuery(e).attr("id").split('_');
         var descrpition = jQuery(".edit"+classname).html();
      
      
        var updatedescripiton = jQuery.confirm({
            
        title: 'Short Description (Shown on Listing Page)',
        content: '<textarea name="taskdescrpition" class="taskdescrpition"  >'+descrpition+'</textarea>',
        confirmButton: 'Update',
        cancelButton: 'Close',
        confirmButtonClass: 'btn mycustomwidth btn-lg btn-primary mysubmitemailbutton',
        cancelButtonClass: 'btn mycustomwidth btn-lg btn-danger',
        columnClass: 'jconfirm-box-container-special',
         closeIcon: true,
        confirm: function () {
            
            
            jQuery(".edit"+classname).empty();
            jQuery(".edit"+classname).append(tinymce.activeEditor.getContent());
            var n = jQuery(".edit"+classname).text().length;
            if(n == 0){
            
                jQuery("#desplaceholder-"+desplaceholder[1]).show();
            
            }else{
                
                jQuery("#desplaceholder-"+desplaceholder[1]).hide();
            }
        }

        });
        
        
  tinymce.init({
  selector: '.taskdescrpition',
  height: 300,
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

}   


function strRemove(theTarget, theString) {
        return jQuery("<div/>").append(
            jQuery(theTarget, theString).remove().end()
        ).html();
       }

function clonebulk_product(e){
        
        
      
        jQuery("#customers_select_search").select2({ allowClear: true });
   
        t.columns().every( function () {
        var that = this;
 
        
           
                that.search(' ').draw();
            
        } );
        
        var uniquecode  = randomString(5, 'a')+'-addnewporduct';
        
        var currentclickid = jQuery(e).attr('id');
        var getselectedtasks = jQuery( '#row-'+currentclickid+'-tasksarray' ).val();
        console.log(getselectedtasks);
        
        var clonetask = jQuery('#'+currentclickid).parent('p').parent('td').parent('tr').addClass('clontrposition');
      
        var countervalue = 1;
        var anSelected = jQuery(e).parents('tr');
        var data=[];
        jQuery(anSelected).find('td').each(function(){
            
            var regex = new RegExp(currentclickid, 'g');
            var res = jQuery(this).html().replace(regex, uniquecode);
            var resnew = res;
                   
            if(countervalue == 4 || countervalue == 6 || countervalue == 7 || countervalue == 8 ){
                resnew = strRemove("span", resnew);
                //console.log(theResult);
            }
            
             countervalue++;
             
            
            
            data.push(resnew);
            
            
        });
        t.row.add(data).draw().nodes().to$().addClass("bulktasktypedrop");
      
      // t.row.add(data).draw().node();
       
        var oldvalue = jQuery('#row-'+uniquecode+'-title').val();
        jQuery('#row-'+uniquecode+'-title').val('Copy of '+oldvalue);
       
        jQuery('#row-'+uniquecode+'-tasksarray').select2();
        jQuery('#row-'+uniquecode+'-tasksarray').val(getselectedtasks).trigger('change.select2');
        jQuery('#row-'+uniquecode+'-levels').select2();
        jQuery('#row-'+uniquecode+'-publishstatus').select2();
        jQuery('#row-'+uniquecode+'-stockstatus').select2();
    
    
    
       
      
   }                                  
 
 
 
 function checkfile(e){
     var currentclickid = jQuery(e).attr('id');
     var productid =  jQuery(e).attr('name');
     var productpic = jQuery('#'+currentclickid)[0].files[0]; 
     if(productpic !==""){
         
         jQuery('#row-'+productid+'-fileuploadbutton').attr("disabled",false);
         
     }
     
     
 }
 
 function removethisimage(e){
     
      var currentclickid = jQuery(e).attr('name');
      jQuery("#row-"+currentclickid+"-showimagediv").hide();
      jQuery("#row-"+currentclickid+"-showaddproductimage").show();
      jQuery("#row-"+currentclickid+"-previewimage").attr("src","");
      jQuery("#row-"+currentclickid+"-imagepostID").val("");
      
 }
 function uploaduserImage(e){
     
     var currentclickid = jQuery(e).attr('name');
     var productpic = jQuery('#row-'+currentclickid+'-file')[0].files[0]; 
     var data = new FormData();
     var url = currentsiteurl+'/';
     var urlnew = url + 'wp-content/plugins/EGPL/orderreport.php?contentManagerRequest=uploadproductimage';
     data.append('productpic', productpic);
     data.append('productid', currentclickid);
     jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                
                     var finalresult = jQuery.parseJSON(data);
                    jQuery("#row-"+currentclickid+"-showaddproductimage").hide();
                    jQuery("#row-"+currentclickid+"-showimagediv").show();
                    jQuery("#row-"+currentclickid+"-previewimage").attr("src",finalresult.url);
                    jQuery("#row-"+currentclickid+"-imagepostID").val(finalresult.id);
                    
                          
                 
                
            },error: function (xhr, ajaxOptions, thrownError) {
                    swal({
					title: "Error",
					text: "There was an error during the requested operation. Please try again.",
					type: "error",
					confirmButtonClass: "btn-danger",
					confirmButtonText: "Ok"
				});
            }
        });
     
 }


 function removebulk_product(e){
      var removeproductID =   jQuery(e).attr('name');
      
      swal({
            title: "Are you sure?",
            text: 'Click confirm to delete this product.',
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {

            
           
            if (isConfirm) {
                 t.row( jQuery(e).parents('tr') ).remove().draw();
                 removeproductList.push(removeproductID);
                 
                swal({
                    title: "Deleted!",
                    text: "Product removed. It will be deleted when you save changes.",
                    type: "success",
                    confirmButtonClass: "btn-success"
                }
                );
            } else {
                swal({
                    title: "Cancelled",
                    text: "Product is safe",
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
            }
        });
         
     
     
     
 }                           
                               
                          
                               
                                
function saveallbulktask(){
   
   
    //jQuery("#customers_select_search").select2("val", "");
   jQuery("#customers_select_search").select2({ allowClear: true });
   // t.search(' ').draw();
    t.columns().every( function () {
        var that = this;
 
        
           
                that.search(' ').draw();
            
        } );
    jQuery("body").css({'cursor':'wait'});
    var taskdataupdate = {};
    var requeststatus = 'stop';
    var errormsg= "";
    var titlemsg = "";
    var specialcharacterstatus = false;
    if(t.rows().data()['length'] == 0 ){
        var requeststatus = 'update';
    }else{
    
    
    
    jQuery( ".saveeverything" ).each(function( index ) {
      
     
        
    var taskid = jQuery( this ).attr('name');
    var taskLabelcheck = jQuery( '#row-'+taskid+'-title' ).val();
     
     
    
    var prodcuttitle = jQuery( '#row-'+taskid+'-title' ).val();
    var prodcutprice = jQuery( '#row-'+taskid+'-price' ).val();
    var prodcutcatID = jQuery( '#row-'+taskid+'-catID' ).val();
    var prodcutlevel = jQuery( '#row-'+taskid+'-levels' ).val();
    var prodcutfileupload = jQuery( '#row-'+taskid+'-imagepostID' ).val();
    var prodcutlongdescripition = jQuery( '#row-'+taskid+'-longdescrpition' ).html();
    
    
    if(jQuery.trim( prodcuttitle ).length !=0 && prodcutprice !=null){
        
  
          
      
        if(/^[ A-Za-z0-9_?()\-]*$/.test(prodcuttitle) == false) {
           specialcharacterstatus = true;
        }else{
           specialcharacterstatus = false;
        }
        
        
        if(specialcharacterstatus == false){
        jQuery('#'+taskid).parent('div').parent('td').parent('tr').removeClass('emptyfielderror');
       
        var singletaskarray={};
      
        
        
        singletaskarray['prodcuttitle'] = prodcuttitle;
        singletaskarray['prodcutprice'] = prodcutprice;
        singletaskarray['prodcutcatID'] = prodcutcatID;
        singletaskarray['prodcutlevel'] = prodcutlevel;
        singletaskarray['prodcutfileupload'] = prodcutfileupload;
        singletaskarray['prodcutlongdescripition'] = prodcutlongdescripition;
        singletaskarray['id'] = taskid;
        taskdataupdate[taskid]=singletaskarray;
        requeststatus = 'update';
        
  }else{
         
         
         jQuery('#'+taskid).parent('div').parent('td').parent('tr').addClass('emptyfielderror');
       
         requeststatus = 'stop';
         errormsg = "Uh-oh, looks like you're using special characters (i.e. '&', ',', etc) that Task titles don't support. Please remove any special characters from the title and try again.";
         titlemsg = 'Unsupported Characters';
         return false;
     }
 
     }else{
         
         
         jQuery('#'+taskid).parent('div').parent('td').parent('tr').addClass('emptyfielderror');
       
         requeststatus = 'stop';
         titlemsg = 'Error';
         errormsg = 'Some required fields are empty.';
         return false;
     }
   
        
    });

}   

    
 if(requeststatus == 'update'){ 
    var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/orderreport.php?contentManagerRequest=bulkproductgenrate';
    var data = new FormData();
    taskdataupdate['removeproducts']=removeproductList;
    data.append('bulkproductsdata',   JSON.stringify(taskdataupdate));
    
    jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                 jQuery('body').css('cursor', 'default');
                  swal({
                    title: "Updated!",
                    text: "All changes saved successfully",
                    type: "success",
                    confirmButtonClass: "btn-success"
                },
        function(isConfirm) {
            jQuery("body").css({'cursor':'wait'});
            location.reload();
            // document.location.href = currentsiteurl+'/dashboard'
        });
                
                
            },error: function (xhr, ajaxOptions, thrownError) {
                     swal({
					title: "Error",
					text: "There was an error during the requested operation. Please try again.",
					type: "error",
					confirmButtonClass: "btn-danger",
					confirmButtonText: "Ok"
				});
      }
        });
    }else{
        jQuery('body').css('cursor', 'default');
        swal({
            title:titlemsg,
	    text:errormsg,
            type:"warning",
	    confirmButtonClass: "btn-danger",
	    confirmButtonText: "Ok"
	}
        );
    }
}        
                               

 
 function chunkify(a, n, balanced) {
    
    if (n < 2)
        return [a];

    var len = a.length,
            out = [],
            i = 0,
            size;

    if (len % n === 0) {
        size = Math.floor(len / n);
        while (i < len) {
            out.push(a.slice(i, i += size));
        }
    }

    else if (balanced) {
        while (i < len) {
            size = Math.ceil((len - i) / n--);
            out.push(a.slice(i, i += size));
        }
    }

    else {

        n--;
        size = Math.floor(len / n);
        if (len % size === 0)
            size--;
        while (i < size * n) {
            out.push(a.slice(i, i += size));
        }
        out.push(a.slice(size * n));

    }

    return out;
}


function stripSlashesspecial(str)
	{
		return str.replace(/\\/g, '');
	}
 function log(text) {
          jQuery('#logs').append(text + '<br>');
        }

//manger task js code

function bulktasksettings(e){
    
  var task_code = jQuery(e).attr('name');
  var task_attribute_value = jQuery('#row-'+task_code+'-attribute').val();
  var selectedtasktype = jQuery( '#bulktasktype_'+task_code ).val();
  var trvalue='';
  if(selectedtasktype == 'color'){
     var attributes_file = task_attribute_value.replace('accept=','');
     
     
     trvalue='<td ><strong>Accept File Types</strong><br>(List of acceptable file extensions)</td><td ><input name="attribure"  placeholder=".png,.eps" id="confrim_attributes"  class="form-control"  value="'+attributes_file+'" ></td>';
  }else if(selectedtasktype == 'textarea'){
       var attributes_file = task_attribute_value.replace('maxlength=','');
       trvalue = '<td ><strong>Max Length</strong><br>(Number of characters allowed)</td><td ><input name="attribure"  placeholder="200" id="confrim_attributes"  class="form-control"  value="'+attributes_file+'" ></td>';
      
  }else{
      
  }
  
  var task_additional_MWComplete = jQuery('#row-'+task_code+'-taskMWC').val();
  var task_additional_MWDueDatePass = jQuery('#row-'+task_code+'-taskMWDDP').val();
  var task_title = jQuery('#row-'+task_code+'-title').val();
  
  var content='';
 
            
  content='<table><tr><h5 style="margin-top: 2px;">'+task_title+'</h5><hr/></tr></table><table><tr><td><strong>Lock task when submitted</strong><br>(User cannot remove their submission)</td><td><input '+task_additional_MWComplete+' type="checkbox" class="toggle-one" id="confrim_taskMWC" data-toggle="toggle"></td></tr><tr><td><strong>Lock task when due date is passed</strong><br>(User cannot submit after due date)</td><td><input '+task_additional_MWDueDatePass+' type="checkbox" class="toggle-one"  id="confrim_taskMWDDP" data-toggle="toggle"></td></tr><tr>'+trvalue+'</tr></table>';
   
 
  jQuery.confirm({
            
        title: 'Advanced',
        content: content,
        confirmButton: 'Update',
        cancelButton: false,
        confirmButtonClass: 'btn mycustomwidth btn-lg btn-primary mysubmitemailbutton',
        closeIcon: true,
        onOpen: function() {
         jQuery('.toggle-one').bootstrapToggle();   
        },
        confirm: function () {
            
            var attributes='';
            if(selectedtasktype == 'color'){
                if(jQuery('#confrim_attributes').val() !=""){
                    attributes+='accept='+jQuery('#confrim_attributes').val();
                }
           }else if(selectedtasktype == 'textarea'){
           
                if(jQuery('#confrim_attributes').val() !=""){
                attributes+='maxlength='+jQuery('#confrim_attributes').val();
                }
            }
            jQuery('#row-'+task_code+'-attribute').val(attributes);
           
           
           if(jQuery('#confrim_taskMWC').is(':checked')){
                jQuery('#row-'+task_code+'-taskMWC').val('checked');
           }else{
              jQuery('#row-'+task_code+'-taskMWC').val(''); 
           }
           if(jQuery('#confrim_taskMWDDP').is(':checked')){
                jQuery('#row-'+task_code+'-taskMWDDP').val('checked');
           }else{
             jQuery('#row-'+task_code+'-taskMWDDP').val('');
           }
           
           
            
            
            
        }

        });
    
}