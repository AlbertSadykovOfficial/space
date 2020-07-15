<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Document</title>
		<link rel="stylesheet" href="../../css/config.css">
		<link rel="stylesheet" href="../../css/style.css">
	</head>
	<script type="text/javascript">
			function change_this_project_name(project_id)
			{
				output = "<form method='POST' action='project_settings.php?project_id="+project_id+"'>"+
							"Название:<br>"+
								"<input type='text' name='changeProjectName' 	placeholder='New Name'><br>"+
							"<input type='submit' value='Применить'>"+
							"</form>";
				document.getElementById('change_this_project_name_id').innerHTML = output;			
			}
			function change_this_project_pass(project_id)
			{
				output = "<form method='POST' action='project_settings.php?project_id="+project_id+"'>"+
							"Новый Пароль:<br>"+
								"<input type='password' name='changeProjectPass1' placeholder='New Password'><br>"+
								"<input type='password' name='changeProjectPass2' placeholder='Repeat New Password'><br>"+
							"<input type='submit' value='Применить'>"+
							"</form>";
				document.getElementById('change_this_project_pass_id').innerHTML = output;			
			}

			function delete_user(project_id)
			{
				output ="<form method='POST' action='project_settings.php?project_id="+project_id+"'>"+
									"#<input type='text' name='delete_user_from_project' placeholder='user id' style='width:60px;'>"+
									"<input type='submit' value='удалить'>"+
								"</form>";
				document.getElementById('delete_user').innerHTML = output;
			}

	</script>
	<body>
		<?php 	 
			session_start();
			require_once("../templates/functions.php");
			$user_id 					= $_SESSION['user_id'];
			$project_name 		= $_SESSION['executable_project_name'];
			$project_id   		= $_SESSION['executable_project_id'];
			$project_id_get		= $_GET['project_id'];

			$admin 						= queryMySQL("SELECT admin FROM projects WHERE id=$project_id AND admin=$user_id")->num_rows;

				if ($project_id !== $project_id_get) 
				{
					die("Access Denied!<br>You are not logged in to this project<br>Please, back to profile and Log in to the project<br><a href='../profile/profile.php?view_id=$user_id'>Back to profile</a>");
				}

				if ($admin) 
				{
						if (isset($_POST['changeProjectName']) && $_POST['changeProjectName'] != '') 
						{
							$project_id 		= $_GET['project_id'];
							$project_name 	= $_POST['changeProjectName'];
							changeThisProjectName($project_id,$project_name);
							$_POST['changeProjectName'] = null; 
							$_SESSION['executable_project_name'] = $project_name;
						}

						if ($_POST['changeProjectPass1'] && $_POST['changeProjectPass1'] != '') 
						{
							$project_id 		= $_GET['project_id'];
							$project_pass1 	= $_POST['changeProjectPass1'];
							$project_pass2 	= $_POST['changeProjectPass2'];
							if ($project_pass1 == $project_pass2) 
							{
								changeThisProjectPassword($project_id,$project_pass1);
							}
						}

						if (isset($_POST['delete_user_from_project']) && $_POST['delete_user_from_project'] != '') 
						{
							if ($_POST['delete_user_from_project'] == $user_id) 
								echo "Вы не можете исключить себя";
							else
								deleteUserFromProject($project_id,$_POST['delete_user_from_project']);
						
						}

						echo "<div class='main'>".
						
						 		 "Изменить данные проекта $project_name:<br>".
									"<div id='change_this_project_name_id'>". 
									"<button onclick='change_this_project_name($project_id_get)'>Изменить Название</button>".
								 "</div>".

								 "<div id='change_this_project_pass_id'>". 
									"<button onclick='change_this_project_pass($project_id_get)'>Изменить Пароль</button>".
								 "</div>".

								 "<div id='delete_this_project_id'>". 
									"<a href='delete_project.php?project_id=$project_id_get'>Удалить проект</a>".
									"</div><br>".

								 "Информация о проекте $project_name:<br>";
								 

									$all_users = queryMySQL("SELECT user,id FROM members WHERE id IN (SELECT user_id FROM users_projects WHERE project_id=$project_id)");
									$how = $all_users->num_rows;
									echo "Все участники($how):<br>";
									for ($i=1; $i <= $how; $i++) 
									{ 
										$data = $all_users->fetch_array(MYSQLI_ASSOC);
										$user_name = $data['user'];
										$user_id	 = $data['id'];
										echo "$user_name (#$user_id)<br>";
									}
						echo "<div id='delete_user'>".
									"<button onclick='delete_user($project_id_get)'>Управление</button>".
								 "</div>";

						echo "</div>";
				}
		?>
	</body>
</html>