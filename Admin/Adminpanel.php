<?php
	require_once("../database.php");
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
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Docent toevoegen</title>
        <!-- FONT AWESOME -->
        <script src="https://use.fontawesome.com/678a0bbe9a.js"></script>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <link href="style.css" rel="stylesheet">
    </head>

    <body class="kleur-effect">
        <nav class="navbar navbar-custom">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="../index.php">Ouderavond</a>
                </div>
                <ul class="nav navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php"> Welcom pagina</a>
                        <li><a href="../Inlog/wachtwoord_wijzigen.php"><span class="glyphicon glyphicon-pencil "></span> Wachtwoord wijzigen</a>
                        </li>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="../Inlog/loguit.php"><span class="glyphicon glyphicon-log-out"></span> Uitloggen</a></li>
                </ul>
            </div>
        </nav>


        <article class="container-fluid">
            <a href="overzicht.php" id="Link">   
                <div class="col-md-2 col-sm-3 col-lg-1 col-xs-6 border margin">
                    <center>
                    <i class="fa fa-list fa-5x" aria-hidden="true"></i><br>
                    Overzicht van alle docenten
                    </center>
                </div>
            </a>

            <a href="docentToevoegen.php" id="Link">   
                <div class="col-md-2 col-sm-3 col-lg-1 col-xs-6 border margin">
                    <center>
                    <i class="fa fa-user-plus fa-5x" aria-hidden="true"></i><br><br>
                    Docent toevoegen
                    </center>
                </div>
            </a>

            <a href="Leerling_Toevoegen.php" id="Link">   
                <div class="col-md-2 col-sm-3 col-lg-1 col-xs-6 border margin">
                    <center>
                    <i class="fa fa-user-plus fa-5x" aria-hidden="true"></i><br><br>
                    Leerling toevoegen
                    </center>
                </div>
            </a>
            <a href="Ouderavond_beheren.php" id="Link">   
                <div class="col-md-2 col-sm-3 col-lg-1 col-xs-6 border margin">
                    <center>
                    <i class="fa fa-cogs fa-5x" aria-hidden="true"></i><br>
                    Ouderavond beheren
                    </center>
                </div>
            </a>
            <a href="Gesprek_Verlengen.php" id="Link">
                <div class="col-md-2 col-sm-3 col-lg-1 col-xs-6 border margin">
                    <center>
                        <i class="fa fa-cogs fa-5x" aria-hidden="true"></i><br><br>
                        Gesperk verlengen
                    </center>
                </div>
            </a>
        </article>


        <div class="footer navbar-fixed-bottom">
            Ouderavond 2016.
            </br>
            &copy; Koen van Kralingen, Paul Backs, Mike de Decker en Jesse van Bree.
        </div>
    </body>
</html>