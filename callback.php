<?php
  require_once("env.php"); // 環境設定ファイルの読み込み

  // アクセストークンを取得する
  // POSTヘッダを生成する
  $header = [
  	'Authorization: Basic ' . base64_encode(CLIENT_ID.':'.CLIENT_SECRET),
  	'Content-Type: application/x-www-form-urlencoded',
  ];
  // POSTパラメータを生成する
  $params = array(
  	'client_id' => CLIENT_ID,
  	'grant_type' => 'authorization_code',
  	'redirect_uri' => CALLBACK_URL,
  	'code' => $_GET['code'],
  );
  // POST送信
  $options = array(
  	'http' => array(
  		'method' => 'POST',
  		'header' => implode(PHP_EOL,$header),
  		'content' => http_build_query($params),
  		'ignore_errors' => true
  	)
  );
  // レスポンス
  $context = stream_context_create($options);
  $response = file_get_contents(TOKEN_URL, false, $context);
  $token = json_decode($response, true); // アクセストークン
  // エラー処理
  if(isset($token['error'])){
  	echo 'ERROR!!!';
  	exit;
  }
  $access_token = $token['access_token']; // アクセストークン
  // $user_id = $token['user_id']; // ユーザID
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
<!--        日付取得のライブラリ-->
        <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
        <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
        <!-- jquery-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Fitbit HeartRate PHP7.0</title>
  </head>
  <body>
    <p>Time: <span class="time"></span></p>
    <p>HeartRate: <span class="heartrate"></span> bpm</p>
    <p>テスト: <span class="test"></span> </p>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <p class="title">1週間のポモドーロ回数</p>
            
        <div id="chart"></div> 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js" type="text/javascript"></script>
        <script type="text/javascript">
            
            var array = {
"activities-heart-intraday": {
        "dataset": [
            {
                "time": "00:00:00",
                "value": 64
            },
            {
                "time": "00:00:10",
                "value": 63
            },
            {
                "time": "00:00:20",
                "value": 64
            },
            {
                "time": "00:00:30",
                "value": 65
            },
            {
                "time": "00:00:45",
                "value": 65
            }
        ],
        "datasetInterval": 1,
        "datasetType": "second"
    }
}
            
          var myArray = [...array['activities-heart-intraday']['dataset'].map(value => value.value)];
          var timeArray = [...array['activities-heart-intraday']['dataset'].map(value => value.time)];
            
       var fitbit_data;     
      // 心拍数を取得する
      getHeartrate();

      // 毎分ごとに心拍数をリクエストする
      // setInterval("getHeartrate()", 60000);

      function getHeartrate(){
        $.post("heartrate.php", {"token": "<?php echo $access_token; ?>"},function(data){ // アクセストークンをPOSTする
          var d = $.parseJSON(data);
          $(".time").text(d[0].time); // 時間を表示する
          $(".heartrate").text(d[0].heartrate); // 心拍数を表示する
          
        var valueArray = [...d[0]['time']['dataset'].map(value => value.value)];
          var timeArray = [...d[0]['time']['dataset'].map(value => value.time)];
          console.log(d[0]['time']['dataset'][0]);
          console.log(d);
          after(valueArray);
        });
      }
      function after(d){
        fitbit_data = d;
      }

               
        
        window.onload = function () {
                //今日の日付呼び出し
                var now = moment();
                var today = now.format("MM/DD");

        var data = {
            //x軸
//          labels: [timeArray],
            //y軸
          series: [[59, 58, 59, 58, 58, 58, 58, 58, 58, 57, 58, 58, 59, 56, 59, 60, 63, 61, 63, 63, 62, 62, 62, 62, 62, 62]]
        };
        var options = {
          fullWidth: true,
          height: 300,
            width:600
            
        };
        new Chartist.Line('#chart', data, options);
            
            }

    </script>
  </body>
</html>

<style type="text/css">
            .title{margin:30px 0 0;text-align:center;}
            #chart{
              margin:10px auto;
              width:100%;
              max-width:600px;
            }  
</style>
