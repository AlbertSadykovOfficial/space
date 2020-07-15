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
          $task_id          = $_GET['project_id'];
      	  $task_list        = queryMySQL("SELECT * FROM report WHERE id LIKE '$task_id%' ORDER BY position");
      		$task_list_length = $task_list->num_rows;   

        // Массив, который будет хранить данные о номере последнего элемента группы (для изменения порядка)
          $position_array   = [];
      		$conclusions_len  = [];

      			if ($task_list_length) 
      			{
      				$task_id  = $_GET['project_id'].'_'; /// (1_2)->(1_2_)
      				$task_len = strlen($task_id);

        				for ($i=1; $i <= $task_list_length; $i++) 
        				{ 
        					$task    = $task_list->fetch_array(MYSQLI_ASSOC);
        					$output  = str_replace("\"", "'", $task['conclusion']);
        					$id_now  = $task['position'];
                  $elem_id = $task['id'];
                 
                  if ($i == 1) 
                  { 
                    $last_id       = $id_now;
                    $prev_group_id = substr($elem_id,0,5);
                  }else
                  {
                    if ($prev_group_id !== substr($elem_id,0,5)) 
                    { 
                      echo "<script>document.getElementById('_$prev_group_id').dataset.poslen = ".($id_now-$last_id).";</script>"; 
                      
                      $prev_group_id = substr($elem_id,0,5);
                      $last_id       = $id_now;
                    }
                  }

        					$conclusions_len[] = strlen($output);

        					echo "<div id='$id_now'".
                            "data-parent='$prev_group_id' ".
                            "class='paper_content_div' ".
                            "title='Элемент: $id_now' ".
                            "oncontextmenu=\"menu('$id_now'); return false; \">".
                            "$output".
                        "</div>\n";

        					array_push($position_array,$id_now);
        				}
            echo "<script>document.getElementById('_$prev_group_id').dataset.poslen =".($id_now-$last_id).";</script>";
      		  echo "</div>";
          }

      	 echo "<!--</form>-->\n</span><br>\n";

      // Заполняем массив длины каждого conclusion		
      		$task_id = $_GET['project_id'];

      		echo "<script> let conclusion_len = [];\n position = []; task_id = '$task_id';\n";
      	
            for ($i=0; $i < $task_list_length ; $i++) 
            { 
        			echo "conclusion_len.push(".$conclusions_len[$i].");\n".
        					 "position.push(".$position_array[$i].");";
        		}
            
      		echo "</script>\n";
      ?>	

</div> <!-- div paper -->


<div id='bar'>
	<button onclick="change_bar_mode();">X</button>
	<?php 
		echo "<div style='text-align:center;'>\n".
				 	"<button style='width:100px;' onclick='upload_values($task_list_length)'>Upload</button>\n".
				 "</div>\n";
	 ?>
	<div>
		<div class="page-orientation">
		    <span>Page orientation:</span><br>
		    <label><input type="radio" name="orientation" value="portrait" checked>Portrait</label>
		    <label><input type="radio" name="orientation" value="landscape">Landscape</label>
		  </div>
		  <button id="convert">EXPORT TO DOC</button>
	</div>
		
  <div class='list_master_small'>
		<p> Level Structure:</p>
			<?php
			echo "$list_masters"; 
      echo "<button onclick='upload_form($project_id)'>Upload files</button>";
      ?>
	</div>

	<div id="download-area"></div>
</div>

<!-- 

            //  $pos_arr_len              = count($position_array)-1;
          //    $last_number_of_this_list = $position_array[$pos_arr_len];
          //    $a                        =  $position_array[0];
            }else
            {
            //  $pos_arr_len = 0;
              //$a = 0;
              //$last_number_of_this_list = 0;
            }
/* YЕ СРАБОТАЕТ, Я ВЫНЕС В ОТДЕЛЬНУЮ ОКНО УДАЛЕНИЕ
echo "<script>";
      for($i = 0; $i <= $pos_arr_len; $i++)
      {
        $last_number_of_this_list = $position_array[$i];
        echo "document.getElementById('_".(string)($i+1)."').dataset.position = ". $last_number_of_this_list ." ;";
        echo "document.getElementById('_".(string)($i+1)." pos').value = ". $last_number_of_this_list.' ;';
      } 
      $last_number_of_this_list = $position_array[$pos_arr_len];
echo  "</script>"

echo "<script>".
    //  "document.getElementById('firstNum').value = $a;".
      "let lastNum = $last_number_of_this_list;".
      "function set_num_input(x,b){". 
        "if(x==1){".
          "if(b == 0) n = 1; else n = $last_number_of_this_list;".
          "document.getElementById('last_elem_num_input').value = n;".
        "alert($last_number_of_this_list);}".
      "}".
      "</script><script>\n".
      "function set_delete_range(a){\n".
  //    "document.getElementById('delete_range').value = ('".$a."_".$last_number_of_this_list."');\n". // ;
      "}".
      "set_delete_range(1);".
      "</script>\n" ;
*/

-->