<?php
	//session_start();

	//haalt de nodige bestanden op
	require_once("../database.php");
	require_once("Ouderavond_Beheren.function.php");
	
	session_start();
	
	if(!isset($_SESSION["ingelogt"])){
		header("location:../Inlog/login.php");
	}
	if(!isset($_SESSION["Admin"])){
			header("location:../Inlog/login.php");
			//echo "kapot2";
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		

		<title>Ouderavond beheren</title>

		<!-- Bootstrap core CSS -->
		<link href="../Bootstrap/css/bootstrap.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="style.css" rel="stylesheet">
	</head>

	<body class="kleur-effect">
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
				</ul>;					
			</div>			
		</nav>
		<div class="hoofd col-md-8 col-md-offset-2">
			<div class=" col-md-6 col-md-offset-3">
				<button onclick="beeindigen_check()" class="btn btn-danger col-md-12">Huidige ouderavond beeindigen</button>
			</div>
			<?php
				//dit wordt alleen uitgevoerd als de funcite voor de ouderavond beeindigen is geweest.
				if(isset($_GET["check"])){
					if($_GET["check"] == true){
						Ouderavond_beindigen();
						
					}
				}
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				
				//var_dump($_SESSION["beeindigt"]);
				//session_destroy();
				if(isset($_SESSION["beeindigt"])){
					if($_SESSION["beeindigt"] == true){
						echo " <p class='text-center'>de huidige ouder avond is succes vol verwijdert.</p>";
						
						//$temp == 0;
						//$temp ++;
						
						//sleep(1);
						
						//unset($_SESSION["beeindigt"]);
						//unset($_SESSION["temp"]);
					}
				}
				//echo $temp;
			?>
			<br>

			<div class=" col-md-6 col-md-offset-3 ">
				<p class="text-center"> hier kunt u een nieuwe ouderavond plannen. Voor dat u een nieuwe ouderavond plant is het Belangerijk om de ouder eerst te beeindigen. </p>
				<form action='' method='post' class='form-signin text-center'>
					de datum van de eerste dag, de rest van de dagen zijn de dagen die er op volgen. 
					<input type="date" name="Datum" class="form-control" required>
					het aantal dagen dat de ouderavond duurt.
					<input type="number" name="Aantal_Dagen" class="form-control" max="3" required>
					de start tijd van de ouderavond.
					<input type="time" name="Begin_Tijd" class="form-control" required>
					de Eind tijd van de ouderavond
					<input type="time" name="Eind_Tijd" class="form-control" required>
					<br>
					
						<button  type="submit" name="submit" class="btn btn-style btn-block"><span class="glyphicon glyphicon-ok"></span>  Bevestigen</button> 
					
				</form>
				
				<?php
					if(isset($_POST["Datum"]) && isset($_POST["Aantal_Dagen"]) && isset($_POST["Begin_Tijd"]) && isset($_POST["Eind_Tijd"])){
						if(Ouderavond_beginnen($_POST["Datum"], $_POST["Aantal_Dagen"], $_POST["Begin_Tijd"], $_POST["Eind_Tijd"]) == true){
							echo "de nieuwe ouderavond is succesvol gepland";
						}
						else{
							echo "er is iets vout gegaan, probeer het op nieuw.";
						}
					}
				?>
			</div>
			
			
		</div>		
	</body>
	<script>
	function beeindigen_check(){
		var beeindigen_check = confirm("u staat nu op het punt om alle gegevens van de huidig openstaande ouderavonden te verwijderen, dit kan NIET worden terug gedraait. Weet u zeker dat u verder wil?");
		if(beeindigen_check == true){
			beeindigen_check = false;
			window.location.replace("Ouderavond_beheren.php?check=true");
		}
		
		//window.location.replace("Ouderavond_beheren.php?check=true");
	}
	
	
	</script>
	<!--Footer-->
	<div class="footer navbar-fixed-bottom">
        Docent informatie 2016.
        </br>
        &copy; Koen van Kralingen, Paul Backs, Mike de Decker en Jesse van Bree.
    </div>
    <!--Einde footer-->
</html>


	
