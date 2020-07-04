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

		function showProfile($user_id)
		{
			if (file_exists("../../images/$user_id.jpg")) //$user
					echo "<img src = '../../images/$user_id.jpg' align = 'middle'>";	
		
			//$result = queryMySQL("SELECT * FROM users_projects WHERE user_id = '$user'");

/*			if ($result->num_rows) 
			{
				$row = $result->fetch_array(MYSQLI_ASSOC);
				echo stripslashes($row['text']).
									"<br style = 'clear : left;'><br>";
			}
*/			
		}

		function showMyProjects($user_id)
		{
			$result = queryMySQL("SELECT * FROM users_projects WHERE user_id = '$user_id'");
			$num = $result->num_rows;
			echo "Найдено совпадений ($num) <br>";
			
			for ($i=1; $i <= $num ; $i++) 
			{ 
					$row = $result->fetch_array(MYSQLI_ASSOC);
					
					$out = $row['project_id'];
					$out = queryMySQL("SELECT * FROM projects WHERE id = '$out'")->fetch_array(MYSQLI_ASSOC)['name'];
					if ($out != NULL)
					{ 
						echo "$out";
						$out = $row['project_id'];
						echo " <a href='../project/project_in.php?project_id=$out'>(войти)</a> <a href='../project/delete_project.php?project_id=$out'>(удалить)</a><br>";
					}
			}
		}

		function deleteThisProject($project_id, $project_name, $project_pass, $user_id)
		{
			$delete = queryMySQL("SELECT * FROM projects WHERE id = $project_id AND name = '$project_name' AND pass = 'project_pass' AND admin=$user_id");
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

		function deleteUserFromProject($project_id, $user_id)
		{
			queryMySQL("DELETE FROM users_projects WHERE project_id='$project_id' AND user_id='$user_id'");
		}


		function create_regexp($level)
		{	
			return "\"^".substr(str_repeat("[0-9]*_",$level),0,-1)."$\"";
		}
	
 ?>