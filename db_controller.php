<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/6/15
 * Time: 3:05 AM
 */
$name = $_REQUEST["name"];
$description = $_REQUEST["description"];
$cost = $_REQUEST["cost"];
$link = $_REQUEST["link"];
printf("%s %s %s %s",$name,$description,$cost,$link);
?>