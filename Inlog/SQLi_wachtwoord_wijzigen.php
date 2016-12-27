<?php
	function wachtwoord_wijzigen($username, $password, $password_nieuw, $password_check){
		require_once("encryptie.php");
		require_once("../database.php");
		
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
				echo "de nieuwen wachtwoorden komen niet overeen.";
			}
		}
		else{
			echo "onjuiste gebruikers naam of wachtwoord";
		}
		
	}
	
?>

