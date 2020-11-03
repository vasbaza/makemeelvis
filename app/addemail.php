<?php
$dbc = mysqli_connect(getenv('HOST'),
    getenv('USR_PASSWORD'),
    getenv('USR_NAME'),
    getenv('DB_NAME'))
or die('Ошибка соединения с MySQL-Server');


$first_name = $_POST['firstname'];
$last_name = $_POST['lastname'];
$email = $_POST['email'];

$query = "insert into email_list (first_name, last_name, email) 
        values('$first_name', '$last_name', '$email')";

mysqli_query($dbc, $query)
or die("Ошибка при выполнении запроса к базе данных");

echo 'Customer added';

mysqli_close($dbc);

?>