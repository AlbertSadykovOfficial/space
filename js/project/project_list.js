
let	before_elem 			= 0;
let	drag_elem 				= 0;
let	drop_is_finished 	= false;

		function show_form($x,$task_len,$id)
		{
			if ($x == 1) 
			{
			document.getElementById('form_but').style.height = '0px';
			document.getElementById('form_but').style.width = '0px';
			document.getElementById('form_but').style.opacity = '0';
			document.getElementById('list').innerHTML= "Create new task:<br>"+
			"<form id='form' style='display:inline-block; height:0px;' method='POST' action = 'project.php?project_id="+$id+"'><br>"+//project.php?project_id="+$id+"
			"<input type='text' name='case_name' 				placeholder='case name' value='case'		 					><br>"+
			"<input type='text' name='case_description' placeholder='description' style='height:100px'		><br>"+
			"<input type='num'  name='case_executor' 		placeholder='executor' value=''			><br>"+
			"<input type='text' name='task_num' 				value='"+($task_len+1)+"' style='display:none'	><br>"+
			"<input id='last_elem_num_input' type='text' name='last_elem_num' value='"+(lastNum+1)+"' style='display:none'	><br>"+
			"<input type='submit' name='create_task' value='Create' >"+
			"</form>";
			document.getElementById('form').style.height = '20px';
			document.getElementById('form').style.opacity = '1'
			document.getElementById('form').style.margin = '0 auto';
			}
		}

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
	 		cell 		 	= document.getElementById(parent_id);
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
/*
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



/*
		function drag($element_id,$len)
		{
			flag = !flag;
			element = document.getElementById('list_q');
			element.style.transition = '0s';
			document.getElementById('_'+$element_id).style.opacity = '0.2';
			document.getElementById('_'+$element_id).style.transition = '0s';
			element.ondragstart = function() {
  				return false;
			}
				f=event.clientY;
				element.onmouseover = function ()
				{
						document.getElementById('_'+(prev)).style.borderTop = '';
	    			document.getElementById('_'+(here)).style.borderTop = '5px solid white';
				}  
				
//	  		element.onmouseup = function()
//	  		{
//	    		document.onmouseover = null;
//	    		element.onmouseup = null;
//				}
				element.onmouseup = function()
				{
					if (flag)
					{
						previous_el = $element_id;
					}else{
						document.getElementById('_'+$element_id).style.opacity = '1';
						document.getElementById('_'+previous_el).style.opacity = '1';
						document.getElementById('_'+(here)).style.borderTop = '';

						change_values(here,previous_el,$element_id,$len);
						return 0;
					}
				}
		}
		function pos($i)
		{
			prev = here;
			here = $i;
		}

		function change_values(into,element,len)
		{
			let result,base ='';
			div_elem_x = document.getElementById('_'+element);
			div_elem_x = '<div id='+'_'+into+' onmouseover=pos('+into+') onmousedown=drag('+into+') data-position='+into+' >'+div_elem_x.innerHTML+'</div>';

			console.log(element, into);

			if (into<element){
				for(i = 1 ; i <= into; i++)
				{
					div_elem = document.getElementById('_'+i).innerHTML;
					div_elem = '<div id='+'_'+i+' onmouseover=pos('+i+') onmousedown=drag('+i+') data-position='+i+' >'+div_elem+'</div>';
					result = result + div_elem;
				}
				result += div_elem_x;
				
				for(i=element-1;i >= into; i--){
					div_elem = document.getElementById('_'+i).innerHTML;
					div_elem_t = '<div id='+'_'+(i+1)+' onmouseover=pos('+(i+1)+') onmousedown=drag('+(i+1)+') data-position='+(i+1)+' >'+div_elem+'</div>';
					base = div_elem_t + base;
				}
				result = result + base;
				base = '';
				for(i = element+1; i < len; i++)
				{
					div_elem = document.getElementById('_'+i);
					div_elem = '<div id='+'_'+i+' onmouseover=pos('+i+') onmousedown=drag('+i+') data-position='+i+' >'+div_elem.innerHTML+'</div>';
					base = div_elem + base;
				}
				result = result + base;
				document.getElementById('list_q').innerHTML = result;
			}
			else
			{
				for(i=element+1; i<=into; i++){
					//div_elem = document.getElementById('_'+i);
					document.getElementById('_'+i).dataset.position = i-1;
					document.getElementById('_'+i).onmouseover = i;
					document.getElementById('_'+i).onmousedown = ("drag("+(i-1)+")");
					document.getElementById('_'+i).id = '_'+ (i-1);
				}
				base = document.getElementById('list_q').innerHTML;
				document.getElementById('list_q').innerHTML = base + div_elem;
			}
		}
	*/
