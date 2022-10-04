<?php 
$dsn = 'mysql:dbname=heroku_c18254f736ad8c3;host=us-cdbr-east-06.cleardb.net;charset=utf8mb4';
$user = 'b595421a0f284e';
$passwd = '646bdea7';
try {
    $pdo = new PDO($dsn, $user, $passwd);
    // orderパラメータの値が存在すれば(並び替えボタンを押した時)その値を変数$orderに代入する
    if (isset($_GET['order'])){
        $order = $_GET['order'];
    } else {
        $order = NULL;
    }
    // keywordパラメータの値が存在すれば(商品名を検索した時)、その値を＄orderに代入する
    if (isset($_GET['keyword'])) {
        $keyword = $_GET['keyword'];
    } else {
        $keyword = NULL;
    }
    // orderパラメータの値によってSQL文を変更する
    if ($order === 'desc') {
        $sql_select = 'SELECT * FROM products WHERE product_name LIKE :keyword ORDER BY updated_at DESC';
    } else {
        $sql_select = 'SELECT * FROM products WHERE product_name LIKE :keyword ORDER BY updated_at ASC';
    }
    // SQL文を用意する
    $stmt_select = $pdo->prepare($sql_select);
    $partial_match = "%{$keyword}%";
    // bindValue()メソッドを使って実際の値をプレースホルダにバインドする
    $stmt_select->bindValue('keyword', $partial_match, PDO::PARAM_STR);
    // SQL文を実行する
    $stmt_select->execute();
    // 上記sqlの実行結果を配列で取得する
    $producs = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    exit ($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- google fontsの読み込み -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <a href="index.php">商品管理アプリ</a>
        </nav>
    </header>
    <main>
        <article class="products">
            <h1>商品一覧</h1>
            <?php
            // 商品の登録、編集、削除後、messageパラメータの値を受け取っていればそれを表示する
            if (isset($_GET['message'])) {
                echo "<p class='success'>{$_GET['message']}</p>";
            } 
            ?>
            <div class="products-ui">
                <div>
                    <a href="read.php?order=desc&keyword=<?= $keyword ?>" >
                        <img src="images/desc.png" alt="降順に並び替え" class="sort-img">
                    </a>
                    <a href="read.php?order=asc&keyword=<?= $keyword ?>">
                        <img src="images/asc.png" alt="昇順に並び替え" class="sort-img">
                    </a>
                    <form action="read.php" method="get" class="search-form">
                        <input type="hidden" name="order" value="<?= $order ?>">
                        <input type="text" class="search-box" placeholder="商品名で検索" name="keyword" value="<?= $keyword ?>">
                    </form>
                </div>
                <a href="create.php" class="btn">商品登録</a>
            </div>
            <table class="products-table">
                <tr>
                    <th>商品コード</th>
                    <th>商品名</th>
                    <th>単価</th>
                    <th>在庫数</th>
                    <th>仕入先コード</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
                <?php 
                // 配列の中身を順番に取り出し、表形式で出力する
                foreach ($producs as $product) {
                    $table_row = "
                    <tr>
                        <td>{$product['product_code']}</td>
                        <td>{$product['product_name']}</td>
                        <td>{$product['price']}</td>
                        <td>{$product['stock_quantity']}</td>
                        <td>{$product['vendor_code']}</td>
                        <td><a href='update.php?id={$product['id']}'><img src='images/edit.png' alt='編集' class='edit-icon'></a></td>
                        <td><a href='delete.php?id={$product['id']}'><img src='images/delete.png' alt='削除' class='delete-icon'></a></td>
                    </tr>
                    ";
                    echo $table_row;
                }
                ?>
            </table>
        </article>
    </main>
    <footer>
        <p class="copyright">&copy; 商品管理アプリ All rights reserved.</p>
    </footer>
</body>
</html>