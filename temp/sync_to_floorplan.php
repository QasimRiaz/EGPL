 <?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
//          global $wp_roles;
       
 $oldvalues = get_option( 'ContenteManager_Settings' );
 $mapapikey = $oldvalues['ContentManager']['mapapikey'];
 $mapsecretkey = $oldvalues['ContentManager']['mapsecretkey'];
 
 
 
if(isset($_POST['userid'])){
    
    $get_all_ids = $_POST['userid'];
   // echo '<pre>';
   // print_r($get_all_ids);exit;
    
}else{
    
    
    
    $meta_query = array(
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key' => 'exhibitor_map_dynamics_ID',
            'value' => false,
            'type' => 'BOOLEAN'
        ),
        array(
            'key' => 'exhibitor_map_dynamics_ID',
            'compare' => 'NOT EXISTS'
        )
    )
);
        $args = array(
	
	'role__not_in' => array('administrator'),
	'meta_query'   => $meta_query,
        'fields'       => 'ID',
	
 ); 
    $get_all_ids = get_users($args);
    
    
}

$count_length = count($get_all_ids);

    include 'cm_header.php';
    include 'cm_left_menu_bar.php';
      
      
   
    
                ?>


   <div class="page-content">
        <div class="container-fluid">
            <header class="section-header" id="bulkimport">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Sync To Floorplan</h3>
                           
                        </div>
                    </div>
                </div>
            </header>
            

            <div class="box-typical box-typical-padding" id="uploadimportfile">
                <p>
                
                    Here you can push your users' company details (name and logo) to the interactive floorplan.
                </p>
                
                   <h5 class="m-t-lg with-border"></h5>
                   
                  
                    
                <form method="post" action="javascript:void(0);" onSubmit="calltoinsertorupdateuser_confrim()">
            
                 
                   
                   
               
                                 <div class="form-group row">
                                   <p class="col-sm-4 ">Total users selected for sync:   <strong><?php echo $count_length;  ?> </strong></p>
                                    <?php if(!empty($mapapikey)&& !empty($mapsecretkey)){?>
                                   <p class="col-sm-6">Estimated time required to perform the sync: <strong><?php echo  $count_length * 3 /60 ; ?> minutes.</strong></p> 
                                     <?php } ?>
                                   
                                     <?php 
                                     foreach($get_all_ids as $id){?>
                                    
                                    <input type="hidden" name="useridarray[]" class="useridarray" value="<?php echo $id;?>" />
                                     
                                    <?php } if(!empty($mapapikey)&& !empty($mapsecretkey)){?>
                                    <div class="col-sm-2">
                                         <button type="submit"  style="color:white !important;" id="starttosync" class="btn btn-inline" value="starttosync">Start Sync</button>
                                         
                                    </div>
                                    <?php }?>
                                    
                                </div>
                                    <?php if(empty($mapapikey)&& empty($mapsecretkey)){?>
                                    <div class="form-group row">
                                        <p class="col-sm-12 "><strong>Cannot perform sync. Not connected to floorplan.</strong></p>
                                    </div>
                                    <?php } ?>
                    
            </form>      
                    
                   
                   <div class="result" style="display: none;">  
                   
                    <h5 class="m-t-lg with-border"></h5> 
                    <div class="form-group row">
                    <label class="col-sm-12 form-control-label">Progress</label>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <div id="totaluser" ></div>
                        </div>    
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                        <div id="prog" style="width:98% !important;" ></div>
                        </div>    
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <div id="progressreport" ><p>0% done</p></div>
                        </div>    
                    </div>
                    <h5 class="m-t-lg with-border"></h5> 
                    
                   </div>
                   
                    <div class="box-typical box-typical-padding" id="syncuserstatus" style="display:none;"></div>    
                
                 
            
                  
                
                      
                      
     </div>
    
            
   </div>
 </div>
      	 <?php   
  
    include 'cm_footer.php';
		
   }else{
       
       $redirect = get_site_url();
       wp_redirect( $redirect );exit;
   
   }
   ?>