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
        <!-- FONT AWESOME -->
        <script src="https://use.fontawesome.com/678a0bbe9a.js"></script>

        <!-- Bootstrap core CSS -->
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">

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
            <div class="col-md-offset-1 col-md-2 "><!-- begin search bar links -->
                <p class="text-center">
                    Kies een leerling om het gesrek van te verlengen.
                </p>
                <input type="text" id="Filter_Box" onkeyup="Filter()" placeholder="Zoek een leerling" class="form-control"><br>
                <div class="Leerling_Lijst_Style">
                    <ul id="Leerling_Lijst">
                        <?php
                            //maakt de querry die alle leerlingen uit de database haalt.
                            $sqli_leerling_Lijst = "SELECT Leerling_ID, Voornaam, Achternaam FROM leerlingen";
                            $sqli_leerling_Lijst_uitkomst = mysqli_query($connect, $sqli_leerling_Lijst);

                            //zet alle leerlingen in een lijst.
                            while($row = mysqli_fetch_array($sqli_leerling_Lijst_uitkomst)){
                                echo "<li><a href='Gesprek_Verlengen.php?ID=".$row["Leerling_ID"]."'>". $row["Leerling_ID"] . " - " . $row['Voornaam'] . " " . $row['Achternaam'] . "</a></li>";
                            }
                        ?>
                    </ul>
                </div>
            </div><!-- Eind search bar links -->
            <div class="col-md-offset-1 col-md-7 vertical-align">
                <div class="col-md-12 Leerling_Lijst_Style">
                    <?php
                        if(isset($ID)) {
                            //schrijft een querry die de naam op haalt.
                            $sqli_leerling_naam = "SELECT Voornaam, Achternaam FROM leerlingen WHERE Leerling_ID = '$ID'";
                            $sqli_leerling_naam_uitkomst = mysqli_query($connect, $sqli_leerling_naam);
                            $row = mysqli_fetch_array($sqli_leerling_naam_uitkomst);
                            echo "<div class='col-md-4 text-center'>";
                            echo "<p class=''>";
                            echo "met hoeveel minuten wilt u het gesrek verlengen voor de leerling: <br>";
                            echo "<b>" . $row['Voornaam'] . " " . $row['Achternaam'] . "</b>";
                            echo "</p>";

                            //geeft het menu waar je kan kiezen met hoeveel minuten je het gesrek wil verlengen.
                            echo "<form action='' method='post' class='no_padding  text-center'>";
                            echo "<input type='radio' name='Tijd' class='' value='5'";if(isset($_POST["Tijd"])){if($_POST["Tijd"] == 5){echo "checked='checked'";}} echo ">5 &nbsp;";
                            echo "<input type='radio' name='Tijd' class='' value='10'";if(isset($_POST["Tijd"])){if($_POST["Tijd"] == 10){echo "checked='checked'";}} echo ">10 &nbsp;";
                            echo "<input type='radio' name='Tijd' class='' value='15'";if(isset($_POST["Tijd"])){if($_POST["Tijd"] == 15){echo "checked='checked'";}} echo ">15 &nbsp;";
                            echo "<input type='radio' name='Tijd' class='' value='20'";if(isset($_POST["Tijd"])){if($_POST["Tijd"] == 20){echo "checked='checked'";}} echo ">20 &nbsp;<br><br>";
                            echo "<input type='submit' value='submit' class='btn btn-primary'>";
                            echo "</form>";
                            echo "</div>";
                        }
                    ?>
                </div>
            </div>

        </div>

        <div class="footer navbar-fixed-bottom">
            Ouderavond 2016.
            <br/>
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