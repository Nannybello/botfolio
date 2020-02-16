<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

include 'autoload.php';

//$logger = new Logger('channel-name');
//$logger->pushHandler(new StreamHandler(ROOT_PATH . '/storage/dialogflow.log', Logger::DEBUG));
//$logger->info("BEGIN", ["-----------------------------------------------------------"]);
//$logger->info("CONTENT", [
//    'GET' => $_GET,
//    'POST' => $_POST,
//    'header' => json_encode(getallheaders(), JSON_PRETTY_PRINT),
//    'body' => json_encode(json_decode(file_get_contents('php://input')), JSON_PRETTY_PRINT),
//]);

ob_start();


//$url = "https://dialogflow.cloud.google.com/v1/integrations/line/webhook/6b8db956-ac4d-4f2e-97d4-c93108d0c9d2";
$url = "https://bots.dialogflow.com/line/botfolio-jnxcqb/webhook";
$headers = getallheaders();
$headers['Host'] = "bots.dialogflow.com";
$json_headers = array();
foreach ($headers as $k => $v) {
    $json_headers[] = $k . ":" . $v;
}
$inputJSON = file_get_contents('php://input');
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $inputJSON);
curl_setopt($ch, CURLOPT_HTTPHEADER, $json_headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 0 | ssl=2
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 0 | ssl=1
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
curl_close($ch);


print_r([
    'header' => getallheaders(),
    'body' => json_decode(file_get_contents('php://input')),
    'result' => $result,
]);
$txt = ob_get_clean();
file_put_contents(ROOT_PATH . '/storage/dialogflow.log', $txt);