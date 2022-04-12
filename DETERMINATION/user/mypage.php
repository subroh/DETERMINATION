<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>マイページ</title>
</head>
<body>
    <?php
    session_start();
    if (!empty($_SESSION['user'])) {
        echo "ようこそ、". $_SESSION['user'] . "さん<br>";
        echo "<hr>";

        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        $sql = "CREATE TABLE IF NOT EXISTS posttable"
            ."("
                . "id INT AUTO_INCREMENT PRIMARY KEY,"
                . "name char(32),"
                . "trouble TEXT,"
                . "choice_1 TEXT,"
                . "choice_2 TEXT,"
                . "decision TEXT,"
                . "myid INT,"
                . "good INT"
            .");";
        $pdo->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS likestable"
            ."("
                . "id INT AUTO_INCREMENT PRIMARY KEY,"
                . "like_user char(32),"
                . "like_post INT"
            .");";
        $pdo->query($sql);

        if (!empty($_POST["trouble"]) && !empty($_POST["choice_1"]) && !empty($_POST["choice_2"])) {
            //投稿モード
            $sql = "INSERT INTO posttable (name, trouble, choice_1, choice_2, decision) VALUES (:name, :trouble, :choice_1, :choice_2, :decision)";
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(':name', $_SESSION["user"], PDO::PARAM_STR);
            $stmt -> bindParam(':trouble', $_POST["trouble"], PDO::PARAM_STR);
            $stmt -> bindParam(':choice_1', $_POST["choice_1"], PDO::PARAM_STR);
            $stmt -> bindParam(':choice_2', $_POST["choice_2"], PDO::PARAM_STR);
            $stmt -> bindParam(':decision', $ramdom, PDO::PARAM_STR);
            if (rand(1,2) == 1) $ramdom = $_POST["choice_1"];
            else $ramdom = $_POST["choice_2"];
            $stmt -> execute();
        }

        if (!empty($_POST["delete"])) {
            //投稿を削除
            $sql = 'DELETE FROM posttable WHERE myid=:myid AND name=:name';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':myid', $_POST['delete'], PDO::PARAM_INT);
            $stmt->bindParam(':name', $_SESSION['user'], PDO::PARAM_STR);
            $stmt->execute();
        }

        //連番へ→myidの定義
        $sql = 'SELECT * FROM posttable';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        $num = 1;
        foreach ($results as $row){
            if ($row['name'] == $_SESSION['user']) {
                $sql = 'UPDATE posttable SET myid=:myid WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':myid', $num, PDO::PARAM_INT);
                $stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
                $stmt->execute();
                $num++;
            }
        }

        echo "自分の投稿<br>";
        $sql = 'SELECT * FROM posttable';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            if ($row['name'] == $_SESSION['user']) {
                $sql = "SELECT count(*) FROM likestable WHERE like_post=:like_post";
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindParam(':like_post', $row['id'], PDO::PARAM_INT);
                $stmt -> execute();
                $count = $stmt->fetchColumn();

                echo $row['myid'].' ';
                echo $row['trouble'].' ';
                echo "A." . $row['choice_1'].' ';
                echo "B." . $row['choice_2']. ' ';
                echo "結論:" . $row['decision']. "に決定！";
                echo "(" . $count. "いいね) <br>";
            }
        }
        echo "<hr>"; ?>
        <form method="post" action="">投稿フォーム<br>
        	<input type="text" name="trouble" placeholder="悩み"><br>
        	<input type="text" name="choice_1" placeholder="選択肢1"><br>
        	<input type="text" name="choice_2" placeholder="選択肢2"><br>
        	<input type="submit" name="submit">
        </form>
        <form method="post" action="">削除フォーム<br>
        	<input type="number" name="delete" min=1 placeholder="削除対象番号"><br>
        	<input type="submit" name="submit">
        </form>
        <hr>
        <a href="./allpost.php">投稿一覧</a>
        <form method="post" action="" name="logout">
            <input type="hidden" name="logout" value="ログアウト">
            <a href="#" onclick="document.logout.submit();">ログアウト</a>
        </form>
        <?php
        if (!empty($_POST['logout'])){ //ログアウトリンクが押されたら
            $_SESSION = array();//セッションの中身をすべて削除
            session_destroy();//セッションを破壊
            header("Location: ../account/login.php");
        }
    } else echo 'ログアウトされています<br>'; ?>
</body>
</html>
