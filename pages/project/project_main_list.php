
<style type="text/css">
#list_q
{
	cursor: pointer;
}
.description_window
{
	position: absolute;
	background-color: rgba(0, 0, 0, 0.65);
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
.list_master
{
	display:inline-block;
	width:15%;
	margin-left:4%;
	margin-bottom: 2%;
	vertical-align: top;
	border-radius: 5px;
	background-color: rgba(255,255,255,0.2);
}
.list_slave
{
	background-color: rgba(0,0,0,0.2);
	border-radius: 5px;
	padding:10px 10px;
	margin-bottom: 2%;
	margin: 2% 5%;
}
.list_master a
{
	text-decoration:none;
	color:black;
}
.list_slave a
{
	text-decoration:none;
	color:white;
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
				"Описание:<br><textarea style='min-width:99%; max-width:99%;'>"+x.description+"</textarea><br>"+
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
				"<input type='text' name='task_id' 				value='"+param_2+"' style='display:none'	>"+
				"<button type='submit' name='change_task'>Изменить</button><br>"+
				"</form><form method = 'POST'  action = 'project.php?project_id="+param_1+"'>"+
					"<input  type='text' 	name='delete_task_id' value='"+param_2+"' style='display:none'>"+
					"<button type='submit'>Удалить</button>"+
				"</form>";
			}else
			{
				name = 'Input Name';
				executor = 'WHO?';
				description = 'What you Want To Do?';
				out = "<input type='text' name='task_num' 				value='"+(param_2+1)+"' style='display:none'	><br>"+
							"<input id='last_elem_num_input' type='text' name='last_elem_num' value='"+(lastNum+1)+"' style='display:none'	><br>"+
							"<input type='submit' name='create_task' value='Create' >"+
							"</form>";
			}
			
			output = "<div class='description_window'>"+"<div>"+
				"<form method = 'POST' action = 'project.php?project_id="+id+"'>"+
						"<input type='text' 						name='case_name' 				placeholder='case name' value='"+name+"'>"+
				"<button onclick='close_description()'>X</button><br>"+
				"Executor:<br>"+
						"<input type='text'  						name='case_executor' 		placeholder='executor' 	value='"+executor+"'><br>"+
				"Описание:<br>"+		
						"<textarea style='min-width:99%; max-width:99%;'		name='case_description' placeholder='description'>"+description+"</textarea><br>"+
				out +
			"</div></div>";
			document.getElementById('list').insertAdjacentHTML('afterend',output);
		}			
 </script>
	<?php

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
		$level = substr_count($id, '_');
		$REGEXP = create_regexp($level+1);

		//$task_list = queryMySQL("SELECT * FROM list WHERE LENGTH(id) = $id_length AND id LIKE '$id%' ORDER BY id");
		
		$task_list = queryMySQL("SELECT * FROM list WHERE id LIKE '$id%' AND id REGEXP $REGEXP ORDER BY id");
		$task_list_length = $task_list->num_rows;

		$REGEXP = create_regexp($level+2);

		$sub_task_list = queryMySQL("SELECT * FROM list WHERE id LIKE '$id%' AND id REGEXP $REGEXP ORDER BY id");
		$sub_task_list_length = $sub_task_list->num_rows;
		
		$first_time = true;

	//echo "<script>console.log(".$sub_task_list_length.",".$level.",".$REGEXP.")</script>";
		echo "<div id='list' style='color:white; text-align:center;'>".
						"<div style='width:100%;'><br>".
							"<span id='list_q'>";

									$div_value = "";
									$list_count = $task_list_length;
									$array_with_ref = [];
									$list_master = "";
									$list_masters = "";
