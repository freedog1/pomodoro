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

  function todayWorkTime($i){
        try {
            // PDOインスタンスを生成    
            $ini = parse_ini_file('./db.ini',FALSE);
            $pdo = new PDO('mysql:host='.$ini['host'].';dbname='.$ini['dbname'].';charset=utf8', $ini['dbuser'], $ini['dbpass']);

                // エラー（例外）が発生した時の処理を記述
                } catch (PDOException $e) {

                  // エラーメッセージを表示させる
                  echo 'データベースにアクセスできません！' . $e->getMessage();
                  // 強制終了
                  exit;
                }

            $daysql = "SELECT * FROM work WHERE stop_at BETWEEN :today1 AND :today2";
            $weeksql = "SELECT * FROM work WHERE stop_at BETWEEN :week1 AND :today2";
            $day = $pdo->prepare($daysql);
            $week = $pdo->prepare($weeksql);

            date_default_timezone_set('Asia/Tokyo');
            $day->bindValue(':today1',date("Y/m/d 00:00:00"));
            $day->bindValue(':today2',date("Y/m/d 23:59:59"));
            $week->bindValue(':week1',date("Y/m/d 00:00:00", strtotime("-6 day")));
            $week->bindValue(':today2',date("Y/m/d 23:59:59"));
            if($day->execute()){
    //          テーブルのレコード数を取得する
                $day_row_cnt = $day->rowCount();
                if($i == 0){
                    $todayTime = $day_row_cnt * 0.42;
                    echo $todayTime;
                    echo "h";
                }elseif($i == 1){
                    echo $day_row_cnt;
                    echo "回";
                }
            }
            if($week->execute()){
    //        テーブルのレコード数を取得する
                $week_row_cnt = $week->rowCount();
                if($i == 2){
                    $weekTime = $week_row_cnt * 0.42;
                    echo $weekTime;
                    echo "h";
                }elseif($i == 3){
                    echo $week_row_cnt;
                    echo "回";
                }

            }
    }


?>
<!doctype html>
<html lang="ja" >
  <meta charset="utf-8">
  <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>ポモドーロ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link href="cover.css" rel="stylesheet">
    <!--  日付取得のライブラリ-->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
    <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>

    
        <style>
        #rest-time{
            display: none;
        }
        #timerType{

    color: darkslateblue;
    background: #c6e4ff;
    border-bottom: solid 6px #aac5de;
    border-radius: 9px;
            font-size: 40px;
        }
        .card-deck{
            font-size: 30px;
            color:black;
            
          }
          .font-weight-normal{
            font-size:20px;
            color:darkred;
          }
          .chart-type{
            color: black;
            background-color: white;
          }
          .col-4{
             margin: auto;
          }
          #syutyu{
            font-size: 20px;
            border-top: solid 6px #1dc1d6;
            
          }
          #cons-type{
            background-color:aliceblue;
            font-size: 25px;
          }
        
    </style>
  </head>
  <body  class="text-center" >
    <a id="skippy" class="sr-only sr-only-focusable" href="#content">
  <div class="container">
    <span class="skiplink-text">Skip to main content</span>
  </div>
</a>

    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
  <header class="masthead mb-auto">
    <div class="inner">
      <!-- <h3 class="masthead-brand">Cover</h3> -->
      <h3 class="masthead-brand">ポモドーロ</h3>
      <nav class="nav nav-masthead justify-content-center">
        <!-- <a class="nav-link active" href="#">Home</a> -->
        <a class="nav-link" href="index.php">ホーム</a>
        <!-- <a class="nav-link" href="#">Features</a> -->
        <a class="nav-link active" href="fitbit_index.php">データ</a>
      </nav>
    </div>
  </header>

  <main role="main" class="inner cover">
    <!-- <h1 class="cover-heading">Cover your page.</h1> -->
    <h1 class="cover-heading">データ</h1>
    
    <!-- <p class="lead">Cover is a one-page template for building simple and beautiful home pages. Download, edit the text, and add your own fullscreen background photo to make it your own.</p> -->
    <p class="lead"></p>
    <div class="row chart-type">
      <div class="col-8">
    <?php require("show_status.php"); ?>
        </div>
      <div class="col-4">
      <p>1週間のポモドーロ回数の推移です。</p>
      </div>
    </div>
    <P><br></P>
    <div class="row chart-type">
      <div class="col-8">
    
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
        $.post("heartrate.php", {"token": "<?php echo $access_token; ?>", "date": "<?php echo $date; ?>", "starttime": "<?php echo $startTime; ?>", "endtime": "<?php echo $stopTime; ?>"},function(data){ // アクセストークンをPOSTする
          var d = $.parseJSON(data);
//          $(".time").text("aaa"); // 時間を表示する
          
//          $(".heartrate").text(d[0].heartrate); // 心拍数を表示する
          
        valueArray = [...d[0]['time']['dataset'].map(value => value.value)];
          var timeArray = [...d[0]['time']['dataset'].map(value => value.time)];
          console.log(d[0]['time']['dataset'][0]);
          console.log(d);
          showChart(valueArray);  //チャート表示
//          $(".time").text(showAverage(valueArray)); //心拍数平均を表示
          $(".time").text(showAverage(valueArray).toFixed(1));
          $(".cons").text(concentrateScore(valueArray));
          variance(valueArray);
          concentrateScore(valueArray);
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
          height: 200,
            width:400,                   //最大値最小値設定
          low:50,
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
        function showAverage(ary){
          var sum = 0;
          for(var i = 0; i<ary.length; i++){
            var sum = sum + ary[i];
          }
          var average = sum/ary.length;
//          return average.toFixed(1);
            return average;
        }
          
        //心拍数の分散を取得
        function variance(ary){
          var ave = showAverage(ary);
          var varia = 0;
          for (i=0; i<ary.length; i++) {
            varia = varia + Math.pow(ary[i] - ave, 2);
          } 
          console.log(varia / ary.length)
          return (varia / ary.length);
        }
          
        //集中力判定
        function concentrateScore(ary){
          //
          var ave = showAverage(ary);
          var varia = variance(ary);
          if(ave > 65 && varia >10){
            console.log("高ストレス");
            return "高ストレス　　休憩しましょう";
          }else if(ave > 65){
            console.log("高集中");
            return "高集中   さすが！！";
          }else if(varia > 65){
            console.log("集中してない");
            return "集中してない";
          }else{
            console.log("安定集中");
            return "安定集中  その調子!";
          }
        }

    </script>
      </div>
      <div class="col-4">
        <p>左チャートは直近の作業の心拍数です。</p>
        <p>平均心拍数: <span class="time"></span></p>
        <p id="syutyu">↓↓集中力評価↓↓</p>
        <p id="cons-type"><span class="cons"></span></p>
      </div>
    </div>
    <p class="lead">
      <!-- <a href="#" class="btn btn-lg btn-secondary">Learn more</a> -->
    </p>
    
    <div id="comment">
        <p>----コメント----</p>
        <p>集中力評価は４タイプあります。</p>
    </div>
    
  </main>

  <footer class="mastfoot mt-auto">
    <div class="inner">
      <p>Cover template for <a href="https://getbootstrap.com/">Bootstrap</a>, by <a href="https://twitter.com/mdo">@mdo</a>.</p>
    </div>
  </footer>
</div>


  </body>
</html>



