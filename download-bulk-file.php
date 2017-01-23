<?php 

if (!empty($_GET['zipname'])) {
    
    ?> 

<h2 style="text-align: center;">Please wait. Your download will begin shortly.</h2>
<form id="myform" action="bulkdownload.php" method="post">
  <?php
      require_once('../../../wp-load.php');
    global $wpdb;
    $counter = 100 ;
    $zip_folder_name=$_GET['zipname'];
    $users = $wpdb->get_results( "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = '".$zip_folder_name."'" );
    
    
    foreach ( $users as $user ) {
        $file_url = get_user_meta($user->user_id, $zip_folder_name);
        
        if(!empty($file_url[0]['file'])){
            
            echo '<input type="hidden" name="result[]" value="'. $file_url. '">';
            $counter=$counter+50 ;
        }
        

        
    }
  
    ?>

 
  
  <input type="hidden" name="zipfoldername" value="<?php echo $zip_folder_name;?>">
  <input type="hidden" id="counter" value="<?php echo $counter;?>">
</form>
    
    <script type="text/javascript">
        document.getElementById('myform').submit();
        var counter = parseInt(document.getElementById('counter').value);
        setTimeout(function(){
        var getUrl = window.location;
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
        var url = baseUrl + "bulk-download-files/";
        console.log(counter);
        window.location.replace(url);
    }, 2000+counter);
       
        
    </script>
    
<?php
     }
?>
 
    