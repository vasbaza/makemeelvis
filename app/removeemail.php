<img src="blankface.jpg" width="161" height="350" alt="" style="float:right"/>
<img name="elvislogo" src="elvislogo.gif" width="229" height="32" border="0" alt="Make Me Elvis"/>
<p>Выберете, пожалуйста, адреса электронной почты, которые вы хотите удалить из листа рассылки
    и нажмите кнопку "Удалить".</p>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

    <?php
    $dbc = mysqli_connect(getenv('HOST'),
        getenv('USR_PASSWORD'),
        getenv('USR_NAME'),
        getenv('DB_NAME'))
    or die('Ошибка соединения с MySQL-Server');

    // Удаление записей только в том случае, если форма была отправлена на сервер для выполнения

    if (isset($_POST['Удалить'])) {
        foreach ($_POST['todelete'] as $delete_id) {
            $query = "delete from email_list where id = $delete_id";
            mysqli_query($dbc, $query)
            or die('Ошибка запроса к базе данных');
        }
        echo 'Покупатель(ли) удален(ы).<br />';
    }

    // Ввод записей покупателей вместе с кнопками с независимой фиксацией
    // для отметки удаляемых пользователей

    $query = "select * from email_list";
    $result = mysqli_query($dbc, $query);

    while ($row = mysqli_fetch_array($result)) {
        echo '<input type="checkbox" value = "' . $row['id'] . '" name = "todelete[]" />';
        echo $row['first_name'];
        echo ' ' . $row['last_name'];
        echo ' ' . $row['email'];
        echo '<br />';
    }

    mysqli_close($dbc);
    ?>
    <input type="submit" name="Удалить" value="Удалить"/>
</form>
