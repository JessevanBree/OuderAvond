<?php
	session_start();

	//controleerd of de session niet al verlopen is
	require_once("SESSION_Time_Out.php");

	if(!isset($_SESSION["ingelogt"])){
		header("location:Inlog/login.php");
	}
	
	
	//haalt de nodige bestanden op
	require_once("database.php");
	require_once("inschrijven.function.php");

	//geeft de tijdzone aan waar wij zijn.
	date_default_timezone_set('europe/amsterdam');
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Ouderavond</title>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<link rel="icon" href="favicon.ico" type="image/x-icon">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

		<title>aanmelden</title>

		<!-- Bootstrap core CSS -->
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet">

		<!-- Latest compiled and minified JavaScript -->
		<script src="bootstrap/js/vendor/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>

		<!-- Custom styles for this template -->
		<link href="style-aa.css" rel="stylesheet">
	</head>
	
	<body class="kleur-effect">
		<nav class="navbar navbar-custom">
				<div class="container-fluid">
					<div class="navbar-header">
						<a class="navbar-brand" href="index.php">Ouderavond</a>
					</div>
					<ul class="nav navbar-nav">
						<li class="nav-item">
							<a class="nav-link" href="index.php"><span class="glyphicon glyphicon-th-list"></span> Welkom pagina</a>
						</li>
						<li>
							<a href="Inlog/wachtwoord_wijzigen.php"><span class="glyphicon glyphicon-pencil "></span> Wachtwoord wijzigen</a>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="Inlog/loguit.php"><span class="glyphicon glyphicon-log-out"></span> Uitloggen</a></li><!--Uitloggen knop -->					
					</ul>
				</div>
		</nav>
		<div class="container-fluid" style="overflow:auto;max-height: 90vh;">
			<?php
				if(isset($_GET["ID"])){
					$ID = $_GET["ID"];
					if(is_numeric($ID)){
						//controleert of het ID wel bestaad.
						$sqli_leerling_naam = "SELECT Voornaam, Achternaam FROM docenten WHERE Docent_ID = '$ID'";
						$sqli_leerling_naam_uitkomst = mysqli_query($connect, $sqli_leerling_naam);
						if(mysqli_num_rows($sqli_leerling_naam_uitkomst) == 1){
								require("sloten.php");
						}
						else{
							echo "<div class='col-md-2'></div>";
						}
					}
					else{
						echo "<div class='col-md-2'></div>";
					}
				}
				else{
					echo "<div class='col-md-2'></div>";
				}
			?>
			<div class="col-md-8">
				<div class="col-md-12 well">
						<?php
							//controleert of de leerling zich al heeft ingeschreven.
							$sqli_select_inschrijving = "SELECT DISTINCT Tijd_Slot FROM tijden_binnen_avond WHERE Leerling_ID = '".$_SESSION["Inlog_ID"]."' AND Afgerond = 0 GROUP BY Docent_ID";
							if(mysqli_num_rows(mysqli_query($connect, $sqli_select_inschrijving)) == 3){
								$sqli_select_gegevens_inschrijving = "SELECT DISTINCT docenten.Voornaam, docenten.Achternaam, tijden_binnen_avond.Begin_tijd, tijden_binnen_avond.Datum FROM tijden_binnen_avond JOIN docenten ON docenten.Docent_ID = tijden_binnen_avond.Docent_ID WHERE Leerling_ID = '".$_SESSION["Inlog_ID"]."' AND tijden_binnen_avond.Afgerond = 0";
								$sqli_select_gegevens_inschrijving_uitkomst = mysqli_query($connect, $sqli_select_gegevens_inschrijving);
								//var_dump($row);
								echo "<div class='col-md-12'>";
								echo "<p class='text-center text-style'>hier zijn uw voorlopige inschrijvings gegevens.</p>";
									while($row = mysqli_fetch_array($sqli_select_gegevens_inschrijving_uitkomst)){
										echo "<br>";
										echo "<br>";

										//echo de datum die is ingeplant
										echo "<div class='col-md-2 col-md-offset-1'>";
										echo "de Datum: <br>";
										echo $row["Datum"];
										echo "</div>";
										//echo de tijd waarom je verwacht wordt.
										echo "<div class='col-md-2 col-md-offset-1'>";
										echo "de Tijd: <br>";
										echo $row["Begin_tijd"];
										echo "</div>";
										//echo de docent
										echo "<div class='col-md-2 col-md-offset-1'>";
										echo "de docent: <br>";
										echo strtoupper(substr($row["Voornaam"], 0, 1)) . ". " . $row["Achternaam"];
										echo "</div>";

										echo "<br>";
										echo "<br>";
									}
								echo "</div>";
							}
							else{
								if(isset($ID)){
									//geeft het overzicht van waar je nu ben ingeschreven.
									$sqli_select_gegevens_inschrijving = "SELECT DISTINCT docenten.Voornaam, docenten.Achternaam, tijden_binnen_avond.Begin_tijd, tijden_binnen_avond.Datum FROM tijden_binnen_avond JOIN docenten ON docenten.Docent_ID = tijden_binnen_avond.Docent_ID WHERE Leerling_ID = '".$_SESSION["Inlog_ID"]."' AND tijden_binnen_avond.Afgerond = 0";
									$sqli_select_gegevens_inschrijving_uitkomst = mysqli_query($connect, $sqli_select_gegevens_inschrijving);
									if(mysqli_num_rows($sqli_select_gegevens_inschrijving_uitkomst) >= 1){
										echo "<div class='col-md-12'>";
										echo "<p class='text-center text-style'>hier zijn uw verlopige inschrijvings gegevens.</p>";
										while ($row = mysqli_fetch_array($sqli_select_gegevens_inschrijving_uitkomst)) {
											echo "<br>";
											//echo de datum die is ingeplant
											echo "<div class='col-md-2 col-md-offset-2'>";
											echo "de Datum: <br>";
											echo $row["Datum"];
											echo "</div>";
											//echo de tijd waarom je verwacht wordt.
											echo "<div class='col-md-2 col-md-offset-1'>";
											echo "de Tijd: <br>";
											echo $row["Begin_tijd"];
											echo "</div>";
											//echo de docent
											echo "<div class='col-md-2 col-md-offset-1'>";
											echo "de docent: <br>";
											echo strtoupper(substr($row["Voornaam"], 0, 1)) . ". " . $row["Achternaam"];
											echo "</div>";
											echo "<br>";
											echo "<br>";
										}
										echo "</div>";
										//geeft ruimte tussen het overzicht van inschrijvingen en het formulier
										echo "<div class='spacer col-md-12'></div>";
									}
									echo "<p class='text-center text-style'>kies een datum en een moment van de avond.</p>";
									//geeft het menue voor het opgeven van waarneer je komt.
									echo "<form action='' method='get' class='form-signin text-center'>";
									//maakt een ontzichtbare input om het gekozen docent id bewaardt te laten
									echo "<input type='hidden' name='ID' value='".$ID."'>";

									echo " kies een datum ";
									echo "<select name='Datum' required>";
									//maatk een querry om alledatums op te halen
									$sqli_datums = "SELECT Datum FROM tijden_binnen_avond WHERE afgerond='0' AND Docent_ID='$ID' AND Tijd_Slot NOT IN (SELECT Tijd_Slot FROM tijden_binnen_avond WHERE Afgerond = 0 AND Leerling_ID != 0) GROUP BY Datum";
									$sqli_datums_uitkomst = mysqli_query($connect, $sqli_datums);

									//controleerd of de docent nog datums heeft die kunnen.
									if(mysqli_num_rows($sqli_datums_uitkomst) > 0){
										echo "<option value='0'></option>";

										while($row = mysqli_fetch_array($sqli_datums_uitkomst)){
											echo "<option value='" . $row["Datum"] . "'>" . $row["Datum"] . "</option>";
										}

									}
									else{
										echo "<option value='vol'>Vol</option>";
									}
									echo "</select>";

									echo " kies een deel van de avond ";
									echo "<select name='avond_deel' required>";
									echo "<option value='begin'> begin van de avond</option>";
									echo "<option value='eind'> eind van de avond</option>";
									echo "</select>";
									echo "&nbsp;&nbsp;";
									echo "<input type='submit' class='btn btn-style' value='Bevestigen'>";
									echo "</form>";

									//controleert of de post gebeurt is
									if(isset($_GET["Datum"]) && isset($_GET["avond_deel"]) && isset($ID)){
										if($_GET["Datum"] != 0 && $_GET["Datum"] != 'vol') {
											$post_check = true;
										}
										elseif($_GET["Datum"] == 'vol'){
											echo "<p class='text-center'><br>de docent zit vol, ga naar je mentor om een afspraak te maken.</p>";
										}
										else{
											echo "<p class='text-center'><br>u moet een datum selecteren.</p>";
										}
									}
								}
								else{
									//hier de docent selecteren.
									echo "<div class='text-center'>";
									echo "<p>";
									echo "Kies de docent waarmee je een 10 minuten gesprek wil hebben";
									echo "<p>";
									echo "</div>";
									//zet alle docenten neer als optie
									$sqli_docenten = "SELECT DISTINCT Docent_ID, Voornaam, Achternaam FROM docenten WHERE Docent_ID NOT IN (SELECT DISTINCT Docent_ID FROM tijden_binnen_avond WHERE Leerling_ID = '".$_SESSION["Inlog_ID"]."' AND Afgerond='0') AND Docent_ID IN (SELECT DISTINCT Docent_ID FROM tijden_binnen_avond WHERE Afgerond='0')";
									$sqli_docenten_uitkomst = mysqli_query($connect, $sqli_docenten);
									//controleerd of er wel een ouderavond is
									if(mysqli_num_rows($sqli_docenten_uitkomst) > 0){
										$i = -1;
										echo "<table class='col-md-12'><tr>";
										while($row = mysqli_fetch_array($sqli_docenten_uitkomst)){
											$i++;
											if($i<=2){
												echo "<td>";
												echo "<a href='inschrijven.php?ID=". $row["Docent_ID"] . "'>" . strtoupper(substr($row["Voornaam"], 0, 1)) . ". " . $row["Achternaam"]."<br>";
												echo "</td>";
											}
											else{
												echo "</tr><tr>";
												echo "<td>";
												echo "<a href='inschrijven.php?ID=". $row["Docent_ID"] . "'>" . strtoupper(substr($row["Voornaam"], 0, 1)) . ". " . $row["Achternaam"]."<br>";
												echo "</td>";
												$i = 0;
											}
										}
										echo "</tr></table>";
									}
									else{
										//er is geen ouderavond.
										echo"<p class='text-center'>Er is geen ouderavond gepland, of je hebt ja al meerdere keren ingeschreven</p>";
									}


									//controleert of de leerling zich al heeft ingeschreven, anders krijg je dit voerzicht niet
									$sqli_select_inschrijving = "SELECT DISTINCT Tijd_Slot FROM tijden_binnen_avond WHERE Leerling_ID = '".$_SESSION["Inlog_ID"]."' AND Afgerond = 0 GROUP BY Docent_ID";
									if(mysqli_num_rows(mysqli_query($connect, $sqli_select_inschrijving)) >= 1){
										$sqli_select_gegevens_inschrijving = "SELECT DISTINCT docenten.Voornaam, docenten.Achternaam, tijden_binnen_avond.Begin_tijd, tijden_binnen_avond.Datum FROM tijden_binnen_avond JOIN docenten ON docenten.Docent_ID = tijden_binnen_avond.Docent_ID WHERE Leerling_ID = '".$_SESSION["Inlog_ID"]."' AND tijden_binnen_avond.Afgerond = 0";
										$sqli_select_gegevens_inschrijving_uitkomst = mysqli_query($connect, $sqli_select_gegevens_inschrijving);
										//var_dump($row);

										//geeft ruimte tussen het overzicht van inschrijvingen en het formulier
										echo "<div class='spacer col-md-12'></div>";

										echo "<div class='col-md-12'>";
										echo "<p class='text-center text-style'>hier zijn uw voorlopige inschrijvings gegevens.</p>";
										while($row = mysqli_fetch_array($sqli_select_gegevens_inschrijving_uitkomst)){
											echo "<br>";

											//echo de datum die is ingeplant
											echo "<div class='col-md-2 col-md-offset-2'>";
											echo "de Datum: <br>";
											echo $row["Datum"];
											echo "</div>";
											//echo de tijd waarom je verwacht wordt.
											echo "<div class='col-md-2 col-md-offset-1'>";
											echo "de Tijd: <br>";
											echo $row["Begin_tijd"];
											echo "</div>";
											//echo de docent
											echo "<div class='col-md-2 col-md-offset-1'>";
											echo "de docent: <br>";
											echo strtoupper(substr($row["Voornaam"], 0, 1)) . ". " . $row["Achternaam"];
											echo "</div>";

											echo "<br>";
											echo "<br>";
										}
										echo "</div>";
									}
								}
							}
						?>
				</div>
				<?php
					if(isset($post_check)){
						if($post_check == true){
							echo "<div class=' col-md-12 well'>";
								echo "<div class='col-md-12' >";
									//echo de datum die is op gegeven in de post
									echo "<div class='col-md-2 col-md-offset-1'>";
										echo "de Datum: <br>";
										echo $_GET["Datum"];
									echo "</div>";
									//geeft aan welk gedeelte van de avond is gekozen
									echo "<div class='col-md-3 text-center'>";
										echo "het gedeelte van de avond: <br>";
										if($_GET["avond_deel"] == "begin"){
											echo "begin van de avond";
										}
										else{
											echo "het eind van de avond";
										}
									echo "</div>";
									//geeft de naam van de docent die gekozen is
									echo "<div class='col-md-2 col-md-offset-1'>";
										echo "de docent: <br>";
										//haalt de naam van de docent op uit de database, dit moet omdat in de post alleen een ID zit.
										$sqli_select_docent_naam = "SELECT Voornaam, Achternaam FROM docenten WHERE Docent_ID='$ID'";
										$row = mysqli_fetch_array(mysqli_query($connect, $sqli_select_docent_naam));
										//echo de docent naam
										echo strtoupper(substr($row["Voornaam"], 0, 1)) . ". " . $row["Achternaam"];
									echo "</div>";

									//zet alle post variablen in gewoone variablen, dit is nodig want anders verwijnen de ingevoerde gegevens.
									$_SESSION["datum"] = $_GET["Datum"];
									$_SESSION["avond_deel"] = $_GET["avond_deel"];
									$_SESSION["docent"] = $ID;

									//geeft de knop dat je het er mee eens bent en dat je dit wil laten invoeren.
									//dit doe ik doormiddel van een hidden form met hidden imput
									echo "<form action='' method='post' class='form-signin text-center col-md-2 '>";
										echo "<input type='hidden' value='check' name='check'>";
										echo "<input type='submit' class='btn btn-style' value='Bevestigen'>";
									echo "</form>";
								echo "</div>";

								//controleert of de post check gebeurt is.
								if(isset($_POST["check"])){
									//controleert of allegegevens aanwezig zijn.
									if(isset($_SESSION["datum"]) && isset($_SESSION["avond_deel"]) && isset($_SESSION["docent"])){
										//controleert of de fucntie gewerkt heeft.
										if(inschrijven($_SESSION["datum"], $_SESSION["avond_deel"], $_SESSION["docent"]) == true){
											echo "<div class='col-md-4 col-md-offset-4 text-center '>";
												echo "<p class='Gelukt'><br>";
													echo "uw inschrijving is gelukt <br>";
												echo "</p>";
											echo "</div>";

											//verwijdert de $_SESSION variabelen
											unset($_SESSION["datum"]);
											unset($_SESSION["avond_deel"]);
											unset($_SESSION["docent"]);
											//unset($post_check);
										}
									}
								}
							echo "</div>";
						}
					}
				//var_dump($_SESSION["post_check"]);
				?>
			</div>

		</div> <!-- /container -->
		<div class="footer navbar-fixed-bottom">
            Ouderavond 2016.
            <br/>
            &copy; Koen van Kralingen, Paul Backs, Mike de Decker en Jesse van Bree.
        </div>
		
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<script src="bootstrap/js/ie10-viewport-bug-workaround.js"></script>
	</body>
</html>

