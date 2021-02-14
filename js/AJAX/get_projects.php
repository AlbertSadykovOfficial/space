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
?>

<?php
		
    if (isset($_GET['project_name'])) 
    {
        $name = $_GET['project_name'];

        $result = queryMySQL("SELECT * FROM projects WHERE name LIKE '$name%' AND is_private = false LIMIT 10");


        if ($result->num_rows == 0) 
        {
            $output = '<div>По запросу ничего не найдено</div>';
        }

        for ($i=0; $i < $result->num_rows; $i++) 
        { 
            $project = $result->fetch_array(MYSQLI_ASSOC);
            $id = $project['id'];
            
            if ($project['pass'] === null)
                $output = $output."<div style='padding:0;' onclick=enter_to_project('$id',0)>".$project['name'].'</div>';
            else
                $output = $output."<div style='padding:0;' onclick=enter_to_project('$id',1)>".$project['name'].'</div>';
        }
        echo $output;
    }

    if (isset($_GET['project_key']))
    {
        $project_key = $_GET['project_key'];

        $result = queryMySQL("SELECT id,name FROM projects WHERE id IN (SELECT project_id FROM permission WHERE project_key='$project_key')");

        if ($result->num_rows) 
        {
            $project= $result->fetch_array(MYSQLI_ASSOC);
            $id 		= $project['id'];
            $output = $output."<div style='padding:0;' onclick=enter_to_project('$id',1)>".$project['name'].'</div>';
        }
        else
        {
            $output = 'Групп с таким ключом не найено';
        }

        echo $output;
    }
?>