<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
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
            $check = "";
            $sql = 'SELECT * FROM usertable';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                if ($row['user'] == $_POST['user'] && $row['password'] == $_POST['password']) {
                    session_start();
                    $_SESSION['user'] = $_POST['user'];
                    header("Location: ../user/mypage.php");
	                exit;
                }
            } echo "ユーザーが存在しないか、パスワードが正しくありません<br>";
        }
    ?>
    <form method="post" action="">ログイン<br>
        <input type="text" name="user" placeholder="ユーザー名"><br>
        <input type="text" name="password" placeholder="パスワード"><br>
    	<input type="submit" name="submit">
    </form>
    <a href="./registration/mail_input.php">新規ユーザー登録</a>
</body>
</html>
