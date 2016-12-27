<?php
	require_once("../database.php");
	
	session_start();
	
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
        <title>Docent toevoegen</title>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

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
                    <a class="navbar-brand" href="#">Ouderavond</a>             
                </div>
                <ul class="nav navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="Adminpanel.php"><span class="glyphicon glyphicon-calendar"></span> Administrator overzicht</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="../Inlog/loguit.php"><span class="glyphicon glyphicon-log-out"></span> Uitloggen</a></li><!--Uitloggen knop -->                   
                </ul>
            </div>
        </nav>
    
    <form action="docentToevoegen.php" method="post">
        <div class="col-md-5 col-md-offset-1">
            <label for="Naam Docent">Voornaam Docent:</label>
            <input type="text" name="Voornaam" placeholder="Naam Docent" class="form-control" required>


            <label for="Achternaam">Achternaam Docent:</label>
            <input type="text" name="Achternaam" placeholder="Achternaam" class="form-control" min="0" max="99" value="" required>
            

            <label for="Inlognaam">Inlognaam Docent:</label>
            <input type="text" name="Inlognaam" placeholder="Inlognaam" class="form-control" min="0" max="99" value="" required>

            <label for="Wachtwoord">Tijdelijk wachtwoord Docent:</label>
            <input type="text" name="Wachtwoord" placeholder="Wachtwoord" class="form-control" min="0" max="99" value="" required>


            <label for="Afkorting">Afkorting Docent:</label>
            <input type="text" name="Afkorting" placeholder="Afkorting" class="form-control" required max=3>
            
            <div class="form-group">
            <label for="Admin">Admin:</label>
            <select class="form-control" id="Admin" name="admin">
                <option value=1>Ja</option>
                <option value=0>Nee</option>
            </select>
            </div>
            

            <br>
            <br>
            <button class=" btn btn-style" type="submit" name="Verzenden">
                <span class="glyphicon glyphicon-ok"></span> Bevestig
            </button>
        </div>
            
    </form>

    <?php
    if(isset($_POST['Verzenden'])){
        $ToevoegenQ = "INSERT INTO `docenten` (`Docent_ID`, `Voornaam`, `Achternaam`, `Afkorting`, `Inlognaam`, `Wachtwoord`, `Salt`, `Eerste_inlog`) VALUES (NULL, '".$_POST['Voornaam']."', '".$_POST['Achternaam']."', '".$_POST['Afkorting']."', '".$_POST['Inlognaam']."', '".$_POST['Wachtwoord']."', '0', '".$_POST['admin']."');";

        $toevoegen = mysqli_query($Verbinding,$ToevoegenQ);
        if(!$ToevoegenQ){

            echo "<div class='alert alert-danger col-md-5 ' role='alert'>Gegevens niet ingevoegd</div>";
        }else{
            echo "<div class='alert alert-success col-md-5 ' role='alert'>Gegevens ingevoegd</div>";
        }

    }

        ?>
        </br>
        

        <div class="footer navbar-fixed-bottom">
            Ouderavond 2016.
            </br>
            &copy; Koen van Kralingen, Paul Backs, Mike de Decker en Jesse van Bree.
        </div>
    </body>
</html>