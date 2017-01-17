<?php
	function wachtwoord_wijzigen($username, $password, $password_nieuw, $password_check){
		require_once("encryptie.php");
		require_once("../database.php");

		//verdedigen tegen SQL injection
		//stripcslashes haalt alle ongewenste tekens uit de ingevoerde waarde.
		$username = stripcslashes($username);
		$password = stripcslashes($password);
		$password_nieuw = stripcslashes($password_nieuw);
		$password_check = stripcslashes($password_check);
		//zorgt er voor dat de speciale teken uit de ingevoerde waarde wordt gehaald.
		$username = mysqli_real_escape_string($connect, $username);
		$password = mysqli_real_escape_string($connect, $password);
		$password_nieuw = mysqli_real_escape_string($connect, $password_nieuw);
		$password_check = mysqli_real_escape_string($connect, $password_check);

		//schrijft de querry's die gebruikt gaan worden voor het ophalen van de gegevens
		$sqli_Leerling_inlog = "SELECT Leerling_ID, Salt FROM leerlingen WHERE Leerling_ID = '$username'";
		$sqli_docent_inlog = "SELECT Docent_ID, Salt FROM docenten WHERE Afkorting = '$username'";
		$sqli_Leerling_inlog_uitkomst = mysqli_query($connect, $sqli_Leerling_inlog);
		$sqli_docent_inlog_uitkomt = mysqli_query($connect, $sqli_docent_inlog);

		if(mysqli_num_rows($sqli_Leerling_inlog_uitkomst) == 1){
			$row = mysqli_fetch_array($sqli_Leerling_inlog_uitkomst);
			//de inlognaam is van een leerling
			//encrypt het password
			$password = $password . $row["Salt"];

			$password = encryptie($password);

			$sqli_eerste_inlog = "SELECT Leerling_ID FROM leerlingen WHERE Leerling_ID='$username' AND Wachtwoord='$password'";
			$sqli_eerste_inlog_uitkomst = mysqli_query($connect, $sqli_eerste_inlog);

			if(mysqli_num_rows($sqli_eerste_inlog_uitkomst)== 1){
				//je inlog gegevens zijn correct, dus het wachtwoord kan worden gewijzigd.
				//controleerd of de bijde nieuwe wachtwoorden overeen komen.
				if($password_nieuw == $password_check){
					//het wachtwoord moet een encryptie krijgen. voor de encryptie wordt ook een salt gebruikt.
					$salt = gen_salt();
					//voegt het wachtwoord smen met de salt.
					$password = $password_nieuw . $salt;
					//encrypt het wachtwoord
					$password = encryptie($password);

					//bereid de querry voor die het updaten van het wachtwoord doet.
					$sqli_password_update = "UPDATE leerlingen SET Wachtwoord='$password', Salt='$salt', Eerste_Inlog='1' WHERE Leerling_ID='$username'";
					if(mysqli_query($connect, $sqli_password_update)){
						session_destroy();
						header("location: login.php");
					}
					else{
						echo "er is een onbekende vout op getreden, probeer het nog eens.";
					}
				}
				else{
					echo "<p class='text-center'>de nieuwen wachtwoorden komen niet overeen.</p>";
				}
			}
			else{
				//de inloggegevens zijn niet correct
				echo "<p class='text-center'>onjuiste gebruikers naam of wachtwoord</p>";
			}
		}
		elseif(mysqli_num_rows($sqli_docent_inlog_uitkomt) == 1){
			$row = mysqli_fetch_array($sqli_docent_inlog_uitkomt);
			//de inlognaam is van een docent
			//encrypt het password
			$password = $password . $row["Salt"];

			$password = encryptie($password);

			//controleerd je inlog gegevens
			$sqli_eerste_inlog = "SELECT Docent_ID FROM docenten WHERE Afkorting='$username' AND Wachtwoord='$password'";
			$sqli_eerste_inlog_uitkomst = mysqli_query($connect, $sqli_eerste_inlog);

			if(mysqli_num_rows($sqli_eerste_inlog_uitkomst) == 1){
				//je inlog gegevens zijn correct, dus het wachtwoord kan worden gewijzigd.
				//controleerd of de nieuwe wachtwoorden overeen komen
				if($password_nieuw == $password_check){
					//het wachtwoord moet een encryptie krijgen. voor de encryptie wordt ook een salt gebruikt.
					$salt = gen_salt();
					//voegt het wachtwoord smen met de salt.
					$password = $password_nieuw . $salt;
					//encrypt het wachtwoord
					$password = encryptie($password);

					//bereid de querry voor die het updaten van het wachtwoord doet.
					$sqli_password_update = "UPDATE docenten SET Wachtwoord='$password', Salt='$salt', Eerste_Inlog='1' WHERE Afkorting='$username'";
					if(mysqli_query($connect, $sqli_password_update)){
						session_destroy();
						header("location: login.php");
					}
					else{
						echo "er is een onbekende vout op getreden, probeer het nog eens.";
					}
				}
				else{
					echo "<p class='text-center'>de nieuwen wachtwoorden komen niet overeen.</p>";
				}
			}
			else{
				//de inloggegevens zijn niet correct
				echo "<p class='text-center'>onjuiste gebruikers naam of wachtwoord</p>";
			}
		}
		else{
			//de inlognaam is niet bekent
			echo "<p class='text-center'>onjuiste gebruikers naam of wachtwoord</p>";
			//echo "Test";
			//echo mysqli_num_rows($sqli_docent_inlog_uitkomt);
		}

		/*
		//controleert of de inloggegevens correct zijn.(leerlingen)
		$sqli_inlog_check = "SELECT * FROM leerlingen WHERE Leerling_ID='$username' AND Wachtwoord='$password'";
		$sqli_inlog_check_uitkomst = mysqli_query($connect, $sqli_inlog_check);
		//controleert of de inloggegevens correct zijn.(Docenten)
		$sqli_inlog_check_docent = "SELECT * FROM docenten WHERE Afkorting='$username' AND Wachtwoord='$password'";
		$sqli_inlog_check_docent_uitkomst = mysqli_query($connect, $sqli_inlog_check_docent);
		if(mysqli_num_rows($sqli_inlog_check_uitkomst) >=  1){
			//de inlog gegevens zijn goed dus het wachtwoord kan worden gewijzigd.
			//controleert of het nieuwe wachtwoord wel 2 keer goed is ingevoerd.
			if($password_nieuw == $password_check){
				//het wachtwoord moet een encryptie krijgen. voor de encryptie wordt ook een salt gebruikt.
				$salt = gen_salt();
				//voegt het wachtwoord smen met de salt.
				$password = $password_nieuw . $salt;
				//encrypt het wachtwoord
				$password = encryptie($password);
				
				//bereid de querry voor die het updaten van het wachtwoord doet.
				$sqli_password_update = "UPDATE leerlingen SET Leerling_ID='$username', Wachtwoord='$password', Salt='$salt', Eerste_Inlog='1' WHERE Leerling_ID='$username'";
				if(mysqli_query($connect, $sqli_password_update)){
					session_destroy();
					header("location: login.php");
				}
				else{
					echo "er is een onbekende vout op getreden, probeer het nog eens.";
				}
			}
			else{
				echo "de nieuwen wachtwoorden komen niet overeen.";
			}
		}
		elseif(mysqli_num_rows($sqli_inlog_check_docent_uitkomst) >=  1){
			//de inlog gegevens zijn goed dus het wachtwoord kan worden gewijzigd.
			//controleert of het nieuwe wachtwoord wel 2 keer goed is ingevoerd.
			if($password_nieuw == $password_check){
				//het wachtwoord moet een encryptie krijgen. voor de encryptie wordt ook een salt gebruikt.
				$salt = gen_salt();
				//voegt het wachtwoord smen met de salt.
				$password = $password_nieuw . $salt;
				//encrypt het wachtwoord
				$password = encryptie($password);
				
				//bereid de querry voor die het updaten van het wachtwoord doet.
				$sqli_password_update = "UPDATE docenten SET Wachtwoord='$password', Salt='$salt', Eerste_Inlog='1' WHERE Afkorting='$username'";
				if(mysqli_query($connect, $sqli_password_update)){
					session_destroy();
					header("location: login.php");
				}
				else{
					echo "er is een onbekende vout op getreden, probeer het nog eens.";
				}
			}
			else{
				echo "<p class='text-center'>de nieuwen wachtwoorden komen niet overeen.</p>";
			}
		}
		else{
			echo "<p class='text-center'>onjuiste gebruikers naam of wachtwoord</p>";
		}
		*/
		
	}
	
?>