echo "<div class='hero'><div class='wrapper'> <ul class='task_list'>";		
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
																"<img src='$server_content_folder/settings_icon.png' class='show_form_button' onclick="."show_form_2($i,'$pj','$id',1)>".
																"<a href='project.php?project_id=$id'>".
																	"<img src='$server_content_folder/Entrance_icon.png' class='show_form_button'>".
																"</a><br>";
											$list_masters = $list_masters.$list_master."</div>";
											//echo 		$list_master;
											//echo		 "<div>";
															
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
														 //<li id='_$sub_id' class='list__cell js-cell'></li>
														//	 "<div id='_$sub_id' class='list__card' draggable='true' onmousedown=dragAndDrop(this)>$sub_name</div>";					
																	$task = $sub_task_list->fetch_array(MYSQLI_ASSOC);
																	$sub_id = $task['id'];
																	$sub_name = $task['case_'];
																	$sub_case_descript = $task['case_description'];
																	$sub_case_executor = $task['executor'];
																}
												$first_stack = "$first_stack $second_stack <div id='LAST_$i' data-prevelem='$sub_id' class='list_slave list__card add_list_button' ><a href='javascript:void(0);' onclick=show_form_2(1,'$id',$sub_count,0)>+ Добавить </a></div></li>";				
													//<button id='form_but'onclick="."show_form_2(1,'$id',$sub_count,0)".">Create Case</button>
								//				echo "<div class='list_slave' ><a href='javascript:void(0);' onclick="."show_form_2(1,'$id',$sub_count,0)".">+ Добавить </a></div>";
										//		echo"</div>";
										//	echo"</div>"; //$pj".'_'."$i
										}
										echo "$first_stack_name $first_stack";
echo "</ul> </div></div>";
echo "\n<script>document.getElementById('LAST_".$task_list_length."').dataset.prevelem = document.getElementById('LAST_".$task_list_length."').parentNode.children[document.getElementById('LAST_".$task_list_length."').parentNode.children.length-2].id.substr(1); document.getElementById('LAST_".$task_list_length."').dataset.is_last='true';</script>\n";
										$id = $_GET['project_id'];
										//<button id='form_but' onclick="."show_form_2(1,'$id',$list_count,0)".">Create List</button>
								echo "<div class='list_master'><a href='javascript:void(0);' onclick="."show_form_2(1,'$id',$list_count,0)".">Создать лист</a></div>";
			echo "$div_value </span>";
		}

		

	/*echo "<form style='display:inline-block;' method = 'POST' action = 'project.php?project_id=$id'>".
					"<input  id='delete_range' type='text' 	name='delete_range' value='$task_list_length' 	style='display:none'>".
					"<input  id='rrr' type='text' name='lastNumm' style='display:none' value=''>".
					"<button type='submit' name='delete_task_id' value='ALL'>Удалить весь список</button>"."
				</form>";
	*/
	echo "</div></div>";
?>

