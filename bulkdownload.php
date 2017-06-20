<?php 

if(isset($_POST['zipfoldername'])) {
    
    
   
$filesnames_download = $_POST['result'];



$zip_folder_name=$_POST['zipfoldername'];
# create new zip opbject
$zip = new ZipArchive();
# create a temp file & open it
$tmp_file = tempnam('.','');
$zip->open($tmp_file, ZipArchive::CREATE);
# loop through each file

    # download file
    foreach ($filesnames_download as $file_name) {
    $data_image_company = explode("*",$file_name);
    
     //$zip->addFile($data_image_company[1]);
      $fileName = $data_image_company[0].'_'.basename($file_name);
      $zip->addFile($data_image_company[1], $fileName);
   // $download_file = file_get_contents($data_image_company[1]);
    #add it to the zip
    //$zip->addFromString($data_image_company[0].'_'.basename($file_name),$data_image_company[1]);
    }
# close zip
$zip->close();
# send the file to the browser as a download
header('Content-disposition: attachment; filename='.$zip_folder_name.'.zip');
header('Content-type: application/zip');


readfile($tmp_file);

    
    
die();
}else{
    
    
    
} 
