
<style type="text/css">
	
.description_window
{
	position: absolute;
	background-color: rgba(0, 0, 0, 0.5);
	width: 100%;
	height: 100%;
}
.description_window div
{
	position: relative;
	 background-color: white; 
	 width:  30%; 
	 height: 80%;
	 margin: 0 auto; 
	 z-index:999;
}
.description_window button
{
	float: right;
}
.description_window input
{
	display: inline-block;
	width: 40%;
	margin-left: 30%;
	text-align:center;
	border: none;
	border-bottom: black 2px;
}
.show_form_button
{
	height: 14px;
	background-color: white;
	margin-left: 10px;
}
</style>
 <script>
 	
		function show_description(elem,this_id,elem_id) 
		{
			x = document.getElementById('_'+elem).dataset;
			output = "<div class='description_window'>"+"<div>"+
				"<form method = 'POST' action = 'project.php?project_id="+this_id+"'>"+
				"<input type='text' value='"+x.name+"'>"+
				"<button onclick='close_description()'>X</button><br>"+
				"Executor:<br><input type='text' value='"+x.executor+"'><br>"+
				"Описание:<br><textarea style='width:99%;'>"+x.description+"</textarea><br>"+
				"<button type='submit'>Изменить</button><br>"+
				"</form>"+
				"<form method = 'POST'  action = 'project.php?project_id="+this_id+"'>"+
					"<input 								   type='text' 	name='delete_task_id' value='"+elem_id+"' style='display:none'>"+
					"<button type='submit'>Удалить</button>"+
				"</form>"+
			"</div></div>";
			document.getElementById('list').insertAdjacentHTML('afterend',output);
		}			
		function close_description()
		{
			document.getElementsByClassName('description_window')[0].remove();
		}
	/// возможно,лагает	
		function show_form_2(elem,param_1,param_2,CHECK) 
		{
			id = param_1;
			if (CHECK == 1) 
			{
				x = document.getElementById('_'+elem).dataset;
				name = x.name;
				executor = x.executor;
				description = x.description;
				out = 
				"<button type='submit'>Изменить</button><br>"+
				"</form><form method = 'POST'  action = 'project.php?project_id="+param_1+"'>"+
					"<input  type='text' 	name='delete_task_id' value='"+param_2+"' style='display:none'>"+
					"<button type='submit'>Удалить</button>"+
				"</form>";
			}else
			{
				name = 'Input Name';
				executor = 'WHO?';
				description = 'What you Want To Do?';;
				out = "<input type='text' name='task_num' 				value='"+(param_2+1)+"' style='display:none'	><br>"+
							"<input id='last_elem_num_input' type='text' name='last_elem_num' value='"+(lastNum+1)+"' style='display:none'	><br>"+
							"<input type='submit' name='create_task' value='Create' >"+
							"</form>";
			}
			
			output = "<div class='description_window'>"+"<div>"+
				"<form method = 'POST' action = 'project.php?project_id="+id+"'>"+
						"<input type='text' 	name='case_name' 				placeholder='case name' value='"+name+"'>"+
				"<button onclick='close_description()'>X</button><br>"+
				"Executor:<br>"+
						"<input type='text'  	name='case_executor' 		placeholder='executor' 	value='"+executor+"'><br>"+
				"Описание:<br>"+		
						"<textarea style='width:99%;'		name='case_description' placeholder='description'>"+description+"</textarea><br>"+
				out +
			"</div></div>";
			document.getElementById('list').insertAdjacentHTML('afterend',output);
		}			
 </script>
	<?php
		$id = $_GET['project_id'];

		if (isset($_POST['delete_task_id'])) 
		{
			if($_POST['delete_task_id'] == 'ALL')
			{
				$x = (int)$_POST['lastNumm'];
				$dontknow = $_POST['delete_range'] -1;
				$delete_task_num = $id.'_';
				queryMySQL("DELETE FROM list 		WHERE id 	LIKE '$delete_task_num%'");
				queryMySQL("DELETE FROM report 	WHERE id 	LIKE '$delete_task_num%'");

				if(strlen($id) >= 3) queryMySQL("INSERT INTO report VALUES('$id',$x-$dontknow,'$id')");

				queryMySQL("UPDATE report SET position = position - $dontknow WHERE position > $x AND id LIKE '$project_id%'");
				$_POST['delete_task_id'] = null;
				$_POST['delete_task_position'] = null;
				$_POST['delete_range'] = null;
			}
			else
			{
				$del_position = (int)$_POST['delete_task_position']; 
				$del_id 			= $_POST['delete_task_id'];	
				$del_position = queryMySQL("SELECT position FROM report WHERE id LIKE '$del_id%' ORDER BY id");
				$how = $del_position->num_rows;
				$del_position = $del_position->fetch_array(MYSQLI_ASSOC)['position'];
				echo "<script>console.log($del_position, $how);</script>";
				queryMySQL("DELETE FROM list 		WHERE id LIKE '$del_id%'");
				queryMySQL("DELETE FROM report 	WHERE id LIKE '$del_id%'"); 
				queryMySQL("UPDATE report SET position = position - $how WHERE  position > $del_position AND id LIKE '$project_id%'");
				//queryMySQL("UPDATE report SET position = position - 1 WHERE  position > $del_position AND id LIKE '$project_id%'");
				$_POST['delete_task_id'] = null;
				$_POST['delete_task_position'] = null;
			}
		}
		if (isset($_POST['create_task'])) 
		{
			$case_num 					= stripslashes($id.'_'.$_POST['task_num']);
			$case_name				 	= stripslashes($_POST['case_name']);
			$case_description 	= stripslashes($_POST['case_description']);
			$case_executor 			= stripslashes($_POST['case_executor']);
			
			
			if (strlen($id) == 1) 	$last_number_of_this_list = (int)$_POST['last_elem_num']-1;
			
			$last_number_of_this_list = (int)$_POST['last_elem_num'];

			if ($_POST['task_num'] == 1 && strlen($id) != 1) 
				queryMySQL("UPDATE report SET id='$case_num', conclusion='$case_name' WHERE id = '$id'");
			else
			{	
				queryMySQL("UPDATE report SET position = position + 1 WHERE  position >= $last_number_of_this_list AND id LIKE '$project_id%'");
				queryMySQL("INSERT INTO report VALUES('$case_num',$last_number_of_this_list,'$case_name')");
			}
		
			queryMySQL("INSERT INTO list VALUES('$case_num','$case_name','$case_description','$case_executor')");
			$_POST['create_task'] = null;
			$_POST['last_elem_num'] = null;
		}
		# !!! УПАДЕТ ЛОГИКА ПРИ ПРЕВЫШЕНИИ 10 ЗНАЧЕНИЙ
		/* A_a ... 

			Если мы в проекте (А), нам нужно вывести все его ДОЧЕРНЕЕ содержимое:
			1) id LIKE '$id%' 				| находит все, что начинается с (А)
			2) LENGTH(id) = $id_length| Берет результаты запроса А + 2символа--> A_a
		*/
		//$task_list = queryMySQL("SELECT * FROM list WHERE id LIKE '$id%'");
