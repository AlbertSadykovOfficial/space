<?php

		require_once('../templates/functions.php');
		

		$result = queryMySQL("SELECT id FROM report WHERE 1");

			for ($i = 0; $i < $result->num_rows; $i++) 
			{ 
				$id = $result->fetch_array(MYSQLI_ASSOC)['id'];
				echo "$id";
				queryMySQL("UPDATE report SET name=(SELECT case_ FROM list WHERE id='$id' LIMIT 1) WHERE id='$id'");
				
			}
			

?>