<?php
// Silence is golden.
 if (current_user_can('administrator') ) {
       
      get_header();
				
                ?>
                
                <div class="main_content_area">
                    <div class="container">
                        <div class="row">
                            <div class="span12">
                               <p>Edit Sponsor </p> 
                               
                            </div>
                        </div>
                    </div>
                </div>
				<?php get_footer(); 
       
   }else{
       echo 'Not Found';
   }
