
<!doctype html>
<html lang="ja" >
  <meta charset="utf-8">
  <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>ポモドーロ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link href="cover.css" rel="stylesheet">
    
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
        <a class="nav-link active" href="index.php">ホーム</a>
        <!-- <a class="nav-link" href="#">Features</a> -->
        <a class="nav-link" target="_blank" href="fitbit_index.php">データ</a>
      </nav>
    </div>
  </header>

  <main role="main" class="inner cover">
    <!-- <h1 class="cover-heading">Cover your page.</h1> -->
    <h1 class="cover-heading">ポモドーロタイマー</h1>
    
    <!-- <p class="lead">Cover is a one-page template for building simple and beautiful home pages. Download, edit the text, and add your own fullscreen background photo to make it your own.</p> -->
    <p class="lead"></p>
    <h2 id="timerType">25分作業</h2>
    <h1 id="timerLabel">00:01:00</h1>
    
<!--    <h1 id="timerLabel">25:00:00</h1>-->
    <input type="button" class="myButton btn-lg btn-primary ajax" onclick="start()" value="START" id="startBtn">
    <input type="button" class="myButton btn-lg btn-warning" onclick="stop()" value="STOP">
    <input type="button" class="myButton btn-lg btn-success" onclick="reset()" value="RESET">
    
    <p class="lead">
      <!-- <a href="#" class="btn btn-lg btn-secondary">Learn more</a> -->
    </p>
    <div class="card-deck mb-3 text-center">
    <div class="card mb-4 shadow-sm">
      <div class="card-header">
        <!-- <h4 class="my-0 font-weight-normal">Free</h4> -->
        <h4 class="my-0 font-weight-normal">今日の作業時間</h4>
      </div>
      <div class="card-body">
        <!-- <h1 class="card-title pricing-card-title">$0 <small class="text-muted">/ mo</small></h1> -->
        <?php todayWorkTime(0); ?>
      </div>
    </div>
      
    <div class="card mb-4 shadow-sm">
      <div class="card-header">
        <!-- <h4 class="my-0 font-weight-normal">Free</h4> -->
        <h4 class="my-0 font-weight-normal">今日のポモドーロ回数</h4>
      </div>
      <div class="card-body">
        <!-- <h1 class="card-title pricing-card-title">$0 <small class="text-muted">/ mo</small></h1> -->
        <?php todayWorkTime(1); ?>
      </div>
    </div>
  </div>
    
      <div class="card-deck mb-3 text-center">
    <div class="card mb-4 shadow-sm">
      <div class="card-header">
        <!-- <h4 class="my-0 font-weight-normal">Free</h4> -->
        <h4 class="my-0 font-weight-normal">今週の作業時間</h4>
      </div>
      <div class="card-body">
        <!-- <h1 class="card-title pricing-card-title">$0 <small class="text-muted">/ mo</small></h1> -->
        <?php todayWorkTime(2); ?>
      </div>
    </div>
      
    <div class="card mb-4 shadow-sm">
      <div class="card-header">
        <!-- <h4 class="my-0 font-weight-normal">Free</h4> -->
        <h4 class="my-0 font-weight-normal">今週のポモドーロ回数</h4>
      </div>
      <div class="card-body">
        <!-- <h1 class="card-title pricing-card-title">$0 <small class="text-muted">/ mo</small></h1> -->
        <?php todayWorkTime(3); ?>
      </div>
    </div>
  </div>
    
    <div id="comment">
        <p>----コメント----</p>
<!--        <p>デモ用のため、作業時間を1秒、休憩時間を0.5秒にしています。</p>-->
    </div>
    <script type="text/javascript">
    var status = 0; // 0:停止中 1:動作中
    var work_status = 0; // 0:作業中 1:休憩中
    var time = 100;
//    var time = 150000;
    var startBtn = document.getElementById("startBtn");
    var timerLabel = document.getElementById('timerLabel');
    var timerType = document.getElementById('timerType');
    
    //音を出す
    function sound(){
                document.getElementById('sound-file').play();
            }
    // STARTボタン
	function start(){
        // 動作中にする
        status = 1;
        // スタートボタンを押せないようにする
        startBtn.disabled = true;

        timer();
    }

    // STOPボタン
    function stop(){
        // 停止中にする
        status = 0;
        // スタートボタンを押せるようにする
        startBtn.disabled = false;
    }

    // RESETボタン
    function reset(){
        // 停止中にする
        status = 0;
        // タイムを0に戻す
        time = 100;
//        time = 150000;
        // タイマーラベルをリセット
//        timerLabel.innerHTML = '25:00:00';
        timerLabel.innerHTML = '00:01:00';
        // スタートボタンを押せるようにする
        startBtn.disabled = false;
        // 表示切り替え
        timerType.innerHTML = "25分作業";
    }
//    休憩時間
    function rest(){
        // 停止中にする
        status = 0;
        // タイムを5分にする
        time = 50;
//        time = 30000;
        // タイマーラベルをセット
//        timerLabel.innerHTML = '05:00:00';
        timerLabel.innerHTML = '0:00:50';
        // スタートボタンを押せるようにする
        startBtn.disabled = false;
    }
    
    

    function timer(){
        // ステータスが動作中の場合のみ実行
        if (status == 1) {
            setTimeout(function() {
                time--;

                // 分・秒・ミリ秒を計算
                var min = Math.floor(time/100/60);
                var sec = Math.floor(time/100);
                var mSec = time % 100;

                // 分が１桁の場合は、先頭に０をつける
                if (min < 10) min = "0" + min;

                // 秒が６０秒以上の場合　例）89秒→29秒にする
                if (sec >= 60) sec = sec % 60;

                // 秒が１桁の場合は、先頭に０をつける
                if (sec < 10) sec = "0" + sec;
    
		      // ミリ秒が１桁の場合は、先頭に０をつける
                if (mSec < 10) mSec = "0" + mSec;

                // タイマーラベルを更新
                timerLabel.innerHTML = min + ":" + sec + ":" + mSec;
                
                //0になったらreset()を呼ぶ
                if(time == 0) {
                    sound();　　//音呼び出し
                    if(work_status == 0){
                        timerType.innerHTML = "お疲れ様です。<br>5分休憩しましょう!";
                        work_status = 1;
//                        作業時間の登録

                    $.ajax({
                        url : 'record.php',
                        type : "POST",
                        cache: false,        //cacheを使うか使わないかを設定
                        dataType:'json',     //data type script・xmlDocument・jsonなど
                        data : {work_time:25, post_data_2:"piyo"}
                    }).done(function(response) {
                        console.log("ajax通信に成功しました");
                        console.log(response[0]);
                        console.log(response[1]);

                    }).fail(function(xhr) {
//                        console.log("ajax通信に失敗しました");

                    });

//                    作業と休憩の表示切り替え
                        rest();
                    }else{
                        
                        timerType.innerHTML = "25分作業を始めましょう!";
                        work_status = 0;
                        reset();
                    }
                }
                // 再びtimer()を呼び出す
                timer();
                
                
            }, 10);
        }
    }
    //表示変更function
//    function $(id) { return document.getElementById(id); }
    

</script>
    <audio id="sound-file" preload="auto">
            <source src="decision26.mp3" type="audio/mp3">
            <source src="decision26.wav" type="audio/wav">
        </audio>
    
    
    
  </main>

  <footer class="mastfoot mt-auto">
    <div class="inner">
      <p>Cover template for <a href="https://getbootstrap.com/">Bootstrap</a>, by <a href="https://twitter.com/mdo">@mdo</a>.</p>
    </div>
  </footer>
</div>


  </body>
</html>

<?php
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

