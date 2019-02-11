<?php 




 

//$files_urls_array = $_POST['filesurl'][0];
//$files = explode(',', $files_to_zip);

if(!empty($_GET['filename'])){
    
$file_name = str_replace('"', '', $_GET['filename']);
$zip_folder_name=$_GET['zipname'];
$cname=$_GET['cname'];
//echo '<pre>';
//print_r($files);

//foreach($files as $file){
 
  //  echo $file = str_replace('"', '', $file).'<br>';
//}
//exit;

   
# create new zip opbject
$zip = new ZipArchive();
# create a temp file & open it
$tmp_file = tempnam('.','');
$zip->open($tmp_file, ZipArchive::CREATE);
# loop through each file

    # download file
   
    $download_file = file_get_contents($file_name);
    #add it to the zip
    $zip->addFromString($cname.'_'.basename($file_name),$download_file);

# close zip
$zip->close();
# send the file to the browser as a download
header('Content-disposition: attachment; filename='.$zip_folder_name.'.zip');
header('Content-type: application/zip');


readfile($tmp_file);
}