
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
				description = 'What you Want To Do?';;
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

 <script>
/*let last;
function create_empty_div(id,which)
{
	if (document.getElementById('div_conteiner') != null && last != id)
	document.getElementById('div_conteiner').remove();

	//container = "<div id='div_conteiner' style='width:50px; height:20px; background-color:green; color:black;'>--->  "+div_elem+"</div>";
	
	container = "<div id='div_conteiner' ondragenter='return dragEnter(event)' ondrop='return dragDrop(event)' ondragover='return dragOver(event)' style='height:20px; width:100px; background-color:white;'></div>";

	if (document.getElementById('div_conteiner') == null) {
		if (which == 0)
		document.getElementById(id).childNodes[4].innerHTML = document.getElementById(id).childNodes[4].innerHTML + container;
		else
		document.getElementById(id).insertAdjacentHTML('beforeBegin',container);
 last = id;
 }
}

function delete_empty_div(id)
{
	document.getElementById('div_conteiner').remove();
}
//c_flag = 0;
let first_func =  function() {

		  //	document.getElementById('drag_me').remove();
				if (check) {return 0};
			//	document.getElementById('list').removeAttribute('onclick');
		}
let second_func = function(){
					alert('done');
				for(i=0;i < document.getElementsByClassName('list_slave').length;i++)
					{document.getElementsByClassName('list_slave')[i].removeAttribute('onmouseover');}
				
					document.getElementById('form_but').removeAttribute('onmouseover');
		  		document.getElementById('div_conteiner').remove();
		  		check = 1;
		  		change_position(0,0)
				}		
	check  = 0;
	c_flag = 0;
function change_position(element,id)
{
	c_flag = !c_flag;
	if (c_flag) {
	element.style.opacity = '0.2';
	////////// АККУРАТНО, ПЛОХО ПЕРЕДАЕМ СКВОЗЬ ФУНКЦИИ
		div_elem = "<div id='drag_me' style='height:20px; width:"+element.style.width+"px; background-color:green; position:absolute; display:inline-block; '>"+element.innerHTML+"</div>";
	//document.body.insertAdjacentHTML('afterBegin',div_elem);
			for(i=0;i < document.getElementsByClassName('list_slave').length;i++)
		document.getElementsByClassName('list_slave')[i].setAttribute('onmouseover',document.getElementsByClassName('list_slave')[i].dataset.over);
					 // 	element.style.opacity = '1';
		create_empty_div(id,1);
		check = 0;
		//document.body.addEventListener('mousemove',first_func);

		document.body.addEventListener('click',second_func);
 }else{
		//document.body.removeEventListener('click', document.body.onclick, false);
		document.body.removeEventListener('click', second_func, false);
	}
}


function upload_values(x)
{
	alert(x);
}
function dragStart(ev) {
   ev.dataTransfer.effectAllowed='move';
   ev.dataTransfer.setData("Text", ev.target.getAttribute('id'));   
   ev.dataTransfer.setDragImage(ev.target,100,100);
   return true;
}

function dragEnter(ev) {
   event.preventDefault();
   return true;
}
function dragOver(ev) {
    event.preventDefault();
   // return true;
}

function dragDrop(ev,elem) {
   var data = ev.dataTransfer.getData("Text");
   ev.target.appendChild(document.getElementById(data));
   ev.stopPropagation();
		
	upload_values(elem.dataset.position);
   return false;
}
*/
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

											$list_master="<div id='_$i' ".
																"class='list_master'".
																"data-position='$i' ".
																"data-name='$name' ".
																"data-description='$case_descript' ".
																"data-executor='$case_executor' ".
																">$name".
																"<img src='../../images/settings_icon.png' class='show_form_button' onclick="."show_form_2($i,'$pj','$id',1)>".
																"<a href='project.php?project_id=$id'>".
																	"<img src='../../images/Entrance_icon.png' class='show_form_button'>".
																"</a><br>";
											$list_masters = $list_masters.$list_master."</div>";
											echo 		$list_master;
											echo		 "<div>";
															// Не пашет
															if ($first_time) 
															{
																$task = $sub_task_list->fetch_array(MYSQLI_ASSOC);
																$sub_id = $task['id'];
																$sub_name = $task['case_'];
																$sub_case_descript = $task['case_description'];
																$sub_case_executor = $task['executor'];

																$first_time = false;
															}
																$sub_count = 0;
																while($id == mb_substr($sub_id,0,strlen($id)-strlen($sub_id)))
																{ 
																	$sub_count++;
																	echo "<div id='_$sub_id' ".
																					"class='list_slave'".
																					"data-position='' ".
																					"data-name='$sub_name' ".
																					"data-description='$sub_case_descript' ".
																					"data-executor='$sub_case_executor' ".
																					"data-over = \"create_empty_div('_$sub_id',1)\" ".
																		//			"draggable='true' ondragstart='return dragStart(event)' ".
																		//		"onmouseover=\"create_empty_div('_$sub_id',1)\" ".
																		//			"onclick=\"change_position(this,'_$sub_id');\"".
																		//			"onmouseout=\"delete_empty_div('_$i')\" ".
																					">$sub_name".																			
																					"<img src='../../images/settings_icon.png' class='show_form_button' onclick="."show_form_2('$sub_id','$pj','$sub_id',1)>".
																					"<a href='project.php?project_id=$sub_id'>".
																						"<img src='../../images/Entrance_icon.png' class='show_form_button'>".
																					"</a>".
																				"</div>";
																	$task = $sub_task_list->fetch_array(MYSQLI_ASSOC);
																	$sub_id = $task['id'];
																	$sub_name = $task['case_'];
																	$sub_case_descript = $task['case_description'];
																	$sub_case_executor = $task['executor'];
																}
													//<button id='form_but'onclick="."show_form_2(1,'$id',$sub_count,0)".">Create Case</button>
												echo "<div class='list_slave' ><a href='javascript:void(0);' onclick="."show_form_2(1,'$id',$sub_count,0)".">+ Добавить </a></div>";
												echo"</div>";
											echo"</div>"; //$pj".'_'."$i
										}
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