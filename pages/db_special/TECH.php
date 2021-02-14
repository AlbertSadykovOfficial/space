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
		
		echo queryMySQL("SELECT conclusion FROM report WHERE id='16_1'")->fetch_array(MYSQLI_ASSOC)['conclusion'];
?>
 <script src='../../js/AJAX/ajaxRequest.js'></script>
<script>
		function board_synchronization()
		{
			DATA_ELEMENTS_ARRAY = [
				['video_1', '10%','10%', '32%', 1, '1','rgb(282,189,53)', 'Пикник', 'https://youtube.com/embed/okmR0aUlL5E',''],
				['image_1', '20%','20%', '20%', 1,'2','rgb(282,189,53)', 'Горы', 'https://im0-tub-ru.yandex.net/i?id=277a5cfcbf1c78896e8efed5c0e87817&n=13',''],
				['note_1',  '40%','50%', '20%', 1, '3','rgb(282,189,53)', 'Надо сделать', 'Уборку,Диван, Мадам','']
			];
			json_board  = JSON.stringify(DATA_ELEMENTS_ARRAY);
			json_board 	= String(json_board).replace('&','(!AMP!)');
			args = 'project_id=1&' + "data_board=" + String(json_board).replace('&','(!AMP!)');
	    //Отправляем запроc
	    ajaxRequest("POST", 'https://www.space.com/js/AJAX/board_synchronization.php', args, function(){});
		}
		board_synchronization();
</script>
<?php
	
		echo queryMySQL("SELECT conclusion FROM report WHERE position=1")->fetch_array(MYSQLI_ASSOC)['conclusion'];
?>
