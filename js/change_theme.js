		
    function change_theme(page)
    {
        if (document.getElementById('dark_theme_css') !== null)
        {
            document.getElementById('dark_theme_css').remove();
            document.getElementsByTagName('head')[0].insertAdjacentHTML('beforeEnd',
            	"<link id='light_theme_css' rel = 'stylesheet' type = 'text/css' href = '../../css/light/"+page+"_light.css'>");
            theme_to_cookie('light');
        }
        else if (document.getElementById('light_theme_css') !== null)
				{
            document.getElementById('light_theme_css').remove();
            document.getElementsByTagName('head')[0].insertAdjacentHTML('beforeEnd',
            	"<link id='dark_theme_css' rel = 'stylesheet' type = 'text/css' href = '../../css/light/"+page+"_dark.css'>");
            theme_to_cookie('dark');
        }
        else
        {
            document.getElementsByTagName('head')[0].insertAdjacentHTML('beforeEnd',
            "<link id='light_theme_css' rel = 'stylesheet' type = 'text/css' href = '../../css/light/"+page+"_light.css'>");
            theme_to_cookie('light');
        }
    }

    function theme_to_cookie(theme)
    {
        ajaxRequest("GET", 'https://www.space.com/js/AJAX/theme_to_cookie.php', 'theme='+theme , function(){});
    }