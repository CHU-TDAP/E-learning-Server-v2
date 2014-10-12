<?php
require_once __DIR__.'/../config.php';
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>專案設定資訊</title>
    </head>
    <body>
        <h1>系統資訊</h1>
        <ul>
            <li>主機名稱: <?php echo gethostname() ?></li>
        </ul>
        
        <h1>專案設定資訊</h1>
        <ul>
            <li>資料庫類型: <?php echo DB_TYPE ?></li>
            <li>資料庫位址: <?php echo DB_HOST ?></li>
            <li>資料庫連結埠: <?php echo DB_PORT ?></li>
            <li>資料庫連結帳號: <?php echo DB_USER ?></li>
            <li>資料庫名稱: <?php echo DB_NAME ?></li>
            <li>資料庫內資料表前綴字串: <?php echo DB_PREFIX ?></li>
        </ul>
    </body>
</html>