<?php
require 'db_credentials.php';
require 'db_actions_common.php';
require 'memcached_work.php';
clearCache();
$msg = array('type' => '', 'message' => '');
$ok = true;
$db = mysqli_connect(HOST, USER, PASSWORD, DATABASE, PORT) or die('Could not connect: ' . mysql_error());
mysqli_real_query($db, "SET NAMES 'utf8'");
$stmt = $db->prepare("INSERT INTO products(name, description, price, imageUrl) VALUES (?,?,?,?)");
if (!$stmt) {
    $ok = false;
    $msg['message'] .= "Не удалось подготовить запрос: (" . $db->errno . ") " . $db->error;
} else {
    if (!$stmt->bind_param("ssis", $name, $description, $cost, $link)) {
        $ok = false;
        $msg['message'] .= "Не удалось привязать параметры: (" . $stmt->errno . ") " . $stmt->error;
    }
    if (!$stmt->execute()) {
        $ok = false;
        $msg['message'] .= "Не удалось выполнить запрос: (" . $stmt->errno . ") " . $stmt->error;
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