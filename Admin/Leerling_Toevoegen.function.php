<?php
	//de fucntie om een enkele leerling toetevoegen.
	function Leerling_Toevoegen($Leerling_Nummer, $Voornaam, $Voorvoegsel, $Achternaam, $Relatie_tot_deelnemer, $Begindatum, $Einddatum, $Plaatsing, $Tijdelijk_Wachtwoord){
		//haalt de nodige bestanden op
		require("../database.php");
		
		//beveiligen tegen SQL injection en scripting

        $Leerling_Nummer = stripcslashes($Leerling_Nummer);
		$Voornaam = stripcslashes($Voornaam);
        $Voorvoegsel = stripcslashes($Voorvoegsel);
		$Achternaam = stripcslashes($Achternaam);
        $Relatie_tot_deelnemer = stripcslashes($Relatie_tot_deelnemer);
        $Begindatum = stripcslashes($Begindatum);
        $Einddatum = stripcslashes($Einddatum);
        $Plaatsing = stripcslashes($Plaatsing);
		$Tijdelijk_Wachtwoord = stripcslashes($Tijdelijk_Wachtwoord);

        $Leerling_Nummer = mysqli_real_escape_string($connect, $Leerling_Nummer);
		$Voornaam = mysqli_real_escape_string($connect, $Voornaam);
        $Voorvoegsel = mysqli_real_escape_string($connect, $Voorvoegsel);
		$Achternaam = mysqli_real_escape_string($connect, $Achternaam);
        $Relatie_tot_deelnemer = mysqli_real_escape_string($connect, $Relatie_tot_deelnemer);
        $Begindatum = mysqli_real_escape_string($connect, $Begindatum);
        $Einddatum = mysqli_real_escape_string($connect, $Einddatum);
        $Plaatsing = mysqli_real_escape_string($connect, $Plaatsing);
		$Tijdelijk_Wachtwoord = mysqli_real_escape_string($connect, $Tijdelijk_Wachtwoord);

		//bereid de querry voor de de gegevens in de database zetten
		$sqli_gegevensinvoer = "INSERT INTO leerlingen 
							(
								Leerling_ID,
                                Voornaam,
                                Voorvoegsel,
                                Achternaam,
                                Relatie_tot_deelnemer,
                                Begindatum,
                                Einddatum,
                                Plaatsing,
                                Wachtwoord,
                                Salt,
                                Eerste_Inlog
							)
							VALUES
							(
								'$Leerling_Nummer',
								'$Voornaam',
								'$Voorvoegsel',
								'$Achternaam',
								'$Relatie_tot_deelnemer',
								'$Begindatum',
								'$Einddatum',
								'$Plaatsing',
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
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				//var_dump($data);
				$data = explode(";", $data[0]);
				//var_dump($data);
				
				//bekijkt of de inlog waarde al bestaad binnen de database
				$sqli_inlog_check = "SELECT Leerling_ID FROM leerlingen WHERE Leerling_ID='$data[0]'";
				$sqli_inlog_check_uitkomst = mysqli_query($connect, $sqli_inlog_check);
				//var_dump($sqli_inlog_check_uitkomst);
				if(mysqli_num_rows($sqli_inlog_check_uitkomst) != 1){
                    //maatk het tijdelijke wachtwoord aan.
                    $Wachtwoord = $data[1] . $data[2] . $data[3];
                    //maakt de salt en de eerste inlog aan.
                    $Salt = 0;
                    $Eerste_Innlog = 1;

					//maakt de querry
					$sqli_gegevensinvoer = "INSERT INTO leerlingen 
					(
						Leerling_ID,
						Voornaam,
						Voorvoegsel,
						Achternaam,
						Relatie_tot_deelnemer,
						Begindatum,
						Einddatum,
						Plaatsing,
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
						'$Wachtwoord',
						'$Salt',
						'$Eerste_Innlog'
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