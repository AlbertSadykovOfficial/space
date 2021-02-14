    function set_body_params()
    {
        document.getElementsByTagName('body')[0].style.width = '';
        document.getElementsByTagName('body')[0].style.height= '';

        SCREEN_WIDTH 	= document.getElementsByTagName('body')[0].offsetWidth;
        SCREEN_HEIGHT = document.getElementsByTagName('body')[0].offsetHeight;
		
        document.getElementsByTagName('body')[0].style.width = SCREEN_WIDTH + 'px';
        document.getElementsByTagName('body')[0].style.height= SCREEN_HEIGHT+ 'px';
        set_body_font_size(SCREEN_WIDTH);
    }

    function set_body_font_size(screen_width)
    {
        document.getElementsByTagName('body')[0].style.fontSize = screen_width/80 + 'px';
    }

    set_body_params();