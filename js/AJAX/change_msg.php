<?php
    session_start();

    $dbhost = 'localhost';
    $dbname = 'space_db';	// Должна быть создана
    $dbuser = 'albert';
    $dbpass = 'pass';

    $connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    if ($connection->connect_error) die($connection->connect_error);
	
    function queryMySQL($query)
    {
        global $connection; // Обращение к подкючению БД вне функции
        $result = $connection->query($query);
        if(!$result) die ($connection->error);

        return $result;
    }

    function sanitizeString($var)
    {
        global $connection;

        $var = strip_tags($var);
        $var = htmlentities($var);
        $var = stripslashes($var);

        return $connection->real_escape_string($var);
    }
?>

<?php
    if (isset($_POST['msg']))
    {
        $id  = $_POST['id'];
        $msg = sanitizeString($_POST['msg']);
        queryMySQL("UPDATE members SET msg='$msg' WHERE id='$id'");
    }
?>