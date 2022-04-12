<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規ユーザー登録</title>
</head>
<body>
    <?php
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        $sql = "CREATE TABLE IF NOT EXISTS usertable"
            ."("
                . "id INT AUTO_INCREMENT PRIMARY KEY,"
                . "user char(32),"
                . "password char(32)"
            .");";
        $pdo->query($sql);

        if (!empty($_POST['user']) && !empty($_POST['password'])) {
            //登録済みデータとの比較
            $check = "";
            $sql = 'SELECT * FROM usertable';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                if ($row['user'] == $_POST["user"]) $check = $row["user"];
            }
            if (empty($check)) {
                //ユーザー名かぶり無し→登録
                $sql = "INSERT INTO usertable (user, password) VALUES (:user, :password)";
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindParam(':user', $_POST["user"], PDO::PARAM_STR);
                $stmt -> bindParam(':password', $_POST["password"], PDO::PARAM_STR);
                $stmt -> execute();

                //使用したurl内のランダム文字列をテーブルから消去(フラグ管理)
                $sql = 'DELETE FROM pre_usertable WHERE urltoken=:urltoken';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':urltoken', $_SERVER['QUERY_STRING'], PDO::PARAM_STR);
                $stmt->execute();

                header( "Location: ./complete.php" );
	            exit;
            } else echo "このユーザー名は使用されています<br>";
        }
    ?>
    <?php
    //url内のランダム文字列がテーブルに存在するかチェック
    $check = "";
    $sql = 'SELECT * FROM pre_usertable';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        if ($row['urltoken'] == $_SERVER['QUERY_STRING']) $check = $row["urltoken"];
    }

    if (!empty($check)) { //ランダム文字列が存在→まだurlが未使用 ?>
        <form method="post" action="">新規ユーザー登録<br>
            <input type="text" name="user" placeholder="ユーザー名"><br>
            <input type="text" name="password" placeholder="パスワード"><br>
        	<input type="submit" name="submit">
        </form> <?php
    } else echo "このURLは使用済みです<br>"; ?>
</body>
</html>
