<?php
// show error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);
 
// home page url
$home_url="http://localhost/judbrass/api/";
 
// page given in URL parameter, default page is one
$page = isset($_GET['page']) ? $_GET['page'] : 1;
 
// set number of records per page
$records_per_page = 5;
 
// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;

// show error reporting
error_reporting(E_ALL);
 
// set your default time-zone
date_default_timezone_set('Asia/Manila');
 
// variables used for jwt
$key = "edC2XXlcjJ`FCx6[b:g*lnYUz[}Y[a6aYQdyl~SSoPR<3G{Q:^COOAo1=39}4g";
$iss = "http://localhost/judbrass";
$aud = "http://localhost/judbrass";

$nbf = 1357000000;


?>