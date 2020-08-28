<?php
  // 心拍数
    $access_token = $_POST["token"]; // アクセストークン

    $api_url = 'https://api.fitbit.com/1/user/-/activities/heart/date/today/1d/1sec.json'; // Get Heart Rate Intraday Time Series
    
    $test_date= '2020-08-25';
    $test_starttime = '02:00';
    $test_endtime = '02:25';
//    $api_url2 = 'https://api.fitbit.com/1/user/-/activities/heart/date/${test_date}/1d/1m/time/${test_starttime}/${test_endtime}.json'; 
//    $api_url2 = 'https://api.fitbit.com/1/user/-/activities/heart/date/2020-08-25/1d/1m/time/02:00/02:25.json';
 $api_url2 = "https://api.fitbit.com/1/user/-/activities/heart/date/${test_date}/1d/1min/time/${test_starttime}/${test_endtime}.json";

  $header = 'Authorization: Bearer ' . $access_token; // アクセストークン
  // $params = array('access_token' => $access_token);
  $options = array(
    'http' => array(
      'method' => 'GET',
      'header' => $header,
      'ignore_errors' => true
    )
  );

  $context = stream_context_create($options); // HTTPリクエストを$api_urlに対して送信する
  $heartrate_json = file_get_contents($api_url2, false, $context); // レスポンス
  $heatrate = json_decode($heartrate_json, true); // 配列デコード
//  $heatrate_len = count($heatrate["activities-heart-intraday"]["dataset"]);

  // レスポンス配列
  $response = array();

//  $response[] = array(
//    "time" => $heatrate["activities-heart-intraday"]["dataset"][$heatrate_len-1]["time"],
//    "heartrate" => $heatrate["activities-heart-intraday"]["dataset"][$heatrate_len-1]["value"],
//    "test" => $heatrate["activities-heart-intraday"]["dataset"][$heatrate_len-1]["value"]
//  );  
  $response[] = array(
    "time" => $heatrate["activities-heart-intraday"]
  );

  // レスポンスを返す
  echo json_encode($response); // JSONエンコード
?>
