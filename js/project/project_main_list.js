let here,prev = 1;
let	flag = 0;
let	previous_el = 0;

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
	