

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
<!-- jquery-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    
    
    <title>ポモドーロ</title>
    <style>
        /* ここにCSSを書いていきます。 */
        #rest-time{
            display: none;
        }
        
    </style>
</head>
<body>
<div class="container">
    <h1 class="title">ポモドーロタイマー</h1>
    <h2 id="timerType">25分作業</h2>
    <h1 id="timerLabel">00:01:00</h1>
    <input type="button" class="myButton ajax" onclick="start()" value="START" id="startBtn">
    <input type="button" class="myButton" onclick="stop()" value="STOP">
    <input type="button" class="myButton" onclick="reset()" value="RESET">
</div>
    
<!--作業時間の表示    -->
<div id="status">
    <h3>今日の作業時間</h3>
    <?php todayWorkTime(0); ?>
    <h3>今日のポモドーロ回数</h3>
    <?php todayWorkTime(1); ?>
    <h3>今週の作業時間</h3>
    <?php todayWorkTime(2); ?>
    <h3>今週のポモドーロ回数</h3>
    <?php todayWorkTime(3); ?>
    

    
    <div id="show_status">
    <a href="show_status.php">ポモドーロの記録を見る</a>
    </div>
    
</div>
    
    
<script type="text/javascript">
    var status = 0; // 0:停止中 1:動作中
    var work_status = 0; // 0:作業中 1:休憩中
    var time = 100;
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
        // タイマーラベルをリセット
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
        // タイムを0に戻す
        time = 50;
        // タイマーラベルをリセット
        timerLabel.innerHTML = '00:0:50';
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
                        timerType.innerHTML = "お疲れ様です。5分休憩しましょう!";
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
                        console.log("ajax通信に失敗しました");

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
    <p><a onClick="sound()">このテキストを押すと挨拶します！</a></p>

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
                }elseif($i == 1){
                    echo $day_row_cnt;
                }
            }
            if($week->execute()){
    //        テーブルのレコード数を取得する
                $week_row_cnt = $week->rowCount();
                if($i == 2){
                    $weekTime = $week_row_cnt * 0.42;
                    echo $weekTime;
                }elseif($i == 3){
                    echo $week_row_cnt;
                }

            }
    }
 

?>
