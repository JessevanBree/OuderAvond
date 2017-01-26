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
        <title>Docent toevoegen</title>
        <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
        <link rel="icon" href="../favicon.ico" type="image/x-icon">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

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
                        <a class="nav-link" href="Adminpanel.php"><span class="glyphicon glyphicon-calendar"></span> Docenten</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="../Inlog/loguit.php"><span class="glyphicon glyphicon-log-out"></span> Uitloggen</a></li><!--Uitloggen knop -->                   
                </ul>
            </div>
        </nav>
    
    <form action="docentToevoegen.php" method="post">
        <div class="col-md-3 col-md-offset-1">
            <h3>Docent Toevoegen</h3>
            <br>
            <label for="Naam Docent">Voornaam:</label>
            <input type="text" name="Voornaam" placeholder="Naam Docent" class="form-control" required value="<?php echo isset($_POST['Voornaam']) ? $_POST['Voornaam'] : '' ?>">

            <label for="Achternaam">Achternaam:</label>
            <input type="text" name="Achternaam" placeholder="Achternaam" class="form-control" min="0" max="99" required value="<?php echo isset($_POST['Achternaam']) ? $_POST['Achternaam'] : '' ?>">

            <label for="Afkorting">Afkorting:</label>
            <input type="text" name="Afkorting" placeholder="Afkorting" class="form-control" required max=3 value="<?php echo isset($_POST['Achternaam']) ? $_POST['Achternaam'] : '' ?>">

            <label for="Email">Email:</label>
            <input type="email" name="Email" placeholder="Email" class="form-control" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" title="Vul een geldig E-mail adres in." value="<?php echo isset($_POST['Achternaam']) ? $_POST['Achternaam'] : '' ?>">

            <label for="Wachtwoord">Tijdelijk wachtwoord:</label>
            <input type="text" name="Wachtwoord" placeholder="Wachtwoord" class="form-control" min="0" max="99" required value="<?php echo isset($_POST['Achternaam']) ? $_POST['Achternaam'] : '' ?>">
            

            <br>
            <br>
            <button class=" btn btn-style" type="submit" name="Verzenden">
                <span class="glyphicon glyphicon-ok"></span> Bevestig
            </button>
        </div>
            
    </form>
        <?php
            if(isset($_POST['Verzenden'])){
                $Docent_Check = "SELECT Afkorting FROM docenten WHERE Afkorting ='".$_POST['Afkorting']."';";
                $Docent_Check_Uitvoer = mysqli_query($connect, $Docent_Check);
                $row = mysqli_fetch_array($Docent_Check_Uitvoer);

                if($row["Afkorting"] != $_POST['Afkorting']) {
                    if (isset($_POST['Voornaam']) && ($_POST['Achternaam']) && ($_POST['Afkorting']) && ($_POST['Email']) && ($_POST['Wachtwoord'])) {
                        //encypt het wachtwoord. (haalt eerst het bestand op wat daarvoor gebruikt wordt.
                        require_once("../Inlog/encryptie.php");
                        //maakt een salt
                        $Salt = gen_salt();
                        //combineert het wachtwoordt met de salt
                        $wachtwoord = $_POST["Wachtwoord"] . $Salt;
                        //encypt het wachtwoord
                        $wachtwoord = encryptie($wachtwoord);

                        $ToevoegenQ = "INSERT INTO docenten (Docent_ID, Voornaam, Achternaam, Afkorting, Email , Wachtwoord, Salt, Eerste_inlog) 
                                       VALUES (DEFAULT , '" . $_POST['Voornaam'] . "', '" . $_POST['Achternaam'] . "', '" . $_POST['Afkorting'] . "', '" . $_POST['Email'] . "', '$wachtwoord', '$Salt', '0');";
                        if (mysqli_query($connect, $ToevoegenQ)) {
                            echo "<div class='alert alert-success col-md-offset-1 col-md-3 text-center' role='alert'>Gegevens ingevoegd</div>";
                        }
                        else {
                            echo "<div class='alert alert-danger col-md-offset-1 col-md-3 text-center' role='alert'>Gegevens niet ingevoegd</div>";
                        }
                    }
                }
                else {
                    echo "<div class='alert alert-danger col-md-offset-1 col-md-4 text-center' role='alert'>Een docent met deze afkorting is al ingevoerd in het systeem </div>";
                }
            }
        ?>
        <br>
        <!-- Footer -->
        <div class="footer navbar-fixed-bottom">
            Ouderavond 2016.
            <br>
            &copy; Koen van Kralingen, Paul Backs, Mike de Decker en Jesse van Bree.
        </div>
    </body>
</html>