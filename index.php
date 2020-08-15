<!DOCTYPE html>
<html lang="ja">
<head>
<!-- jquery-->
    <script
  src="https://code.jquery.com/jquery-3.5.1.js"
  integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
    
    
    <meta charset="utf-8">
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
    <h2 id="timer-type">25分作業</h2>
    <h1 id="timerLabel">00:01:00</h1>
    <input type="button" class="myButton" onclick="start()" value="START" id="startBtn">
    <input type="button" class="myButton" onclick="stop()" value="STOP">
    <input type="button" class="myButton" onclick="reset()" value="RESET">
</div>
<script type="text/javascript">
    var status = 0; // 0:停止中 1:動作中
    var work_status = 0; // 0:作業中 1:休憩中
    var time = 100;
    var startBtn = document.getElementById("startBtn");
    var timerLabel = document.getElementById('timerLabel');

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
        var el = $("timer-type");
        el.innerHTML = "25分作業";
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
    //表示変更function
    function $(id) { return document.getElementById(id); }

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
                    if(work_status == 0){
                        var el = $("timer-type");
                        el.innerHTML = "お疲れ様です。5分休憩しましょう!";
                        work_status = 1;
//                        作業時間の登録
//                    $.ajax({
//                        url : "record.php",
//                        type : "POST",
//                        data : {work_time:25, post_data_2:"piyo"}
//                    }).done(function(response, textStatus, xhr) {
//                        console.log("ajax通信に成功しました");
//                        console.log(response[0]);
//                        console.log(response[1]);
//                    }).fail(function(xhr, textStatus, errorThrown) {
//                        console.log("ajax通信に失敗しました");
//                    });
                    
//                    作業と休憩の表示切り替え
                        rest();
                    }else{
                        var el = $("timer-type");
                        el.innerHTML = "25分作業を始めましょう!";
                        work_status = 0;
                        reset();
                    }
                }
                // 再びtimer()を呼び出す
                timer();
                
                
            }, 10);
        }
    }
    
 
    
    
//    function test(){
//            document.getElementById("php_code").innerHTML="<?php echo("hello"); ?>";    
//}
</script>
    
<!--
    <a href="#" style="display:block; color:#000033; font-family:Tahoma; font-size:12px;"     
onclick="test(); return false;"> test </a>
<span id="php_code"> </span>
-->
</body>