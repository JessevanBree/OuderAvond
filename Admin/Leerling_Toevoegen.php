<?php
	session_start();

	//haalt de nodige bestanden op
	require_once("../database.php");
	require_once("Leerling_Toevoegen.function.php");

    //controleerd of de session niet al verlopen is
    require_once("../SESSION_Time_Out.php");
	
	if(!isset($_SESSION["ingelogt"])){
		header("location:../Inlog/login.php");
	}
	if(!isset($_SESSION["Admin"])){
			header("location:../Inlog/login.php");
			//echo "kapot2";
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Toevoegen</title><!--Website titel -->
		<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
		<link rel="icon" href="../favicon.ico" type="image/x-icon">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

		<!-- Bootstrap core CSS -->
		<link href="../bootstrap/css/bootstrap.css" rel="stylesheet">

		<!-- Latest compiled and minified JavaScript -->
		<script src="../bootstrap/js/vendor/jquery.min.js"></script>
		<script src="../bootstrap/js/bootstrap.min.js"></script>

		<!-- Custom styles for this template -->
		<link href="style.css" rel="stylesheet">
	</head>

	<body class="kleur-effect"> <!--body backgroud kleur -->
		<nav class="navbar navbar-custom">	<!--Nav bar begin -->		
			<div class="container-fluid">
				<div class="navbar-header">				
					<a class="navbar-brand" href="../index.php">Ouderavond</a> <!--Titel navbar -->
				</div>
				<ul class="nav navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="Adminpanel.php"><span class="glyphicon glyphicon-calendar"></span> Docenten</a> <!--Link in navbar -->
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="../Inlog/loguit.php"><span class="glyphicon glyphicon-log-out"></span> Uitloggen</a></li><!--Uitloggen knop -->					
				</ul>					
			</div>			
		</nav>		
		<div class="hoofd-div">
		<!--linker helft-->
			<div class="col-md-offset-1 col-md-4 ">
				<div class="text-center"><!-- Header centeren-->
					<h3>Handmatig leerling toevoegen</h3>	
				</div> <!-- Header div sluiten-->			
				<form action="" method="post" class="form-signin text-center">
					<label for="Leerling_Nummer">Leerling nummer</label>
					<input type="number" name="Leerling_Nummer" class="form-control" value="<?php echo isset($_POST['Leerling_Nummer']) ? $_POST['Leerling_Nummer'] : '' ?>" placeholder="leerling nummer" required>

					<label for="Voornaam">Voornaam</label>
					<input type="text" name="Voornaam" class="form-control" value="<?php echo isset($_POST['Voornaam']) ? $_POST['Voornaam'] : '' ?>" required max="255" placeholder="Voornaam">

					<label for="Voorvoegsel">Voorvoegsel</label>
					<input type="text" name="Voorvoegsel" class="form-control" value="<?php echo isset($_POST['Voorvoegsel']) ? $_POST['Voorvoegsel'] : '' ?>" max="255" placeholder="Voorvoegsel">

					<label for="Achternaam">Achternaam</label>
					<input type="text" name="Achternaam" class="form-control" value="<?php echo isset($_POST['Achternaam']) ? $_POST['Achternaam'] : '' ?>" required max="255" placeholder="Achternaam">

					<label for="Relatie_tot_deelnemer">Relatie tot deelnemer</label>
					<input type="number" name="Relatie_tot_deelnemer" class="form-control" value="<?php echo isset($_POST['Relatie_tot_deelnemer']) ? $_POST['Relatie_tot_deelnemer'] : '' ?>" max="99999999999" placeholder="Relatie tot deelnemer">

					<label for="Begindatum">Begindatum</label>
					<input type="date" name="Begindatum" class="form-control" value="<?php echo isset($_POST['Begindatum']) ? $_POST['Begindatum'] : '' ?>" required max="255"  placeholder="Begindatum">

					<label for="Einddatum">Einddatum</label>
					<input type="date" name="Einddatum" class="form-control" value="<?php echo isset($_POST['Einddatum']) ? $_POST['Einddatum'] : '' ?>" max="255"  placeholder="Einddatum">

					<label for="Plaatsing">Plaatsing</label>
					<input type="text" name="Plaatsing" class="form-control" value="<?php echo isset($_POST['Plaatsing']) ? $_POST['Plaatsing'] : '' ?>" max="255"  placeholder="Plaatsing">

					<label for="Tijdelijk_Wachtwoord">Tijdelijk Wachtwoord</label>
					<input type="password" name="Tijdelijk_Wachtwoord" class="form-control" value="<?php echo isset($_POST['Tijdelijk_Wachtwoord']) ? $_POST['Tijdelijk_Wachtwoord'] : '' ?>" required max="255"  placeholder="Tijdelijk_Wachtwoord">

					<br>
					<button type="submit" class="btn btn-style btn-block" name="submit"><span class="glyphicon glyphicon-ok"></span> Bevestig</button>
					<br>
					<?php
					//haalt de nodige bestanden op
					require_once("../database.php");
					require_once("Leerling_Toevoegen.function.php");

					if(isset($_POST["submit"])){
						//controleert of de gebruiker al bestaad.
						$sqli_leerling_check = "SELECT Leerling_ID FROM leerlingen WHERE Leerling_ID = '".$_POST["Leerling_Nummer"]."'";
						if(mysqli_fetch_array(mysqli_query($connect, $sqli_leerling_check)) == 0){
							//voegt de leerling toe aan het systeem
							if (Leerling_Toevoegen($_POST["Leerling_Nummer"], $_POST["Voornaam"], $_POST["Voorvoegsel"],  $_POST["Achternaam"], $_POST["Relatie_tot_deelnemer"], $_POST["Begindatum"], $_POST["Einddatum"], $_POST["Plaatsing"], $_POST["Tijdelijk_Wachtwoord"]) == true){
								echo"de leerling is toegevoed aan het systeem";
							}
						}
						else{
							//geeft de melding dat de leerling al bestaad
							echo "de leerling bestaad al in het systeem";
						}
					}
					?>
				</form>				

			</div>		
			<!--rechter helft-->
			<div class="col-md-offset-2 col-md-4 csv-div"> 
				<h3>Csv importeren</h3>	 					
				<b>CSV bestand importeren</b>				
				<form action="" method="post" id="form1" style="margin-bottom: 0;" enctype="multipart/form-data">
					<input style="float:left;" type="file" name="csv" accept=".csv">
				</form>
				<br>
				<br>				
				<button type="submit" class="btn btn-style btn-csv" form="form1" value="Submit" ><span class="glyphicon glyphicon-ok"></span> Bevestig</button>
				<br>				
				<?php
					if(isset($_FILES["csv"])){						
						//echo "test";					
						//ob_flush();
						//var_dump($_FILES["csv"]);echo "<br>";
						csv_import();
						//flush();
					}
				?>				
			</div>			
		</div> <!--Einde hoofd container -->		
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<script src="../bootstrap/js/ie10-viewport-bug-workaround.js"></script>
		<!--Footer-->
		<div class="footer navbar-fixed-bottom">
            Ouderavond 2016.
            <br/>
            &copy; Koen van Kralingen, Paul Backs, Mike de Decker en Jesse van Bree.
        </div>
        <!--Einde footer-->
	</body>
</html>


	
