<?php

$serverName = "localhost";
$userName = "user_db";
$userPassword = "password_db";
$dbName = "Day";
$connect = mysqli_connect($serverName, $userName, $userPassword, $dbName) or die ("connect error" . mysqli_error());
mysqli_set_charset($connect, "utf8");
$query_day = "SELECT name,date_mong FROM today WHERE MONTH(STR_TO_DATE(date_mong, ’ % Y -%m -%d’))= MONTH(NOW()) AND DAY(STR_TO_DATE(date_mong, ’ % Y -%m -%d’))= DAY(NOW()) ";
$resource = mysqli_query($connect, $query_day) or die ("error" . mysqli_error());

$count_row = mysqli_num_rows($resource);
if ($count_row > 0) {
    while ($result = mysqli_fetch_array($resource)) {
        $name = $result[‘name’];
        $date = $result[‘date_mong’];

        $query_line = "SELECT user_id FROM line_log GROUP BY user_id";
        $resource_line = mysqli_query($connect, $query_line) or die ("error" . mysqli_error());

        $count_row_line = mysqli_num_rows($resource_line);
        if ($count_row_line > 0) {
            while ($result = mysqli_fetch_array($resource_line)) {
                $user_id = $result[‘user_id’];

                for ($i = 1; $i <= $count_row_line; $i++) {
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.line.me/v2/bot/message/push",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{
                \r\n\r\n \"to\": \"$user_id\",\r\n\r\n \"messages\": [{
                    \r\n\r\n \"type\": \"flex\",\r\n\r\n \"altText\": \"วันนี้เป็นวันพระ\",\r\n\r\n \"contents\": \r\n{
                        \r\n\"type\": \"bubble\",\r\n \"hero\": {
                            \r\n \"type\": \"image\",\r\n \"url\": \"https://f.ptcdn.info/716/040/000/o3npyu1b2ovxUqEPxDb-o.jpg\",\r\n \"size\": \"full\",\r\n \"aspectRatio\": \"20:13\",\r\n \"aspectMode\": \"cover\",\r\n \"action\": {\r\n \"type\": \"uri\",\r\n \"uri\": \"https://f.ptcdn.info/716/040/000/o3npyu1b2ovxUqEPxDb-o.jpg\"\r\n }\r\n },\r\n \"body\": {\r\n \"type\": \"box\",\r\n \"layout\": \"vertical\",\r\n \"contents\": [\r\n {\r\n \"type\": \"text\",\r\n \"text\": \"วันนี้ วันพระ\",\r\n \"weight\": \"bold\",\r\n \"size\": \"xl\"\r\n },\r\n {\r\n \"type\": \"box\",\r\n \"layout\": \"baseline\",\r\n \"margin\": \"md\",\r\n \"contents\": [\r\n {\r\n \"type\": \"icon\",\r\n \"size\": \"sm\",\r\n \"url\": \"https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png\"\r\n },\r\n {\r\n \"type\": \"icon\",\r\n \"size\": \"sm\",\r\n \"url\": \"https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png\"\r\n },\r\n {\r\n \"type\": \"icon\",\r\n \"size\": \"sm\",\r\n \"url\": \"https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png\"\r\n },\r\n {\r\n \"type\": \"icon\",\r\n \"size\": \"sm\",\r\n \"url\": \"https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png\"\r\n },\r\n {\r\n \"type\": \"icon\",\r\n \"size\": \"sm\",\r\n \"url\": \"https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png\"\r\n },\r\n {\r\n \"type\": \"text\",\r\n \"text\": \"วันพระประจำปี 2561\",\r\n \"size\": \"sm\",\r\n \"color\": \"#999999\",\r\n \"margin\": \"md\",\r\n \"flex\": 0\r\n }\r\n ]\r\n },\r\n {\r\n \"type\": \"box\",\r\n \"layout\": \"vertical\",\r\n \"margin\": \"lg\",\r\n \"spacing\": \"sm\",\r\n \"contents\": [\r\n {\r\n \"type\": \"box\",\r\n \"layout\": \"baseline\",\r\n \"spacing\": \"sm\",\r\n \"contents\": [\r\n {\r\n \"type\": \"text\",\r\n \"text\": \" \",\r\n \"color\": \"#aaaaaa\",\r\n \"size\": \"sm\",\r\n \"flex\": 1\r\n },\r\n {\r\n \"type\": \"text\",\r\n \"text\": \"$name\",\r\n \"wrap\": true,\r\n \"color\": \"#666666\",\r\n \"size\": \"sm\",\r\n \"flex\": 5\r\n }\r\n ]\r\n }\r\n \r\n ]\r\n }\r\n ]\r\n }\r\n}\r\n }]\r\n\r\n}\r\n\r\n\r\n",
                        CURLOPT_HTTPHEADER => array(
                            "authorization: Bearer Line_token",
                            "cache - control: no - cache",
                            "content - type: application / json",
                            "postman - token: 71e40b26–87b8–5f38–477c - 9bbb4cbffa88"
                        ),
                    ));
                }
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    echo $response;
                }
            }
        } else {
            echo " Not now";
        }
    }
} else {
    echo " Not today";
}
?>