<?php
// Template Name: Bulk Edit Task 
if (current_user_can('administrator') || current_user_can('contentmanager')) {
   
    require_once('lib/woocommerce-api.php');
    $productCatName = $_GET['productCatName'];
    
    //$query = new WC_Product_Query();
    $args = array(
    'category' => array( $productCatName ),
    'limit' => -1,
     );
    $products = wc_get_products( $args );
    $tasktitle_list = array();
    if($productCatName == 'booth'){
        
        $productfieldPreFix = "Booth";
        
    }else if($productCatName == 'addons'){
        $productfieldPreFix = "Add-On";
    }else if($productCatName == 'package'){
        $productfieldPreFix = "Packages";
    }
    
    
    
    
    foreach ($products as $single_product) {
        
            
        
           
            $tasktitle_list[] = $single_product->name;
            
        
        }
    sort($tasktitle_list);
      
    global $wp_roles;
    if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
     $all_roles = get_option($get_all_roles_array);
     $test = 'custome_task_manager_data';
     $result = get_option($test);
     $tasktitle_listNames = array();
     
     
        foreach ($result['profile_fields'] as $key=>$value){ 
            
            
            
            $tasktitle_listNames[$key] = htmlspecialchars($value['label']);
    
        }
   
     
     
     
     
    ?> 
    

    <?php
    include 'cm_header.php';
    include 'cm_left_menu_bar.php';
    

    
      ?>
<!--   <div class="spoverlay overlay-hugeinc " id="loadingalert">
   <div class="sweet-alert showSweetAlert visible" data-custom-class="" data-has-cancel-button="false" data-has-confirm-button="true" data-allow-outside-click="false" data-has-done-function="false" data-animation="pop" data-timer="null" style="display:block;border: #b7b7b8 solid 1px;height: 329px;">
                
                <div class="sa-icon sa-info" style="display: block;"></div>
                <h2>Wait</h2>
            <p style="display: block;">Please wait ......</p>
           
   </div>			
</div>-->

    
        
<div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Manage Booth Products</h3>

                        </div>
                    </div>
                </div>
            </header>
           
            <div class="box-typical box-typical-padding">
                <p>
                    You can edit all existing booth products here.
                </p>
               
                <h5 class="m-t-lg with-border"></h5>
                <div class="form-group row">
                  
                    <div class="col-sm-6">
                   
                              
                       <select class="specialsearchfilter select2" id="customers_select_search" data-placeholder="Quick Search"  data-allow-clear="true" style="width:95%;border: #d6e2e8 solid 1px; height: 36px; border-radius: 3px;  padding-left: 10px;">
   
                           <option value=""></option>
                     <?php  foreach ($tasktitle_list as $key=>$value){ ?>
                        <option value="<?php echo $value;?>"><?php echo $value;?></option>
                        
                       
                     <?php  }?>
                        
                        
                       </select>
                  </div>
               
                    
                    <div class="col-sm-6">
                        <form method="post" action="javascript:void(0);" onSubmit="saveallbulktask()">
                        


                        <button  style="float: right;" type="submit" name="savealltask" class="btn btn-lg mycustomwidth btn-success" value="Register">Save All Changes</button>
                        
                    </div>
                </div>
                <div class="form-group row">
                    
                    
                            <select  class="addnewproductdata-taskslist" style="display: none;">
                                
                                
                               <?php  foreach ($tasktitle_listNames as $taskkey => $taskname) {
                                                       
                                                      

                                                            echo '<option value="' . $taskkey . '">' . $taskname . '</option>';
                                                     
                                                    }?>
                            </select>
                            <select class="addnewproductdata-level" style="display: none;" >

                            <option value="all">All</option>
                            <?php 
                            
                            foreach ($all_roles as $key=>$name) {
                                if($key !='administrator' && $key !='subscriber'){
                                    echo '<option value="' . $key . '">' . $name['name'] . '</option>';
                                }
                            }
                            ?>
                            </select>
                           
                       
                    
                     
                      
             
                    <table  class="table-bordered compact dataTable no-footer cards bulkproductedits"  width="100%">
                            <thead>
                                <tr class="text_th" >
                                    <th >Action</th>
                                    <th >Title</th>
                                    <th >Price</th>
                                    <th >Assign Level</th>
                                    <th ><?php echo $productfieldPreFix;?> Image</th>
                                    <th ><?php echo $productfieldPreFix;?> Description </th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                 foreach ($products as $single_product) {
                                     
                                    
                                   
                                     

                                    $task_code = $single_product->id;
                                    $selectedTaskListData = get_post_meta( $task_code);
                                    
                                    
                                    
                                    
                                    if(!empty($selectedTaskListData['seletedtaskKeys'][0])){
                                        
                                        $selectedTaskList = unserialize($selectedTaskListData['seletedtaskKeys'][0]);
                                    }else{
                                        
                                        $selectedTaskList['selectedtasks'] =[];
                                    }
                                    
                                    
                                    ?>

                                    <tr>

                                        <td><div class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a">
                                                
                                               
                                                <i  data-toggle="tooltip" title="Remove this prodcut" name="<?php echo $task_code; ?>" onclick="removebulk_product(this)" class="hi-icon fusion-li-icon fa fa-times-circle saveeverything" ></i>
                                                 
                                            </div> </td>
                                        <td>
                                            
                                            <?php if($productfieldPreFix == 'Booth'){ 
                                                
                                                
                                                 // $title = explode(' ',$single_product->name);
                                                  $retitle = $single_product->name;?>
                                                 <input type="text"  id="row-<?php echo $task_code; ?>-title" class="form-control marginetopbottom" name="Product Lable" placeholder="Title" data-toggle="tooltip" title="Title" value="<?php echo trim($retitle); ?>" required> 
                                                
                                           <?php }else{?>
                                            <input type="text"  id="row-<?php echo $task_code; ?>-title" class="form-control marginetopbottom" name="Product Lable" placeholder="Title" data-toggle="tooltip" title="Title" value="<?php echo htmlspecialchars($single_product->name); ?>" required> 
                                            
                                            
                                           <?php }?>
                                            
                                            <input type="hidden"  id="row-<?php echo $task_code; ?>-catID"   value="<?php echo htmlspecialchars($single_product->category_ids[0]); ?>" > 
                                        </td>
                                        <td>
                                           <div class="input-group marginetopbottom" ><span class="input-group-addon"><?php echo get_woocommerce_currency_symbol( $currency ); ?></span><input data-toggle="tooltip" title="Price" type="number" id="row-<?php echo $task_code; ?>-price" value="<?php echo $single_product->price;?>" class="form-control currency" required></div>
                                            
                                       </td>
                                     
                                           
                                      
                                       
                                        <td> 
                                            <div class="marginetopbottom">
                                                <select class="select2 "  data-placeholder="Select Levels" title="Select Levels" id="row-<?php echo $task_code; ?>-levels" data-allow-clear="true" data-toggle="tooltip" >
                                                    <?php
                                                   

                                                    foreach ($all_roles as $key => $name) {
                                                        if($key !='administrator' && $key !='subscriber'){
                                                        if ($key == $single_product->tax_class) {

                                                            echo '<option value="' . $key . '" selected="selected">' . $name['name'] . '</option>';
                                                        } else {

                                                            echo '<option value="' . $key . '">' . $name['name'] . '</option>';
                                                        }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>   
                                           
                                        </td>
                                        
                                        
                                        
                                         <td>
                                           <?php if(!empty($single_product->image_id)){
                                               
                                               $url = wp_get_attachment_thumb_url($single_product->image_id);
                                               echo '<div class="marginetopbottom" id="row-'.$task_code.'-showimagediv">
                                                     <img id="row-'.$task_code.'-previewimage" src="'.$url.'" height="100">
                                                         <input type="hidden" value="'.$single_product->image_id.'" id="row-'.$task_code.'-imagepostID">
                                                    <button class="btn btn-small btn-danger ourcustomebutton imagepreviewbutton" id="row-'.$task_code.'-removebutton" onclick="removethisimage(this)" name="'.$task_code.'" >Remove</button>
                                                    </div><div style="display:none" id="row-'.$task_code.'-showaddproductimage">
                                                 <input  placeholder="Image" data-toggle="tooltip" title="Product Image"  class="form-control marginetopbottom"  onchange="checkfile(this)"  name="'.$task_code.'" type="file" id="row-'.$task_code.'-file" class="form-control" >
                                                 <button class="btn btn-small btn-info ourcustomebutton" id="row-'.$task_code.'-fileuploadbutton" onclick="uploaduserImage(this)" name="'.$task_code.'" disabled="true">Upload</button>
                                                 </div>';
                                           }else{
                                               
                                                echo '<div class="marginetopbottom" style="display:none" id="row-'.$task_code.'-showimagediv">
                                                      <img id="row-'.$task_code.'-previewimage"  height="100">
                                                      <input type="hidden"  id="row-'.$task_code.'-imagepostID">
                                                    <button class="btn btn-danger btn-info ourcustomebutton imagepreviewbutton" id="row-'.$task_code.'-removebutton" onclick="removethisimage(this)" name="'.$task_code.'" >Remove</button>
                                                    </div><div  id="row-'.$task_code.'-showaddproductimage">
                                                 <input  placeholder="Image" data-toggle="tooltip" title="Product Image"  class="form-control marginetopbottom"  onchange="checkfile(this)"  name="'.$task_code.'" type="file" id="row-'.$task_code.'-file" class="form-control" >
                                                 <button class="btn btn-small btn-info ourcustomebutton" id="row-'.$task_code.'-fileuploadbutton" onclick="uploaduserImage(this)" name="'.$task_code.'" disabled="true">Upload</button>
                                                 </div>';
                                           
                                               
                                               
                                               
                                           }?>
                                             
                                           </td>
                                      
                                        
                                         <td>
                                            <div class="addscrolproducts topmarrginebulkedit">
                                                <div id="row-<?php echo $task_code; ?>-longdescrpition" class='editprodcutlongdiscrpition_<?php echo $task_code; ?>'><?php echo $single_product->description; ?></div>

                                                <p ><i class="font-icon fa fa-edit" id='prodcutlongdiscrpition_<?php echo $task_code; ?>'title="Edit your product description" data-toggle="tooltip" style="cursor: pointer;color: #0082ff;"onclick="bulkproduct_long_descripiton(this)"></i>
        <?php if (!empty($single_product->short_description)) { ?>

                                                        <span id="longdesplaceholder-<?php echo $task_code; ?>" style="display:none;margin-left: 10px;color:gray;">Description </span>
        <?php } else { ?>

                                                        <span id="longdesplaceholder-<?php echo $task_code; ?>" style="margin-left: 10px;color:gray;">Description </span>
        <?php }; ?>
                                                </p>
                                            </div> 
                                        </td>
                                    </tr>  


    <?php } ?>        

                            </tbody>

                        </table>
                </div>
                <div class="form-group row">
                    
                    <div class="col-sm-10">
                        


                        <button  type="submit"  name="savealltask"   class="btn btn-lg mycustomwidth btn-success" value="Register">Save All Changes</button>
                    </div>
                </div>
            </form>
            </div>
        </div>
</div>


    <?php include 'cm_footer.php'; ?>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/bulk_edit_product.js?v=1.34"></script>
        <?php
        
    } else {
        $redirect = get_site_url();
        wp_redirect($redirect);
        exit;
    }
    ?>