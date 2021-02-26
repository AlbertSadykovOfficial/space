 <?php

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
		
	//	echo queryMySQL("SELECT conclusion FROM report WHERE id='16_1'")->fetch_array(MYSQLI_ASSOC)['conclusion'];
?>
 <script src='../../js/AJAX/ajaxRequest.js'></script>
<script>
		function board_synchronization()
		{
			/*
			[
				['video_1', '10%','10%', '32%', 1, '1','rgb(282,189,53)', 'Пикник', 'https://youtube.com/embed/okmR0aUlL5E',''],
				['image_1', '20%','20%', '20%', 1,'2','rgb(282,189,53)', 'Горы', 'https://im0-tub-ru.yandex.net/i?id=277a5cfcbf1c78896e8efed5c0e87817&n=13',''],
				['note_1',  '40%','50%', '20%', 1, '3','rgb(282,189,53)', 'Надо сделать', 'Уборку,Диван, Мадам','']
			];
		*/
			DATA_ELEMENTS_ARRAY = 
			[
				["note_1","1%","3%","19%",0.25,"2","rgb(255, 189, 53)","Заметочка","Надо сделать доску, чтобы было что показать","","undefined"],
				["image_1","78%","4%","18%",1.5815899581589958,"3","rgb(67, 170, 139)","CHILL","http://space.com/storage/project_1/image/CHILL_front.JPG","hide"," "],
				["image_2","22%","3%","20%",1.515625,"4","rgb(51, 101, 138)","CHILL","http://space.com/storage/project_1/image/IMG_2690.JPG",""," "],
				["image_3","68%","52%","7%",1.1428571428571428,"5","rgb(234, 226, 183)","LOGO","http://space.com/storage/project_1/image/logo2.jpg",""," "],
				["audio_1","22%","77%","19%",0.23921568627450981,"6","rgb(51, 101, 138)","FORCE GHOSTED.mp3","http://space.com/storage/project_1/multimedia/Fusion42 - FORCE GHOSTED.mp3",""," "],
				["file_1","85%","25%","4%","auto","7","rgba(0, 0, 0, 0)","file.exe","http://space.com/storage/project_1/another/1.ewb",""," "],
				["file_2","79%","26%","4%","auto","8","rgba(0, 0, 0, 0)","Result.doc","http://space.com/storage/project_1/another/1.doc",""," "],
				["file_3","85%","11%","4%","auto","9","rgba(0, 0, 0, 0)","Economy","http://space.com/storage/project_1/another/1.pptx",""," "],
				["canvas_1","77%","5%","19%",0.746031746031746,"1","rgb(19, 111, 99)","Документы","",""," "],
				["file_4","78%","12%","4%","auto","10","rgba(0, 0, 0, 0)","Tables","http://space.com/storage/project_1/another/x.xls",""," "],["file_5","91%","9%","5%","auto","11","rgba(0, 0, 0, 0)","DATA","http://space.com/storage/project_1/another/x.accdb",""," "],
				["video_1","44%","3%","31%",0.6176470588235294,"12","rgb(0, 48, 73)","Исландия","https://youtube.com/embed/lBJyaIR1mlw",""," "],
				["video_2","44%","52%","23%",0.6140939597315436,"13","rgb(255, 189, 53)","Филлипины","https://youtube.com/embed/tOSPQeSOE2w","","undefined"],
				["checklist_1","1%","17%","19%","auto","14","rgb(67, 170, 139)","Планы на день","{1,Встать с кровати}{1,Заправить кровать}{1,Выпить теплый чай }{1,Собрать в ВУЗ}{1,Закончить пары}{1,CHILL}",""," "],
				["checklist_2","1%","52%","19%","auto","15","rgb(19, 111, 99)","Работа","{1,Добавить фунциональность}{1,Доработать дизайн}{1,Получить прибыль}{1,Получить сверхприбыль}{0,CHILL}",""," "],
				["image_4","77%","42%","19%",0.6507936507936508,"16","rgb(51, 101, 138)","Красиво","https://im0-tub-ru.yandex.net/i?id=3c3b42fadcbd2b0ddf70e4f17e0c8f29(!AMP!)n=13",""," "],
				["audio_2","68%","74%","29%",0.1639784946236559,"17","rgb(0, 48, 73)","Лекция  - Кибербезопасность","http://space.com/storage/project_1/multimedia/Fusion42 - FORCE GHOSTED.mp3",""," "]
			];
			json_board  = JSON.stringify(DATA_ELEMENTS_ARRAY);
			json_board 	= String(json_board).replace('&','(!AMP!)');
			args = 'project_id=1&' + "data_board=" + String(json_board).replace('&','(!AMP!)');
	    //Отправляем запроc
	    ajaxRequest("POST", 'http://space.com/js/AJAX/board_synchronization.php', args, function(){});
		}
		board_synchronization();
</script>
<?php
	
		echo queryMySQL("SELECT conclusion FROM report WHERE position=1")->fetch_array(MYSQLI_ASSOC)['conclusion'];
?>
