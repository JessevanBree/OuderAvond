<?php

	session_start();

    //controleerd of de session niet al verlopen is
    require_once("../SESSION_Time_Out.php");

	if(!isset($_SESSION["ingelogt"])){
		header("location:../Inlog/login.php");
	}
	if(!isset($_SESSION["Admin"])){
			header("location:../Inlog/login.php");
			//echo "kapot2";
	}

date_default_timezone_set("Europe/Amsterdam");
include("../database.php");

//Datums van vandaag (Voor Querys)
$datumVandaag = date("d-m-Y");

$sqlGeenAfspraak = "SELECT inschrijving.Leerling_ID, leerlingen.Voornaam, leerlingen.Achternaam FROM inschrijving JOIN leerlingen ON inschrijving.Leerling_ID=leerlingen.Leerling_ID WHERE inschrijving.Tijd_Slot = 0 ORDER BY leerlingen.Achternaam ASC;";
$sqlVandaag = "SELECT inschrijving.Leerling_ID, leerlingen.Voornaam, leerlingen.Achternaam, tijden_binnen_avond.Begin_Tijd FROM inschrijving JOIN leerlingen ON inschrijving.Leerling_ID= leerlingen.Leerling_ID JOIN tijden_binnen_avond ON inschrijving.Tijd_Slot= tijden_binnen_avond.Tijd_Slot WHERE tijden_binnen_avond.Docent_ID=1 AND inschrijving.Afgerond=1 AND tijden_binnen_avond.Datum = '$datumVandaag' ORDER BY tijden_binnen_avond.Begin_Tijd";
$sqlMorgen = "SELECT inschrijving.Leerling_ID, leerlingen.Voornaam, leerlingen.Achternaam, tijden_binnen_avond.Begin_Tijd FROM inschrijving JOIN leerlingen ON inschrijving.Leerling_ID= leerlingen.Leerling_ID JOIN tijden_binnen_avond ON inschrijving.Tijd_Slot= tijden_binnen_avond.Tijd_Slot WHERE tijden_binnen_avond.Docent_ID=1 AND inschrijving.Afgerond=1 AND tijden_binnen_avond.Datum = '$datumVandaag'+1 ORDER BY tijden_binnen_avond.Begin_Tijd";
$sqlLaterWeek = "SELECT inschrijving.Leerling_ID, leerlingen.Voornaam, leerlingen.Achternaam, tijden_binnen_avond.Datum, tijden_binnen_avond.Begin_Tijd FROM inschrijving JOIN leerlingen ON inschrijving.Leerling_ID= leerlingen.Leerling_ID JOIN tijden_binnen_avond ON inschrijving.Tijd_Slot= tijden_binnen_avond.Tijd_Slot WHERE tijden_binnen_avond.Docent_ID=1 AND inschrijving.Afgerond=1 AND tijden_binnen_avond.Datum ORDER BY tijden_binnen_avond.Begin_Tijd";
$sqlGeenAfspraak = "SELECT inschrijving.Leerling_ID, leerlingen.Voornaam, leerlingen.Achternaam FROM inschrijving JOIN leerlingen ON inschrijving.Leerling_ID=leerlingen.Leerling_ID WHERE inschrijving.Tijd_Slot = 0 ORDER BY leerlingen.Achternaam ASC;";

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Overzicht</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

        <!-- Bootstrap core CSS -->
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="Styles/styles.css" rel="stylesheet">
    </head>

  <body class="kleur-effect">
    <nav class="navbar navbar-custom">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="../index.php">Ouderavond</a>
        </div>
        <ul class="nav navbar-nav">
          <li class="navbar-active"><a href="../index.php"><span class="glyphicon glyphicon-calendar"></span> Index</a></li>
          <li><a href="../Inlog/wachtwoord_wijzigen.php"><span class="glyphicon glyphicon-pencil "></span> Wachtwoord wijzigen</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="#"><span class="glyphicon glyphicon-log-out"></span> Uitloggen</a></li>
          <!-- <li><button class="btn navbar-btn"type="button" name="button">Uitloggen</button></li> -->
        </ul>
      </div>
    </nav>
    <article class="container-fluid">
      <div class="row">
        <center>
        <?php
		
        echo ("Datum van vandaag: ". $datumVandaag);
        ?>
        <center>
        <br><br>
      </div>

      <div class="col-lg-3 col-md-6">
        <fieldset>
          <legend>Welke leerlingen komen vandaag</legend>
          <table class="table table-striped">
            <tr>
              <th>Leerlingnummer</th>
              <th>Leerlingnaam</th>
              <th>tijd</th>
            </tr>
			<?php
			$query = mysqli_query($connect,$sqlVandaag);
			while($row = mysqli_fetch_assoc($query)){
				echo ("<tr>");
					echo ("<td>" .$row['Leerling_ID'] ."</td>");
					echo ("<td>" .$row['Voornaam'] . " " . $row['Achternaam']. "</td>");
					echo ("<td>" .$row['Begin_Tijd'] ."</td>");
				echo ("</tr>");
			}
			?>
          </table>
        </fieldset>
      </div>

      <div class="col-lg-3 col-md-6">
        <fieldset>
          <legend>Welke leerlingen komen er morgen</legend>
        </fieldset>
        <table class="table table-striped">
          <tr>
            <th>Leerlingnummer</th>
            <th>Leerlingnaam</th>
            <th>tijd</th>
          </tr>
		  <?php
			$query = mysqli_query($connect,$sqlMorgen);
			while($row = mysqli_fetch_assoc($query)){
				echo ("<tr>");
					echo ("<td>" .$row['Leerling_ID'] ."</td>");
					echo ("<td>" .$row['Voornaam'] . " " . $row['Achternaam']. "</td>");
					echo ("<td>" .$row['Begin_Tijd'] ."</td>");
				echo ("</tr>");
			}
			?>
        </table>
      </div>

      <div class="col-lg-3 col-md-6">
        <fieldset>
          <legend>Welke leerlingen komen Later deze week</legend>
        </fieldset>
        <table class="table table-striped">
          <tr>
            <th>Leerlingnummer</th>
            <th>Leerlingnaam</th>
            <th>Datum + tijd</th>
          </tr>
		  <?php
			$query = mysqli_query($connect,$sqlLaterWeek);
			while($row = mysqli_fetch_assoc($query)){
				echo ("<tr>");
					echo ("<td>" .$row['Leerling_ID'] ."</td>");
					echo ("<td>" .$row['Voornaam'] . " " . $row['Achternaam']. "</td>");
					echo ("<td>" . $row['Datum'] ." ".$row['Begin_Tijd'] ."</td>");
				echo ("</tr>");
			}
		?>
        </table>
      </div>

      <div class="col-lg-3 col-md-6">
        <fieldset>
          <legend>Welke leerlingen hebben nog geen afspraak</legend>
        </fieldset>
        <table class="table-striped table">
          <tr>
            <th>Leerlingnummer</th>
            <th>Leerlingnaam</th>
          </tr>
		  <?php
		  $query = mysqli_query($connect,$sqlGeenAfspraak);
		  while($row = mysqli_fetch_assoc($query)){
			echo ("<tr>");
				echo ("<td>" .$row['Leerling_ID'] ."</td>");
				echo ("<td>" .$row['Voornaam'] . " " . $row['Achternaam']. "</td>");
			echo ("</tr>");
		  }
		  ?>
        </table>
      </div>

    </article>
    <!--Footer-->
    <div class="footer navbar-fixed-bottom">
      Docent informatie 2016.
      <br/>
      &copy; Koen van Kralingen, Paul Backs, Mike de Decker en Jesse van Bree.
    </div>
    <!--Einde footer-->
</html>
