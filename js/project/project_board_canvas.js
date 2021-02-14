/*

		Canvas

*/

	function set_draw_type(id,type)
	{	
		document.getElementById(id).dataset.type = type;
		document.getElementById(id).dataset.fsize = document.getElementById('font_size').value;
		document.getElementById(id).dataset.bck = document.getElementById('background').value;
		document.getElementById(id).dataset.brdr = document.getElementById('bordercolor').value;
		document.getElementById('choose_menu').remove();
	}

	function choose_type(id)
	{
		if (document.getElementById('choose_menu') == null)
		{
			document.getElementById('visualization').insertAdjacentHTML('afterBegin',
															"<div id='choose_menu' style='z-index:2000; position:fixed; margin-left:"+event.pageX+"px; margin-top:"+event.pageY+"px'>"+
															"<button onclick=change('"+id+"')>CHANGE</button>"+
															"<button onclick=delete_from_canvas('"+id+"')>Удалить</button>"+
															"<button onclick=set_draw_type('"+id+"','line')>Линия</button>"+
															"<button onclick=set_draw_type('"+id+"','text')>Текст</button>"+
															"<button onclick=set_draw_type('"+id+"','circle')>Круг</button>"+
															"<button onclick=set_draw_type('"+id+"','rectangle')>Прямоугольник</button><br>"+
															"<input id='font_size' 	 type='number' value="+document.getElementById(id).dataset.fsize+" placeholder='Размер Шрифта'>"+
															"<input id='background'  type='color' value="+document.getElementById(id).dataset.bck+">"+
															"<input id='bordercolor' type='color' value="+document.getElementById(id).dataset.brdr+">"+
															"<div>");
		}
	}

	function draw_canvas()
	{
		for (let i = 0; i < CANVAS_ELEMENTS.length; i++)
		{
			update_canvas(CANVAS_ELEMENTS,CANVAS_ELEMENTS[i][0]);
		}
	}

	function update_canvas(e,id)
	{
		document.getElementById(id).children[1].getContext('2d').clearRect(0, 0, document.getElementById(id).children[1].width, document.getElementById(id).children[1].height);
		
		for (i = 0; i < e.length; i++)
		{
			if (e[i][1] !== 'text')
				print_figure(e[i][0],e[i][1],e[i][2],e[i][3],e[i][4],e[i][5],e[i][6],e[i][7],e[i][8],e[i][9],e[i][10]);
			else		
				print_text(e[i][0], e[i][10], e[i][2]*e[i][6], e[i][3]*e[i][7], e[i][8], e[i][9]);
		}
		
	}

	// Функция удалит элемент канваса, если он не в зоне отрисовки
	// Т.е его левая кордината правее самой правой точки и т.д...
	function delete_useless_elems()
	{
		for (let i = CANVAS_ELEMENTS.length-1; i >= 0; i--)
		{
			if (CANVAS_ELEMENTS[i][2] >	1200 || CANVAS_ELEMENTS[i][3] > 700 || CANVAS_ELEMENTS[i][4] < 0 || CANVAS_ELEMENTS[i][5] < 0)
			 CANVAS_ELEMENTS.splice(i,1);
		}
		
	}

	function delete_from_canvas(id)
	{	
		document.getElementById('choose_menu').remove();

		let X1 = event.pageX  - document.getElementById(id).offsetLeft 
									  			+ document.getElementById('viz_content').scrollLeft;
		let Y1 = event.pageY  - document.getElementById(id).offsetTop 										// Поправка на отступ от header
												- document.getElementsByTagName('header')[0].offsetHeight   // Попрака на header страницы
												- document.getElementById(id).children[0].offsetHeight			// Попрака на Заголовок
												+ document.getElementById('viz_content').scrollTop;    			// Попрака на скролл от верха

		for (let i = CANVAS_ELEMENTS.length-1; i >= 0; i--) 
		{
			if ((X1 > CANVAS_ELEMENTS[i][2] && X1 < CANVAS_ELEMENTS[i][4] && Y1 > CANVAS_ELEMENTS[i][3] && Y1 < CANVAS_ELEMENTS[i][5]) 
			||  (X1 < CANVAS_ELEMENTS[i][2] && X1 > CANVAS_ELEMENTS[i][4] && Y1 < CANVAS_ELEMENTS[i][3] && Y1 > CANVAS_ELEMENTS[i][5]))
			{
				CANVAS_ELEMENTS.splice(i,1);
				update_canvas(CANVAS_ELEMENTS,id);
				break;
			}
		}
	}

	function change(id)
	{
		set_draw_type(id,'change');
		document.getElementById(id).children[1].style.cursor = 'all-scroll';
		x_1 = event.pageX;
		y_1 = event.pageY; 
		let X1 = event.pageX  - document.getElementById(id).offsetLeft 
									  			+ document.getElementById('viz_content').scrollLeft;
		let Y1 = event.pageY  - document.getElementById(id).offsetTop 										// Поправка на отступ от header
												- document.getElementsByTagName('header')[0].offsetHeight   // Попрака на header страницы
												- document.getElementById(id).children[0].offsetHeight			// Попрака на Заголовок
												+ document.getElementById('viz_content').scrollTop;    			// Попрака на скролл от верха
		grab = function()
		{
			if(event.which == 1)
			{
				for (let i = CANVAS_ELEMENTS.length-1; i >= 0; i--) 
				{
					if ( X1 > CANVAS_ELEMENTS[i][2] && X1 < CANVAS_ELEMENTS[i][4] && 
							( 	
									(Y1 > CANVAS_ELEMENTS[i][3] && Y1 < CANVAS_ELEMENTS[i][5])
								||
									(Y1 < CANVAS_ELEMENTS[i][3] && Y1 > CANVAS_ELEMENTS[i][5])
							)
						 )
					{
						CANVAS_ELEMENTS[i][8] = document.getElementById(id).dataset.fsize;
						CANVAS_ELEMENTS[i][9] = document.getElementById(id).dataset.bck;
						if (CANVAS_ELEMENTS[i][1] !== 'text')
						{
							CANVAS_ELEMENTS[i][10] = document.getElementById(id).dataset.brdr;
						}

						document.body.addEventListener('mousedown',change_position);
						document.body.addEventListener('mouseup',remove_f); 
						update_canvas(CANVAS_ELEMENTS,id);
						break;
					}
				}
			}
		}
		
		change_position = function()
		{

			if(event.which == 1)
			{
				for (let i = CANVAS_ELEMENTS.length-1; i >= 0; i--) 
				{
					if ( X1 > CANVAS_ELEMENTS[i][2] && X1 < CANVAS_ELEMENTS[i][4] && 
							( 	
									(Y1 > CANVAS_ELEMENTS[i][3] && Y1 < CANVAS_ELEMENTS[i][5])
								||
									(Y1 < CANVAS_ELEMENTS[i][3] && Y1 > CANVAS_ELEMENTS[i][5])
							)
						 )
					{
						x_t1 = CANVAS_ELEMENTS[i][2];
						y_t1 = CANVAS_ELEMENTS[i][3];
						x_t2 = CANVAS_ELEMENTS[i][4];
						y_t2 = CANVAS_ELEMENTS[i][5];
						num = i;

						document.body.addEventListener('mousemove',move_e);
						break;
					}
				}
			}
		}
		num = 0;
		move_e = function ()
		{
			if (x_t1 + (event.pageX - x_1) > 0 && x_t1 + (event.pageX - x_1)< 1200) CANVAS_ELEMENTS[num][2] = x_t1 + (event.pageX - x_1);
			if (y_t1 + (event.pageY - y_1) > 0 && y_t1 + (event.pageY - y_1)< 700) CANVAS_ELEMENTS[num][3] = y_t1 + (event.pageY - y_1);
			CANVAS_ELEMENTS[num][4] = x_t2 + (event.pageX - x_1);
			CANVAS_ELEMENTS[num][5] = y_t2 + (event.pageY - y_1);	

			update_canvas(CANVAS_ELEMENTS,id);
		}

		remove_f = function ()
		{
			document.getElementById(id).children[1].style.cursor = 'crosshair';
			document.body.removeEventListener('click',grab,false);
			document.body.removeEventListener('mousemove',move_e,false);
			document.body.removeEventListener('mousedown',change_position,false);
			document.body.removeEventListener('mouseup',remove_f,false);

			update_canvas(CANVAS_ELEMENTS,id);
		}

		document.body.addEventListener('click',grab);
	}

	function draw_text(id)
	{
			if (document.getElementById(id).dataset.type == 'change')
			{
				return 0;
			}
			document.getElementById(id).insertAdjacentHTML('beforeEnd',"<canvas width=1200px height=700px id='TECHNICAL_CANVAS'></canvas>");

			canvas = document.getElementById('TECHNICAL_CANVAS');
			canvas.style.position = 'absolute';
			canvas.style.marginLeft = -document.getElementById(id).children[1].offsetWidth +'px';

			context = canvas.getContext('2d');
			let start = true;
			let X1 = event.pageX  - document.getElementById(id).offsetLeft 
													  + document.getElementById('viz_content').scrollLeft;
			let Y1 = event.pageY  - document.getElementById(id).offsetTop 										// Поправка на отступ от header
														- document.getElementsByTagName('header')[0].offsetHeight   // Попрака на header страницы
														- document.getElementById(id).children[0].offsetHeight			// Попрака на Заголовок
														+ document.getElementById('viz_content').scrollTop;    			// Попрака на скролл от верха
			
			let	x_c = 1200/document.getElementById(id).offsetWidth;
			let y_c = 700/document.getElementById(id).offsetHeight;
			let text = '';
				input = function ()
				{
					context.font =  document.getElementById(id).dataset.fsize +"px Arial";
					context.fillStyle = document.getElementById(id).dataset.bck;
					//context.strokeStyle = document.getElementById('canvas_1').dataset.brdr;

					if (event.code == 'Backspace') 
					{
						text = text.substring(0,text.length-1);
						event.preventDefault();
					} else if (event.code !== 'Shift' && event.code !== 'OS' && event.code !== 'Tab' && event.code !== 'Escape' && event.code !== 'CapsLock')
					{
						if (event.code == 'Enter') text+= ' ';
						else text += event.key;
					}
					context.clearRect(0, 0, canvas.width, canvas.height);
					
					context.fillText(text, X1*x_c, Y1*y_c);
				}

				stop = function ()
				{
					document.body.removeEventListener('keydown', input, false);
					document.body.removeEventListener('click', stop, false);

					document.getElementById(id).insertAdjacentHTML('beforeBegin',"<span id='TECHNICAL_DIV' style='font-size:"+document.getElementById(id).dataset.fsize+"px'>"+text+"</span>");

					X2 = X1 + document.getElementById('TECHNICAL_DIV').offsetWidth;
					Y2 = Y1 + document.getElementById('TECHNICAL_DIV').offsetHeight;
					console.log(document.getElementById('TECHNICAL_DIV').offsetWidth, document.getElementById('TECHNICAL_DIV').offsetHeight);

					document.getElementById('TECHNICAL_DIV').remove();

					CANVAS_ELEMENTS.push([id, 'text', X1, Y1, X2, Y2, x_c, y_c, document.getElementById(id).dataset.fsize, document.getElementById(id).dataset.bck, text]);
					console.log(CANVAS_ELEMENTS[CANVAS_ELEMENTS.length-1]);
					print_text(id, text, X1*x_c, Y1*y_c, document.getElementById(id).dataset.fsize, document.getElementById(id).dataset.bck);
					document.getElementById('TECHNICAL_CANVAS').remove();
				}

				document.body.addEventListener('keydown',input); 
				document.body.addEventListener('click',stop); 
	}

	function print_text(id, text, X, Y, font_size, color)
	{
		document.getElementById(id).children[1].getContext('2d').font =  font_size +"px Arial";
		document.getElementById(id).children[1].getContext('2d').fillStyle = color;
		document.getElementById(id).children[1].getContext('2d').strokeStyle = document.getElementById(id).dataset.brdr;
		document.getElementById(id).children[1].getContext('2d').fillText(text, X, Y);
		document.getElementById(id).children[1].getContext('2d').stroke();
	}

	function draw(id)
	{
		if(event.which == 1)
		{
			if (document.getElementById(id).dataset.type == 'text' || document.getElementById(id).dataset.type == 'change')
			{
				return 0;
			}

			document.getElementById(id).insertAdjacentHTML('beforeEnd',"<canvas width=1200px height=700px id='TECHNICAL_CANVAS'></canvas>");

			canvas = document.getElementById('TECHNICAL_CANVAS');
			canvas.style.position = 'absolute';
			canvas.style.marginLeft = -document.getElementById(id).children[1].offsetWidth +'px';

			context = canvas.getContext('2d');
			let start = true;
			let X1 = event.pageX  - document.getElementById(id).offsetLeft 
													  + document.getElementById('viz_content').scrollLeft;
			let Y1 = event.pageY  - document.getElementById(id).offsetTop 										// Поправка на отступ от header
														- document.getElementsByTagName('header')[0].offsetHeight   // Попрака на header страницы
														- document.getElementById(id).children[0].offsetHeight			// Попрака на Заголовок
														+ document.getElementById('viz_content').scrollTop;    			// Попрака на скролл от верха
			let X2,Y2;
			
			let	x_c = 1200/document.getElementById(id).offsetWidth;
			let y_c = 700/document.getElementById(id).offsetHeight;
			let type = document.getElementById(id).dataset.type;

			start_draw = function()
			{	
				// Вычисяем координаты курсора относитеьно canvas
				X2 = event.pageX - document.getElementById(id).offsetLeft 
												+ document.getElementById('viz_content').scrollLeft;        // Попрака на скролл от левого края
				Y2 = event.pageY - document.getElementById(id).offsetTop 										// Поправка на отступ от header
												- document.getElementsByTagName('header')[0].offsetHeight   // Попрака на header страницы
												- document.getElementById(id).children[0].offsetHeight			// Попрака на Заголовок
												+ document.getElementById('viz_content').scrollTop;    			// Попрака на скролл от верха

				print_figure('TECHNICAL_CANVAS',type, X1, Y1, X2, Y2, x_c, y_c, document.getElementById(id).dataset.fsize +"px Arial", document.getElementById(id).dataset.bck, document.getElementById(id).dataset.brdr);
			}

			stop_draw = function()
			{
				canvas.remove();
				// Если координата начально точки по X меньше, чем конечной
				// следует поменять их местами (Чтобы начальная точка всегда располагалась левее конечной)
				// Это нужно для уменьшения кол-ва проверок в функции (change)
				if (X1 < X2) 
				{
					CANVAS_ELEMENTS.push([id, type, X1, Y1, X2, Y2, x_c, y_c, document.getElementById(id).dataset.fsize, document.getElementById(id).dataset.bck, document.getElementById(id).dataset.brdr]);
					print_figure(id ,type, X1, Y1, X2, Y2, x_c, y_c, document.getElementById(id).dataset.fsize +"px Arial", document.getElementById(id).dataset.bck, document.getElementById(id).dataset.brdr);
				}
				else
				{
					CANVAS_ELEMENTS.push([id, type, X2, Y2, X1, Y1, x_c, y_c, document.getElementById(id).dataset.fsize, document.getElementById(id).dataset.bck, document.getElementById(id).dataset.brdr]);
					print_figure(id ,type, X2, Y2, X1, Y1, x_c, y_c, document.getElementById(id).dataset.fsize +"px Arial", document.getElementById(id).dataset.bck, document.getElementById(id).dataset.brdr);
				}

				console.log(CANVAS_ELEMENTS[CANVAS_ELEMENTS.length-1]);
				

				document.body.removeEventListener('mousemove', start_draw, false);
				document.body.removeEventListener('mouseup', stop_draw, false);
			}  

				document.body.addEventListener('mousemove',start_draw); 
				document.body.addEventListener('mouseup',stop_draw); 
		}
	}

	function print_figure (id, type, x1, y1, x2, y2, x_c ,y_c, font, fill, stroke)
	{
		if (id == 'TECHNICAL_CANVAS')
		{ 
			context = document.getElementById('TECHNICAL_CANVAS').getContext('2d');
			context.fillStyle = 'white';
			context.clearRect(0, 0, document.getElementById('TECHNICAL_CANVAS').width, document.getElementById('TECHNICAL_CANVAS').height);
		}
		else context = document.getElementById(id).children[1].getContext('2d');

				context.beginPath();
				context.font = font;
				context.fillStyle = fill;
				context.strokeStyle = stroke;

			if (type == 'line') 
			{
				context.moveTo(x1*x_c, y1*y_c);
				context.lineTo(x2*x_c, y2*y_c);
			}
			else if (type == 'rectangle')
			{      
    		context.fillRect(x1*x_c, y1*y_c, (x2-x1)*x_c, (y2-y1)*y_c);  
			}
			else if (type == 'circle')
			{
				if ((x2-x1)*x_c/2 > (y2-y1)*y_c/2) context.arc((x1+(x2-x1)/2)*x_c, (y1+(y2-y1)/2)*y_c, (x2-x1)*x_c/2, 0, 2*Math.PI);
				else 															 context.arc((x1+(x2-x1)/2)*x_c, (y1+(y2-y1)/2)*y_c, (y2-y1)*y_c/2, 0, 2*Math.PI);
				
				context.fill();
			}
			// Рисуем линию до новой координаты
				context.closePath();
				context.stroke();
	}