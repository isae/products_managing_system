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
</head>
<body>
<?php
require_once 'db_credentials.php';
$db = mysqli_connect(HOST, USER, PASSWORD, DATABASE, PORT) or die('Could not connect: ' . mysql_error());
mysqli_real_query($db, "SET NAMES 'utf8'");
mysqli_real_query($db, "SELECT * FROM products WHERE 1");
?>
<div class="container">
    <div id="globalAlert">

    </div>
    <button type="button" id="openModalButton" class="btn btn-primary btn-lg" style="margin: 10px 0 10px 0;">
        Добавить товар
    </button>

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
                        <button id="productAddButton" type="button" type="submit" class="btn btn-primary">Сохранить</button>
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
                        <button id="productDeleteButton" type="button" type="submit" class="btn btn-danger">Удалить</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <table class="table">
        <thead>
        <th>ProductID</th>
        <th>Название</th>
        <th>Описание</th>
        <th>Цена</th>
        <th>Изображение</th>
        <th>Действия</th>
        </thead>
        <tbody>
        <?php if ($result = $db->use_result()): ?>
            <?php while ($row = $result->fetch_row()): ?>
                <tr class="editableRow">
                    <td class="prodID"><?= $row[0] ?></td>
                    <td class="prodName"><?= $row[1] ?></td>
                    <td class="prodDescription"><?= $row[2] ?></td>
                    <td class="prodPrice"><?= $row[3] ?></td>
                    <td class="prodImg"><img src="<?= $row[4] ?>"/></td>
                    <td class="prodButtons">
                        <button type="button" class="btn btn-default" aria-label="Редактировать">
                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-default" aria-label="Удалить">
                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>