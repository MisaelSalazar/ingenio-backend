<?php
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Headers: Content-Type, Authorization");
//header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

//if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  //  http_response_code(200);
    //exit;
//}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

?>
