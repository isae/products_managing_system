<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Product management</title>
    <link rel="stylesheet" href="plugins/bootstrap-3.3.5-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css">
    <script src="plugins/jquery-2.1.4.min.js"></script>
    <script src="plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    <script src="common/common.js"></script>
    <script src="common/table_settings.js"></script>
</head>
<body>
<?php
require_once 'db/db_credentials.php';
require_once 'db/memcached_work.php';
require_once 'db/db_actions_common.php';
$db = mysqli_connect(HOST, USER, PASSWORD, DATABASE, PORT);
?>

<?php if (mysqli_connect_error()): ?>
    <div class="alert alert-danger" role="alert">Отсутствует соединение с базой</div>
<?php endif; ?>

<?php
mysqli_real_query($db, "SET NAMES 'utf8'");
$page = 0;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}
$resource = mysqli_query($db, "SELECT COUNT(*) FROM products");
$rows = mysqli_fetch_row($resource)[0];
$from = $recordsOnPage * $page;
$maxPage = (floor($rows / $recordsOnPage)) - 1;
if ($rows % $recordsOnPage != 0) {
    ++$maxPage;
}
$sort_by = 'productID';
if (isset($_GET['sort_by'])) {
    $sort_by = $_GET['sort_by'];
    if ($sort_by != 'productID' && $sort_by != 'price') $sort_by = 'productID';
}
$order = 'asc';
if (isset($_GET['order'])) {
    $order = $_GET['order'];
    if ($order != 'asc' && $order != 'desc') $order = 'asc';
}
$query = "SELECT * FROM products ORDER BY " . $sort_by . " " . $order . " LIMIT $from, $recordsOnPage;";
if ($memcache_enabled) {
    $result = getPageFromCache($query);
    if (!$result) {
        mysqli_real_query($db, $query);
        $result = mysqli_fetch_all($db->store_result());
        putPageToCache($query, $result);
    }
} else {
    mysqli_real_query($db, $query);
    $result = mysqli_fetch_all($db->store_result());
}
?>

<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel">

    <form>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addProductModalLabel">Добавление товара</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group" id="alertZone">

                    </div>
                    <div class="form-group">
                        <label for="formInput1">Название</label>
                        <input type="text" class="form-control" id="formInput1" placeholder="Название">
                    </div>
                    <div class="form-group">
                        <label for="formInput2">Описание</label>
                        <textarea class="form-control" id="formInput2" placeholder="Описание" rows="3"></textarea>
                    </div>
                    <div class="form-group" style="width: 40%;">
                        <label for="formInput3">Цена</label>

                        <div class="input-group">
                            <input type="text" class="form-control" id="formInput3" placeholder="0">

                            <div class="input-group-addon">.00 руб</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="formInput4">Ссылка на изображение</label>
                        <input type="text" class="form-control" id="formInput4">
                    </div>
                    <div class="form-group" id="formInput5">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button id="productAddButton" type="button" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal fade" id="maybeDeleteModal" tabindex="-1" role="dialog" aria-labelledby="maybeDeleteModalLabel">

    <form>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="maybeDeleteModalLabel"></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button id="productDeleteButton" type="button" class="btn btn-danger">Удалить</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="container">
    <div id="globalAlert">
    </div>
    <div class="row" style="padding: 10px 0 10px 0;">
        <div class="col-xs-2">
            <button type="button" id="openModalButton" class="btn btn-primary">
                Добавить товар
            </button>
        </div>
        <div class="col-xs-3">
        </div>
        <div class="col-xs-3">
            <h4><?= $recordsOnPage ?> записей на странице</h4>
        </div>
        <div class="col-xs-3">
            <h4>Страница <?= $page + 1 ?> из <?= $maxPage + 1 ?></h4>
        </div>
        <div class="col-xs-1">
            <div class="row">
                <div class="btn-group" role="group">
                    <a type="button"
                       class="btn btn-default <?php if ($page == 0): ?>disabled<?php endif; ?>"
                       aria-label="Backward"
                       href="./?page=<?= $page - 1 ?>&sort_by=<?= $sort_by ?>&order=<?= $order ?>">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    </a>
                    <a type="button"
                       class="btn btn-default <?php if ($page == $maxPage): ?>disabled<?php endif; ?>"
                       aria-label="Forward"
                       href="./?page=<?= $page + 1 ?>&sort_by=<?= $sort_by ?>&order=<?= $order ?>">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>ProductID
                <a href="#" onclick="changeOrder('productID','<?= $sort_by ?>','<?= $order ?>',<?= $page ?>);">
                <span style="color: grey" class="glyphicon
                    <?php if ($sort_by == "price"): ?>glyphicon-sort<?php endif; ?>
                    <?php if ($sort_by == "productID" && $order == "asc"): ?>glyphicon-sort-by-attributes<?php endif; ?>
                    <?php if ($sort_by == "productID" && $order == "desc"): ?>glyphicon-sort-by-attributes-alt<?php endif; ?>
                    pull-right" aria-hidden="true">
                </span>
                </a>
            </th>
            <th>Название</th>
            <th>Описание</th>
            <th>Цена
                <a href="#" onclick="changeOrder('price','<?= $sort_by ?>','<?= $order ?>',<?= $page ?>);">
                <span style="color: grey" class="glyphicon
                    <?php if ($sort_by == "productID"): ?>glyphicon-sort<?php endif; ?>
                    <?php if ($sort_by == "price" && $order == "asc"): ?>glyphicon-sort-by-attributes<?php endif; ?>
                    <?php if ($sort_by == "price" && $order == "desc"): ?>glyphicon-sort-by-attributes-alt<?php endif; ?>
                    pull-right" aria-hidden="true">
                </span>
                </a>
            </th>
            <th>Изображение</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($result as $row) : ?>
            <tr class="editableRow">
                <td style="width:10%" class="prodID"><?= $row[0] ?></td>
                <td class="prodName"><?= $row[1] ?></td>
                <td class="prodDescription"><?= $row[2] ?></td>
                <td style="width:7%" class="prodPrice"><?= $row[3] ?></td>
                <td class="prodImg"><img class="visible-lg" style="width:200px;" src="<?= $row[4] ?>"/></td>
                <td class="prodButtons" style="width:100px;">
                    <button type="button" class="btn btn-default center-block" aria-label="Редактировать">
                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-default center-block" aria-label="Удалить">
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php $db->close(); ?>