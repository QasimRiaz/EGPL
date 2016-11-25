<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Ropaki</title>

	

	<script src="js/jquery.js"></script>
        <script src="js/lodash.js"></script>
        <script src="js/time_allowcation.js"></script>
        
        
<?php 

$eventtimeslots = array();
$moduletimeslots = json_decode('[
  {
    "Module": "M1",
    "Start_time": "12:00",
    "Num_of_units": 16,
    "Units_per_application": 1,
    "Number_of_seats": 1
  },
  {
    "Module": "M2",
    "Start_time": "12:00",
    "Num_of_units": 16,
    "Units_per_application": 1,
    "Number_of_seats": 1
  },
  {
    "Module": "KLM",
    "Start_time": "12:00",
    "Num_of_units": 16,
    "Units_per_application": 1,
    "Number_of_seats": 1
  },
  {
    "Module": "W4",
    "Start_time": "12:00",
    "Num_of_units": 4,
    "Units_per_application": 4,
    "Number_of_seats": 30
  },
  {
    "Module": "Pitches",
    "Start_time": "13:00",
    "Num_of_units": 20,
    "Units_per_application": 1,
    "Number_of_seats": 1
  },
  {
    "Module": "Vopak workshop",
    "Start_time": "14:00",
    "Num_of_units": 4,
    "Units_per_application": 4,
    "Number_of_seats": 20
  }
]');

foreach ($moduletimeslots as $modulekey => $module) { 
    $starttime = strtotime($module->Start_time);
  for($i=0; $i<$module->Num_of_units; $i++ ){
        
           
           $starttime =date('h:i', $starttime);
           $eventtimeslots[$module->Module][$i]['status'] = 'notallocated' ;
           $eventtimeslots[$module->Module][$i]['starttime'] = $starttime;
           $starttime = strtotime("+15 minutes", strtotime($starttime));
            
       }
    
    
}
//exit;
       
       
       

        echo '<==================Setp 1 Event time slots ===================>';
        echo '<pre>';
        print_r($eventtimeslots);
        echo '<==================Setp 1 Event time slots ===================></br>';
        
       
      
        
        
        
        
        $eventdata  = '[
  {
    "Username": "p1",
    "Event_Name": "M1",
    "Units_requested": 1
  },
  {
    "Username": "p1",
    "Event_Name": "M2",
    "Units_requested": 1
  },
  {
    "Username": "p1",
    "Event_Name": "Vopak workshop",
    "Units_requested": 4
  },
  {
    "Username": "p2",
    "Event_Name": "KLM",
    "Units_requested": 1
  },
  {
    "Username": "p3",
    "Event_Name": "M1",
    "Units_requested": 1
  },
  {
    "Username": "p1",
    "Event_Name": "Pitches",
    "Units_requested": 1
  },
  {
    "Username": "p2",
    "Event_Name": "Pitches",
    "Units_requested": 1
  },
  {
    "Username": "p3",
    "Event_Name": "Pitches",
    "Units_requested": 1
  },
  {
    "Username": "p4",
    "Event_Name": "Pitches",
    "Units_requested": 1
  },
  {
    "Username": "p3",
    "Event_Name": "W4",
    "Units_requested": 4
  },
  {
    "Username": "p4",
    "Event_Name": "W4",
    "Units_requested": 4
  }
]';
        $new_result = json_decode($eventdata);
       
        echo '<==================Setp 2 Applicants Data===================>';
        echo '<pre>';
        print_r($new_result);
        echo '<==================Setp 2 Applicants Data ===================></br>';

      
    
       $result = array();
       foreach ($new_result as $data=>$key) {
           $id = $new_result[$data]->Username;
           if (isset($result[$id])) {
                 $result[$id][] = $new_result[$data]->Event_Name;
            } else {
               $result[$id] = array($new_result[$data]->Event_Name);
            }
       }
       
      
        echo '<================== Setp 3 Applicant Data Group By ===================>';
        echo '<pre>';
        print_r($result);
        echo '<================== Setp 3 Applicant Data Group By  ===================></br>';
    
      //$finalresult = array();
       foreach ($result as $applicantkey => $applicant) { //loop through each distinct applicant

           foreach ($applicant as $applications) { //loop through each event application of an applicant

               if (array_key_exists($applications, $eventtimeslots)) {

                   //Find available timeslot (eventName, current applicant's applications) returns the next available timeslot and allocate.
                   foreach ($eventtimeslots[$applications] as $currenttimeslotkey => $currenttimeslot) {  //loop through all timeslots of an application's event

                       if ($currenttimeslot['status'] == 'notallocated') {  //Ensure status is not allocated

                           if (in_array($currenttimeslot['starttime'], $result[$applicantkey])) {  //Ensure the current timeslot is not already allocated for this applicant
                               
                           } else {

                               $result[$applicantkey][$applications] = $currenttimeslot['starttime'];  //Assign the current timeslot value to current applicant's current application
                               $eventtimeslots[$applications][$currenttimeslotkey]['status'] = 'allocated';  //Update the status
                               break;
                           }
                       }
                   }
               }
           }
       }
       
      
        echo '<================== Setp 4 Applicant Applications Assign Time slot ===================>';
        echo '<pre>';
        print_r($result);
        echo '<================== Setp 4 Applicant Applications Assign Time slot  ===================></br>';

        echo '<================== Setp 5 Current Time slot Status ===================>';
        echo '<pre>';
        print_r($eventtimeslots);
        echo '<================== Setp 5 Current Time slot Status  ===================></br>';
       
     // echo '<pre>';
     // print_r($result);
     // echo '<pre>';
     // print_r($eventtimeslots);exit;
       ?>
 
    
</head>