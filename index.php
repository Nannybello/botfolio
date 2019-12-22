<?php

//echo "test botfolio";
//include 'public/index.php';


use Illuminate\Support\Facades\Log;

$content = file_get_contents('php://input');

Log::info(json_encode(["GET" => $_GET, "POST" => $_POST, "CONTENT" => $content, "HEADERS" => getallheaders()]));
