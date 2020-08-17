
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

//ajax送信でPOSTされたデータを受け取る
$work_time = $_POST['work_time'];
$post_data_2 = $_POST['post_data_2'];
//受け取ったデータを配列に格納
$return_array = array($work_time, $post_data_2);

                    $sql = "INSERT INTO work (start_at,work_time,stop_count) VALUES (:start_at,:work_time,:stop_count)";

                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':start_at',date("Y/m/d H:i:s"));
                    
                    $stmt->bindValue(':stop_count',25);
                    $stmt->bindValue(':work_time',25);
                    echo date("Y/m/d H:i:s");
                   if($stmt->execute()){
                        echo "成功";
                    }else{
                        echo "失敗";
    
                    }

//ヘッダーの設定
header('Content-type:application/json; charset=utf8');
//「$return_array」をjson_encodeして出力
echo json_encode($return_array);


?>
