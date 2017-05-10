<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
    
    $woocommerce_rest_api_keys = get_option( 'ContenteManager_Settings' );
    $wooconsumerkey = $woocommerce_rest_api_keys['ContentManager']['wooconsumerkey'];
    $wooseceretkey = $woocommerce_rest_api_keys['ContentManager']['wooseceretkey'];
    include 'cm_header.php';
    include 'cm_left_menu_bar.php';
if(!empty($wooconsumerkey) && !empty($wooseceretkey)){
    
       require_once( 'lib/woocommerce-api.php' );
       $url = 'https://'.$_SERVER['SERVER_NAME'];
       $options = array(
            'debug'           => true,
            'return_as_array' => false,
            'validate_url'    => false,
            'timeout'         => 30,
            'ssl_verify'      => false,
        );
       global $wp_roles;
       $all_roles = $wp_roles->get_names();
       $client = new WC_API_Client( $url, $wooconsumerkey, $wooseceretkey, $options );
       $product_cat_list = $client->products->get_categories() ;
       // echo '<pre>';
          // print_r($product_cat_list);exit;
       if(isset($_GET['productid'])){
           
           $product_id = $_GET['productid'];
           $update_product = $client->products->get( $product_id );
           
           
         //  echo '<pre>';
         //  print_r($update_product->product);
           
          // if($update_product->product->tax_class = 'gold'){
          //    echo $update_product->product->tax_class ; 
          // }
           
           
           
         //  exit;
           
       }
       
       
                ?>
        <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Product</h3>
                           
                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding">
                <p>
               Please input your product details. You can also choose to publish this product at a later time by using the Product Status field.
                </p>
                <h5 class="m-t-lg with-border"></h5>
                

              <form method="post" action="javascript:void(0);" onSubmit="check_whocat_selet()">
                
              

                  <input type="hidden" id="productid" value="<?php echo $product_id;?>" />
                  <div class="form-group row">
                          <label class="col-sm-2 form-control-label">Title <strong>*</strong></label>
                          <div class="col-sm-10">

                              <input type="text"  class="form-control" id="ptitle" value="<?php echo $update_product->product->title; ?>" placeholder="Product Title" required>


                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-sm-2 form-control-label">Price <strong>*</strong></label>
                          <div class="col-sm-10">

                              <input type="number"  class="form-control" id="pprice" name="pprice" value="<?php echo $update_product->product->regular_price; ?>" placeholder="Product Price" required>


                          </div>
                      </div>
                      
                     <div class="form-group row">
                          <label class="col-sm-2 form-control-label">Stock status <strong>*</strong></label>
                          <div class="col-sm-10">

                              <select onchange="checkstockstatus()" id="pstrockstatus" class="form-control" required>
                                 
                                <?php if(isset($_GET['productid'])){
                                    if($update_product->product->in_stock == 1){?>
                                    
                                    <option value="instock" selected="selected">In Stock</option> 
                                    <option value="outofstock">Out of Stock</option>
                                    <?php }else{ ?>
                                    <option value="instock">In Stock</option>
                                    <option value="outofstock" selected="selected">Out of Stock</option> 
                                   <?php }  ?>
                                <?php }else{?>
                                    <option value="instock" selected="selected">In Stock</option> 
                                    <option value="outofstock">Out of Stock</option>
                                <?php }?>
                              </select>


                          </div>
                    </div>
                  <div class="quanititybox">
                      <?php if (isset($_GET['productid'])) { if($update_product->product->in_stock == 1){ ?>
                  <div class="form-group row stockstatusbox">
                          <label class="col-sm-2 form-control-label">Stock Quantity<strong>*</strong></label>
                          <div class="col-sm-10">

                              <input type="number"  class="form-control" id="pquanitity" value="<?php echo $update_product->product->stock_quantity; ?>" name="pquanitity" placeholder="Stock Quantity" >


                          </div>
                 </div>
                      <?php }}else{?>
                  <div class="form-group row stockstatusbox" >
                          <label class="col-sm-2 form-control-label">Stock Quantity<strong>*</strong></label>
                          <div class="col-sm-10">

                              <input type="number"  class="form-control" id="pquanitity" name="pquanitity" placeholder="Stock Quantity" >


                          </div></div>
                    <?php }?>
                  </div>  
                  <div class="form-group row">
                          <label class="col-sm-2 form-control-label">Product Status <strong>*</strong></label>
                          <div class="col-sm-10">

                              <select id="pstatus" class="form-control" required>
                                     
                                      
                                      <?php if (isset($_GET['productid'])) {
                                          if ($update_product->product->status == 'publish') {
                                              ?>

                                              <option value="publish" selected="selected">Published</option>
                                             
                                              <option value="draft">Draft (Product will not be visible in the shop)</option>

                                            <?php } else if ($update_product->product->status == 'draft') { ?>

                                              <option value="publish" >Published</option>
                                              
                                              <option value="draft" selected="selected">Draft (Product will not be visible in the shop)</option>

                                            <?php } } else { ?>

                                               <option value="publish" >Published</option>
                                              
                                               <option value="draft" selected="selected">Draft (Product will not be visible in the shop)</option>
                                        <?php } ?>
                              </select>


                          </div>
                   </div>
                  <div class="form-group row">
                          <label class="col-sm-2 form-control-label">Product Category<strong>*</strong></label>
                          <div class="col-sm-10">

                              <select id="pcategories" class="form-control" required>
                                    
                                      <?php if (isset($_GET['productid'])) { ?>

                                          <?php
                                          foreach ($product_cat_list->product_categories as $key => $value) {

                                              if ($update_product->product->categories[0] == $value->name) {
                                                  ?>

                                                  <option value="<?php echo $value->id; ?>" selected="selected"><?php echo $value->name; ?></option>
                                              <?php } else { ?>
                                                  <option value="<?php echo $value->id; ?>" ><?php echo $value->name; ?></option>
                                              <?php }
                                          }
                                      } else { ?>
                                            <?php foreach ($product_cat_list->product_categories as $key => $value) { ?>

                                              <option value="<?php echo $value->id; ?>" ><?php echo $value->name; ?></option>

                                      <?php }
                                  } ?>
                                  </select>


                          </div>
                   </div>
                    <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Assign Level <i data-toggle="tooltip" title="If you select a level here, the buyer of this product will be automatically assigned this level on successfully placing the order." class="fa fa-question-circle" aria-hidden="true"></i></label>
                                    <div class="col-sm-10">
                                           
								 <select  class="form-control" id="roleassign" >
								
                                                                     <option></option>
                                                                     <?php if (isset($_GET['productid'])) { 
                                                                         foreach ($all_roles as $key => $name) {


                                                                             if ($key != 'administrator' && $key != 'contentmanager') {
                                                                                
                                                                                 if($update_product->product->tax_class == $key){
                                                                                     
                                                                                     echo '<option value="' . $key . '" selected="selected">' . $name . '</option>';
                                                                                 }else{
                                                                                 echo '<option value="' . $key . '">' . $name . '</option>';
                                                                                 }
                                                                             }
                                                                         } 
                                                                     }else{ 
                                                                        foreach ($all_roles as $key => $name) {


                                                                             if ($key != 'administrator' && $key != 'contentmanager') {
                                                                                 echo '<option value="' . $key . '">' . $name . '</option>';
                                                                             }
                                                                         }
                                                                        
                                                                         
                                                                        }?>
								 </select>
					    
                                        
                                    </div>
                 </div>
                  <div class="form-group row">
                          <label class="col-sm-2 form-control-label">Product Image </label>
                          <div class="col-sm-10" id="changeimageupload" style="display:none;">
                              <input  type="file" class="form-control" id="updateproductimage" >				
                            </div>
                           <?php if (isset($_GET['productid'])) { 
                               $url = wp_get_attachment_thumb_url($update_product->product->images[0]->id);
                               ?>
                                <div class="col-sm-5 productremoveimageblock">
                                    <img src="<?php echo $url; ?>" />
                                    <input type="hidden" id="productoldimage" value="<?php echo $update_product->product->images[0]->src; ?>" />
                                </div>
                                <div class="col-sm-5 productremoveimageblock" style="margin-top: 5%;">
                                    <a   onclick="changeimage()" class="btn btn-lg btn-danger" >Change Image</a>
                                </div>
                           <?php }else{ ?>
                            <div class="col-sm-10">
                              <input  type="file" class="form-control" id="productimage" >				
                            </div>
                          <?php } ?>
                 </div>
                      <div class="form-group row">
                          <label class="col-sm-2 form-control-label">Product Description </br>(Shown on Detail Page)</label>
                          <div class="col-sm-10">

                             
                              <textarea  class="pdescriptionbox"   id="pdescription"  ><?php echo $update_product->product->description; ?></textarea>		

                          </div>
                      </div>
                     <div class="form-group row">
                          <label class="col-sm-2 form-control-label">Product Short Description </br>(Shown on Listing Page)</label>
                          <div class="col-sm-10">

                             <textarea   class="pdescriptionbox"  id="pshortdescription"  ><?php echo $update_product->product->short_description; ?></textarea>	


                          </div>
                      </div>
                    

                      <div class="form-group row">
                          <label class="col-sm-2 form-control-label"></label>
                          <div class="col-sm-6">
                              <button type="submit" id="addnewproduct" name="addsponsor"  class="btn btn-lg mycustomwidth btn-success" value="Register">Save</button>


                          </div>
                      </div>

                

                </form>
            </div>
        </div>
    </div>
    <?php  }else{?>
   <div class="page-content">
        <div class="container-fluid">
            <header class="section-header" id="bulkimport">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Product</h3>
                           
                        </div>
                    </div>
                </div>
            </header>
            

            <div class="box-typical box-typical-padding" >
                <div class="form-group row">
                
                    <p class="col-sm-12 "><strong>Shop is not configured for this site. Please contact ExpoGenie.</strong></p>
               
                </div>
            </div>
        </div>
    </div>

    <?php }include 'cm_footer.php'; ?>
<script type="text/javascript" src="/wp-content/plugins/EGPL/js/manage-products.js?v=2.17"></script>
   
        
        
   <?php }else{
       $redirect = get_site_url();
    wp_redirect( $redirect );exit;
   
   }
   ?>