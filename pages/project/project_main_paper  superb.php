<?php
		// Массив, который будет хранить данные о номере последнего элемента группы (для изменения порядка)
		$position_array = [];
		
		$task_id = $_GET['project_id'];

		if (isset($_POST['conclusion'])) 
		{  
			$txt = stripslashes($_POST['conclusion']);
			queryMySQL("UPDATE report SET conclusion = '$txt' WHERE id ='$task_id'");
		}
		echo "<div id='paper'>";
	
		$task_list = queryMySQL("SELECT * FROM report WHERE id LIKE '$task_id%' ORDER BY position");
		$task_list_length = $task_list->num_rows;
		echo "<span style='display:inline-block; width:100%; text-align:center'>\n<form method='POST' action='project.php?project_id=$task_id'>\n<textarea name='conclusion' style='width:400px; height:500px'>";

			if ($task_list_length) 
			{
				$task_id = $_GET['project_id'].'_'; /// (1_2)->(1_2_)
				$task_len= strlen($task_id);
				for ($i=1; $i <= $task_list_length; $i++) 
				{ 
					$task = $task_list->fetch_array(MYSQLI_ASSOC);
					$output = $task['conclusion'];
					$id_now = $task['position']; 

					echo "$output $id_now \n"; //
					array_push($position_array,$id_now);
				}

				$pos_arr_len = count($position_array)-1;
				$last_number_of_this_list = $position_array[$pos_arr_len];
				$a =  $position_array[0];
			}else
			{
				$pos_arr_len = 0;
				$a = 0;
				$last_number_of_this_list = 0;
			}
		echo "</textarea>\n<form>\n</span>\n<input type='submit' value='Upload'>\n</div>";

echo "<script>".
		//	"document.getElementById('firstNum').value = $a;".
			"let lastNum = $last_number_of_this_list;".
			"function set_num_input(x,b){". 
				"if(x==1){".
					"if(b == 0) n = 1; else n = $last_number_of_this_list;".
					"document.getElementById('last_elem_num_input').value = n;".
				"alert($last_number_of_this_list);}".
			"}".
			"</script><script>\n".
			"function set_delete_range(a){\n".
	//		"document.getElementById('delete_range').value = ('".$a."_".$last_number_of_this_list."');\n". // ;
			"}".
			"set_delete_range(1);".
			"</script>\n"	;
echo "<script>";
			for($i = 0; $i <= $pos_arr_len; $i++)
			{
				$last_number_of_this_list = $position_array[$i];
				echo "document.getElementById('_".(string)($i+1)."').dataset.position = ". $last_number_of_this_list ." ;";
				echo "document.getElementById('_".(string)($i+1)." pos').value = ". $last_number_of_this_list.' ;';
			} 
			$last_number_of_this_list = $position_array[$pos_arr_len];
			echo "document.getElementById('rrr').value = ".$last_number_of_this_list.";";
echo	"</script>"

?>