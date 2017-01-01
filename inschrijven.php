<?php
	session_start();

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
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

		<title>aanmelden</title>

		<!-- Bootstrap core CSS -->
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet">

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
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="Inlog/loguit.php"><span class="glyphicon glyphicon-log-out"></span> Uitloggen</a></li><!--Uitloggen knop -->					
					</ul>;
				</div>
		</nav>
		<div class="container-fluid" style="overflow:auto;max-height: 90vh;">
			<div class="col-md-2" style='overflow:auto; max-height:85vh'><!--geeft het overzicht (tabel) van de sloten die nog beschikbaar zijn.-->


					<?php
						//maakt een query die er voor zorgt dat het juiste aantal dagen wordt weergegeven.
						$sqli_dagen = "SELECT DISTINCT Datum FROM tijden_binnen_avond WHERE Afgerond='0'";
						$sqli_dagen_uitkomst = mysqli_query($connect, $sqli_dagen);
						//maakt een variabel die aangeeft hoeveel dagen er zijn.
						$max_height = (85 / mysqli_num_rows($sqli_dagen_uitkomst)).'vh';

						while($datum_select = mysqli_fetch_array($sqli_dagen_uitkomst)){
							echo "<div style='overflow:auto; max-height:$max_height'>";
								echo "<table class='table table-condensed'>";
									//geeft boven aan de tabel exstra informatie
									echo "<tr>";
										echo "<td>";
											echo "Dag";
										echo "</td>";
										echo "<td>";
											echo "Datum";
										echo "</td>";
									echo "</tr>";
									//var_dump($datum_select);
									//maakt de querry die alle gegevens ophaalt gerelateerd aan de datum die in de while loop staat.
									$sqli_overzicht = "SELECT Datum, Docent_ID, Begin_Tijd FROM tijden_binnen_avond WHERE Afgerond='0' AND Datum='".$datum_select["Datum"]. "'";
									$sqli_overzicht_uitkomst = mysqli_query($connect, $sqli_overzicht);

									//maakt een while loop die alles in een tabel zet.
									while($row = mysqli_fetch_array($sqli_overzicht_uitkomst)){
										if($row["Docent_ID"] > 0){
											echo "<tr class='warning'>";
												echo "<td>";
													echo date("l", strtotime($row["Datum"]));
												echo "</td>";
												echo "<td>";
													echo $row["Begin_Tijd"];
												echo "</td>";
											echo "</tr>";
										}
										else{
											echo "<tr class='success'>";
												echo "<td>";
													echo date("l", strtotime($row["Datum"]));
												echo "</td>";
												echo "<td>";
													echo $row["Begin_Tijd"];
												echo "</td>";
											echo "</tr>";
										}
									}
									//geeft na de eerste dag een lege regel (voor exstra overzicht
									echo "<tr>";
										echo "<td>";
											echo "";
										echo "</td>";
										echo "<td>";
											echo "";
										echo "</td>";
									echo "</tr>";
								echo "</table>";
							echo "</div>";
						}
					?>

			</div>
			<div class="col-md-8 well">
					<?php
						//controleert of de leerling zich al heeft ingeschreven. 
						$sqli_select_inschrijving = "SELECT Inschrijving_ID FROM inschrijving WHERE Leerling_ID = '".$_SESSION["Inlog_ID"]."' AND Afgerond = 0";
						if(mysqli_num_rows(mysqli_query($connect, $sqli_select_inschrijving)) == 1){
							$sqli_select_gegevens_inschrijving = "SELECT docenten.Voornaam, docenten.Achternaam, tijden_binnen_avond.Begin_tijd, tijden_binnen_avond.Datum FROM inschrijving JOIN tijden_binnen_avond ON tijden_binnen_avond.Tijd_Slot = inschrijving.Tijd_Slot JOIN docenten ON docenten.Docent_ID = tijden_binnen_avond.Docent_ID WHERE Leerling_ID = '".$_SESSION["Inlog_ID"]."' AND inschrijving.Afgerond = 0";
							$row = mysqli_fetch_array(mysqli_query($connect, $sqli_select_gegevens_inschrijving));
							
							//var_dump($row);
							echo "<div class='col-md-12'>";
								echo "hier zijn uw inschrijvings gegevens.";
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
							echo "</div>";
						}
						else{
							//geeft het menue voor het opgeven van waarneer je komt.
							echo "<form action='' method='post' class='form-signin text-center'>";
								echo " kies een datum ";
								echo "<select name='Datum' required>";
									//maatk een querry om alledatums op te halen
									$sqli_datums = "SELECT Datum FROM tijden_binnen_avond WHERE afgerond='0' AND Docent_ID='0' GROUP BY Datum";
									$sqli_datums_uitkomst = mysqli_query($connect, $sqli_datums);
									
									echo "<option value='0'></option>";
									
									while($row = mysqli_fetch_array($sqli_datums_uitkomst)){
										echo "<option value='" . $row["Datum"] . "'>" . $row["Datum"] . "</option>";
									}
								echo "</select>";
								
								echo " kies een deel van de avond ";
								echo "<select name='avond_deel' required>";
									echo "<option value='begin'> begin van de avond</option>";
									echo "<option value='eind'> eind van de avond</option>";
								echo "</select>";
								
								echo " kies een docent ";
								echo "<select name='docent' required>";
									//maatk een querry om alledatums op te halen
									$sqli_docent = "SELECT Docent_ID, Voornaam, Achternaam FROM docenten";
									$sqli_docent_uitkomst = mysqli_query($connect, $sqli_docent);
									
									echo "<option value='0'></option>";
									
									while($row = mysqli_fetch_array($sqli_docent_uitkomst)){
										echo "<option value='" . $row["Docent_ID"] . "'>" . strtoupper(substr($row["Voornaam"], 0, 1)) . ". " . $row["Achternaam"] . "</option>";
									}
								echo "</select>";
								
								echo "<input type='submit' value='submit'>";
							echo "</form>";
						
							//controleert of de post gebeurt is
							if(isset($_POST["Datum"]) && isset($_POST["avond_deel"]) && isset($_POST["docent"])){
								if($_POST["Datum"] != 0){
									if($_POST["docent"] != 0){
										//inschrijven($_POST["Datum"], $_POST["avond_deel"], $_POST["docent"]);
										//zet een variabel die de post goed keurt. 
										$post_check = true;
										
										/*
										if(inschrijven($_POST["Datum"], $_POST["avond_deel"], $_POST["docent"]) == true){
											echo "uw inschrijving is gelukt";
										}
										else{
											echo "er is een onbekende fout opgetreden, probeer het op nieuw of neem contact op met de systeem beheerder. ";
										}
										*/
									}
									else{
										echo "u moet een docent selecteren";
									}
								}
								else{
									echo "u moet een datum selecteren.";
								}
							}
						}
					?>		
			</div>
			
			
			<div class="modal-content col-md-8 col-md-offset-2">
				<?php
					if(isset($post_check)){
						if($post_check == true){
							//echo de datum die is op gegeven in de post
							echo "<div class='col-md-2 col-md-offset-1'>";
								echo "de Datum: <br>";
								echo $_POST["Datum"];
							echo "</div>";
							//geeft aan welk gedeelte van de avond is gekozen
							echo "<div class='col-md-2 col-md-offset-1'>";
								echo "het gedeelte van de avond: <br>";
								if($_POST["avond_deel"] == "begin"){
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
								$sqli_select_docent_naam = "SELECT  Voornaam, Achternaam FROM docenten WHERE Docent_ID='".$_POST["docent"]."'";
								$row = mysqli_fetch_array(mysqli_query($connect, $sqli_select_docent_naam));
								//echo de docent naam
								echo strtoupper(substr($row["Voornaam"], 0, 1)) . ". " . $row["Achternaam"];
							echo "</div>";
							
							//zet alle post variablen in gewoone variablen, dit is nodig want anders verwijnen de ingevoerde gegevens.
							$_SESSION["datum"] = $_POST["Datum"];
							$_SESSION["avond_deel"] = $_POST["avond_deel"];
							$_SESSION["docent"] = $_POST["docent"];
							
							//geeft de knop dat je het er mee eens bent en dat je dit wil laten invoeren.
							//dit doe ik doormiddel van een hidden form met hidden imput
							echo "<form action='' method='post' class='form-signin text-center'>";
								echo "<input type='hidden' value='check' name='check'>";
								echo "<input type='submit' value='submit'>";
							echo "</form>";
						}
					}
					//controleert of de post check gebeurt is.
					if(isset($_POST["check"])){
						//controleert of allegegevens aanwezig zijn.
						if(isset($_SESSION["datum"]) && isset($_SESSION["avond_deel"]) && isset($_SESSION["docent"])){
							//controleert of de fucntie gewerkt heeft.
							if(inschrijven($_SESSION["datum"], $_SESSION["avond_deel"], $_SESSION["docent"]) == true){
								echo "uw inschrijving is gelukt";
							}
							else{
								echo "er is een onbekende fout opgetreden, probeer het op nieuw of neem contact op met de systeem beheerder. ";
							}
							//verwijdert de $_SESSION variabelen
							unset($_SESSION["datum"]);
							unset($_SESSION["avond_deel"]);
							unset($_SESSION["docent"]);
						}
					}
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

<!-- VOORBEELD VAN DE QUERY DIE GEBRUIKT WORDT VOOR HET OPHALEN VAN DE INSCHRIJVINGS GEGEVENS. 
SELECT docenten.Voornaam, docenten.Achternaam, tijden_binnen_avond.Begin_tijd, tijden_binnen_avond.Datum

FROM inschrijving

JOIN tijden_binnen_avond ON tijden_binnen_avond.Tijd_Slot = inschrijving.Tijd_Slot
JOIN docenten ON docenten.Docent_ID = tijden_binnen_avond.Docent_ID

WHERE Leerling_ID = 244384 AND inschrijving.Afgerond = 0

-->