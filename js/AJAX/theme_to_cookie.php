<?php
    if (isset($_GET['theme'])) 
    {
        // Устанавливаем куки theme на месяц для всего домена space
        setcookie('theme', $_GET['theme'], time() + 60 * 60 * 24 * 30, '/');
		}
?>