<?php
// Silence is golden.
get_header();
?>
               <div id="content"  class="full-width">
                    <div class="fusion-row">
                    <div class="fusion-fullwidth fullwidth-box fusion-fullwidth-3  fusion-parallax-none nonhundred-percent-fullwidth">
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
                </div></div>
               <?php  if ( is_user_logged_in() ) {     ?> 
                    <div id="sponsor-status"></div>
             
                    <form method="post" action="javascript:void(0);" onSubmit="change_password_custome()">
                        <table class="table  hometable formtable"  >
                            
                            <tbody>
                                
                                <tr>
                                   
                                    <td><label for="title"><strong>Create New Password</strong></label></td>
                                    <td></td>
                                </tr>

                               
                                
                                <tr class="passwordTest">
                                  
                                    <td> <label for="file">New Password<strong>*</strong></label></td>
                                    <td>  <input   type="password"  class="passwordTestinput" name="newpassword" id="newpassword"  required><div id="messages"></div></td>
                                    
                                    
                                </tr>
                                 <tr class="passwordTest">
                                  
                                    <td> <label for="file">Confirm Password<strong>*</strong></label></td>
                                    <td>  <input   type="password"   class="passwordTestinput" name="confirmpassword" id="confirmpassword"  required></td>
                                    
                                    
                                </tr>
                                
                                <tr>
                                  
                                    <td> </td>
                                    <td> <div id="pass-info"></div> </td>
                                    
                                    
                                </tr>
                                
                                <tr>
                                   <td> </td>
                                    <td><button type="submit"  name="setpassword" class="btn btn-large btn-info button-cancle" style="margin-left: 0px !important;" value="Register">Submit</button><a class="btn btn-large btn-info button-cancle" href="/home">Cancel</a>
                                  
                                    </td>
                                   
                                </tr>

                            </tbody>
                        </table>

                    </form>
               <?php }?>
                </div>
        
	<?php get_footer();

// Omit closing PHP tag to avoid "Headers already sent" issues.