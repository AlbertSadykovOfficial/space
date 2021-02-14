<div id='paper'>
    <div id='content_maker' class='hide'>
        <div>
            Редактирование Элемента
            <button onclick=apply(this)>Apply</button><button style='float:right' onclick=hide_this(this)>X</button>
        </div>
        <textarea  id='content' cols='60' rows='10'></textarea>
    </div>

    <div class='page_navigator'>
        <span>
            <button onclick=change_paper_mode(this) data-mode='0'>По элементно</button>
            <div class='hide'>
                <button onclick=prev_page(this.parentNode)><<Назад</button>
                <button onclick=next_page(this.parentNode)>Вперед>></button>
            </div>
        </span>
    </div>


    <span id='paper_content_span'>
        <div id='look'>
<?php
            $REGEXP           = "\"^". $_GET['project_id']. "_\"";
            $task_id          = $_GET['project_id'];
            // $task_list        = queryMySQL("SELECT * FROM report WHERE id LIKE '$task_id%' ORDER BY position");
            //id LIKE '$id%' AND
            $task_list        = queryMySQL("SELECT * FROM report WHERE id REGEXP $REGEXP OR id='$task_id' ORDER BY position");
            $task_list_length = $task_list->num_rows;   

            // Массив, который будет хранить данные о номере последнего элемента группы (для изменения порядка)
            // $position_array   = [];
            //$conclusions_len  = [];
            echo "<script>START_POSITION = -1;</script>";
            if ($task_list_length) 
            {
                $task_id  = $_GET['project_id'].'_'; /// (1_2)->(1_2_)
                $task_len = strlen($task_id);

                for ($i=1; $i <= $task_list_length; $i++) 
                { 
                    $task    = $task_list->fetch_array(MYSQLI_ASSOC);
                    $name    = $task['name'];  
                    $output  = str_replace("\"", "'", $task['conclusion']);
                    $output  = str_replace('(!2qts!)','"',str_replace("(!1qts!)","'",$output));
                    $position= $task['position'];
                    $elem_id = $task['id'];
                 
                    if ($i == 1) 
                    { 
                        echo "<script>START_POSITION = $position-1;</script>";
                        $last_position = $position;
                        $prev_group_id = substr($elem_id, 0, $SUB_ID_LEN);
                    }else
                    {
                        if ($prev_group_id !== substr($elem_id, 0, $SUB_ID_LEN))
                        {
                            echo "<script>document.getElementById('_$prev_group_id').dataset.poslen = ".($position-$last_position).";</script>";
                            $prev_group_id = substr($elem_id, 0, $SUB_ID_LEN);
                            $last_position = $position;
                        }
                    }
        					//$conclusions_len[] = strlen($output);

                    echo "<div id='$position' ".
                            "data-parent='$prev_group_id' ".
                            "data-id='$elem_id' ".
                            "data-name='$position) $name' ".
                            "class='paper_content_div' ".
                            "title='Элемент: $position ($name)' ".
                            "onclick='full_screen_mode(this); return false;' ".
                            "oncontextmenu=\"menu('$position'); return false; \">".
                            "$output".
                        "</div>\n";
        				//	array_push($position_array,$position);
                }
                
                if ($_GET['project_id'] !== $elem_id)
                {
                    if ($position == $last_position) $last_position--; 
                    // Почему (+1 - не знаю, иначе он не досчитывет 1 элемент )
                    echo "<script>document.getElementById('_$prev_group_id').dataset.poslen =".($position-$last_position+1).";</script>";  
                }
            
            }

      // Заполняем массив длины каждого conclusion		
 //     		$task_id = $_GET['project_id'];

 //     		echo "<script> let conclusion_len = [];\n position = []; task_id = '$task_id';\n";
      	
  //          for ($i=0; $i < $task_list_length ; $i++) 
   //         { 
       // 			echo "conclusion_len.push(".$conclusions_len[$i].");\n".
     //   					 "position.push(".$position_array[$i].");";
        //		}
            
   //   		echo "</script>\n";
 ?>	
        </div>
    </span><br>
</div> <!-- div paper -->


<div id='bar'>
    <button class='bar_button' onclick='change_bar_mode()' style='width:8%;'><img src='https://www.space.com/content/menu_icon.png'></button>

    <div id='show_structure_btn'>
        <button class='bar_button' onclick='show_strucutre()'><img src='https://www.space.com/content/search_icon.png'></button>
        <input type='text' class='hide' onchange='find_by_name()' placeholder='№/name'>
        <div id='structure_pannel' class='hide'>
            
        </div>
    </div>

    <div id='target'>
<?php 
        echo "<div style='text-align:center; height:8%'>\n".
                "<button class='add_button' style='margin-top:0' onclick='upload_values($task_list_length)'>Synchronization</button>\n".
            "</div>\n";
?>
    <div>
        <div class="page-orientation">
            <span>Page Orientation</span><br>
            <label><input type="radio" name="orientation" value="portrait" checked>Portrait</label>
            <label><input type="radio" name="orientation" value="landscape">Landscape</label>
            <button class='add_button' id="convert">SAVE</button>
            <div id="download-area"></div>
        </div>
    </div>
		
    <div class='list_master_small'>
        <p>Level Structure:</p>
<?php
        echo "<div>$list_masters</div></div>"; 
        echo "<button id='upload_files_button' class='add_button' onclick='upload_form($project_id)'>UPLOAD YOUR FILES</button>";
?>
    </div>
</div>