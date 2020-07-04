<?php 
	require_once('../templates/header.php');

		if (isset($_SESSION['user'])) 
		{
			destroySession();
				echo 	"<div class = 'main'>Вы вышли из аккаунта<br>".
							 	"Пожалуйста, <a href='../../index.php'>Нажмите сюда</a>, чтобы обновить страницу.";
		}
		else
				echo 	"<div class='main'><br>".
								"Невозможно завершить сеанс, вы не авторизованы";
?>
 	<br><br></div>
 </body>
</html>