<?php 
    $dbhost = 'localhost';
    $dbname = 'space_db';	// Должна быть создана
    $dbuser = 'albert';
    $dbpass = 'pass';

    $appname = 'SPACE'; // Название соц сети


    $connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    if ($connection->connect_error) die($connection->connect_error);

    function createTable($name, $query)
    {
        queryMySQL("CREATE TABLE IF NOT EXISTS $name($query)");
        echo "Таблица $name создана или уже существала<br>";
    }
 
    function queryMySQL($query)
    {
        global $connection; // Обращение к подкючению БД вне функции
        $result = $connection->query($query);
        if(!$result) die ($connection->error);

        return $result;
    }

    function destroySession()
    {
        $_SESSION = array();																							// Опустошаем сессию

        if (session_id() != '' || isset($_COOKIE[session_name()])) 
            setcookie(session_name(), '', time()-2592000,'/');								// Просрачиваем cookie

        session_destroy();
    }

    function sanitizeString($var)
    {
        global $connection;

        $var = strip_tags($var);
        $var = htmlentities($var);
        $var = stripslashes($var);

        return $connection->real_escape_string($var);
    }

    function deleteThisProject($project_id, $project_name, $project_pass, $user_id)
    {
        $delete = queryMySQL("SELECT * FROM projects 
                                    WHERE id = $project_id 
                                    AND name = '$project_name' 
                                    AND pass = 'project_pass' 
                                    AND admin=$user_id");
        if ($delete->num_rows) 
        {
            $pj_id = $project_id.'_';
            queryMySQL("DELETE FROM users_projects WHERE project_id = '$project_id'");
            queryMySQL("DELETE FROM report WHERE id LIKE '$pj_id%'");
            queryMySQL("DELETE FROM list 	 WHERE id LIKE '$pj_id%'");

            queryMySQL("DELETE FROM projects WHERE project_id = '$project_id'");
        }
    }

    function changeThisProjectName($project_id, $project_name)
    {
        queryMySQL("UPDATE projects SET name ='$project_name' WHERE id=$project_id");
    }

    function changeThisProjectPassword($project_id, $project_pass)
    {
        queryMySQL("UPDATE projects SET pass ='$project_pass' WHERE id=$project_id");
    }

    function deleteUsersFromProject($project_id, $admin_id, $users_id, $action)
    {
        $ids = '';
        if (count($users_id)>0 && $admin_id != $users_id[0]) 
        {
            for ($i=0; $i < count($users_id); $i++)
            {
                if ($users_id[$i] != $admin_id) 
                {
                    $ids = $ids."'".$users_id[$i]."',";
                }
            }
            
            $ids = substr($ids,0,-1);
            
            if ($action === 'exclude')
                queryMySQL("DELETE FROM users_projects WHERE project_id='$project_id' AND user_id IN ($ids)");
            else if ($action === 'lock')
                queryMySQL("UPDATE users_projects SET is_blocked = true WHERE project_id='$project_id' AND user_id IN ($ids)");
            
            echo "Исключаю: $ids";
            
        }else echo 'I Didnt deleted/ anyone';
    }

    function UnlockUserInTheProject($project_id, $users_id)
    {
        queryMySQL("UPDATE users_projects SET is_blocked = false WHERE project_id='$project_id' AND user_id=$users_id");
    }
		
    function create_regexp($id, $level)
    {	
        return "\"^". $id.'_'.substr(str_repeat("[0-9]*_",$level),0,-1)."$\"";
    }
 ?>