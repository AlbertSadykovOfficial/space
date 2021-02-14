<?php 
    require_once('../templates/header.php');

    if (isset($_SESSION['user'])) 
    {
        destroySession();

    }
     echo    "<div class = 'main'>".
                "<a href='login.php'>Вход</a>";
?>
        <br><br></div>
    </body>
<script type="text/javascript">
    document.getElementsByTagName('a')[0].click();
</script>
</html>