<?php 

		require_once('../templates/header.php');

		if (!$loggedin) 
		{
			die("Вы не зарегистрированы<br><a href='../authentication/login.php'>Войти в аккаунт</a>");
		}

		echo "<div class='main'>";

		if (isset($_GET['view_id']))
		{
			$view_id = sanitizeString($_GET['view_id']);
			$user_id = $_SESSION['user_id'];
			
			if ($view_id == $user_id) $prefix = 'Моя';
			else 											$prefix = "$view";
			
			echo  "<br>$prefix Гланая страница <br><br>"; //"<a class='button' href='messages.php?view=$view'>НЕ СЮДА</a>".
			showProfile($view_id);
			
			echo "<br> Проекты пользователя:<br>";
						
						$result = queryMySQL("SELECT * FROM members WHERE id = '$view_id'");
							if ($result->num_rows)
							{
								$row = $result->fetch_array(MYSQLI_ASSOC);
								showMyProjects($row['id']);
							}else{
								echo "Вы пока не участвуете ни в одном проекте";
							}
							if ($view_id == $user_id) die('<a href="../project/create_project.php">Создать проект</a></div></body></html>');
							else 	die();
		}
 ?>
		</div>
 	</body> 
</html>