<?php




$colsdata=json_decode($_POST['cols'], true);
$rowsdata=json_decode($_POST['rows']);
$reportname = $_POST['reportname'];


//echo '<pre>';
//print_r($rowsdata);


foreach ($colsdata as $key=>$value){
    
    
    if($colsdata[$key]['friendly'] == 'Action' || $colsdata[$key]['hidden'] == '1' ){
        
        
        
    }else{
    
   
       $colsarray_excel[] = stripslashes($colsdata[$key]['friendly']); 
    }
    
    
    
}

$dataArray[] = $colsarray_excel;


foreach ($rowsdata as $rowskey){
    
    $rowsdataexcel=array();
    
    foreach ($rowskey as $rowsindex=>$rowsvalue){
      
        
         if ($colsdata[$rowsindex]['friendly'] == 'Action' || $colsdata[$rowsindex]['hidden'] == '1') {
                
        } else {
             if (strpos($rowsindex, 'statusCls') !== false || strpos($rowsindex, 'AutoFormat') !== false){
                 
             }else{
                 
                if ($colsdata[$rowsindex]['type'] == 'date'){
                 if(!empty($rowsvalue)){
                  $rowsdataexcel[] = date('m/d/Y', $rowsvalue/1000);
                 }else{
                     $rowsdataexcel[]="";
                 }
                }else{
                $rowsdataexcel[] = $rowsvalue; 
             }
                
                
            }
            
        }
    }
    
    $dataArray[] = $rowsdataexcel;
}

//echo '<pre>';
//print_r($fulldataarray);exit;



//echo '<pre>';
//print_r($dataArray);exit;




    


require_once('third_party/PHPExcel.php');



// create php excel object
$doc = new PHPExcel();

// set active sheet 
$doc->setActiveSheetIndex(0);

// read data to active sheet
$doc->getActiveSheet()->fromArray($dataArray);

//save our workbook as this file name
$filename = $reportname.'.xlsx';
//mime type
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//tell browser what's the file name
header('Content-Disposition: attachment;filename="' . $filename . '"');

header('Cache-Control: max-age=0'); //no cache
header('Connection: close');


//Content-Type: application/force-download
//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
//if you want to save it as .XLSX Excel 2007 format

$objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel2007');
//$objWriter->save(dirname(__FILE__).'/files/'.$filename);

//force user to download the Excel file without writing it to server's HD

$strRequest = $objWriter->save('php://output');


die();
?>