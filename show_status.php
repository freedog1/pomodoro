<?php


//DB接続
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
//    today1からtoday2間に登録されたレコード呼び出し
    $sql = "SELECT * FROM work WHERE stop_at BETWEEN :today1 AND :today2";

    $stmt = $pdo->prepare($sql);
    //タイムゾーン設定
    date_default_timezone_set('Asia/Tokyo');
//    1日毎、1週間のレコード数を配列に格納
    for($i = 0;$i<7;$i++){
        //today1設定
        $stmt->bindValue(':today1',date("Y/m/d 00:00:00", strtotime("-{$i} day")));
        //today2設定
        $stmt->bindValue(':today2',date("Y/m/d 23:59:59", strtotime("-{$i} day")));

        if($stmt->execute()){
        //   テーブルのレコード数を取得する
            $day_row_cnt = $stmt->rowCount();
            $count[$i] = $day_row_cnt;
            if($i == 0){
            $php_array = array(
                $i => $count[$i],
            );                
            }else{
            $php_array += array(
                $i => $count[$i],
            );       
            }
            
        }
    }

    // 配列($array)をJSONに変換(エンコード)する
    $php_json = json_encode($php_array);
?>



<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
<!--        日付取得のライブラリ-->
        <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
        <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
        <!-- jquery-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        
        
        
        
    </head>
    <body>
        <p class="title">1週間のポモドーロ回数</p>
            
        <div id="chart"></div> 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js" type="text/javascript"></script>
        <script type="text/javascript">
            //php呼び出し
            let js_array = <?php echo $php_json; ?>;
            
            window.onload = function () {
                //今日の日付呼び出し
                var now = moment();
                var today = now.format("MM/DD");

        var data = {
            //x軸
          labels: [now.add(-6, "day").format("MM/DD"), 
                   now.add(1, "day").format("DD"),
                   now.add(1, "day").format("DD"),
                   now.add(1, "day").format("DD"),
                   now.add(1, "day").format("DD"),
                   now.add(1, "day").format("DD"),
                   now.add(1, "day").format("DD")],
            //y軸
          series: [
            [js_array[6],
             js_array[5], 
             js_array[4], 
             js_array[3], 
             js_array[2],
             js_array[1],
             js_array[0]],
          ]
        };
        var options = {
          fullWidth: true,
          height: 300,
            width:600
            
        };
        new Chartist.Line('#chart', data, options);
            
            }
        </script>
    
<!--        <div class="ct-chart ct-perfect-fourth"></div>-->

    
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