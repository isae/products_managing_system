<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/6/15
 * Time: 3:05 AM
 */
require 'db_credentials.php';
require 'db_actions_common.php';
$msg = array('type' => '', 'message' => '');
$ok = true;
$db = mysqli_connect(HOST, USER, PASSWORD, DATABASE, PORT) or die('Could not connect: ' . mysql_error());
$stmt = $db->prepare("INSERT INTO products(name, description, price, imageUrl) VALUES (?,?,?,?)");
if (!$stmt) {
    $ok = false;
    $msg['message'].= "Не удалось подготовить запрос: (" . $db->errno . ") " . $db->error;
} else {
    if (!$stmt->bind_param("ssis", $name,$description,$cost,$link)) {
        $ok = false;
        $msg['message'].= "Не удалось привязать параметры: (" . $stmt->errno . ") " . $stmt->error;
    }
    if (!$stmt->execute()) {
        $ok = false;
        $msg['message'].= "Не удалось выполнить запрос: (" . $stmt->errno . ") " . $stmt->error;
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