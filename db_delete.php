<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/6/15
 * Time: 3:05 AM
 */
require 'db_actions_common.php';
printf("%s %s %s %s",$name,$description,$cost,$link);
$db = mysqli_connect(HOST, USER, PASSWORD, DATABASE, PORT) or die('Could not connect: ' . mysql_error());
mysqli_real_query($db, "SET NAMES 'utf8'");
mysqli_real_query($db, "SELECT * FROM products WHERE 1");
?>