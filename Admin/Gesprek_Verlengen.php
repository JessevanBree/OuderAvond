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
                    <a class="navbar-brand" href="../index.php">Ouderavond</a>
                </div>
                <ul class="nav navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="Adminpanel.php"><span class="glyphicon glyphicon-calendar"></span> Docenten</a>
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
            <div class="col-md-offset-1 col-md-8 vertical-align ">
                <div class="col-md-12"> <!-- NIET VERWIJDEREN -->
                    <div class="col-md-11 Leerling_Lijst_Style">
                        <?php
                            if(isset($ID)) {
                                //schrijft een querry die de naam op haalt.
                                $sqli_leerling_naam = "SELECT Leerling_ID, Voornaam, Voorvoegsel, Achternaam FROM leerlingen WHERE Leerling_ID = '$ID'";
                                $sqli_leerling_naam_uitkomst = mysqli_query($connect, $sqli_leerling_naam);
                                $row = mysqli_fetch_array($sqli_leerling_naam_uitkomst);
                                //zet de naam goed neer
                                if(!empty($row["Voorvoegsel"])){
                                    $naam = $row['Voornaam'] . "&nbsp" . $row["Voorvoegsel"] .  "&nbsp"  . $row['Achternaam'];
                                }
                                else{
                                    $naam = $row['Voornaam'] . "&nbsp" . $row['Achternaam'];
                                }
                                //zet het leerling id in een variabel
                                $Leerling_ID = $row["Leerling_ID"];

                                //geeft het overzicht van de inschrijvings gegevens.
                                $sqli_inschrijving = "SELECT tijden_binnen_avond.Tijd_Slot, tijden_binnen_avond.Datum, tijden_binnen_avond.Begin_Tijd, tijden_binnen_avond.Eind_Tijd, docenten.Docent_ID ,docenten.Voornaam, docenten.Achternaam FROM tijden_binnen_avond JOIN docenten ON tijden_binnen_avond.Docent_ID = docenten.Docent_ID WHERE Afgerond=0 AND Leerling_ID='$ID'";
                                $sqli_inschrijving_uitkomst = mysqli_query($connect, $sqli_inschrijving);
                                if(mysqli_num_rows($sqli_inschrijving_uitkomst) > 0){
                                    $row = mysqli_fetch_array($sqli_inschrijving_uitkomst);
                                    echo "<div class='col-md-7 text-center'>";
                                    echo "<br>";
                                    echo "<p class='text-center'>";
                                    echo "de inschrijvings gegevens van: <b>" . $naam . "</b>";
                                    echo "</p>";
                                    echo "<table class='text-center table'>";
                                        echo "<tr>";
                                            echo "<td>";
                                                echo "datum";
                                            echo "</td>";
                                            echo "<td>";
                                                echo "begin tijd";
                                            echo "</td>";
                                            echo "<td>";
                                                echo "Eind tijd";
                                            echo "</td>";
                                            echo "<td>";
                                                echo "Docent";
                                            echo "</td>";
                                        echo "</tr>";
                                        echo "<tr>";
                                            echo "<td>";
                                                echo $row["Datum"];
                                            echo "</td>";
                                            echo "<td>";
                                                echo $row["Begin_Tijd"];
                                            echo "</td>";
                                            echo "<td>";
                                                echo $row["Eind_Tijd"];
                                            echo "</td>";
                                            echo "<td>";
                                                echo strtoupper(substr($row["Voornaam"], 0, 1)) . ". " . $row["Achternaam"];
                                            echo "</td>";
                                        echo "</tr>";
                                    echo "</table>";
                                    echo "</div>";
                                }
                                else{
                                    echo "<div class='col-md-7 text-center'>";
                                    echo "<br>";
                                    echo "Deze leefling heeft zicht niet ingeschrven.";
                                    echo "</div>";
                                }



                                //geeft het menu waar je het gesrek kan verlengen.
                                echo "<div class=' col-md-4 text-center'>";
                                echo "<p class=''>";
                                echo "met hoeveel minuten wilt u het gesrek verlengen voor de leerling: <br>";
                                echo "<b>" . $naam . "</b>";
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
                    <div class="col-md-1">
                        <!-- spacer, voor goede styling -->
                    </div>
                    <div class="col-md-11 text-center Leerling_Lijst_Style margin_15">
                        <p class='padding_10 font_size'>
                        <?php
                        if(isset($_POST["Tijd"]) && isset($ID)){
                            if($_POST["Tijd"] == 5 || $_POST["Tijd"] == 10){
                                //je witl het gesrek verlengen met 1 slot
                                //controleren of de opvolgende sloten bestaan (zelfde dag / algemeen)
                                $Tijd_Slot_Check_Waarde = $row["Tijd_Slot"] + 1;
                                $sqli_check_slot = "SELECT Tijd_Slot, Leerling_ID, Datum, Begin_Tijd, Docent_ID FROM tijden_binnen_avond WHERE Docent_ID = '".$row["Docent_ID"]."' AND Tijd_Slot = '$Tijd_Slot_Check_Waarde' AND Datum = '".$row["Datum"]."'";
                                $sqli_check_slot_uitkomst = mysqli_query($connect, $sqli_check_slot);
                                if(mysqli_num_rows($sqli_check_slot_uitkomst) >= 1){
                                    //het volgende slot bestaad, dus verder gaan met de andere checks
                                    //controleerd of het slot die je wilt gaan gebruiken al bezet is.
                                    $sqli_check_bezet = "SELECT Tijd_Slot FROM tijden_binnen_avond WHERE Leerling_ID = '0' AND Tijd_Slot = '$Tijd_Slot_Check_Waarde'";
                                    $sqli_check_bezet_uitkomst = mysqli_query($connect, $sqli_check_bezet);
                                    if(mysqli_num_rows($sqli_check_bezet_uitkomst) == 1){
                                        //het slot dat je wil gebruiken is vrij, dus het slot kan worden gereserveerd voor de leerling
                                        $sqli_slot_update = "UPDATE tijden_binnen_avond SET Leerling_ID = '$Leerling_ID' WHERE Tijd_Slot = '$Tijd_Slot_Check_Waarde'";
                                        if(mysqli_query($connect, $sqli_slot_update)){
                                            echo "het gesprek is succesvol verlengt.";
                                        }
                                        else{
                                            echo "ERROR";
                                        }
                                    }
                                    else{
                                        //het opvolgende slot is niet vrij. de sloten indeling moet worden aangepast.
                                        echo "de tijd met wat u het gesrek wil verlengen is bezet, u kunt de gesreken ";
                                        echo "<button onmousedown='PopupCenter(`Adminpanel.php`, `verplaatsen`)' >hier</button>";
                                        echo " herindelen.";

                                        $_SESSION["Docent_ID"] = $row["Docent_ID"];
                                        $_SESSION["Leerling_ID"] = $ID;
                                        $_SESSION["Datum"] = $row["Datum"];
                                    }
                                }
                                else{
                                    echo "het gesrek met de leerling kan niet op de huidige tijd worden verlengt, wilt u de leerling naar een andere dag of tijd verplaatsen klik dan ";
                                    echo "<button onmousedown='PopupCenter(`Adminpanel.php`, `verplaatsen`)' >hier</button>";
                                }
                            }
                            elseif($_POST["Tijd"] == 15 || $_POST["Tijd"] == 20){
                                // je wilt het gesprek verlegnen met 2 sloten.
                                //controleren of de opvolgende sloten bestaan (zelfde dag / algemeen)
                                $Tijd_Slot_Check_Waarde = $row["Tijd_Slot"] + 1;
                                $sqli_check_slot = "SELECT Tijd_Slot, Leerling_ID, Datum, Begin_Tijd FROM tijden_binnen_avond WHERE Docent_ID = '".$row["Docent_ID"]."' AND Tijd_Slot = '$Tijd_Slot_Check_Waarde' AND Datum = '".$row["Datum"]."'";
                                $Tijd_Slot_Check_Waarde1 = $row["Tijd_Slot"] + 2;
                                $sqli_check_slot1 = "SELECT Tijd_Slot, Leerling_ID, Datum, Begin_Tijd FROM tijden_binnen_avond WHERE Docent_ID = '".$row["Docent_ID"]."' AND Tijd_Slot = '$Tijd_Slot_Check_Waarde1' AND Datum = '".$row["Datum"]."'";
                                $sqli_check_slot_uitkomst = mysqli_query($connect, $sqli_check_slot);
                                $sqli_check_slot_uitkomst1 = mysqli_query($connect, $sqli_check_slot1);
                                if((mysqli_num_rows($sqli_check_slot_uitkomst) >= 1) AND (mysqli_num_rows($sqli_check_slot_uitkomst1) >= 1)){
                                    //het volgende slot bestaad, dus verder gaan met de andere checks
                                    //controleerd of het slot die je wilt gaan gebruiken al bezet is.
                                    $sqli_check_bezet = "SELECT Tijd_Slot FROM tijden_binnen_avond WHERE Leerling_ID = '0' AND Tijd_Slot = '$Tijd_Slot_Check_Waarde'";
                                    $sqli_check_bezet1 = "SELECT Tijd_Slot FROM tijden_binnen_avond WHERE Leerling_ID = '0' AND Tijd_Slot = '$Tijd_Slot_Check_Waarde1'";
                                    $sqli_check_bezet_uitkomst = mysqli_query($connect, $sqli_check_bezet);
                                    $sqli_check_bezet_uitkomst1 = mysqli_query($connect, $sqli_check_bezet1);
                                    if((mysqli_num_rows($sqli_check_bezet_uitkomst) == 1) AND (mysqli_num_rows($sqli_check_bezet_uitkomst1) == 1)){
                                        //het slot dat je wil gebruiken is vrij, dus het slot kan worden gereserveerd voor de leerling
                                        $sqli_slot_update = "UPDATE tijden_binnen_avond SET Leerling_ID = '$Leerling_ID' WHERE Tijd_Slot = '$Tijd_Slot_Check_Waarde'";
                                        $sqli_slot_update1 = "UPDATE tijden_binnen_avond SET Leerling_ID = '$Leerling_ID' WHERE Tijd_Slot = '$Tijd_Slot_Check_Waarde1'";
                                        if((mysqli_query($connect, $sqli_slot_update)) AND(mysqli_query($connect, $sqli_slot_update1))){
                                            echo "het gesprek is succesvol verlengt.";
                                        }
                                        else{
                                            echo "ERROR";
                                        }
                                    }
                                    else{
                                        //het opvolgende slot is niet vrij. de sloten indeling moet worden aangepast.
                                        echo "de tijd met wat u het gesrek wil verlengen is bezet, u kunt de gesreken ";
                                        echo "<button onmousedown='PopupCenter(`herindelen.php`, `verplaatsen`)' >hier</button>";
                                        echo " herindelen.";

                                        $_SESSION["Docent_ID"] = $row["Docent_ID"];
                                        $_SESSION["Leerling_ID"] = $ID;
                                        $_SESSION["Datum"] = $row["Datum"];
                                    }
                                }
                                else{
                                    echo "het gesrek met de leerling kan niet op de huidige tijd worden verlengt, wilt u de leerling naar een andere dag of tijd verplaatsen klik dan ";
                                    echo "<button onmousedown='PopupCenter(`Adminpanel.php`, `verplaatsen`)' >hier</button>";
                                }

                            }
                        }
                        ?>
                        </p>
                    </div>
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
                    }
                    else{
                        li[i].style.display = "none";
                    }
                }
            }

            function PopupCenter(url, title, w, h) {
                var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
                var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

                var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
                var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

                var left = ((width / 2) - (w / 2)) + dualScreenLeft;
                var top = ((height / 2) - (h / 2)) + dualScreenTop;
                var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

                // Puts focus on the newWindow
                if (window.focus) {
                    newWindow.focus();
                }
            }
        </script>
    </body>
</html>