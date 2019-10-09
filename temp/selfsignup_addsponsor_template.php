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
  
<div id="content" class="full-width">
        <div class="page-content" style="max-width: 85%;margin-left: auto;margin-right: auto;">
        
            
            
            <div class="fusion-column-wrapper">
				<p>
					<?php 
					if (!(have_posts())) { ?>
					<?php __("There are no posts", "Avada"); ?><?php } ?>   
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
               		<?php the_content(); ?>
           	 	    <?php endwhile; ?> 
        	        <?php endif; ?>
			   </p>

			<div class="fusion-clearfix">
			</div>
		</div>
            
            <h4 >Basic Information</h4>
            <hr>
            <div class="box-typical box-typical-padding">
                

                

              <form method="post" action="javascript:void(0);" onSubmit="selfisignupadd_new_sponsor()">
                 
		 <?php   foreach ($additional_fields as $key=>$value){ 
                           
                                if($additional_fields[$key]['fieldsystemtask'] == "checked" && $additional_fields[$key]['SystemfieldInternal'] != "checked" ){
                                $requiredStatus = $additional_fields[$key]['fieldrequriedstatus'];
                                $requriedStatus = "";
                                $requiredStatueUpdate = "";
                                if($requiredStatus == true){
                                    
                                    
                                    $requiredStatueUpdate = "required='ture'";
                                    $requriedStatus = "*";
                                    
                                }
                                ?>
                                
                                <?php if($additional_fields[$key]['fieldType'] != 'checkbox' && $additional_fields[$key]['fieldType'] != 'display' && $additional_fields[$key]['displayonapplicationform'] == "checked"){ ?>
                               
                                    
                                    
                                    <?php if($additional_fields[$key]['fieldType'] == 'email' || $additional_fields[$key]['fieldType'] == 'text' ||  $additional_fields[$key]['fieldType'] == 'date' ||$additional_fields[$key]['fieldType'] == 'number'){ ?> 
                                         <div class="form-group row">
                                        <div class="col-sm-4 fontclass">
                                        <label ><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatus;?>
                                        <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>

                                          <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                        <?php }?>
                                        </label>
                                        <?php if(!empty($additional_fields[$key]['fielddescription'])){?>

                                        <?php echo $additional_fields[$key]['fielddescription'];?>
                                        <?php }?>
                                        </div>
                                        <div class="col-sm-8">
					<input type="<?php echo $additional_fields[$key]['fieldType'];?>"  class="form-control mymetakey" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>>
                                        </div>
                                         </div>
                                        <?php }else if($additional_fields[$key]['fieldType'] == 'url'){?>
                  
                                            <div class="form-group row" >
                                            <div class="col-sm-4">
                                            <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatussysomb;?>
                                            <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>

                                              <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                            <?php }?>
                                            </label>
                                            <?php if(!empty($additional_fields[$key]['fielddescription'])){?>

                                            <?php echo $additional_fields[$key]['fielddescription'];?>
                                            <?php }?>
                                            </div>
                                            <div class="col-sm-8">
                                            <input type="text"  class="form-control speiclurlfield" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>>
                                            </div>
                                            </div>
                  
                                            
                                        <?php }else if($additional_fields[$key]['fieldType'] == 'textarea'){?>
                                             <div class="form-group row">
                                        <div class="col-sm-4 fontclass">
                                        <label ><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatus;?>
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
                                        <div class="col-sm-4 fontclass">
                                        <label ><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatus;?>
                                        <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>

                                          <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                        <?php }?>
                                        </label>
                                        <?php if(!empty($additional_fields[$key]['fielddescription'])){?>

                                        <?php echo $additional_fields[$key]['fielddescription'];?>
                                        <?php }?>
                                        </div>
                                        <div class="col-sm-8">
                                             
                                             <?php if($additional_fields[$key]['multiselect'] == 'chekced') {?>
                                              <select class="select2 mycustomedropdown mymetakey"  title="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" data-allow-clear="true" data-toggle="tooltip" multiple="multiple" <?php echo $requiredStatueUpdate;?>>
                                                    <?php  foreach ($all_roles as $key => $name) { 
                                                        
                                                        
                                                        if ($key != 'administrator' && $key != 'contentmanager' && $key != 'subscriber') {
                                                        ?>
                                                  
                                                         <option value='<?php echo$key; ?>'><?php echo $name['name'];?></option>
                                                    
                                                    <? }} ?>
                                                   
                                              </select>
                                             <?php }else {?>
                                                    
                                            <select class="select2 mycustomedropdown mymetakey" style="width:100%"  title="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>"  <?php echo $requiredStatueUpdate;?>>

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
                                                     
                                                     <input  class="mycustomcheckbox"  <?php echo $requiredStatueUpdate;?> type="checkbox" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>"><?php echo '   '.$additional_fields[$key]['fieldName'];?><br/>
                                             
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
                                    <div class="col-sm-4 fontclass">
                                    <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatussysomb;?>
                                    <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>
                                    
                                      <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                    <?php }?>
                                    </label>
                                    <?php if(!empty($additional_fields[$key]['fielddescription'])){?>
                                    
                                    <?php echo $additional_fields[$key]['fielddescription'];?>
                                    <?php }?>
                                    </div>
                                    <div class="col-sm-8">
                                        
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
                                              <select class="select2 mycustomedropdown mymetakey"  title="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" data-allow-clear="true" data-toggle="tooltip" multiple="multiple" <?php echo $requiredStatueUpdate;?>>
                                                    <?php foreach ($additional_fields[$key]['options'] as $key=>$value){ ?>
                                                  
                                                         <option value='<?php echo $value->label;?>'><?php echo $value->label;?></option>
                                                    
                                                    <? } ?>
                                                   
                                              </select>
                                             <?php }else {?>
                                                
                                                    <select class="select2 mycustomedropdown mymetakey" style="width:100%"   title="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>"  <?php echo $requiredStatueUpdate;?>>

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
                             
                               
	                  
                      <div class="form-group row">
                                     <label class="col-sm-4 fontclass"></label>
                                    <div class="col-sm-8">
                                             <button type="submit" id="selfisignup" name="selfisignup"  class="button fusion-button fusion-button-default button-square fusion-button-xlarge button-xlarge button-flat  fusion-mobile-button continue-center" value="Register">Submit</button>
                                            
                                        
                                    </div>
                                </div>
                  
                

                </form>
            </div>
        </div>
    </div>
</div>
    
<?php   get_footer(); ?>
<script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/select2/select2.full.js?v=2.95"></script>
                            <script>jQuery('.mycustomedropdown').select2(); </script>