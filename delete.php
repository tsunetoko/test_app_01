<?php
$dsn = 'mysql://b595421a0f284e:646bdea7@us-cdbr-east-06.cleardb.net/heroku_c18254f736ad8c3?reconnect=true';
$user = 'root';
$passwd = 'root';
try {
    $pdo = new PDO($dsn, $user, $passwd);
    $sql_delete = 'DELETE FROM products WHERE id = :id';
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $stmt_delete->execute();
    $count = $stmt_delete->rowCount();
    $message = "商品を{$count}件削除しました。";
    header("Location: read.php?message={$message}");
} catch (PDOException $e) {
    exit($e->getMessage());
}
?>