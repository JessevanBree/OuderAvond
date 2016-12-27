<?php
	//de fucntie om een enkele leerling toetevoegen.
	function Leerling_Toevoegen($Leerling_Nummer, $Voornaam, $Achternaam, $Telefoon, $Email, $Woonplaats, $Straatnaam, $Huisnummer, $Postcode, $Tijdelijk_Wachtwoord){
		//haalt de nodige bestanden op
		require("../database.php");
		
		//beveiligen tegen SQL injection en scripting
		$Voornaam = stripcslashes($Voornaam);
		$Achternaam = stripcslashes($Achternaam);
		$Email = stripcslashes($Email);
		$Woonplaats = stripcslashes($Woonplaats);
		$Straatnaam = stripcslashes($Straatnaam);
		$Tijdelijk_Wachtwoord = stripcslashes($Tijdelijk_Wachtwoord);
		
		$Voornaam = mysqli_real_escape_string($connect, $Voornaam);
		$Achternaam = mysqli_real_escape_string($connect, $Achternaam);
		$Email = mysqli_real_escape_string($connect, $Email);
		$Woonplaats = mysqli_real_escape_string($connect, $Woonplaats);
		$Straatnaam = mysqli_real_escape_string($connect, $Straatnaam);
		$Tijdelijk_Wachtwoord = mysqli_real_escape_string($connect, $Tijdelijk_Wachtwoord);
		
		//bereid de querry voor de de gegevens in de database zetten
		$sqli_gegevensinvoer = "INSERT INTO leerlingen 
							(
								Leerling_ID,
								Voornaam,
								Achternaam,
								Telefoon,
								Email,
								Woonplaats,
								Straatnaam,
								Huisnummer,
								Postcode,
								Wachtwoord,
								Salt,
								Eerste_Inlog
							)
							VALUES
							(
								'$Leerling_Nummer',
								'$Voornaam',
								'$Achternaam',
								'$Telefoon',
								'$Email',
								'$Woonplaats',
								'$Straatnaam',
								'$Huisnummer',
								'$Postcode',
								'$Tijdelijk_Wachtwoord',
								'0',
								'0'
							)";
		//var_dump($sqli_gegevensinvoer);
		if(mysqli_query($connect, $sqli_gegevensinvoer)){
			return true;
		}
		else{
			echo "als u deze melding ziet, neem dan contact op met het de beheerder van het systeem <br> error: 15";
		}
	}
	
	//de fucntie om een csv bestad te importeren.
	
	
	//geeft een modal met daarin "loading"
	//echo "<script> var modal_open = true </script>";
	
	function csv_import(){
		//haalt de nodige bestanden op
		require("../database.php");
		//$connect = mysqli_connect("localhost", "root", "usbw", "ouder_avond");
		
		//maakt een waarde aan voor te kijken hoeveel er niet zijn toegevoegd.
		$NietToegevoegd = 0;
		$Toegevoegd = 0;
		
		//echo "<script> var modal_open = true </script>";
		
		if (($handle = fopen($_FILES["csv"]["tmp_name"], "r")) !== FALSE) {
			
			//
			
			
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				//var_dump($data);
				$data = explode(";", $data[0]);
				//var_dump($data);
				
				//bekijkt of de inlog waarde al bestaad binnen de database
				$sqli_inlog_check = "SELECT Leerling_ID FROM leerlingen WHERE Leerling_ID='$data[0]'";
				$sqli_inlog_check_uitkomst = mysqli_query($connect, $sqli_inlog_check);
				//var_dump($sqli_inlog_check_uitkomst);
				if(mysqli_num_rows($sqli_inlog_check_uitkomst) != 1){
					//maakt de querry
					$sqli_gegevensinvoer = "INSERT INTO leerlingen 
					(
						Leerling_ID,
						Voornaam,
						Achternaam,
						Telefoon,
						Email,
						Woonplaats,
						Straatnaam,
						Huisnummer,
						Postcode,
						Wachtwoord,
						Salt,
						Eerste_Inlog
					)
					VALUES
					(
						'$data[0]',
						'$data[1]',
						'$data[2]',
						'$data[3]',
						'$data[4]',
						'$data[5]',
						'$data[6]',
						'$data[7]',
						'$data[8]',
						'$data[9]',
						'data[10]',
						'data[11]'
					)";
					
					//var_dump($sqli_insert);echo "<br>";
					//voert de querry uit
					if(mysqli_query($connect, $sqli_gegevensinvoer)){
						$Toegevoegd ++;
					}
					else{
						//echo "<script> var modal_open = false </script>";
						echo "als u deze melding ziet, neem dan contact op met het de beheerder van het systeem <br> error: 13";
						return false;
					}
					
					
					
					//omtezorgen dat het script zeer grote bestanden kan importeren.
					set_time_limit(0);
				}
				else{
					$NietToegevoegd ++;
				}
			}
			fclose($handle);
			//echo "<script> var modal_open = false </script>";
			echo "Aantal toegevoegde leerlingen: " . $Toegevoegd;
			echo "<BR>";
			echo "Aantal albestaande leerlingen: " . $NietToegevoegd;
		}
		else{
			echo "als u deze melding ziet, neem dan contact op met het de beheerder van het systeem <br> error: 14";
		}
	}
	
	
	
	
?>