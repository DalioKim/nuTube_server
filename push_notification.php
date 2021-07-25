<?php
//php 상에서 시간을 한국 서울 시간으로 지정해준다.
date_default_timezone_set('Asia/Seoul');

  //클라이언트로부터 전달받은 데이터를 변수에 저장한다.
  $apiKey = $_POST['apiKey'];
  $token = $_POST['token'];
  $title = $_POST['title'];
  $startTime = $_POST['startTime'];


//예약시간과 현재시간을 비교해서
//예약시간까지 sleep을 걸고,
//push 알람을 보낸다.

//현재시간을 저장한다.
$nowTime = date("H:i:s");
$waitTime = (strtotime($startTime) - strtotime($nowTime));

//30초전에 방에 입장할 수 있게끔 30을 뺴준다.
$waitTime= $waitTime-30;
//대기시간동안 sleep을 걸어둔다.
sleep($waitTime);


    define("GOOGLE_API_KEY", "AAAASS7TEQY:APA91bH-qwaudcEVcER7331nRkTb4RzAnhswyEB2D1xuFK5oz4kuu519_JuXH7oQL9REK_4pxqNKAATaj9bdWSPO5mNhI71LsAYLZh0doLKx7-tLfnEwclcLJLzEvEUpr1d853DIaOFc");

    function send_notification ($tokens, $message)
    {


    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array(
         'registration_ids' => $tokens,
         'data'             => $message
    );

    $headers = array(
        'Authorization:key =' .GOOGLE_API_KEY,
        'Content-Type: application/json'
    );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);

            if ($result === FALSE) {
               die('Curl failed: ' . curl_error($ch));
            }

            curl_close($ch);

            return $result;
    }



    $tokens = array($token);

    $myMessage = "최초공개 영상이 곧 시작됩니다.";

$message = array(
    "title"     => $title,
    "body"   => $myMessage
);

    //영상 시작시간까지 대기한다.
    $message_status = send_notification($tokens, $message);


    //echo $message_status;


    echo json_encode(array("response"=>$waitTime));

 ?>
