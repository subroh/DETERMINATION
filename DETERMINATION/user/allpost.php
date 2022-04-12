<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>投稿一覧</title>
</head>
<body>
    <?php
    session_start();
    if (!empty($_SESSION['user'])) {
        //ユーザーIDと投稿IDを元にいいねの重複チェックを行う関数
        function is_exsist_like($like_user, $like_post){
            $dsn = 'データベース名';
            $user = 'ユーザー名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            $sql = "SELECT * FROM likestable WHERE like_user = :like_user AND like_post = :like_post";
            $stmt = $pdo->prepare($sql);
            $stmt -> bindParam(':like_user', $like_user, PDO::PARAM_STR);
            $stmt -> bindParam(':like_post', $like_post, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        }

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

        //いいね機能
        if (!empty($_POST['like_num'])) {
            $flg = 0; //$flg=0ならいいねする $flg=1ならいいね削除
            $sql = 'SELECT * FROM likestable';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //ログインユーザーが既にいいね済→$flg=1
                if ($row['like_user'] == $_SESSION['user'] && $row['like_post'] == $_POST['like_num']) $flg = 1;
            }
            if ($flg != 1) {
                //まだログインユーザーにいいねされていない→いいねテーブルに追加
                $sql = "INSERT INTO likestable (like_user, like_post) VALUES (:like_user, :like_post)";
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindParam(':like_user', $_SESSION["user"], PDO::PARAM_STR);
                $stmt -> bindParam(':like_post', $_POST['like_num'], PDO::PARAM_INT);
                $stmt -> execute();
            } else {
                //既にログインユーザーにいいねされている→いいねテーブルから削除
                $sql = 'DELETE FROM likestable WHERE like_user=:like_user AND like_post=:like_post';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':like_user', $_SESSION["user"], PDO::PARAM_STR);
                $stmt->bindParam(':like_post', $_POST['like_num'], PDO::PARAM_INT);
                $stmt->execute();
            }
        }

        echo "投稿一覧<br>";
        $sql = 'SELECT * FROM posttable';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['name'].' ';
            echo $row['trouble'].' ';
            echo "A." . $row['choice_1'].' ';
            echo "B." . $row['choice_2']. ' ';
            echo "結論:" . $row['decision']. "に決定！"; ?>
            <form action="" method="POST" style="display:inline">
            <input type=hidden name="like_num" value = "<?php echo $row['id'] ?>">
            <?php if (!is_exsist_like($_SESSION['user'],$row['id'])){ ?>
            <input type="submit" name="submit" value="いいね">
            <?php } else { ?>
            <input type="submit" name="submit" value="いいね解除">
            <?php } ?>
            </form> <br><?php
        }
        ?>
        <hr>
        <a href="./mypage.php">マイページ</a> <?php
    } else echo 'ログアウトされています<br>'; ?>
</body>
</html>
