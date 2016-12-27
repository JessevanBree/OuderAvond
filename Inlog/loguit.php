<?php
	session_start();
	//sluit de session.
	if(session_destroy()){
		//stuurt je door naar de index pagina. (de begin pagina.)
		header("location:login.php");
	}
?>