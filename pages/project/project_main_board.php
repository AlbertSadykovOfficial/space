<?php
		$id = $_GET['project_id'];
		if (isset($_POST['board_elements'])) 
		{
			$x = $_POST['board_elements'];
			queryMySQL("UPDATE list SET board = '$x' WHERE id = '$id'");
			$_POST['board_elements'] = null;
		}

		$str = queryMySQL("SELECT board FROM list WHERE id = '$id'")->fetch_array(MYSQLI_ASSOC)['board'];

			echo "<div id='visualization'>".
							"<div id='tool_bar'>".
								"<span style='display:inline-block' onclick=create_new_element('note','viz_content')>Note</span>   ".
								"<span style='display:inline-block' onclick=create_new_element('image','viz_content')>Photo</span>  ".
								"<span style='display:inline-block' onclick=create_new_element('video','viz_content')>Video</span>  ".
								"<span style='display:inline-block' onclick=create_new_element('audio','viz_content')>Audio</span>	".
								"<span style='display:inline-block' onclick=create_new_element('file','viz_content')>File</span>".
							"</div>".

							"<div id='viz_content'>".
								"<button onclick=update_data('".$id."'); style='position:fixed'>SAVE ALL</button>";

			if ($str == null || $str == '') 
			{
			
				echo "<script> let DATA_ELEMENTS_ARRAY = [];</script>";
				
			}else
			{
				echo "<script> let DATA_ELEMENTS_ARRAY = $str;</script>";
			}
			
				echo 	"</div>".// viz_content
						"</div>";// vizualization
 ?>