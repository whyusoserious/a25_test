<?php
require_once 'sdbh.php';
$dbh = new sdbh();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productId']) && isset($_POST['days'])) {
    $productId = $_POST['productId'];
    $days = $_POST['days'];

    // Выполнение запроса для получения стоимости по ID продукта
    $query = "SELECT TARIFF, PRICE FROM a25_products WHERE ID = $productId";
    $result = $dbh->query_exc($query);

    $row = $result->fetch_assoc();
    $tariff = $row['TARIFF'];
    $price = $row['PRICE'];

    // Проверка наличия значения в 'tariff' и возврат стоимости или цены
    if (!is_null($tariff)) {
        $tariff = unserialize($tariff);
        $cost = null;
        foreach ($tariff as $k => $p) {
            if ($days >= $k) {
                $cost = $p;
            } else {
                break;
            }
        }
        echo $cost;
    } else {
        echo $price;
    }

} else {
    echo "Invalid request";
}

