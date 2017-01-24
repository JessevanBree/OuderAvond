<?php
	function inschrijven($datum, $avond_deel, $docent){
		require("database.php");
		
		//var_dump($datum, $avond_deel, $docent);
		
		if($avond_deel == "begin"){
			//avond_deel begin (17:00)
			//haalt het eerste mogelijke slot op dat beschikbaar is
            $sqli_tijd_select = "SELECT Tijd_Slot, Docent_ID, Begin_Tijd FROM tijden_binnen_avond WHERE Leerling_ID = '0' AND Datum='$datum' AND Docent_ID = '$docent' AND Begin_Tijd NOT IN (SELECT Begin_Tijd FROM tijden_binnen_avond WHERE Afgerond='0' AND Datum='$datum' AND Leerling_ID ='".$_SESSION["Inlog_ID"]."')";
            $sqli_tijd_select_uitkomst = mysqli_query($connect, $sqli_tijd_select);
			$row = mysqli_fetch_array($sqli_tijd_select_uitkomst);


            //controleerd of je al ben ingeschreven bij deze docent.
            $sqli_inschrijving_check = "SELECT Tijd_Slot FROM tijden_binnen_avond WHERE Docent_ID = '$docent' AND Afgerond='0' AND Leerling_ID ='".$_SESSION["Inlog_ID"]."' ";
            $sqli_inschrijving_check_uitkomst = mysqli_query($connect, $sqli_inschrijving_check);
            if(mysqli_num_rows($sqli_inschrijving_check_uitkomst) >= 1){
                //je bent ingeschreven bij deze docent.
                echo "<p class='text-center col-md-12''><br>U bent al ingeschreven voor deze docent. <br> klik <a href='inschrijven.php'>hier</a> om u inteschrijven voor een andere docent </p>";
            }
            else{
                //je bent niet ingeschrven bij deze docent.
                $sqli_tijdslot_update = "UPDATE tijden_binnen_avond SET Leerling_ID ='".$_SESSION["Inlog_ID"]."' WHERE Docent_ID='$docent' AND Datum='$datum' AND Tijd_Slot = '".$row["Tijd_Slot"]."'";
                if(!mysqli_query($connect, $sqli_tijdslot_update)){
                    return false;
                }
                //nu is het tijdslot niet meer te kiezen, en heeft de leerling zijn inschrijving voltooid.
                return true;
            }
		}
		else{
            //avond_deel eind (21:00)
            //haalt het laatst mogelijke slot op dat beschikbaar is
            $sqli_tijd_select = "SELECT Tijd_Slot FROM tijden_binnen_avond WHERE Leerling_ID = '0' AND Datum='$datum' AND Docent_ID = '$docent' AND Begin_Tijd NOT IN (SELECT Begin_Tijd FROM tijden_binnen_avond WHERE Afgerond='0' AND Datum='$datum' AND Leerling_ID ='".$_SESSION["Inlog_ID"]."') ORDER BY Tijd_Slot DESC";
            $sqli_tijd_select_uitkomst = mysqli_query($connect, $sqli_tijd_select);
            $row = mysqli_fetch_array($sqli_tijd_select_uitkomst);

            //controleerd of je al ben ingeschreven bij deze docent.
            $sqli_inschrijving_check = "SELECT Tijd_Slot FROM tijden_binnen_avond WHERE Docent_ID = '$docent'";
            $sqli_inschrijving_check_uitkomst = mysqli_query($connect, $sqli_inschrijving_check);
            if (mysqli_num_rows($sqli_inschrijving_check_uitkomst) >= 1) {
                //je bent ingeschreven bij deze docent.
                echo "<p class='text-center col-md-12'><br>U bent al ingeschreven voor deze docent. <br> klik <a href='inschrijven.php'>hier</a> om u inteschrijven voor een andere docent </p>";
            } else {
                //je bent niet ingeschrven bij deze docent.
                $sqli_tijdslot_update = "UPDATE tijden_binnen_avond SET Leerling_ID ='" . $_SESSION["Inlog_ID"] . "' WHERE Docent_ID='$docent' AND Datum='$datum' AND Tijd_Slot = '" . $row["Tijd_Slot"] . "'";
                if (!mysqli_query($connect, $sqli_tijdslot_update)) {
                    return false;
                }
                //nu is het tijdslot niet meer te kiezen, en heeft de leerling zijn inschrijving voltooid.
                return true;
            }
        }
		
	}
?>