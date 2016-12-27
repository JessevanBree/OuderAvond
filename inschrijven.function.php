<?php
	function inschrijven($datum, $avond_deel, $docent){
		
		
		
		
		require("database.php");
		
		//var_dump($datum, $avond_deel, $docent);
		
		if($avond_deel == "begin"){
			//avond_deel begin (17:00)
			//haalt het eerste mogelijke slot op dat beschikbaar is
			$sqli_tijd_select = "SELECT Tijd_Slot, Docent_ID FROM tijden_binnen_avond WHERE Docent_ID = '0' AND Datum='$datum'";
			$sqli_tijd_select_uitkomst = mysqli_query($connect, $sqli_tijd_select);
			$row = mysqli_fetch_array($sqli_tijd_select_uitkomst);
			
			//we hebben nu het vrije slot, dus we kunnen het slot gaan reserveren en de inschrijving compleet maken. 
			//de inschrijving maken
			$sqli_insert_inschrijving = "INSERT INTO inschrijving (Inschrijving_ID, Leerling_ID, Tijd_Slot, Afgerond) VALUES (DEFAULT, '".$_SESSION["Inlog_ID"]."', '".$row["Tijd_Slot"]."', '0')";
			if(!mysqli_query($connect, $sqli_insert_inschrijving)){
				return false;
			}
			
			//geeft het tijdslot een docent
			$sqli_tijdslot_update = "UPDATE tijden_binnen_avond SET Docent_ID='$docent' WHERE Tijd_Slot = '".$row["Tijd_Slot"]."'";
			if(!mysqli_query($connect, $sqli_tijdslot_update)){
				return false;
			}
			
			//nu is het tijdslot niet meer te kiezen, en heeft de leerling zijn inschrijving voltooid.
			
			return true;
		}
		else{
			//avond_deel eind (21:00)
			//haalt het laatst mogelijke slot op dat beschikbaar is
			$sqli_tijd_select = "SELECT Tijd_Slot, Docent_ID FROM tijden_binnen_avond WHERE Docent_ID = '0' AND Datum='$datum' ORDER BY Tijd_Slot DESC";
			$sqli_tijd_select_uitkomst = mysqli_query($connect, $sqli_tijd_select);
			$row = mysqli_fetch_array($sqli_tijd_select_uitkomst);
			
			//we hebben nu het vrije slot, dus we kunnen het slot gaan reserveren en de inschrijving compleet maken. 
			//de inschrijving maken
			$sqli_insert_inschrijving = "INSERT INTO inschrijving (Inschrijving_ID, Leerling_ID, Tijd_Slot, Afgerond) VALUES (DEFAULT, '".$_SESSION["Inlog_ID"]."', '".$row["Tijd_Slot"]."', '0')";
			if(!mysqli_query($connect, $sqli_insert_inschrijving)){
				return false;
			}
			
			//geeft het tijdslot een docent
			$sqli_tijdslot_update = "UPDATE tijden_binnen_avond SET Docent_ID='$docent' WHERE Tijd_Slot = '".$row["Tijd_Slot"]."'";
			if(!mysqli_query($connect, $sqli_tijdslot_update)){
				return false;
			}
			
			//nu is het tijdslot niet meer te kiezen, en heeft de leerling zijn inschrijving voltooid.
			
			return true;
		}
		
		
	}
	
	
	//eind tijd 9 uur (21:00)
	
?>