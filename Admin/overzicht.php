<?php
	session_start();

	//haalt de nodige bestanden op
	require_once("../database.php");

    //controleerd of de session niet al verlopen is
    require_once("../SESSION_Time_Out.php");
	
	if(!isset($_SESSION["ingelogt"])){
		header("location:../Inlog/login.php");
	}
	if(!isset($_SESSION["Admin"])){
			header("location:../Inlog/login.php");
			//echo "kapot2";
	}
  

  //sql codes ophalen voor overzicht
  $sqlDatums = "SELECT DISTINCT(tijden_binnen_avond.Datum) FROM tijden_binnen_avond 
  JOIN docenten ON tijden_binnen_avond.Docent_ID=docenten.Docent_ID 
  WHERE docenten.Afkorting = '".$_SESSION["Inlog_ID"]."'
  ORDER BY tijden_binnen_avond.Datum"; //CONTROLEER leerlingID of dit goed is
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
		<link rel="icon" href="../favicon.ico" type="image/x-icon">
		<title>Overzicht</title><!--Website titel -->
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
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						<div class="alert alert-info alert-dismissible " role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<strong>Uitleg</strong> Dit is de overzichtpagina. Hier staan alle gesprekken die u heeft met de leerlingen die op een bepaalde datum zijn ingeschreven.
						</div>
					</div>
				</div>
		<?php
	//Query uitvoeren
    $result = mysqli_query($connect,$sqlDatums);
	
	//Dit draait aan het aantal Datums wat er zijn
    while($row = mysqli_fetch_array($result)){
      

      
      ?>
      <div class="col-md-6">
        <div class="text-center">

          <h2><?php echo $row['Datum']; ?></h2>
        </div>

        <?php
		//Alle gegevens van de leerlingen + tijd
        $sqlGegevens = "SELECT leerlingen.Achternaam, leerlingen.Voornaam, leerlingen.Voorvoegsel, tijden_binnen_avond.Leerling_ID, tijden_binnen_avond.Begin_Tijd, tijden_binnen_avond.Eind_Tijd 
						FROM tijden_binnen_avond 
						JOIN docenten ON tijden_binnen_avond.Docent_ID=docenten.Docent_ID 
						JOIN leerlingen ON tijden_binnen_avond.Leerling_ID = leerlingen.Leerling_ID 
						WHERE docenten.Afkorting = '".$_SESSION["Inlog_ID"]."' 
						AND tijden_binnen_avond.Datum = '".$row['Datum']."';"; 
        $result1 = mysqli_query($connect,$sqlGegevens);
		//echo $sqlGegevens; //Controle van sql code
		//echo $sqlDatums; // Controle SQL
		
		//Als er geen gesprekken wordt er een melding weergeven
		$totaal = mysqli_num_rows($result1);
		if($totaal > 0){
			?>
			<table class="table table-striped">
						<thead>
							<tr>
								<td>Leerlingnummer</td>
								<td>Naam</td>
								<td>Start / Eindtijd</td>
							</tr>
						</thead>
						<tbody>
			<?php
		}else{
			?>
			<div class="alert alert-danger col-sm-11" role="alert"><strong>Let op!</strong> Geen leerling heeft zich op deze datum ingeschreven</div>
			<?php
		}
		
		
		
		
		
		$result1 = mysqli_query($connect,$sqlGegevens);
		//Draait zolang er records zijn per datum
        while($row = mysqli_fetch_assoc($result1)){
			//zet de naam goed neer
			if(!empty($row["Voorvoegsel"])){
				$naam = $row['Voornaam'] . "&nbsp" . $row["Voorvoegsel"] .  "&nbsp"  . $row['Achternaam'];
			}
			else{
				$naam = $row['Voornaam'] . "&nbsp" . $row['Achternaam'];
			}
          ?>			
		
						<tr>
							<td><?php echo $row['Leerling_ID']; ?></td>
							<td><?php echo $naam; ?></td>
							<td><?php echo $row['Begin_Tijd'] ." / ".$row['Eind_Tijd'] ; ?></td>
						</tr>
							
					<?php
        }
        ?>
					
					</tbody>
				</table>
			
      </div>
      <?php
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


	
