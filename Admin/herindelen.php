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

//controleerd of het ID in de URL goed is.
if(isset($_GET["ID"])){
    $ID = $_GET["ID"];
    if(is_numeric($ID)){
        //controleert of het ID wel bestaad.
        $sqli_leerling_naam = "SELECT Voornaam, Achternaam FROM leerlingen WHERE Leerling_ID = '$ID'";
        $sqli_leerling_naam_uitkomst = mysqli_query($connect, $sqli_leerling_naam);
        if(!mysqli_num_rows($sqli_leerling_naam_uitkomst) == 1){
            header("Location: Gesprek_Verlengen.php");
        }
    }
    else{
        header("Location: Gesprek_Verlengen.php");
    }
}

?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Docent toevoegen</title>

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
            <a class="navbar-brand" href="#">Ouderavond</a>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="page-header text-center ">
        <h1>de ouderavond opnieuw indelen</h1>
        <p class="font_size">sleep de tijden in de goede volgorde.</p>
    </div>
    <div class="col-md-8 col-md-offset-2">
        etest
    </div>
</div>

<div class="footer navbar-fixed-bottom">
    Ouderavond 2016.
    <br/>
    &copy; Koen van Kralingen, Paul Backs, Mike de Decker en Jesse van Bree.
</div>

<script>

</script>
</body>
</html>