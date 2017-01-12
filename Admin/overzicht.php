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
  $sqlDatums = "SELECT DISTINCT(tijden_binnen_avond.Datum) FROM tijden_binnen_avond ORDER BY tijden_binnen_avond.Datum";
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Overzicht</title><!--Website titel -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

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
						<a class="nav-link" href="Adminpanel.php"><span class="glyphicon glyphicon-calendar"></span> Docenten</a> <!--Link in navbar -->
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="../Inlog/loguit.php"><span class="glyphicon glyphicon-log-out"></span> Uitloggen</a></li><!--Uitloggen knop -->					
				</ul>					
			</div>			
		</nav>		
		<div class="hoofd-div">
		<?php
    $result = mysqli_query($connect,$sqlDatums);

    while($row = mysqli_fetch_array($result)){
      

      
      ?>
      <div class="col-md-4">
        <div class="text-center">
          <h2><?php echo $row['Datum']; ?></h2>
        </div>
        <?php
        $sqlGegevens = "SELECT * FROM tijden_binnen_avond WHERE tijden_binnen_avond.Datum = '".$row['Datum']."';";
        $result = mysqli_query($connect,$sqlGegevens);
        while($row1 = mysqli_fetch_assoc($result)){
          echo $row1['Datum'];
        }
        ?>
      </div>
      <?php
    }
    ?>
			
				
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


	
