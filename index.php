<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<?php
include_once 'db_credentials.php';
$mysqli = mysqli_connect(HOST, USER, PASSWORD, DATABASE, PORT);
$result = mysqli_real_query($mysqli, "SELECT * FROM products WHERE 1");

?>
<h1>Hello, world!</h1>
<table>
    <thead>
    <th>ProductID</th>
    <th>Name</th>
    <th>Description</th>
    <th>Price</th>
    <th>Image</th>
    </thead>
    <tbody>
    <?php
    do {
        if ($result = $mysqli->use_result()) {
            while ($row = $result->fetch_row()) {
                printf("<tr>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>",
                    $row[0], $row[1], $row[2], $row[3], $row[4]);
            }
            $result->close();
        }
    } while ($mysqli->next_result());
    ?>
    </tbody>
</table>

</body>
</html>