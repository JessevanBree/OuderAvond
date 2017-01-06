<?php
	function Ouderavond_beindigen(){
		//session_start();
		
		require("../database.php");
		
		//maakt een query die alle openstaande tijdsloten sluit. 
		$sqli_tijdsloten_sluit = "UPDATE tijden_binnen_avond SET Afgerond = 1 WHERE Afgerond = 0";
		if(mysqli_query($connect, $sqli_tijdsloten_sluit)){
			//maakt een query die alle onafgeronde bezoeken sluit.
			$sqli_inschrijvingen_sluit = "UPDATE inschrijving SET Afgerond = 1 WHERE Afgerond = 0";
			if(mysqli_query($connect, $sqli_inschrijvingen_sluit)){
				//maakt een variabel aan die wordt gebruikt voor het weergeven van een bericht als het gelukt is.
				$_SESSION["beeindigt"] = true;
				$_SESSION["temp"] = 0;
				//stuurt je naar de zelfde pagina, maar haalt du URL extentie weg.
				header("Location: Ouderavond_beheren.php");
			}
			else{
				echo "er is iets fout gegaan, probeer het op nieuw.";
			}
		}
		else{
			echo "er is iets fout gegaan, probeer het op nieuw.";
		}
	}

	function Ouderavond_beginnen($datum, $Aantal_Dagen, $Begin_Tijd, $Eind_Tijd, $Docenten_Array){
		require("../database.php");
		
		
		//maakt de datum goed
		date_default_timezone_set('europe/amsterdam');
		$datum = date("d-m-Y", strtotime($datum));

		//var_dump(count($Docenten_Array));

		//maakt voor iedere docent de juiste hoeeveelheid sloten aan.
		for($X = 0; $X < count($Docenten_Array); $X++){

			//een for loop die net zo vaak draait als de hoeveel heid dagen.
			for($Y = 0; $Y < $Aantal_Dagen; $Y++){
				//maakt een variabel die gebruikt wordt voor het maken van de pauzes
				$Pauze = 0;

				//berekent het aantal tijdsloten.
				$Temp = round($Eind_Tijd - $Begin_Tijd);
				$Tijd_Sloten = ($Temp * 60 / 10);

				//zogt er voor dat alles makkelijker is om in de db te zetten
				$Begin_Tijd_Getal1 = substr($Begin_Tijd, 0, 2);
				$Begin_Tijd_Getal2 = substr($Begin_Tijd, 3, 4);

				$Eind_Tijd_Getal1 = substr($Begin_Tijd, 0, 2);
				$Eind_Tijd_Getal2 = substr($Begin_Tijd, 3, 4) + 10;

				//een for loop die net zo veel keer draait als er tijd sloten zijn.
				for($Z = 0; $Z < $Tijd_Sloten; $Z++){
					//stelt de begin en eind tijden op nieuw samen.
					$Begin_Tijd_Af = $Begin_Tijd_Getal1 . ":" . $Begin_Tijd_Getal2;
					$Eind_tijd_Af = $Eind_Tijd_Getal1 . ":" . $Eind_Tijd_Getal2;

					//verhoogt de variabel die de pauze moet regelen met 1
					$Pauze ++;

					//controleerdt of het huidige tijslot een pauze moet zijn.
					if($Pauze == 13){
						//geeft dit slot als pauze, dit wordt gedaan door geen mogelijk in teplannen voor een leerling
					}
					elseif($Pauze == 14){
						//geeft dit slot als pauze, dit wordt gedaan door geen mogelijk in teplannen voor een leerling
						//zet de waarde waar de pauze mee gecontroleerd wordt weer terug op een.
						$Pauze = 0;
					}
					else{
						//zet het tijdslot in de database
						$sqli_insert = "INSERT INTO tijden_binnen_avond (Tijd_Slot, Datum, Docent_ID, Leerling_ID,Begin_Tijd, Eind_tijd, Afgerond) VALUES (DEFAULT, '$datum', '". $Docenten_Array[$X] ."','0', '$Begin_Tijd_Af', '$Eind_tijd_Af', DEFAULT)";
						mysqli_query($connect, $sqli_insert);
					}

					//weizigt de getallen die in de db moeten komen.
					if($Begin_Tijd_Getal2 == 50){
						$Begin_Tijd_Getal1 ++;
						$Begin_Tijd_Getal2 = 00;
					}
					else{
						$Begin_Tijd_Getal2 = $Begin_Tijd_Getal2 + 10;
					}

					if($Eind_Tijd_Getal2 == 50){
						$Eind_Tijd_Getal1 ++;
						$Eind_Tijd_Getal2 = 00;
					}
					else{
						$Eind_Tijd_Getal2 = $Eind_Tijd_Getal2 + 10;
					}
				}
				//verhoogt de datum met 1 dag
				$datum = date("d-m-Y", strtotime($datum . '+1 day'));

			}

		}
		return true;
	}
?>
