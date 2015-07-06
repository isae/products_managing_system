<?php
require 'db_credentials.php';
require 'db_actions_common.php';
$id = $_POST["productID"];
$msg = array('type' => '', 'message' => '');
$ok = true;
$db = mysqli_connect(HOST, USER, PASSWORD, DATABASE, PORT) or die('Could not connect: ' . mysql_error());
$stmt = $db->prepare("DELETE FROM products WHERE productID=?");
if (!$stmt) {
    $ok = false;
    $msg['message'].= "Не удалось подготовить запрос: (" . $db->errno . ") " . $db->error;
} else {
    if (!$stmt->bind_param("i", $id)) {
        $ok = false;
        $msg['message'].= "Не удалось привязать параметры: (" . $stmt->errno . ") " . $stmt->error;
    }
    if (!$stmt->execute()) {
        $ok = false;
        $msg['message'].= "Не удалось выполнить запрос: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(mysqli_affected_rows($db)!=1){
        $ok = false;
        $msg['message'].= "Товар с ID ". $id ." в базе отсутствует";
    }
}
if($ok){
    $msg['type']="success";
    $msg['message']="Success!";
} else {
    $msg['type']="error";
}
echo json_encode($msg);
?>