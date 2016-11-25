<?php

//echo $_GET['fieldname'];exit;


if (!empty($_GET['fieldname'])) {
      require_once('../../../wp-load.php');
    $filed_name = $_GET['fieldname'];
    $user_id = $_GET['userid'];
    $base_url = "http://" . $_SERVER['SERVER_NAME'];
    $user_last = get_user_meta($user_id, $filed_name);

   // echo '<pre>';
  //  print_r($user_last);
   // exit;
    $download_array = $user_last[0]['file'];



    header('Location: ' . $base_url . '/wp-content/plugins/EGPL/download-lib-one.php?zipname=' . $filed_name . '&filename=' . $download_array);
}