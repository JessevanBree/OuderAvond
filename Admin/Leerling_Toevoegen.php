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
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<link rel="icon" href="../Afbeeldingen/login.png">

		<!-- Bootstrap core CSS -->
		<link href="../bootstrap/css/bootstrap.css" rel="stylesheet">

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
						<a class="nav-link" href="Adminpanel.php"><span class="glyphicon glyphicon-calendar"></span> Admin overzicht</a> <!--Link in navbar -->
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
					<label for="Achternaam">Achternaam</label>
					<input type="text" name="Achternaam" class="form-control" value="<?php echo isset($_POST['Achternaam']) ? $_POST['Achternaam'] : '' ?>" required max="255" placeholder="Achternaam">
					<label for="Telefoon">Telefoon</label>
					<input type="number" name="Telefoon" class="form-control" value="<?php echo isset($_POST['Telefoon']) ? $_POST['Telefoon'] : '' ?>" required max="99999999999" placeholder="Telefoon">
					<label for="email">E-mail</label>
					<input type="email" name="Email" class="form-control" value="<?php echo isset($_POST['Email']) ? $_POST['Email'] : '' ?>" required max="255"  placeholder="E-mail">
					<label for="Woonplaats">Woonplaats</label>
					<input type="text" name="Woonplaats" class="form-control" value="<?php echo isset($_POST['Woonplaats']) ? $_POST['Woonplaats'] : '' ?>" required max="255"  placeholder="Woonplaats">
					<label for="Straatnaam">Straatnaam</label>
					<input type="text" name="Straatnaam" class="form-control" value="<?php echo isset($_POST['Straatnaam']) ? $_POST['Straatnaam'] : '' ?>" required max="255"  placeholder="Straatnaam"> 
					<label for="Huisnummer">Huisnummer</label>
					<input type="number" name="Huisnummer" class="form-control" value="<?php echo isset($_POST['Huisnummer']) ? $_POST['Huisnummer'] : '' ?>" required max="255"  placeholder="Huisnummer">
					<label for="Postcode">Postcode</label>
					<input type="text" name="Postcode" pattern="[0-9]{4}[A-Za-z]{2}" title="4 nummers 2 letters" class="form-control" value="<?php echo isset($_POST['Postcode']) ? $_POST['Postcode'] : '' ?>" required max="6"  placeholder="Postcode">
					<label for="Tijdelijk_Wachtwoord">Tijdelijk wachtwoord</label>
					<input type="text" name="Tijdelijk_Wachtwoord" class="form-control" value="<?php echo isset($_POST['Tijdelijk_Wachtwoord']) ? $_POST['Tijdelijk_Wachtwoord'] : '' ?>" required max="255"  placeholder="Tijdelijk wachtwoord">
					<br>
					<button type="submit" class="btn btn-style btn-block" name="submit"><span class="glyphicon glyphicon-ok"></span> Bevestig</button>
				</form>				
				<?php
					//haalt de nodige bestanden op
					require_once("../database.php");
					require_once("Leerling_Toevoegen.function.php");
					
					if(isset($_POST["submit"])){
						//controleert of de gebruiker al bestaad.
						$sqli_leerling_check = "SELECT Leerling_ID FROM leerlingen WHERE Leerling_ID = '".$_POST["Leerling_Nummer"]."'";
						if(mysqli_fetch_array(mysqli_query($connect, $sqli_leerling_check)) == 0){
							//voegt de leerling toe aan het systeem
							if (Leerling_Toevoegen($_POST["Leerling_Nummer"], $_POST["Voornaam"], $_POST["Achternaam"], $_POST["Telefoon"], $_POST["Email"], $_POST["Woonplaats"], $_POST["Straatnaam"], $_POST["Huisnummer"], $_POST["Postcode"], $_POST["Tijdelijk_Wachtwoord"]) == true){
								echo"de leerling is toegevoed aan het systeem";
							}
						}
						else{
							//geeft de melding dat de leerling al bestaad
							echo "de leerling bestaad al in het systeem";
						}
					}					
				?>			
			</div>		
			<!--rechter helft-->
			<div class="col-md-offset-2 col-md-4 csv-div"> 
				<h3>Csv importeren</h3>	 					
				<b>CSV bestand importeren</b>				
				<form action="" method="post" id="form1" style="margin-bottom: 0px;" enctype="multipart/form-data">
					<input style="float:left;" type="file" name="csv" accept=".csv">
				</form>
				<br>
				<br>				
				<button type="submit" class="btn btn-style btn-csv" form="form1" value="Submit" onclick="modal_open()"><span class="glyphicon glyphicon-ok"></span> Bevestig
				</button>
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
		<script src="../Bootstrap/js/ie10-viewport-bug-workaround.js"></script>		
		<script>
			
			var modal_open = false;
			
			//haald de modal op
			var modal = document.getElementById("myModal");
		
			// Get the <span> element that closes the modal
			var span = document.getElementsByClassName("close")[0];
			
			
			//var modal_open = true;
			
			
			
			if(modal_open == true){
				modal.style.display = "block";
			}
			
			function modal_open(){
				
				modal.style.display = "block";
				
				alert("test");
			}
		</script>
		<!--Footer-->
		<div class="footer navbar-fixed-bottom">
            Ouderavond 2016.
            </br>
            &copy; Koen van Kralingen, Paul Backs, Mike de Decker en Jesse van Bree.
        </div>
        <!--Einde footer-->
	</body>
</html>


	
