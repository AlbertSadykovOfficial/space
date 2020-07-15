<span class="project_switch_panel" onclick="switch_mode_num('left')" style="margin-top:20%;height:15%; z-index: 1000;"></span>
<span class="project_switch_panel" onclick="switch_mode_num('right')" style="margin-left: 95%;margin-top:20%;height:15%; z-index: 1000;"></span>

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
			$case_board					=	null;
			
			
			if (strlen($id) == 1) 	$last_number_of_this_list = (int)$_POST['last_elem_num']-1;
			
			$last_number_of_this_list = (int)$_POST['last_elem_num'];

			if ($_POST['task_num'] == 1 && strlen($id) != 1) 
			{
				queryMySQL("UPDATE report SET id='$case_num' WHERE id = '$id'");
			}
			else
			{	
				queryMySQL("UPDATE report SET position = position + 1 WHERE  position >= $last_number_of_this_list AND id LIKE '$project_id%'");
				queryMySQL("INSERT INTO report VALUES('$case_num',$last_number_of_this_list,'$case_name')");
			}
		
			queryMySQL("INSERT INTO list VALUES('$case_num','$case_name','$case_description','$case_executor','$case_board')");
			$_POST['create_task'] = null;
			$_POST['last_elem_num'] = null;
		}

		if (isset($_POST['change_task'])) 
		{
			$case_id = $_POST['task_id'];
			$case_name				 	= stripslashes($_POST['case_name']);
			$case_description 	= stripslashes($_POST['case_description']);
			$case_executor 			= stripslashes($_POST['case_executor']);

			queryMySQL("UPDATE list SET case_= '$case_name', case_description = '$case_description', executor = '$case_executor' WHERE id = '$case_id'");
		}


		$id = $_GET['project_id'];

		if (isset($_POST['conclusion'])) 
		{ 
			$nums = $_POST['conclusion'];
			$leng = strlen($nums);
			for ($i=0; $i < $leng; $i++)
			{ 
				if ($nums[$i] != ',') {
					$pos = $pos.$nums[$i];
				}else{
					$pos = (int)$pos;
					$txt = stripslashes($_POST[$pos]);
					queryMySQL("UPDATE report SET conclusion = '$txt' WHERE id LIKE '$id%' AND position=$pos");
					$pos = '';
				}
			}
		}


?>
		
