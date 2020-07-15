	<?php

		$id 				= $_GET['project_id'].'_';
		$id_length 	= strlen($id)+1;
		$level 			= substr_count($id, '_');
		$REGEXP 		= create_regexp($level+1);

		//$task_list = queryMySQL("SELECT * FROM list WHERE LENGTH(id) = $id_length AND id LIKE '$id%' ORDER BY id");
		
		$task_list 				= queryMySQL("SELECT * FROM list WHERE id LIKE '$id%' AND id REGEXP $REGEXP ORDER BY id");
		$task_list_length = $task_list->num_rows;

		$REGEXP = create_regexp($level+2);

		$sub_task_list 				= queryMySQL("SELECT * FROM list WHERE id LIKE '$id%' AND id REGEXP $REGEXP ORDER BY id");
		$sub_task_list_length = $sub_task_list->num_rows;
		
		$first_time = true;

	//echo "<script>console.log(".$sub_task_list_length.",".$level.",".$REGEXP.")</script>";
		echo "<div id='list' style='color:white; text-align:center;'>".
						"<div style='width:100%; height: 95%'><br>".
							"<span id='list_q'>";

									$list_count 		= $task_list_length;
									$list_master 		= "";
									$list_masters 	= "";

									if ($task_list->num_rows) 
									{
					echo "<div class='hero'>".
									"<div class='wrapper'>".
										"<ul class='task_list'>";	
										$pj = $_GET['project_id'];
										for ($i=1; $i <= $task_list_length; $i++) 
										{ 
											$task 					= $task_list->fetch_array(MYSQLI_ASSOC);
											$name 					= $task['case_'];
											$case_descript 	= $task['case_description'];
											$case_executor 	= $task['executor'];
											$id 						= $task['id'];
											if((int)substr($id,-1) != $i){	$list_count = $i-1;	} /// НЕ правильно делает, если не по порядку


									$first_stack_name = $first_stack_name."<li id='_$i caption' class='list__caption'>$name</li>";
									$first_stack = $first_stack."<li id='_$project_id".'_'."$i' class='list__cell js-cell'>";


											$list_master = "<div id='_$i' ".
																"class='list_master'".
																"data-position='$i' ".
																"data-name='$name' ".
																"data-description='$case_descript' ".
																"data-executor='$case_executor' ".
																">$name".
																"<img src='$server_content_folder/settings_icon.png'".
																			"class='show_form_button' onclick=".
																			"show_form_2($i,'$pj','$id',1)>".
																"<a href='project.php?project_id=$id'>".
																	"<img src='$server_content_folder/Entrance_icon.png' class='show_form_button'>".
																"</a><br>";
											$list_masters = $list_masters.$list_master."</div>";
															
															if ($first_time) 
															{
																$task = $sub_task_list->fetch_array(MYSQLI_ASSOC);
																$sub_id = $task['id'];
																$sub_name = $task['case_'];
																$sub_case_descript = $task['case_description'];
																$sub_case_executor = $task['executor'];

																$first_time = false;
															}
															$second_stack = '';
															
															$sub_count = 0;
															while($id == mb_substr($sub_id,0,strlen($id)-strlen($sub_id)))
															{ 
																$sub_count++;
																$second_stack = $second_stack."<div id='_$sub_id' ".
																				"class='list__card list_slave' draggable='true' onmousedown=dragAndDrop(this) ".
																			//	"class=''".
																				"data-position='' ".
																				"data-name='$sub_name' ".
																				"data-description='$sub_case_descript' ".
																				"data-executor='$sub_case_executor' ".
																		//		"data-over = \"create_empty_div('_$sub_id',1)\" ".
																				"style='display:block;'".
																				">$sub_name".																			
																				"<a href='#'><img src='$server_content_folder/settings_icon.png' class='show_form_button' onclick="."show_form_2('$sub_id','$pj','$sub_id',1)></a>".
																				"<a href='project.php?project_id=$sub_id'>".
																					"<img src='$server_content_folder/Entrance_icon.png' class='show_form_button'>".
																				"</a>".
																			"</div>";					
																$task = $sub_task_list->fetch_array(MYSQLI_ASSOC);
																$sub_id = $task['id'];
																$sub_name = $task['case_'];
																$sub_case_descript = $task['case_description'];
																$sub_case_executor = $task['executor'];
															}
												$first_stack = "$first_stack $second_stack".
																				"<div id='LAST_$i' ".
																							"data-prevelem='$sub_id' ".
																							"class='list_slave list__card add_list_button'>".
																							"<a href='javascript:void(0);' onclick=show_form_2(1,'$id',$sub_count,0)>+ Добавить</a>".
																				'</div>'.
																		   '</li>';				
										} // for circle
										
										echo "$first_stack_name $first_stack".
												'</ul>'.
											'</div>'.
										'</div>';

										// Самому последнему элменту присваиваем аттрибут, чтобы знать что это за элемент 
										echo "<script>document.getElementById('LAST_".$task_list_length."').dataset.is_last='true';</script>\n";

										$id = $_GET['project_id'];
										echo "<div class='list_master'>".
														"<a href='javascript:void(0);' onclick="."show_form_2(1,'$id',$list_count,0)".">Создать лист</a>".
													'</div>';
									} // if ($task_list->num_rows)

	echo '</span></div></div>';

											//  Присвоние 
										//echo "\n<script>document.getElementById('LAST_".$task_list_length."').dataset.prevelem = document.getElementById('LAST_".$task_list_length."').parentNode.children[document.getElementById('LAST_".$task_list_length."').parentNode.children.length-2].id.substr(1);";

		/*echo "<form style='display:inline-block;' method = 'POST' action = 'project.php?project_id=$id'>".
					"<input  id='delete_range' type='text' 	name='delete_range' value='$task_list_length' 	style='display:none'>".
					"<input  id='rrr' type='text' name='lastNumm' style='display:none' value=''>".
					"<button type='submit' name='delete_task_id' value='ALL'>Удалить весь список</button>"."
				</form>";
	*/
?>

