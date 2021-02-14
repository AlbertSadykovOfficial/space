
    function full_screen_mode(element)
    {
        SCREEN_WIDTH = document.getElementsByTagName('body')[0].offsetWidth;
        SCREEN_HEIGHT = document.getElementsByTagName('body')[0].offsetHeight;
        w 		= element.offsetWidth;
        h 		=	element.offsetHeight;
        text 	= element.children[0].innerHTML;
        x = (text.replace(/<br>/g,'\n')).replace(/<!--[\s\S]*?--!?>/g, "").replace(/<(?!img)\/?[a-z][^>]*(>|$)/gi, "");//replace(/<\/?[^>]+>/g,'');

        fs = to_number(document.getElementsByTagName('body')[0].style.fontSize);
			
        k = (w*h) / (SCREEN_WIDTH*SCREEN_HEIGHT);
        console.log(k);
        output="<div class='full_screen' onclick='this.remove()' ";

        if (SCREEN_WIDTH > SCREEN_HEIGHT)
        {					
            if 			(k < 0.5)
            {
                output += "><div style='margin:0 auto; width:"+50+"%; border:1px solid black;'>"+text+"</div>";
            }
            else if (k < 1)	
            {
                start = 0;
                finish= x.length/2;

                for (i=0; i < 2; i++)
                {
                    output += "><div style='max-width:50%; margin:0 auto; display:inline-block; width:"+49+"%; border:1px solid black;'>"+x.substring(start,finish)+"</div>";
                    start 	= finish+1;
                    finish	= finish+x.length/2;
                }
            }
            else if (k > 1)
            {
                start = 0;
                finish= x.length/3;
						
                for (i=0; i < 3; i++)
                {
							//console.log(x,x.length,start,finish,k); (Math.floor((w*h) / (SCREEN_WIDTH*SCREEN_HEIGHT)))
                    output += "><div style='font-size:"+(fs)+"px; display:inline-block; width:"+32+"%; border:1px solid black;'>"+x.substring(start,finish)+"</div>";
                    start 	= finish+1;
                    finish	= finish+x.length/3;
                }
            }
        }//output += "<div style='max-width:50%; margin:0 auto'>"+text+"</div>";
        else
        {
            output += "style='font-size:"+(fs/k)+"px'>"+
                    "<div>"+ text + "</div>";
            //if (element.classList.contains('full_screen')) element.classList.remove('full_screen');
            //else 																					 element.classList.add('full_screen');
        }
        output += '</div>';
        document.getElementsByTagName('body')[0].insertAdjacentHTML('afterBegin',output);

        imgs = document.getElementsByClassName('full_screen')[0].getElementsByTagName('img');

        for (i=0; i<imgs.length; i++)
        {
            k = imgs[i].offsetWidth/imgs[i].parentNode.offsetWidth;
            imgs[i].style.width = (imgs[i].offsetWidth/k)+'px';
            imgs[i].style.height = (imgs[i].offsetHeight/k)+'px';
        }
    }