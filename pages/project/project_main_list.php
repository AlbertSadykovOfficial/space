<?php

    $id 				= $_GET['project_id'];
    //$level 			= substr_count($id, '_');
    $REGEXP 		= create_regexp($id,1);
//id LIKE '$id%' AND
    $task_list 				= queryMySQL("SELECT * FROM list WHERE id REGEXP $REGEXP ORDER BY id");
    $task_list_length = $task_list->num_rows;

    $REGEXP = create_regexp($id,2);
//id LIKE '$id%' AND 
    $sub_task_list 				= queryMySQL("SELECT * FROM list WHERE id REGEXP $REGEXP ORDER BY id");
    $sub_task_list_length = $sub_task_list->num_rows;
		
    $first_time = true;

	//echo "<script>console.log(".$task_list_length.",".$level.",".$REGEXP.")</script>";
    echo "<div id='list' style='color:white; text-align:center;'>".
            "<div style='width:100%; height: 95%'><br>";//.

                $list_count 		= $task_list_length;
                $list_master 		= "";
                $list_masters 	= "";

                if ($task_list->num_rows) 
									{ 
    echo        "<div class='wrapper' style='width:".(100*$task_list_length/5)."%; height:90%;'>".
                    "<ul class='task_list'>";	
                        $pj = $_GET['project_id'];
                        
                        for ($i=1; $i <= $task_list_length; $i++) 
                        { 
                            $task 					= $task_list->fetch_array(MYSQLI_ASSOC);
                            $name 					= $task['case_'];
                            $case_descript 	= $task['case_description'];
                            $case_executor 	= $task['executor'];
                            $id 						= $task['id'];
                            $deadline				=	$task['deadline'];

                            if (strlen($id) > $SUB_ID_LEN) 
                            {
                                $SUB_ID_LEN 	= strlen($id); 
                            }
											
                            if((int)substr($id,-1) != $i){	$list_count = $i-1;	} /// НЕ правильно делает, если не по порядку


                    $first_stack_name = $first_stack_name."<li id='_$i caption' class='list__caption'".
                                                    "oncontextmenu=\"show_form_2($i,'$pj','$id',1); return false\" ".
                                                    "style='width:".(100/$task_list_length-2)."%'>".
                                                    "<span>$name</span>".
                                                    "<a href='project.php?project_id=$id'>".
                                                        "<img src='$server_content_folder/enter_icon.png' class='show_form_button'>".
                                                    "</a></li>";
																									//$project_id
                    $first_stack = $first_stack."<li id='_$pj".'_'."$i' class='list__cell js-cell' ".
                                                    "style='width:".(100/$task_list_length-2)."%'>";


                    $list_master = "<div id='_$i' ".
                                        "class='list_master'".
                                        "data-position='$i' ".
                                        "data-name='$name' ".
                                        "data-description='$case_descript' ".
                                        "data-executor='$case_executor' ".
                                        "data-deadline='$deadline'".
                                        "oncontextmenu="."\"show_form_2($i,'$pj','$id',1); return false\">".
                                        "<span>$name</span>".
                                        "<a href='project.php?project_id=$id'>".
	                                            "<img src='$server_content_folder/enter_icon.png' class='show_form_button'>".
                                        "</a><br>";																		
                    $list_masters = $list_masters.$list_master."</div>";
															
                            if ($first_time) 
                            {
                                $task 						 = $sub_task_list->fetch_array(MYSQLI_ASSOC);
                                $sub_id 					 = $task['id'];
                                $sub_name 				 = $task['case_'];
                                $sub_case_descript = $task['case_description'];
                                $sub_case_executor = $task['executor'];
                                $sub_deadline			 = $task['deadline'];
															 // ! Запоминаем длину id дочернего элемента, 
															 //	чтобы потом сколько в этот элент входит других элементов
															 // (project_man_paper.php главный цикл)
																//if (strlen($sub_id) != 0)
																//$SUB_ID_LEN 			 = strlen($sub_id); 

                                $first_time = false;
                            }
                            $second_stack = '';
															
                            $sub_count = 0;
                            while($id == mb_substr($sub_id,0,strlen($id)-strlen($sub_id)))
                            { 
                                $sub_count++;
                                $second_stack = $second_stack.
                                    "<div id='_$sub_id' ".
                                        "class='list__card list_slave' draggable='true' onmousedown=dragAndDrop(this) ".
                                        "data-position='' ".
                                        "data-name='$sub_name' ".
                                        "data-description='$sub_case_descript' ".
                                        "data-executor='$sub_case_executor' ".
                                        "data-deadline='$sub_deadline'".
                                        "style='display:block;'".
                                        "oncontextmenu="."\"show_form_2('$sub_id','$pj','$sub_id',1); return false\">".
                                        "<span>$sub_name</span>".																			
                                        "<a href='project.php?project_id=$sub_id'>".
                                            "<img src='$server_content_folder/enter_icon.png' class='show_form_button'>".
                                        "</a>".
                                    "</div>";					
                                $task 						 = $sub_task_list->fetch_array(MYSQLI_ASSOC);
                                $sub_id 					 = $task['id'];
                                $sub_name 				 = $task['case_'];
                                $sub_case_descript = $task['case_description'];
                                $sub_case_executor = $task['executor'];
                                $sub_deadline			 = $task['deadline'];
                            }

                            $first_stack = "$first_stack $second_stack".
                                        "<div id='LAST_$i' ".
                                            "data-prevelem='$sub_id' ".
                                            "class='list_slave list__card add_list'>".
                                                "<div class='add_list_button'>".
                                                    "<a href='javascript:void(0);' onclick=show_form_2(1,'$id',$sub_count,0) style='color:rgb(22,22,22)'>ADD</a>".
                                                "</div>".
                                        '</div>'.
																		  '</li>';	
                        } // for circle
                        
                        echo "$first_stack_name $first_stack".
                            '</ul>'.
                        '</div>'; // wrapper
										// Самому последнему элменту присваиваем аттрибут, чтобы знать что это за элемент 
                        echo "<script>document.getElementById('LAST_".$task_list_length."').dataset.is_last='true';</script>\n";
                    } // if ($task_list->num_rows)
									
                    $id = $_GET['project_id'];
                    $list_master = "<div id='add_group_button' class='list_master'><a href='javascript:void(0);' onclick="."show_form_2(1,'$id',$task_list_length,0)".">Создать лист</a></div>";
                    echo $list_master;
                    $list_masters .= $list_master;
    echo			'</div>'; 
    echo'</div>'; // list

											//  Присвоние 
										//echo "\n<script>document.getElementById('LAST_".$task_list_length."').dataset.prevelem = document.getElementById('LAST_".$task_list_length."').parentNode.children[document.getElementById('LAST_".$task_list_length."').parentNode.children.length-2].id.substr(1);";

		/*echo "<form style='display:inline-block;' method = 'POST' action = 'project.php?project_id=$id'>".
					"<input  id='delete_range' type='text' 	name='delete_range' value='$task_list_length' 	style='display:none'>".
					"<input  id='rrr' type='text' name='lastNumm' style='display:none' value=''>".
					"<button type='submit' name='delete_task_id' value='ALL'>Удалить весь список</button>"."
				</form>";
	*/
?>