<script type="text/javascript">

	function update_pos_values()
	{
		// Последний элемент группы : (Добавить)  является вспомогательным и его не нужно счиать,
		// Поэтому при пересчете с него надо снять Класс карточки
		document.querySelectorAll('.js-cell').forEach(cell => {
				cell.children[cell.children.length-1].classList.remove('list__card');
		});

		
		elem = document.getElementsByClassName('list__card');
		pos = elem[0].dataset.poslen; 
		//console.log(elem[0].id, pos);
		for (i=1;i<elem.length; i++)
		{
			pos = Number(pos) + Number(elem[i].dataset.poslen);
			
			// На синхронизацию
			//console.log(elem[i].id, pos); 
		}

		//  Восстанавливаем класс карточки после персчета
		document.querySelectorAll('.js-cell').forEach(cell => {
				cell.children[cell.children.length-1].classList.add('list__card');
		});
	}

	before_elem = 0;
	drag_elem = 0;
	drop_is_finished = false;
	transit = function ()
	{	 
		 before_elem = String(this.id);

		 if (drop_is_finished == true) 
		 {
		 		if (before_elem != drag_elem) {

		 			drag_elem = change_drag_element(before_elem,drag_elem);
		 			document.getElementById(drag_elem).insertAdjacentHTML('beforeBegin','<div id=\'TECHNICAL_MOVE_DIV\'></div>');
		 			document.getElementById('TECHNICAL_MOVE_DIV').append(document.getElementById(drag_elem));
		 			document.getElementById(before_elem).insertAdjacentHTML('beforeBegin',document.getElementById('TECHNICAL_MOVE_DIV').innerHTML);
			 		document.getElementById('TECHNICAL_MOVE_DIV').remove();

			 		drop_is_finished = false;
			 		remove_listener();
			 		update_pos_values();
			 	}
		 }
	}

	function change_drag_element(before_elem_id,target_id)
	{
		parent_id = document.getElementById(before_elem_id).parentNode.id;
 		cell = document.getElementById(parent_id);
 		parent_id = parent_id+'_'; 

		// Вычленяем последние цифры id (1_2_31)-> 31
 		x = Number((cell.children[0].id).substr(parent_id.length));
 		
 		//  Цикл поиска самого большого числа (-1 потому что последний элемет(Добавить) не надо считать)
 		for (i = 1; i < cell.children.length-1; i++)
 		{
 			// Находим самое большое число из всех
 			if (Number((cell.children[i].id).substr(parent_id.length)) > x) x = Number((cell.children[i].id).substr(parent_id.length))
 		}
 		x = x + 1;
 		
 		// Обрезаем Лишний (_) и меняем ссылку 
 		document.getElementById(String(target_id)).children[1].href = 'project.php?project_id='+(parent_id + x).substr(1);
 		//console.log(before_elem_id);
 		if (before_elem_id.includes('LAST') && document.getElementById(before_elem_id).dataset.is_last == 'true')
 		{
 			change_paper_elements(document.getElementById(before_elem_id).dataset.prevelem,target_id.substr(1),(parent_id + x).substr(1),document.getElementById(String(target_id)).dataset.poslen, 1);		
 		}else if (before_elem_id.includes('LAST')) 
 		{
 			change_paper_elements(document.getElementById(before_elem_id).dataset.prevelem,target_id.substr(1),(parent_id + x).substr(1),document.getElementById(String(target_id)).dataset.poslen);
 		} else
 		{
 		 change_paper_elements(before_elem_id.substr(1),target_id.substr(1),(parent_id + x).substr(1),document.getElementById(String(target_id)).dataset.poslen);
 		}
 		// Меняем id на +1 от самого большого найденного номера 
 		document.getElementById(String(target_id)).id = parent_id + x;

 		/// ОТДАЕМ ЗАПРОС AJAX на изменение id 
 		///  AJAX CODE

 		return  (parent_id + x);
	}

	function change_paper_elements(before_elem_id,target_id,new_target_id,len, mode = 0)
	{
		after_constant = 0;
		if (mode)
		{
			after_constant = 1;
		}

		elements = document.getElementsByClassName('paper_content_div');
			type = 0;
		//	console.log(before_elem_id,target_id,new_target_id,len);
			for (i = 0; i < elements.length; i++)
			{
					if(elements[i].dataset.parent == target_id)
					{
						last_i = i;
						elements[i].dataset.parent = new_target_id;

						if (document.getElementById('TECHNICAL_MOVE_DIV') == null)
							document.getElementById('look').insertAdjacentHTML('afterBegin','<div id=\'TECHNICAL_MOVE_DIV\'></div>');

						document.getElementById('TECHNICAL_MOVE_DIV').append(elements[i]);
						if (type != 2) type = 1;
						continue; 
					}
					else if	(elements[i].dataset.parent == before_elem_id)
					{
						if (type != 1) type = 2;
					}
			}

			if (type == 2)
			{
					for (i=last_i; i > 0; i--)
					{
							num = Number(Number(elements[i].id) + Number(len));
							elements[i].title =	'Элемент: '+ num;
							elements[i].setAttribute('oncontextmenu', 'menu(\''+ num +'\'); return false');
							elements[i].id 		= num;

							if (elements[i].dataset.parent == before_elem_id)
							{
								for(j=0; j < document.getElementById('TECHNICAL_MOVE_DIV').children.length; j++)
								{
									document.getElementById('TECHNICAL_MOVE_DIV').children[j].id = Number(elements[i].id) - len + j + after_constant;// + 1;
									document.getElementById('TECHNICAL_MOVE_DIV').children[j].title = 'Элемент: '+ Number(Number(elements[i].id)- len + j + after_constant);// + 1);
									document.getElementById('TECHNICAL_MOVE_DIV').children[j].setAttribute('oncontextmenu', 'menu(\''+Number(Number(elements[i].id) - len + j + after_constant) +'\'); return false');
								}

								if (after_constant) 
								{
									num = Number(Number(elements[i].id) - Number(len));
									elements[i].title =	'Элемент: '+ num;
									elements[i].setAttribute('oncontextmenu', 'menu(\''+ num +'\'); return false');
									elements[i].id 		= num;
									elements[i].insertAdjacentHTML('afterend',document.getElementById('TECHNICAL_MOVE_DIV').innerHTML);
								}else
								{
										elements[i].insertAdjacentHTML('beforeBegin',document.getElementById('TECHNICAL_MOVE_DIV').innerHTML);
								}
								
								document.getElementById('TECHNICAL_MOVE_DIV').remove();
								break;
							}
					}
			}else if(type == 1)
			{
				for ( i = last_i + 1; i < elements.length; i++)
				{
					if (elements[i].dataset.parent == before_elem_id) 
					{
						for(j=0; j < document.getElementById('TECHNICAL_MOVE_DIV').children.length; j++)
						{
							document.getElementById('TECHNICAL_MOVE_DIV').children[j].id = Number(elements[i].id) - len + j + after_constant;// - 1;
							document.getElementById('TECHNICAL_MOVE_DIV').children[j].title = 'Элемент: '+ Number(Number(elements[i].id) - len + j + after_constant);
							document.getElementById('TECHNICAL_MOVE_DIV').children[j].setAttribute('oncontextmenu', 'menu(\''+Number(Number(elements[i].id) - len + j + after_constant) +'\'); return false');
						}
						
						if (after_constant) 
						{
							elements[i].insertAdjacentHTML('afterend',document.getElementById('TECHNICAL_MOVE_DIV').innerHTML);
							elements[i].title =	'Элемент: '+ Number(elements[i].id - len);
							elements[i].setAttribute('oncontextmenu', 'menu(\''+ Number(elements[i].id - len)+'\'); return false');
							elements[i].id 		= Number(elements[i].id - len);	
						}
						else
						{
							elements[i].insertAdjacentHTML('beforeBegin',document.getElementById('TECHNICAL_MOVE_DIV').innerHTML);
						}

						document.getElementById('TECHNICAL_MOVE_DIV').remove();
						break;
					}

					elements[i].title =	'Элемент: '+ Number(elements[i].id - len);
					elements[i].setAttribute('oncontextmenu', 'menu(\''+ Number(elements[i].id - len)+'\'); return false');
					elements[i].id 		= Number(elements[i].id - len);					
				}
			}
	}

	function add_listener()
	{
			x = document.getElementsByClassName('list__card');
			for (i=0; i< x.length; i++)
			{
				x[i].addEventListener('mousemove',transit);
			}
	}

	function remove_listener()
	{
			x = document.getElementsByClassName('list__card');
			for (i=0; i< x.length; i++)
			{
				x[i].removeEventListener('mousemove',transit);
			}
	}
	function update_last__group_elem()
	{
		// Функция необходима для смены мест в paper
		// При переносе первого элемента какого-либо столбца элемент LAST, хранящий id того элемента,
		// Теряет свою актуальность, поэтому в data элементам LAST мы всегда присваиваем 1ое значение
		// Следующего стобца. 
			elements = document.getElementsByClassName('add_list_button');

			for (i=0; i < elements.length; i++)
			{
				if (i < elements.length-1 && elements[i+1].parentNode.children.length > 1) 
					elements[i].dataset.prevelem = elements[i+1].parentNode.children[0].id.substr(1);
				else
				{
					// Если у послеующей группы нет 1го элемента, то мы иттерируем назад по группам
					// в поисках 1й группы, в которой есть хоть какой-то элемент помимо LAST
					// При нахождении такой группы, находим в ней предпоследний элемент (потому что LAST последний) 
					// Далее устанавливаем data нашему элементу.
					for (j=i; j > 0; j--)
					{
						if (elements[j].parentNode.children.length > 1) 
						{
							elements[i].dataset.prevelem = elements[j].parentNode.children[elements[j].parentNode.children.length-2].id.substr(1);
							break;
						}
					}	
				}
			}
	}

	function dragAndDrop(elem)
	{
		 add_listener();
		 update_last__group_elem();
		elem.classList.add('js-card');
    card = document.querySelector('.js-card');
    cells = document.querySelectorAll('.js-cell');

    const dragStart = function () {
        setTimeout(() => {
            this.classList.add('hide');
        }, 0);
    };
    
    const dragEnd = function () {
        this.classList.remove('hide');
        card.removeEventListener('dragstart', dragStart);
    		card.removeEventListener('dragend', dragEnd);
    	cells.forEach(cell => {
        cell.removeEventListener('dragover', dragOver);
        cell.removeEventListener('dragenter', dragEnter);
        cell.removeEventListener('dragleave', dragLeave);
        cell.removeEventListener('drop', dragDrop);
    	});
    	drag_elem = elem.id;
    	drop_is_finished = true;
    };

    const dragOver = function (evt) {
        evt.preventDefault();
    };

    const dragEnter = function (evt) {
        evt.preventDefault();
        this.classList.add('hovered');
    };

    const dragLeave = function () {
        this.classList.remove('hovered');
    };
    const dragDrop = function () {
        card.classList.remove('js-card')
        this.classList.remove('hovered');
    };
    cells.forEach(cell => {
        cell.addEventListener('dragover', dragOver);
        cell.addEventListener('dragenter', dragEnter);
        cell.addEventListener('dragleave', dragLeave);
        cell.addEventListener('drop', dragDrop);
    });
  card.addEventListener('dragstart', dragStart);
  card.addEventListener('dragend', dragEnd);
};
</script>
<style>
.wrapper *, *::before, *::after {
    box-sizing: border-box;
    padding: 0;
    margin: 0;
}

