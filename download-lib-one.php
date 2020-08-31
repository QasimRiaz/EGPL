<?php 




 

//$files_urls_array = $_POST['filesurl'][0];
//$files = explode(',', $files_to_zip);

if(!empty($_GET['filename'])){
    
$file_name = str_replace('"', '', $_GET['filename']);
$zip_folder_name=$_GET['zipname'];
$cname=$_GET['cname'];
   
  $zip = new ZipArchive();
  $filename = "./".$zip_folder_name.".zip";

  if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
    exit("cannot open <$filename>\n");
  }
    
    $download_file = file_get_contents($file_name);
    #add it to the zip
    $zip->addFromString($cname.'_'.basename($file_name),$download_file);
  
    $zip->close();
    
     header('Content-Type: application/zip');
     header('Content-Disposition: attachment; filename="'.basename($filename).'"');
     header('Content-Length: ' . filesize($filename));

     flush();
     readfile($filename);
     // delete file
     unlink($filename);
  
die();

}