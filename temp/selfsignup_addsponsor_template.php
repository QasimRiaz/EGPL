<?php
// Silence is golden.
 get_header();
 require_once plugin_dir_path( __DIR__ ) . 'includes/egpl-custome-functions.php';
 $GetAllcustomefields = new EGPLCustomeFunctions();
 $additional_fields = $GetAllcustomefields->getAllcustomefields();
 function sortByOrder($a, $b) {
            return $a['fieldIndex'] - $b['fieldIndex'];
        }

 usort($additional_fields, 'sortByOrder');


//echo '<pre>';
//print_r($additional_fields);exit;
 
 
 
 
 $base_url  = get_site_url();
?>
  <script>
    currentsiteurl = '<?php echo $base_url;?>';
  </script> 
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
  <link href="<?php echo $base_url;?>/wp-content/plugins/EGPL/js/jquery-confirm.css" rel="stylesheet">
   <link href="<?php echo $base_url;?>/wp-content/plugins/EGPL/cmtemplate/css/lib/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
  <style> 
 .form-group a {
    color: #428bca !important;
 }
 .select2-selection__arrow{
     
     top:10px !important;
 }
 .select2-selection{
     
     height: 50px !important;
 }
 
 </style>
 
 <div class="container" style="margin-top: 25px;">

     <div class="">
         <div class="card card-custom gutter-b">

             <div class="card-header">
                 <div class="card-title">
                     <span class="card-icon">
                         <i class="fab fa-wpforms text-primary fa-lg"></i>
                     </span>
                     <h4>Registration </h4>
                 </div>
             </div>

             <?php // TO SHOW THE PAGE CONTENTS
             while (have_posts()) : the_post();
                 ?> <!--Because the_content() works only inside a WP Loop -->
                 <div class="card-body">
                     <!--begin::Top-->
                     <div class="d-flex">    
                         <div class="entry-content-page">
    <?php the_content(); ?> <!-- Page Content -->
                         </div><!-- .entry-content-page -->
                     </div>
                     <!--end::Top-->

                 </div>
                 <?php endwhile; //resetting the page loop
             ?>	

         </div>
     </div>
     <div class="">
         
         <div class="">
             
             <div class="card card-custom gutter-b example example-compact">
                 <div class="card-header">
                     <h3 class="card-title">Basic Information</h3>
                    
                 </div>
                 <!--begin::Form-->
                 <form method="post" action="javascript:void(0);" onSubmit="selfisignupadd_new_sponsor()">
                     <div class="card-body">
                         
                         <div class="form-group row">
                             <label class="col-2 col-form-label">Email *</label>
                             <div class="col-10">
                                 
                                  <input type="email"  class="form-control mymetakey" id="Semail" name="Semail" placeholder="Email" required="true">
                             </div>
                         </div>
                         
                         <div class="form-group row">
                             <label class="col-2 col-form-label">First Name *</label>
                             <div class="col-10">
                                 
                                   <input type="text"  class="form-control mymetakey" id="first_name" name="first_name" placeholder="First Name" required="true">
                             </div>
                         </div>
                         
                         <div class="form-group row">
                             <label class="col-2 col-form-label">Last Name *</label>
                             <div class="col-10">
                                 
                                  <input type="text"  class="form-control mymetakey" id="last_name" name="last_name" placeholder="Last Name" required="true">
                             </div>
                         </div>
                         
                         <div class="form-group row">
                             <label class="col-2 col-form-label">Company Name *</label>
                             <div class="col-10">
                                 
                                  <input type="text"  class="form-control mymetakey" id="company_name" name="company_name" placeholder="Company Name" required="true">
                             </div>
                         </div>
                         
                         
                          <?php  foreach ($additional_fields as $key=>$value){ 
                           
                                if($additional_fields[$key]['fieldsystemtask'] == "checked" && $additional_fields[$key]['SystemfieldInternal'] != "checked" ){
                                $requiredStatus = $additional_fields[$key]['fieldrequriedstatus'];
                                $requriedStatus = "";
                                $requiredStatueUpdate = "";
                                if($requiredStatus == true){
                                    
                                    
                                    $requiredStatueUpdate = "required='ture'";
                                    $requriedStatus = "*";
                                    
                                }
                                ?>
                                
                                <?php if($additional_fields[$key]['fielduniquekey'] !="Semail" && $additional_fields[$key]['fielduniquekey'] !="first_name" && $additional_fields[$key]['fielduniquekey'] !="last_name" && $additional_fields[$key]['fielduniquekey'] !="company_name" && $additional_fields[$key]['fieldType'] != 'checkbox' && $additional_fields[$key]['fieldType'] != 'display' && $additional_fields[$key]['displayonapplicationform'] == "checked"){ ?>
                               
                                    
                                    
                                    <?php if($additional_fields[$key]['fieldType'] == 'email' || $additional_fields[$key]['fieldType'] == 'text' ||  $additional_fields[$key]['fieldType'] == 'date' ||$additional_fields[$key]['fieldType'] == 'number'){ ?> 
                                        <div class="form-group row">
                                            <div class="col-2">
                                            <label class="col-form-label"><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatus;?>
                                            <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>

                                              <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                            <?php }?>
                                            </label>
                                            <?php if(!empty($additional_fields[$key]['fielddescription'])){?>

                                            <?php echo $additional_fields[$key]['fielddescription'];?>
                                            <?php }?>
                                            </div>
                                        <div class="col-10">
					<input type="<?php echo $additional_fields[$key]['fieldType'];?>"  class="form-control mymetakey" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>>
                                        </div>
                                         </div>
                                        <?php }else if($additional_fields[$key]['fieldType'] == 'url'){?>
                  
                                            <div class="form-group row" >
                                            <div class="col-2">
                                            <label class="col-form-label"><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatussysomb;?>
                                            <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>

                                              <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                            <?php }?>
                                            </label></div>
                                            <?php if(!empty($additional_fields[$key]['fielddescription'])){?>

                                            <?php echo $additional_fields[$key]['fielddescription'];?>
                                            <?php }?>
                                            
                                            <div class="col-10">
                                            <input type="text"  class="form-control speiclurlfield" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>>
                                            </div>
                                            </div>
                  
                                            
                                        <?php }else if($additional_fields[$key]['fieldType'] == 'textarea'){?>
                                             
                                        <div class="col-2">
                                        <label class="col-form-label"><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatus;?>
                                        <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>

                                          <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                        <?php }?>
                                        </label>
                                        <?php if(!empty($additional_fields[$key]['fielddescription'])){?>

                                        <?php echo $additional_fields[$key]['fielddescription'];?>
                                        <?php }?>
                                        </div>
                                        <div class="col-sm-8">
                                             
                                             <textarea  <?php echo $additional_fields[$key]['attribute'];?>  class="form-control mymetakey" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>></textarea>
                                             </div>
                                         </div>
                                       <?php }else if($additional_fields[$key]['fieldType'] == 'dropdown'){?>
                                             <div class="form-group row">
                                        <div class="col-2">
                                        <label class="col-form-label"><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatus;?>
                                        <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>

                                          <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                        <?php }?>
                                        </label>
                                        <?php if(!empty($additional_fields[$key]['fielddescription'])){?>

                                        <?php echo $additional_fields[$key]['fielddescription'];?>
                                        <?php }?>
                                        </div>
                                        <div class="col-10">
                                             
                                             <?php if($additional_fields[$key]['multiselect'] == 'chekced') {?>
                                              <select class="select2 form-control mycustomedropdown mymetakey"  title="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" data-allow-clear="true" data-toggle="tooltip" multiple="multiple" <?php echo $requiredStatueUpdate;?>>
                                                    <?php  foreach ($all_roles as $key => $name) { 
                                                        
                                                        
                                                        if ($key != 'administrator' && $key != 'contentmanager' && $key != 'subscriber') {
                                                        ?>
                                                  
                                                         <option value='<?php echo$key; ?>'><?php echo $name['name'];?></option>
                                                    
                                                    <? }} ?>
                                                   
                                              </select>
                                             <?php }else {?>
                                                    
                                            <select class="select2 form-control mycustomedropdown mymetakey" style="width:100%"  title="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>"  <?php echo $requiredStatueUpdate;?>>

                                                       <?php  foreach ($all_roles as $key => $name) { 
                                                        
                                                        
                                                        if ($key != 'administrator' && $key != 'contentmanager' && $key != 'subscriber') {
                                                        ?>
                                                  
                                                         <option value='<?php echo $key;?>'><?php echo $name['name'];?></option>
                                                    
                                                       <? }} ?>

                                                   </select>
                                             
                                             <?php } ?>
                                             </div> </div>
                                <?php }}?><?php if($additional_fields[$key]['fieldType'] == 'checkbox'){?> 
                                       
                                             <div class="form-group row" >
                                                 
                                                 <div class="col-sm-12" style='color:#333'>
                                                     
                                                     <input  class="mycustomcheckbox form-control"  <?php echo $requiredStatueUpdate;?> type="checkbox" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>"><?php echo '   '.$additional_fields[$key]['fieldName'];?><br/>
                                             
                                                </div>
                                            </div>            
                                               
                                            
                                       <?} if($additional_fields[$key]['fieldType'] == 'display'){?>
                                     
                                             <div class="form-group row" >
                                                 
                                                 <div class="col-sm-12 fontclass">
                                                     
                                                     <?php echo $additional_fields[$key]['fielddescription'];?>
                                                </div>
                                            </div>   
                                       <?}?>
                                    
                                 
                           
                                <?php }} ?>
                    
                        <input  type="hidden" class="form-control mymetakey" name="selfsignupstatus" id="selfsignupstatus" value="Pending" >				
								
			<?php   foreach ($additional_fields as $key=>$value){ 
                           
                                if($additional_fields[$key]['fieldsystemtask'] != "checked" && $additional_fields[$key]['SystemfieldInternal'] != "checked" && $additional_fields[$key]['displayonapplicationform'] == "checked"){
                                
                                
                                
                                if($additional_fields[$key]['fieldType'] != 'checkbox' && $additional_fields[$key]['fieldType'] != 'display'){ 
                                    
                                    $requiredStatus = $additional_fields[$key]['fieldrequriedstatus'];
                                    $requriedStatussysomb = "";
                                    $requiredStatueUpdate = "";
                                    
                                    if($requiredStatus == true){
                                    
                                    
                                    $requiredStatueUpdate = "required='ture'";
                                    $requriedStatussysomb = "*";
                                    
                                    }
                                    
                                    
                                    
                                    ?>
                               
                                    <div class="form-group row" >
                                    <div class="col-2">
                                    <label class="col-form-label"><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatussysomb;?>
                                    <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>
                                    
                                      <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                    <?php }?>
                                    </label>
                                    <?php if(!empty($additional_fields[$key]['fielddescription'])){?>
                                    
                                    <?php echo $additional_fields[$key]['fielddescription'];?>
                                    <?php }?>
                                    </div>
                                    <div class="col-10">
                                        
                                      <?php if($additional_fields[$key]['fieldType'] == 'text' || $additional_fields[$key]['fieldType'] == 'email'  ||$additional_fields[$key]['fieldType'] == 'date' ||$additional_fields[$key]['fieldType'] == 'number'){ ?> 
                                     
                                            <input type="<?php echo $additional_fields[$key]['fieldType'];?>"  class="form-control mymetakey" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>>
                                       
                                        <?php }else if($additional_fields[$key]['fieldType'] == 'url'){?>
                  
                                           
                                            <input type="text"  class="form-control speiclurlfield" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>>
                                            
                  
                                            
                                        <?php }else if($additional_fields[$key]['fieldType'] == 'textarea'){?>
                                        
                                             <textarea  <?php echo $additional_fields[$key]['attribute'];?>  class="form-control mymetakey" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>></textarea>
                                        
                                        
                                       <?php }else if($additional_fields[$key]['fieldType'] == 'file'){?>
                                           
                                           
                                           <input <?php echo $additional_fields[$key]['attribute'];?> type="<?php echo $additional_fields[$key]['fieldType'];?>"  class="form-control" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="customefiels[]" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>>
                                       
                                           
                                       <?php }else if($additional_fields[$key]['fieldType'] == 'dropdown'){?>
                                             
                                             
                                             <?php if($additional_fields[$key]['multiselect'] == "checked") {?>
                                              <select class="select2 form-control mycustomedropdown mymetakey"  title="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" data-allow-clear="true" data-toggle="tooltip" multiple="multiple" <?php echo $requiredStatueUpdate;?>>
                                                    <?php foreach ($additional_fields[$key]['options'] as $key=>$value){ ?>
                                                  
                                                         <option value='<?php echo $value->label;?>'><?php echo $value->label;?></option>
                                                    
                                                    <? } ?>
                                                   
                                              </select>
                                             <?php }else {?>
                                                
                                                    <select class="select2 form-control mycustomedropdown mymetakey" style="width:100%"   title="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>"  <?php echo $requiredStatueUpdate;?>>

                                                       <?php foreach ($additional_fields[$key]['options'] as $key=>$value){ ?>
                                                  
                                                         <option value='<?php echo $value->label;?>'><?php echo $value->label;?></option>
                                                    
                                                       <? } ?>

                                                   </select>
                                             
                                             <?php } ?>
                                            
                                       <?php } ?> </div> </div> <?php }?> 
                                        
                                    
                                       <?php if($additional_fields[$key]['fieldType'] == 'checkbox'){ 
                                           
                                            $requiredStatus = $additional_fields[$key]['fieldrequriedstatus'];
                                    $requriedStatussysomb = "";
                                    $requiredStatueUpdate = "";
                                    
                                    if($requiredStatus == true){
                                    
                                    
                                    $requiredStatueUpdate = "required='ture'";
                                    $requriedStatussysomb = "*";
                                    
                                    }
                                           
                                           
                                           ?>
                                             <div class="form-group row" >
                                                
                                                 <div class="col-sm-8" style="color:#333;">
                                                     
                                                     <input  class="mycustomcheckbox"  <?php echo $requiredStatueUpdate;?> type="checkbox" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>"><?php echo '   '.$additional_fields[$key]['fieldName'];?><br/>
                                             
                                                  </div> 
                                                 </div>
                                               
                                            
                                       <?}?>
                                       <?php if($additional_fields[$key]['fieldType'] == 'display'){ ?>
                                             <div class="form-group row" >
                                                 
                                                 <div class="col-sm-12 fontclass">
                                                     
                                                     <?php echo $additional_fields[$key]['fielddescription'];?>
                                                </div>  
                                                 </div>
                                       <?}?>
                                  
                                 
                                    
                           
                       <?php }} ?>
                         
                         
                         
                         <!--end: Code-->
                     </div>
                     <div class="card-footer">
                         <div class="row">
                             <!-- <div class="col-6"></div> -->
                             <div class="col-12" style="text-align: center;">
                                 <button type="submit" id="selfisignup" name="selfisignup" class="btn btn-success mr-2 eg-buttons">Submit</button>
                                 <a href="<?php echo $base_url;?>" class="btn btn-secondary">Cancel</a>
                             </div>
                         </div>
                     </div>
                 </form>
             </div>
         </div>
         
     </div>
</div>
       
    
<?php   get_footer(); ?>
<script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/select2/select2.full.js?v=2.95"></script>
 <script src="<?php echo $base_url;?>/wp-content/plugins/EGPL/cmtemplate/js/lib/bootstrap-sweetalert/sweetalert.min.js"></script>
                            <script>jQuery('.mycustomedropdown').select2(); </script>