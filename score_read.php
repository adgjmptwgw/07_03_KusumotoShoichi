<?php
// DB接続の設定
// DB名は`gsacf_x00_00`にする
$dbn = 'mysql:dbname=gsacf_l03_03;charset=utf8;port=3306;host=localhost';
$user = 'root';
$pwd = '';

try {
  // ここでDB接続処理を実行する
  $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
  // DB接続に失敗した場合はここでエラーを出力し，以降の処理を中止する
  echo json_encode(["db error" => "{$e->getMessage()}"]);
  exit();
}

// データ取得SQL作成
$sql = 'SELECT * FROM score_table ORDER BY score desc'; //スコアを昇順に並び替え
        // SELECT user_id FROM user WHERE name = $name AND password = $password;
        // $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        // $stmt->bindValue(':password', md5($password), PDO::PARAM_STR);

// SQL準備&実行
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();


// データ登録処理後
if ($status == false) {
  $error = $stmt->errorInfo();
  exit('sqlError:' . $error[2]);
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する

} else {
  // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
  // fetchAll()関数でSQLで取得したレコードを配列で取得できる
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  // データの出力用変数（初期値は空文字）を設定  ※DBの表示画面にある全てのテーブル
  $output = "";
  // <tr><td>date</td><td>score</td><tr>の形になるようにforeachで順番に$outputへデータを追加
  // `.=`は後ろに文字列を追加する，の意味
  foreach ($result as $record) {
    $output .= "<tr>";
    $output .= "<td>{$record["id"]}</td>";
    $output .= "<td>{$record["date"]}</td>";
    $output .= "<td>{$record["score"]}</td>";
    $output .= "<td><a href='score_delete.php?id={$record["id"]}'>delete</a></td>";
    $output .= "</tr>";
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>スコア</title>
  <style>
    body {
      background-color: black;
      color: white;
    }

    legend {
      font-size: 30px;
      color: yellow;
    }

    legend,
    a,
    table {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    td {
      padding: 10px;
    }

    th {
      padding-top: 10px;
      padding-right: 80px;
    }

    .reset {
      text-decoration: none;
    }

    a:visited {
      color: greenyellow;
    }

    a:hover {
      color: #FF0099;
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <fieldset>
    <legend>スコアランキング</legend>
    <a href="score_input.php" class="reset">ゲームに戻る</a>
    <table>
      <thead>
        <tr>
          <th>id</th>
          <th>date</th>
          <th>score</th>
        </tr>
      </thead>
      <tbody>
        <!-- ここに<tr><td>date</td><td>score</td><tr>の形でデータが入る -->
        <?= $output ?>
      </tbody>
    </table>
  </fieldset>
</body>

</html>