<?php
// Silence is golden.
if (current_user_can('administrator') || current_user_can('contentmanager')) {

   
    ?>


    <?php
    // 123 should be replaced with a specific Page's id from your site, which you can find by mousing over the link to edit that Page on the Manage Pages admin page. The id will be embedded in the query string of the URL, e.g. page.php?action=edit&post=123.

    $content_ID = $_POST['getallPages'];
    $page_data = get_page($content_ID);
    $site_url  = get_site_url();


    $content = $page_data->post_content;
    $editor_id = 'mycustomeditor';
    
    include 'cm_header.php';
    include 'cm_left_menu_bar.php';
    query_posts('category_name=Content Manager Editor&showposts=-1');
    
    ?>



   <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Content Editor</h3>
                           
                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding">
                <p>
                
                Here you can edit various content sections displayed in different pages of the site. Be sure to carefully review your changes before saving since your saved changes will be published immediately.
                </p>

                <h5 class="m-t-lg with-border"></h5>

              <form method="post" action="<?php echo $site_url;?>/content-editor/" >
                    
                   <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Select <strong>*</strong></label>
                                    <div class="col-sm-10">
                                        
                          <select name="getallPages" id="getallPagesContent" onchange="this.form.submit()" class="form-control">
                            
                              <option value=""> </option>
                           <?php
                                  while (have_posts()) : the_post();
                                      // do whatever you want
                                      $postid = get_the_ID();
                                      if (isset($_POST['getallPages'])) {


                                          if ($_POST['getallPages'] == $postid) {
                                              ?>

                                              <option value="<?php the_ID(); ?>" selected="selected"><?php the_title(); ?> </option>

                                          <?php } else { ?>


                                              <option value="<?php the_ID(); ?>" ><?php the_title(); ?> </option>



                                              <?php
                                          }
                                      } else {
                                          ?>

                                          <option value="<?php the_ID(); ?>"><?php the_title(); ?> </option>


                                      <?php } endwhile;
                                                ?>






                                            </select>
                                    </div>
                                    
                    </div>
         </form>

            </div>
        <?php if (!empty($_POST)) { ?>          
              <div class="box-typical box-typical-padding" id="contenteditor" >
               

          

             <form method="post" action="javascript:void(0);" onSubmit="conform_update_content_page()">
                    
                   <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Title <strong>*</strong></label>
                                    <div class="col-sm-10">
                                         <div class="form-control-wrapper form-control-icon-left">    
							<input type="text"  class="form-control" id="pagetitle" placeholder="Title" value="<?php echo $page_data->post_title; ?>" required>
							<i class="font-icon fa fa-arrow-right"></i>	
                                       </div>
                                    </div>
                   </div>
                  <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Content <strong>*</strong></label>
                                    <div class="col-sm-10">
                                        <textarea id="mycustomeditor"><?php echo $content?></textarea>
                                    </div>
                   </div>
                    <div class="form-group row">
                                    <label class="col-sm-2 form-control-label"></label>
                                    <div class="col-sm-6">
                                             <input type="hidden"  name="pagecontentid" id="pagecontentid" value="<?php echo $content_ID; ?>" >
                                             <button type="submit"    class="btn btn-lg mycustomwidth btn-success" value="Update">Update</button>
                                            
                                        
                                    </div>
                                </div>
                 
                 
                 
                 
               </form>
          <?php }?>
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