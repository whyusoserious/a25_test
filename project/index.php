<?php
require_once 'backend/sdbh.php';
$dbh = new sdbh();

?>
<html>
    <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="assets/css/style.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"  crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="js/main.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row row-header">
                <div class="col-12">
                    <img src="assets/img/logo.png" alt="logo" style="max-height:50px"/>
                    <h1>Прокат</h1>
                </div>
            </div>
            <div class="row row-body">
                <div class="col-6">
                    <h4>Дополнительные услуги:</h4>
                    <ul>
                    <?
                        $services = unserialize($dbh->mselect_rows('a25_settings', ['set_key' => 'services'], 0, 1, 'id')[0]['set_value']);
                        foreach($services as $k => $s) { ?>
                            <li><?=$k?>: <?=$s?> в день</li>
                        <?}
                    ?>
                    </ul>
                </div>
                <div class="col-6">
                    <h4>Стоимость проката:</h4>
                    <ul>
                        <?
                            $products = $dbh->mselect_rows('a25_products', null, 0, 3, 'id');
                            foreach ($products as $product) {
                                $name = $product["NAME"];
                                $tariff = $product["TARIFF"];
                                $price = $product["PRICE"];

                                echo "<b>$name <br></b>";
                                if (!is_null($tariff)) {
                                    $tariffValues = unserialize($tariff);
                                    foreach ($tariffValues as $d => $p) {
                                        $d++;
                                        if ($d == 1 || $d == 31){
                                            echo "От $d дня за $p <br>";
                                        }
                                        else {
                                            echo "От $d дней за $p <br>";
                                        }


                                    }
                                } else {
                                    echo "От 1 дня за $price <br>";
                                }
                            }
                        ?>
                    </ul>
                </div>

            </div>
            <div class="row row-body form-calc">
                <div class="col-3">
                    <h4>Форма расчета:</h4>
                    <i class="bi bi-activity"></i>
                </div>
                <div class="col-9">
                    <form action="" id="form">
                        <label class="form-label" for="product">Выберите продукт:</label>
                        <select class="form-select" name="product" id="product">
                            <ul>
                                <?
                                    foreach ($products as $product) {
                                        $id = $product["ID"];
                                        $name = $product["NAME"];
                                        $tariff = $product["TARIFF"];
                                        $price = $product["PRICE"];

                                        $tooltip = "";
                                        foreach ($tariff as $days => $value) {
                                            $tooltip .= "от $days дней проката - $value<br>";
                                        }

                                        if (!is_null($tariff)) {
                                            $tariffValues = unserialize($tariff);
                                            $minTariff = min($tariffValues);
                                            echo "<option value='$id'>$name от $minTariff</option>";
                                        } else {
                                            echo "<option value='$id'>$name за $price</option>";
                                        }
                                    }
                                ?>
                            </ul>
                        </select>

                        <label for="customRange1" class="form-label">Количество дней:</label>
                        <input type="number" class="form-control" id="customRange1" min="1" max="30">

                        <label for="customRange1" class="form-label">Дополнительно:</label>
                        <?
                            $form_id = 0;
                            foreach($services as $k => $s) {
                                $form_id++?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="<?=$s?>" id="flexCheckChecked<?=$form_id?>" checked>
                                <label class="form-check-label" for="flexCheckChecked<?=$form_id?>">
                                    <?=$k?> за <?=$s?>
                                </label>
                            </div>
                            <?}
                        ?>
                        <button type="submit" class="btn btn-primary">Рассчитать</button>
                        <br>
                        <label for="totalCost1" class="form-label" id="finalCost">Итого:</label>
                        <span type="text" class="form-control" id="totalCost"></span>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>