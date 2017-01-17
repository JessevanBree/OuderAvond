<html>
	<body>
		<?php
			//error_reporting(0);
			function inloggen($username, $password){
				require_once("encryptie.php");
				require_once("../database.php");
				
				session_start();
					
				//verdedigen tegen SQL injection
				//stripcslashes haalt alle ongewenste tekens uit de ingevoerde waarde.
				$username = stripcslashes($username);
				$password = stripcslashes($password);
				//zorgt er voor dat de speciale teken uit de ingevoerde waarde wordt gehaald.
				$username = mysqli_real_escape_string($connect, $username);
				$password = mysqli_real_escape_string($connect, $password);

				//schrijft de querry's die gebruikt gaan worden voor het ophalen van de
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

					//controleerd je inlog gegevens
					$sqli_eerste_inlog_met_eerste_inlog = "SELECT Leerling_ID FROM leerlingen WHERE Leerling_ID='$username' AND Wachtwoord='$password' AND Eerste_Inlog='0'";
					$sqli_eerste_inlog = "SELECT Leerling_ID FROM leerlingen WHERE Leerling_ID='$username' AND Wachtwoord='$password'";
					$sqli_eerste_inlog_met_eerste_inlog_uitkomst = mysqli_query($connect, $sqli_eerste_inlog_met_eerste_inlog);
					$sqli_eerste_inlog_uitkomst = mysqli_query($connect, $sqli_eerste_inlog);

					if(mysqli_num_rows($sqli_eerste_inlog_met_eerste_inlog_uitkomst) == 1){
						//je bent voor het eerst ingelogt, je wordt doorverzwezen.
						header("location:wachtwoord_wijzigen.php");
					}
					elseif(mysqli_num_rows($sqli_eerste_inlog_uitkomst)== 1){
						//zorgt er voor dat de andere pagina's kunnen controleren of er ingelogt is.
						$_SESSION["ingelogt"] = true;
						//zet je inlognaam in een variablen.
						$_SESSION["Inlog_ID"] = $username;
						//je wordt door gestuurd naar de hoofd pagina.
						header("location: ../Index.php");
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
					$sqli_eerste_inlog_met_eerste_inlog = "SELECT Docent_ID FROM docenten WHERE Afkorting='$username' AND Wachtwoord='$password' AND Eerste_Inlog='0'";
					$sqli_eerste_inlog = "SELECT Docent_ID FROM docenten WHERE Afkorting='$username' AND Wachtwoord='$password'";
					$sqli_eerste_inlog_met_eerste_inlog_uitkomst = mysqli_query($connect, $sqli_eerste_inlog_met_eerste_inlog);
					$sqli_eerste_inlog_uitkomst = mysqli_query($connect, $sqli_eerste_inlog);

					if(mysqli_num_rows($sqli_eerste_inlog_met_eerste_inlog_uitkomst) == 1){
						//je bent voor het eerst ingelogt, je wordt doorverzwezen.
						header("location:wachtwoord_wijzigen.php");
					}
					elseif(mysqli_num_rows($sqli_eerste_inlog_uitkomst) == 1){
						//zorgt er voor dat de andere pagina's kunnen controleren of er ingelogt is.
						$_SESSION["ingelogt"] = true;
						//zorgt er voor dat de gebruiker wordt gezien als admin
						//zet je inlognaam in een variablen.
						$_SESSION["Admin"] = true;
						$_SESSION["Inlog_ID"] = $username;
						//je wordt door gestuurd naar de hoofd pagina.
						header("location: ../Index.php");
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

				//controleerdt of er voor de eerste keer wordt ingelogt(leerlingen)
				$sqli = "SELECT Leerling_ID, Wachtwoord, Salt FROM leerlingen WHERE Leerling_ID='$username' AND Wachtwoord='$password' AND eerste_inlog=1";
				$uitkomst = mysqli_query($connect, $sqli);
				//controleerdt of er voor de eerste keer wordt ingelogt(docenten)
				$sqli_docenten = "SELECT Docent_ID, Wachtwoord, Salt FROM docenten WHERE Afkorting='$username' AND Wachtwoord='$password' AND eerste_inlog=1";
				$uitkomst_docenten = mysqli_query($connect, $sqli_docenten);
				if(mysqli_num_rows($uitkomst) >= 1){
					//je bent voor het eerst ingelogt.
					//je wordt door verwezen naar een andere pagina om je wachtwoord intestellen. 
					header("location:wachtwoord_wijzigen.php");
				}
				elseif(mysqli_num_rows($uitkomst_docenten) >= 1){
					//je bent voor het eerst ingelogt.
					//je wordt door verwezen naar een andere pagina om je wachtwoord intestellen. 
					header("location:wachtwoord_wijzigen.php");
				}
				else{
					//je bent niet voor het eerst ingelogt.
					//encrypt het wachtwoord zodat deze kan worden vergeleken met het wachtwoord in de database
					//maakt de querry die de salt uit de database opvraagt. 
					$sqli_salt = "SELECT Salt FROM leerlingen WHERE Leerling_ID = '$username'";
					$sqli_salt_docent = "SELECT Salt FROM docenten WHERE Afkorting = '$username'";
					
					//voert de querry uit die de salt uit de database haalt.
					$result = mysqli_query($connect, $sqli_salt);
					$TEMP = mysqli_fetch_array($result);
					$salt = $TEMP[0];
					//voert het zelfde uit maar dan voor de docenten
					$result_docent = mysqli_query($connect, $sqli_salt_docent);
					$TEMP_docent = mysqli_fetch_array($result_docent);
					$salt_docent = $TEMP_docent[0];
					
					//conbineerd de salt en het wachtwoord.
					$password_leerling = $password . $salt;
					//encrypt het wachtwoord. (gebruikt eigengemaakte functie)
					$password_leerling = encryptie($password_leerling);
					//doet het zelfde maar dan voor de docent
					$password_docent = $password . $salt_docent;
					//encrypt het wachtwoord. (gebruikt eigengemaakte functie)
					$password_docent = encryptie($password_docent);
					
					//controleerdt de username en password in de database
					//maakt de querry voor het selecteren van de gegevens.
					$sqli = "SELECT Leerling_ID FROM leerlingen WHERE Leerling_ID='$username' AND Wachtwoord='$password_leerling'"; 
					//var_dump($sqli);
					//voert de querry $sqli uit en zet de uitkomst in een variablen.
					$uitkomst = mysqli_query($connect, $sqli);
					//doet het zelfde maar dan voor de docent
					$sqli_docent = "SELECT Docent_ID FROM docenten WHERE Afkorting='$username' AND Wachtwoord='$password_docent'"; 
					$uitkomst_docenten = mysqli_query($connect, $sqli_docent);
					
					//als de gebruikers naam en wachtwoord in de database zijn word er een session begonnen.
					//als de gegevens niet in de database voor komen wordt er een vout melding gegeven. 
					if(mysqli_num_rows($uitkomst) >= 1){
						//zorgt er voor dat de andere pagina's kunnen controleren of er ingelogt is.
						$_SESSION["ingelogt"] = true;
						//zet je inlognaam in een variablen.
						$_SESSION["Inlog_ID"] = $username;
						//je wordt door gestuurd naar de hoofd pagina. 
						header("location: ../Index.php");
						//echo "kapot";
					}
					elseif(mysqli_num_rows($uitkomst_docenten) >= 1){
						//zorgt er voor dat de andere pagina's kunnen controleren of er ingelogt is.
						$_SESSION["ingelogt"] = true;
						//zorgt er voor dat de gebruiker wordt gezien als admin
						//zet je inlognaam in een variablen.
						$_SESSION["Admin"] = true;
						$_SESSION["Inlog_ID"] = $username;
						//je wordt door gestuurd naar de hoofd pagina. 
						header("location: ../Index.php");
					}
					else{
						echo "<p class='text-center'>onjuiste gebruikers naam of wachtwoord</p>";
					}
				}
				
				*/
				
				
				
				
				//$sqli="SELECT * FROM login WHERE username='$username' AND password='$password'"; 
			}
		?>
		
	</body>
<html>