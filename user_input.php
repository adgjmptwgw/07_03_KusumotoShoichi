<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザー新規登録画面</title>
</head>

<body>
  <form action="user_create.php" method="POST">
    <fieldset>
      <legend>ユーザー新規登録画面</legend>
      <a href="user_read.php">一覧画面</a>
      <div>
        name: <input type="text" name="name">
      </div>
      <div>
        password: <input type="text" name="password">
      </div>
      <div>
        <button>submit</button>
      </div>
    </fieldset>
  </form>

</body>

</html>