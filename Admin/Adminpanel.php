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
        <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
        <link rel="icon" href="../favicon.ico" type="image/x-icon">
        <!-- FONT AWESOME -->
        <script src="https://use.fontawesome.com/678a0bbe9a.js"></script>

        <!-- Bootstrap core CSS -->
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">

        <!-- Latest compiled and minified JavaScript -->
        <script src="../bootstrap/js/vendor/jquery.min.js"></script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>
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
                <div class="col-md-2 col-sm-3 col-lg-1 col-xs-6 border margin text-center">
                    <i class="fa fa-list fa-5x DocToevIMG" aria-hidden="true"></i><br>
                    Overzicht van alle docenten
                </div>
            </a>

            <a href="docentToevoegen.php" id="Link" onmouseover="document.getElementById('myImage').src='../Afbeeldingen/docent-add.png'" onmouseout="document.getElementById('myImage').src='../Afbeeldingen/docent-add-hover.png'">
                <div class="col-md-2 col-sm-3 col-lg-1 col-xs-6 border margin text-center" >
                    <img id="myImage" src="../Afbeeldingen/docent-add-hover.png" alt="Image not found" class="DocToevIMG"><br>
                    Docent <br> toevoegen
                </div>
            </a>

            <a href="Leerling_Toevoegen.php" id="Link">   
                <div class="col-md-2 col-sm-3 col-lg-1 col-xs-6 border margin text-center">
                    <i class="fa fa-user-plus fa-5x DocToevIMG" aria-hidden="true"></i>
                    Leerling <br> toevoegen
                </div>
            </a>
            <a href="Ouderavond_beheren.php" id="Link">   
                <div class="col-md-2 col-sm-3 col-lg-1 col-xs-6 border margin text-center">
                    <i class="fa fa-cogs fa-5x DocToevIMG" aria-hidden="true"></i><br>
                    Ouderavond <br> beheren
                </div>
            </a>
            <a href="Gesprek_Verlengen.php" id="Link">
                <div class="col-md-2 col-sm-3 col-lg-1 col-xs-6 border margin text-center">
                        <i class="fa fa-commenting-o fa-5x DocToevIMG" aria-hidden="true"></i><br>
                        Gesperk <br> verlengen
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