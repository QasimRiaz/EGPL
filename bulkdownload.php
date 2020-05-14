<?php 

if(isset($_POST['zipfoldername'])) {
    
    
   
$filesnames_download = $_POST['result'];
$zip_folder_name=$_POST['zipfoldername'];

  $zip = new ZipArchive();
  $filename = "./".$zip_folder_name.".zip";

  if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
    exit("cannot open <$filename>\n");
  }
  
  
  foreach ($filesnames_download as $file_name) {
    $data_image_company = explode("*",$file_name);
    
     //$zip->addFile($data_image_company[1]);
      $fileName = $data_image_company[0].'_'.basename($file_name);
      $zip->addFile($data_image_company[1], $fileName);
   // $download_file = file_get_contents($data_image_company[1]);
    #add it to the zip
    //$zip->addFromString($data_image_company[0].'_'.basename($file_name),$data_image_company[1]);
    }
    $zip->close();
    
     header('Content-Type: application/zip');
     header('Content-Disposition: attachment; filename="'.basename($filename).'"');
     header('Content-Length: ' . filesize($filename));

     flush();
     readfile($filename);
     // delete file
     unlink($filename);
  
die();
  
}else{
    
    
    
} 
