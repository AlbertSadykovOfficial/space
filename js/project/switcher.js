    const MODE_ARRAY = ['list','paper','visualization'];//,'short_list'

    const FIRST_MODE = 0;
    const LAST_MODE = MODE_ARRAY.length-1;
    let mode_now = 0; // center

    function switch_mode_num(direction)
    {
        if (direction == 'left' && FIRST_MODE < mode_now)
        {
            mode_now--;
        }else if (direction == 'right' && mode_now < LAST_MODE) 
        {
            mode_now++;
        }else
        {
            console.log('Крайняя страница');
        }
        switch_mode(direction,mode_now);
    }

    function switch_mode(direction,mode_now)
    {
        if (direction == 'left' && mode_now == 1)
        {
            document.getElementById('update_board_btn').click();
        }
		
        for(i=0; i <= LAST_MODE; i++)
        {
            document.getElementById(MODE_ARRAY[i]).style.width = '0%';
        }
        document.getElementById(MODE_ARRAY[mode_now]).style.width = '100%';

        if (mode_now == 0) 	document.getElementsByClassName('project_switch_panel')[0].classList.add('hide');
        else 								document.getElementsByClassName('project_switch_panel')[0].classList.remove('hide');
		
        if (mode_now == 1) 	document.getElementById('bar').classList.remove('hide');
        else 								document.getElementById('bar').classList.add('hide');
        if (mode_now == 2) 
        {
            document.getElementById('tool_bar').classList.remove('hide');
            document.getElementById('board_map').classList.remove('hide');

            document.getElementsByClassName('project_switch_panel')[1].classList.add('hide');
        }
        else
        {
            document.getElementById('tool_bar').classList.add('hide');
            document.getElementById('board_map').classList.add('hide');

            document.getElementsByClassName('project_switch_panel')[1].classList.remove('hide');
        }
    }