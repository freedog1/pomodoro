<?php
  require_once("env.php"); // 環境設定ファイルの読み込み
  require("show_status.php");

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
    <p>25分の平均心拍数: <span class="time"></span></p>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <p class="title">25分間の心拍数</p>
            
        <div id="chart2"></div> 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js" type="text/javascript"></script>
        <script type="text/javascript">
            
            
            
//      // 心拍数を取得する
      getHeartrate();

      // 毎分ごとに心拍数をリクエストする
      // setInterval("getHeartrate()", 60000);

      //心拍数の取得とチャートの表示
      function getHeartrate(){
        $.post("heartrate.php", {"token": "<?php echo $access_token; ?>", "date": '2020-08-25', "starttime": "02:00", "endtime": "02:25"},function(data){ // アクセストークンをPOSTする
          var d = $.parseJSON(data);
//          $(".time").text("aaa"); // 時間を表示する
          
//          $(".heartrate").text(d[0].heartrate); // 心拍数を表示する
          
        valueArray = [...d[0]['time']['dataset'].map(value => value.value)];
          var timeArray = [...d[0]['time']['dataset'].map(value => value.time)];
          console.log(d[0]['time']['dataset'][0]);
          console.log(d);
          showChart(valueArray);  //チャート表示
          $(".time").text(showAvarage(valueArray)); // 時間を表示する
//          showAvarage(valueArray);  //心拍数平均の取得
        });
      }
   

//        //チャート表示
        function showChart(ary){
                //今日の日付呼び出し
//                var now = moment();
//                var today = now.format("MM/DD");
        var data = {
            //x軸
//          labels: [timeArray],
            //y軸
          series: [ary]
        };
        var options = {
          fullWidth: true,
          height: 300,
            width:600,                   //最大値最小値設定
          low:55,
          scales: {                          //軸設定
                yAxes: [{                      //y軸設定
                    display: true,             //表示設定
                    scaleLabel: {              //軸ラベル設定
                       display: true,          //表示設定
                       labelString: '縦軸ラベル',  //ラベル
                       fontSize: 18,               //フォントサイズ
                    },
                }],
        }
        };
        new Chartist.Line('#chart2', data, options);   
        }
          
        //心拍数平均
        function showAvarage(ary){
          var sum = 0;
          for(var i = 0; i<ary.length; i++){
            var sum = sum + ary[i];
          }
          var avarage = sum/ary.length;
          return avarage.toFixed(1);
        }

    </script>
  </body>
</html>

<style type="text/css">
            .title{margin:30px 0 0;text-align:center;}
            #chart2{
              margin:10px auto;
              width:100%;
              max-width:600px;
            }  
</style>
