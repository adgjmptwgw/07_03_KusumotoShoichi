<?php
// DB接続の設定
// DB名は`gsacf_x00_00`にする

include('functions.php');  //同じページに関数”connect_to_db()”が無い為、呼び出す為のページ名を入力
$pdo = connect_to_db();  //関数の実行

// データ取得SQL作成  ※phpMyAdminで登録した全てのテーブルの内容を取り出す。WHEREで一部取り出せる
// 例： 'SELECT * FROM to_table WHERE name → G's太郎

$sql = 'SELECT * FROM login_table';

// SQL準備&実行
$stmt = $pdo->prepare($sql); // sql文をstmtへ渡す処理
$status = $stmt->execute();

// データ登録処理後
if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
  // fetchAll()関数でSQLで取得したレコードを配列で取得できる
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  //$outputの中にphpMyAdominのテーブルの内容をいれる。
  $output = ""; //これから使うので（以下参照）空で定義
  // <tr><td>name</td><td>password</td><tr>の形になるようにforeachで順番に$outputへデータを追加
  // `.=`は後ろに文字列を追加する，の意味
  foreach ($result as $record) {
    $output .= "<tr>";
    $output .= "<td>{$record["name"]}</td>";
    $output .= "<td>{$record["password"]}</td>";
    // edit deleteリンクを追加
    // 同ページのHTML部分に全てのidを割り振る(ここにデータが飛ぶ→$id = $_GET['id'];)。idを渡す為、?が必要。
    $output .= "<td><a href='user_edit.php?id={$record["id"]}'>edit</a></td>";
    $output .= "<td><a href='user_delete.php?id={$record["id"]}'>delete</a></td>";
    $output .= "</tr>";
  }
  // 上記のforeach文のまとめ
  // <tr>
  //  <td>
  //    {$record["name"]}  
  //  </td>
  //  <td>
  //    {$record["password"]}
  //  </td>
  //  <td>
  //    <a href='password_edit.php?id={$record["id"]}'>edit</a>
  //  </td>
  //  <td>
  //   <a href='password_delete.php?id={$record["id"]}'>delete</a>
  //  </td>
  // </tr>

  // $valueの参照を解除する．解除しないと，再度foreachした場合に最初からループしない
  // 今回は以降foreachしないので影響なし
  unset($record);
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DB連携型userリスト（一覧画面）</title>
</head>

<body>
  <fieldset>
    <legend>DB連携型userリスト（一覧画面）</legend>
    <a href="login_input.php">入力画面</a>
    <table>
      <thead>
        <tr>
          <th>name</th>
          <th>password</th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <!-- ここに<tr><td>name</td><td>password</td><tr>の形でデータが入る -->
        <!-- "?="は『?php echo="" ?』の略 -->
        <?= $output ?>
      </tbody>
    </table>
  </fieldset>
</body>

</html>