.wrapper {
    font-family: Arial, sans-serif;
    font-size: 16px;
    line-height: normal;
    font-weight: 400;
}

.hero {
    width: 100%;
    height: 90%;
    min-height: 100vh;
}

.wrapper {
    width: 1500px;
    margin: 0 auto;
		height:100%;
    overflow-y: scroll;
}

.task_list {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.list__cell {
   /* flex-basis: calc(25% - 40px); */
    min-height: 15px;
    margin: 20px;
    list-style: none;
    box-shadow: 0px 0px 7px 5px rgba(0,0,0,0.2);
    overflow: hidden;
    background-color:#f0f2f5;;

    width: 160px;
    height: auto;
}

.list__caption {
    width: 160px;
    margin: 0 20px;
    list-style: none;
    font-weight: bold;
    color: #0747a6;
}

.list__card {
	color:black;
    display: flex;
    flex-wrap: wrap;
    flex-direction: column;
    text-align: center;
   /*  min-height: 100%; */
    cursor: all-scroll;
}

.list__card-header {
    text-transform: lowercase;
    font-weight: bold;
    padding: 12px 20px;
    background-color: #0747a6;
    color: white;
}

.list__card-info {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #eff5ff;
    flex-grow: 1;
    padding: 12px 20px;
    font-size: 14px;
    text-transform: uppercase;
    font-weight: 600;
}

.hovered {
    background-color: #d1d8e2;
}

.hide {
    display: none;
}
</style>

<script>/*	
	function change_pos_values(elem,id,start,finish)
	{
		last_group_elem_s = Number(elem.children[elem.children.length-1].dataset.startpos);
		last_group_elem_f = Number(elem.children[elem.children.length-1].dataset.finishpos);
		cards = document.getElementsByClassName('list__card');
		//console.log(cards[1].dataset.startpos, cards[1].dataset.finishpos);
		if (elem.children[0].dataset.startpos > start){
				for (i = 0; i < cards.length; i++)
				{
					if (cards[i].dataset.startpos > start && cards[i].dataset.finishpos <= last_group_elem_f) 
					{
						if (cards[i].dataset.startpos != start && cards[i].dataset.finishpos != finish) 
						{
							cards[i].dataset.startpos = cards[i].dataset.startpos - Number(finish - start + 1);
							cards[i].dataset.finishpos= cards[i].dataset.finishpos - Number(finish - start + 1);
						}
					}
				}
			document.getElementById(id).dataset.startpos = last_group_elem_s;
			document.getElementById(id).dataset.finishpos =last_group_elem_s + Number(finish - start);
		}
		else
		{
			for (i = 0; i < cards.length; i++)
				{
					if (cards[i].dataset.startpos > last_group_elem_f && cards[i].dataset.finishpos < start) 
					{
						if (cards[i].dataset.startpos != start && cards[i].dataset.finishpos != finish) 
						{
							cards[i].dataset.startpos = Number(cards[i].dataset.startpos) + Number(finish - start + 1);
							cards[i].dataset.finishpos= Number(cards[i].dataset.finishpos) + Number(finish - start + 1);
						}
					}
				}
			document.getElementById(id).dataset.startpos = 	last_group_elem_s + 1;
			document.getElementById(id).dataset.finishpos = last_group_elem_s + 1 + Number(finish - start);
		}
	}
*/
</script>