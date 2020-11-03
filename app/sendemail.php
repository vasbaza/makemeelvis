<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Make Me Elvis - Send Email</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<img src="blankface.jpg" width="161" height="350" alt="" style="float:right"/>
<img name="elvislogo" src="elvislogo.gif" width="229" height="32" border="0" alt="Make Me Elvis"/>
<p><strong>Private:</strong>ТОЛЬКО для покупателей Элмера<br/>
    Составьте и отправьте электронное письмо для покупателей, внесенный в лист рассылки.</p>

<?php
$pSubject = '';
$text = '';
if (isset($_POST['Submit'])) {

    require_once '../vendor/autoload.php';

    $pEmailGmail = getenv('MAIL_NAME');
    $pPasswordGmail = getenv('MAIL_PASSWORD');

    $transport = new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
    $transport->setUsername($pEmailGmail)->setPassword($pPasswordGmail);

    $pFromName = 'Elvis';
    $pSubject = $_POST['subject'];
    $text = $_POST['elvismail'];
    $output_form = false;

    if (empty($pSubject) && empty($text)) {
        echo 'Вы забыли ввести тему и содержание письма.<br/>';
        $output_form = true;
    }
    if (empty($pSubject) && (!empty($text))) {
        echo 'Вы забыли ввести тему письма.<br/>';
        $output_form = true;
    }
    if ((!empty($pSubject)) && empty($text)) {
        echo 'Вы забыли ввести содержание письма.<br/>';
        $output_form = true;
    }
    if ((!empty($pSubject)) && (!empty($text))) {

        $dbc = mysqli_connect(getenv('HOST'),
            getenv('USR_PASSWORD'),
            getenv('USR_NAME'),
            getenv('DB_NAME'))
        or die('Ошибка соединения с MySQL-Server');

        $query = "select * from email_list";

        $result = mysqli_query($dbc, $query)
        or die("Ошибка при выполнении запроса к базе данных");


        while ($row = mysqli_fetch_array($result)) {
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $to = $row['email'];

            $msg = "Уважаемый $first_name $last_name, \n $text";

            $mMailer = new Swift_Mailer($transport);
            $mEmail = new Swift_Message();
            $mEmail->setSubject($pSubject);
            $mEmail->setTo($to);
            $mEmail->setFrom(array($pEmailGmail => $pFromName));
            $mEmail->setBody($msg, 'text/html');
            $mMailer->send($mEmail);

            echo 'Электронное письмо отправлено: ' . $to . '<br />';
        }
        mysqli_close($dbc);
    }
} else {
    $output_form = true;
}
if ($output_form) {
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="subject">Тема электронного письма</label><br/>
    <input id="subject" name="subject" type="text" size="30"
           value="<?php echo $pSubject; ?>"/><br/>
    <label for="elvismail"> Содержание электронного письма</label><br/>
    <textarea id="'elvismail" name="elvismail" rows="8" cols="40"><?php echo $text; ?></textarea><br/>
    <input type="Submit" name="Submit" value="Отправить"/>
</form>
<?php
}
?>