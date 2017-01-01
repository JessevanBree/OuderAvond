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
                        <a class="nav-link" href="Adminpanel.php"><span class="glyphicon glyphicon-calendar"></span> Administrator overzicht</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="../Inlog/loguit.php"><span class="glyphicon glyphicon-log-out"></span> Uitloggen</a></li>
                </ul>
            </div>
        </nav>

        <div class="container-fluid">
            <input type="text" id="Filter_Box" onkeyup="Filter()" placeholder="Zoek een leerling">

            <ul id="Leerling_Lijst">
                <?php
                    //maakt de querry die alle leerlingen uit de database haalt.
                    $sqli_leerling_Lijst = "SELECT Leerling_ID, Voornaam, Achternaam FROM leerlingen";
                    $sqli_leerling_Lijst_uitkomst = mysqli_query($connect, $sqli_leerling_Lijst);

                    //zet alle leerlingen in een lijst.
                    while($row = mysqli_fetch_array($sqli_leerling_Lijst_uitkomst)){
                        echo "<li><a href='#'>" . $row['Voornaam'] . " " . $row['Achternaam'] . "</a></li>";
                    }
                ?>
            </ul>


        </div>

        <div class="footer navbar-fixed-bottom">
            Ouderavond 2016.
            </br>
            &copy; Koen van Kralingen, Paul Backs, Mike de Decker en Jesse van Bree.
        </div>

        <script>
            function Filter() {
                // Declare variables
                var input, filter, ul, li, a, i;
                input = document.getElementById('Filter_Box');
                filter = input.value.toUpperCase();
                ul = document.getElementById("Leerling_Lijst");
                li = ul.getElementsByTagName('li');

                // Loop through all list items, and hide those who don't match the search query
                for (i = 0; i < li.length; i++) {
                    a = li[i].getElementsByTagName("a")[0];
                    if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        li[i].style.display = "";
                    } else {
                        li[i].style.display = "none";
                    }
                }
            }
        </script>
    </body>
</html>