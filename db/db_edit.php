<?php
require 'db_credentials.php';
require 'db_actions_common.php';
require 'memcached_work.php';
clearCache();
$id = $_POST["productID"];
$msg = array('type' => '', 'message' => '');
$ok = true;
$db = mysqli_connect(HOST, USER, PASSWORD, DATABASE, PORT);
if (mysqli_connect_error()) {
    $ok = false;
    $msg['message'] = "Отсутствует соединение с базой";
} else {
    mysqli_real_query($db, "SET NAMES 'utf8'");
    $stmt = $db->prepare("UPDATE products SET name=?, description=?, price=?, imageUrl=? WHERE productID=?");
    if (!$stmt) {
        $ok = false;
        $msg['message'] .= "Не удалось подготовить запрос: (" . $db->errno . ") " . $db->error;
    } else {
        if (!$stmt->bind_param("ssisi", $name, $description, $cost, $link, $id)) {
            $ok = false;
            $msg['message'] .= "Не удалось привязать параметры: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            $ok = false;
            $msg['message'] .= "Не удалось выполнить запрос: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (mysqli_affected_rows($db) != 1) {
            $ok = false;
            $msg['message'] .= "Товар с ID " . $id . " в базе отсутствует";
        }
    }
}
if ($ok) {
    $msg['type'] = "success";
    $msg['message'] = "Success!";
} else {
    $msg['type'] = "error";
}
echo json_encode($msg);
?>