//
		
		$id = $_GET['project_id'].'_';
		$id_length = strlen($id)+1; 
		
		$task_list = queryMySQL("SELECT * FROM list WHERE LENGTH(id) = $id_length AND id LIKE '$id%' ORDER BY id");
		$task_list_length = $task_list->num_rows;
		echo "<div id='list' style='color:white'>".
						"<div style='display:inline-block; width:40%;'></div>".
						"<div style='display:inline-block; width:auto;'><br>".
							"<span id='list_q'>";

									$div_value = "";
									$number = $task_list_length;
									$array_with_ref = [];
									if ($task_list->num_rows) 
									{
										$pj = $_GET['project_id'];
										for ($i=1; $i <= $task_list_length; $i++) 
										{ 
											$task = $task_list->fetch_array(MYSQLI_ASSOC);
											$name = $task['case_'];
											$case_descript = $task['case_description'];
											$case_executor = $task['executor'];
											$id 	= $task['id'];
											if((int)substr($id,-1) != $i){	$number = $i-1;	} /// НЕ правильно делает, если не по порядку
											
											echo "<div id='_$i' ".
																"data-position='$i' ".
																"data-name='$name' ".
																"data-description='$case_descript' ".
																"data-executor='$case_executor' ".
																">$name".
																"<img src='../../images/settings_icon.png' class='show_form_button' onclick="."show_form_2($i,'$pj','$id',1)>".
																"<a href='project.php?project_id=$id'>".
																	"<img src='../../images/Entrance_icon.png' class='show_form_button'>".
																"</a>".
													 "</div>"; //$pj".'_'."$i
										}
			echo "$div_value </span>";
		}

		$id = $_GET['project_id'];

	echo "<button id='form_but' onclick="."show_form_2(1,'$id',$number,0)".">Create case</button>"; 
	echo "<form style='display:inline-block;' method = 'POST' action = 'project.php?project_id=$id'>".
					"<input  id='delete_range' type='text' 	name='delete_range' value='$task_list_length' 	style='display:none'>".
					"<input  id='rrr' type='text' name='lastNumm' style='display:none' value=''>".
					"<button type='submit' name='delete_task_id' value='ALL'>Удалить весь список</button>"."
				</form>";
	echo "</div><div style='display:inline-block; width:30%;'></div></div>";


?>