	function switch_mode_num(element)
	{
			if (element == 'left' && FIRST_MODE < mode_now)
			{
				mode_now--;
			//	console.log(mode_now);
			}else if (element == 'right' && mode_now < LAST_MODE) 
			{
				mode_now++;
			//	console.log(mode_now);
			}else
			{
				console.log('Крайняя страница');
			}
		switch_mode(mode_now);
	}

	function switch_mode(mode_now)
	{
		for(i=0; i <= LAST_MODE; i++)
		{
			document.getElementById(MODE_ARRAY[i]).style.width = '0%';
		}
		document.getElementById(MODE_ARRAY[mode_now]).style.width = '100%';

	}