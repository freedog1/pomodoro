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

//受け取ったデータを配列に格納
//$return_array = array($work_time, $post_data_2);

    $sql = "SELECT * FROM work WHERE stop_at BETWEEN :today1 AND :today2";

    $stmt = $pdo->prepare($sql);
    date_default_timezone_set('Asia/Tokyo');
    for($i = 0;$i<7;$i++){
//        $stmt->bindValue(':today1',date("Y/m/d 00:00:00"));
//        $stmt->bindValue(':today2',date("Y/m/d 23:59:59"));
        $stmt->bindValue(':today1',date("Y/m/d 00:00:00", 
        strtotime("-{$i} day")));
        $stmt->bindValue(':today2',date("Y/m/d 23:59:59", 
        strtotime("-{$i} day")));

        echo date("Y/m/d H:i:s",strtotime("-{$i} day"));
        echo "<br />";
        if($stmt->execute()){
        //   テーブルのレコード数を取得する
            $day_row_cnt = $stmt->rowCount();
            echo $day_row_cnt;
            echo "<br />";
            $count[$i] = $day_row_cnt;
            echo $count[$i];
            echo "<br />";
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

//    //ヘッダーの設定
//    header('Content-type:application/json; charset=utf8');
//    //「$return_array」をjson_encodeして出力
//    echo json_encode($return_array);


    // 連想配列($array)をJSONに変換(エンコード)する
    $php_json = json_encode($php_array);
?>



<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
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
            
            
                let js_array = <?php echo $php_json; ?>;
                
                
                
                
            window.onload = function () {
                var now = moment();
                var today = now.format("MM/DD");
                console.log(today);
//                var tomorrow = today.add(1, "months").format("MM/DD");
//                console.log(tomorrow);
                var date = moment('2015-01-23');
                console.log(now.add(1, "day").format("MM/DD")); // 2015-02-23

        var data = {
            
          labels: [now.add(-7, "day").format("MM/DD"), 
                   now.add(1, "day").format("DD"),
                   now.add(1, "day").format("DD"),
                   now.add(1, "day").format("DD"),
                   now.add(1, "day").format("DD"),
                   now.add(1, "day").format("DD"),
                   now.add(1, "day").format("DD")],
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