<?php
require_once("../database.php");

session_start();

//controleerd of de session niet al verlopen is
//require_once("../SESSION_Time_Out.php");

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

    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    <link rel="icon" href="../favicon.ico" type="image/x-icon">

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

<div class="container-fluid margin_bottom">
    <div class="page-header text-center ">
        <h1>de ouderavond opnieuw indelen</h1>
        <p class="font_size">sleep de tijden in de goede volgorde.</p>
        <p> dit is de ouderavond voor:
            <b>
            <?php
                //haalt de docent gegevens op
                $sqli_docent_naam = "SELECT Voornaam, Achternaam FROM docenten WHERE Docent_ID = '".$_SESSION["Docent_ID"]."'";
                $sqli_docent_naam_uitkomst = mysqli_query($connect, $sqli_docent_naam);
                $docent_naam = mysqli_fetch_array($sqli_docent_naam_uitkomst);
                echo $docent_naam["Voornaam"] . " " . $docent_naam["Achternaam"];
                echo " op " . $_SESSION["Datum"];
            ?>
            </b>
        </p>
    </div>
    <div class="col-md-8 col-md-offset-2">

        <?php

        //zet alles binnen een formulier.
        echo "<form action='' method='post' class='no_padding  text-center'>";
        //geeft alle opties qua leerlingen die moeten worden ingedeelt.
        echo "<div id='redips-drag'>";
        echo "<table class='table table-condensed margin_bottom ' style='font-size: 15px' id='table1'>";
            echo "<colgroup>";
                echo "<col width='240'/>";
                echo "<col width='240'/>";
            echo "</colgroup>";
            echo "<tr>";
                echo "<th colspan='2' id='message' class='redips-drag t1 status_ding'>Status</th>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='redips-mark desc'>";
                    echo "<p>Begin tijd</p>";
                echo "</td>";
                echo "<td  class='redips-mark desc'>";
                    echo "<p>Leerling</p>";
                echo "</td>";
            echo "</tr>";
        //haalt alle leerlingen op die op die dag staan.
        $sqli_gegevens = "SELECT Tijd_Slot, Datum, Begin_Tijd, Eind_Tijd, Leerling_ID FROM tijden_binnen_avond WHERE Afgerond=0 AND Docent_ID='".$_SESSION["Docent_ID"]."' AND Datum='".$_SESSION["Datum"]."'";//ALLE SLOTEN
        $sqli_gegevens_uitkomst = mysqli_query($connect, $sqli_gegevens);
        while($row = mysqli_fetch_array($sqli_gegevens_uitkomst)){
            echo "<tr>";
                echo "<td class='redips-mark desc'>";
                    echo "<p>".$row['Begin_Tijd']."</p>";
                echo "</td>";
                echo "<td>";
                    //geeft het tijdslot als hidden input (wordt gebruikt voor de her indeling
                    echo "<input type='hidden' name='Tijd_Slot[]' value='".$row["Tijd_Slot"]."'>";

                    //kan slecht telezen zijn. voor exstra info ga naar: Paul (244384)
                    echo "<div id='link1' class='redips-drag t1'>";
                        //controleerd of het leerling id 0 is, als het 0 wordt er Leeg weergegeven.
                        if($row["Leerling_ID"] == 0){
                            echo "beschikbaar.";
                        }
                        else{
                            echo $row["Leerling_ID"];
                        }
                        echo "<input type='hidden' name='Leerling[]' value='".$row["Leerling_ID"]."'>";

                    echo "</div>";
                echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<input type='submit' value='submit' name='submit' class='col-md-offset-2 col-md-8 btn btn-primary'>";
        echo "</div>";
        echo "</form>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";

        if(isset($_POST["submit"])){
            $koekjes = count($_POST["Leerling"]);
            //var_dump($koekjes);
            for($X = 0; $X < $koekjes; $X++){
                //var_dump($_POST["Leerling"][$X]);
                if($_POST["Leerling"][$X] != 0){
                    //controleerd of er meerder van de zelfde waarde zijn binnen de array.
                    $check_Voor_opvoling = HEEFT_DUPE($X);
                    if($check_Voor_opvoling == 2){
                        //er zijn 2x de zelrde waarde gevonden.
                        //echo "er zijn er 2";
                        //controleerd of de volgende ook 2 is.
                        if($_POST["Leerling"][$X] == $_POST["Leerling"][$X + 1]){
                            //controleerd of de leeling niet op de geselecteerde tijd als is ingeschreven.
                            //om dit te controleren moeten we eerst de gegevens hebben.
                            $sqli_inschrijvings_gegevens = "SELECT Tijd_Slot, Begin_Tijd, Eind_Tijd, Datum, Leerling_ID FROM tijden_binnen_avond WHERE Tijd_Slot = '".$_POST["Tijd_Slot"][$X]."'";
                            $row = mysqli_fetch_array(mysqli_query($connect, $sqli_inschrijvings_gegevens));
                            //contoleerd of de leerling kan op die tijd.
                            $sqli_tijd_check = "SELECT Tijd_Slot FROM tijden_binnen_avond WHERE Leerling_ID='".$_POST["Leerling"][$X]."' AND Begin_Tijd='".$row["Begin_Tijd"]."' AND Datum='".$row["Datum"]."' AND Tijd_Slot!='".$row["Tijd_Slot"]."'";
                            $sqli_tijd_check_uitkomst = mysqli_query($connect, $sqli_tijd_check);
                            if(mysqli_num_rows($sqli_tijd_check_uitkomst) == 0){
                                //controleerd of de leeling niet op de geselecteerde tijd als is ingeschreven.
                                //om dit te controleren moeten we eerst de gegevens hebben.
                                $sqli_inschrijvings_gegevens = "SELECT Tijd_Slot, Begin_Tijd, Eind_Tijd, Datum, Leerling_ID FROM tijden_binnen_avond WHERE Tijd_Slot = '".$_POST["Tijd_Slot"][$X + 1]."'";
                                $row = mysqli_fetch_array(mysqli_query($connect, $sqli_inschrijvings_gegevens));
                                //contoleerd of de leerling kan op die tijd.
                                $sqli_tijd_check = "SELECT Tijd_Slot FROM tijden_binnen_avond WHERE Leerling_ID='".$_POST["Leerling"][$X + 1]."' AND Begin_Tijd='".$row["Begin_Tijd"]."' AND Datum='".$row["Datum"]."' AND Tijd_Slot!='".$row["Tijd_Slot"]."'";
                                $sqli_tijd_check_uitkomst = mysqli_query($connect, $sqli_tijd_check);
                                if(mysqli_num_rows($sqli_tijd_check_uitkomst) == 0){
                                    $Alle_Checks_Goed = true;
                                }
                                else{
                                    $Alle_Checks_Goed = false;
                                    $Leerling_Kan_Niet_Op_Datum = true;
                                    break;
                                }
                            }
                            else{
                                $Alle_Checks_Goed = false;
                                $Leerling_Kan_Niet_Op_Datum = true;
                                break;
                            }
                            //de volgende is ook 2, die die hoeft niet gecontroleerd te worden. dus ver hoog de X waarde met 1.
                            $X ++;
                        }
                        else{
                            $Alle_Checks_Goed = false;
                            $Niet_Op_Volgend = true;
                            break;
                        }
                    }
                    elseif($check_Voor_opvoling == 3){
                        //er zijn 3x de zelrde waarde gevonden.
                        //echo "er zijn er 3";
                        //controleerd of de volgende 2 ook de zelfde leerling zijn.
                        if($_POST["Leerling"][$X] == $_POST["Leerling"][$X + 1] && $_POST["Leerling"][$X] == $_POST["Leerling"][$X + 2]){
                            //controleerd of de leeling niet op de geselecteerde tijd als is ingeschreven.
                            //om dit te controleren moeten we eerst de gegevens hebben.
                            $sqli_inschrijvings_gegevens = "SELECT Tijd_Slot, Begin_Tijd, Eind_Tijd, Datum, Leerling_ID FROM tijden_binnen_avond WHERE Tijd_Slot = '".$_POST["Tijd_Slot"][$X]."'";
                            $row = mysqli_fetch_array(mysqli_query($connect, $sqli_inschrijvings_gegevens));
                            //contoleerd of de leerling kan op die tijd.
                            $sqli_tijd_check = "SELECT Tijd_Slot FROM tijden_binnen_avond WHERE Leerling_ID='".$_POST["Leerling"][$X]."' AND Begin_Tijd='".$row["Begin_Tijd"]."' AND Datum='".$row["Datum"]."' AND Tijd_Slot!='".$row["Tijd_Slot"]."'";
                            $sqli_tijd_check_uitkomst = mysqli_query($connect, $sqli_tijd_check);
                            //var_dump($sqli_tijd_check); echo "<br>";
                            if(mysqli_num_rows($sqli_tijd_check_uitkomst) == 0){
                                //controleerd of de leeling niet op de geselecteerde tijd als is ingeschreven.
                                //om dit te controleren moeten we eerst de gegevens hebben.
                                $sqli_inschrijvings_gegevens = "SELECT Tijd_Slot, Begin_Tijd, Eind_Tijd, Datum, Leerling_ID FROM tijden_binnen_avond WHERE Tijd_Slot = '".$_POST["Tijd_Slot"][$X + 1]."'";
                                $row = mysqli_fetch_array(mysqli_query($connect, $sqli_inschrijvings_gegevens));
                                //contoleerd of de leerling kan op die tijd.
                                $sqli_tijd_check = "SELECT Tijd_Slot FROM tijden_binnen_avond WHERE Leerling_ID='".$_POST["Leerling"][$X + 1]."' AND Begin_Tijd='".$row["Begin_Tijd"]."' AND Datum='".$row["Datum"]."' AND Tijd_Slot!='".$row["Tijd_Slot"]."'";
                                $sqli_tijd_check_uitkomst = mysqli_query($connect, $sqli_tijd_check);
                                //var_dump($sqli_tijd_check); echo "<br>";
                                if(mysqli_num_rows($sqli_tijd_check_uitkomst) == 0){
                                    //controleerd of de leeling niet op de geselecteerde tijd als is ingeschreven.
                                    //om dit te controleren moeten we eerst de gegevens hebben.
                                    $sqli_inschrijvings_gegevens = "SELECT Tijd_Slot, Begin_Tijd, Eind_Tijd, Datum, Leerling_ID FROM tijden_binnen_avond WHERE Tijd_Slot = '".$_POST["Tijd_Slot"][$X + 2]."'";
                                    $row = mysqli_fetch_array(mysqli_query($connect, $sqli_inschrijvings_gegevens));
                                    //contoleerd of de leerling kan op die tijd.
                                    $sqli_tijd_check = "SELECT Tijd_Slot FROM tijden_binnen_avond WHERE Leerling_ID='".$_POST["Leerling"][$X + 2]."' AND Begin_Tijd='".$row["Begin_Tijd"]."' AND Datum='".$row["Datum"]."' AND Tijd_Slot!='".$row["Tijd_Slot"]."'";
                                    $sqli_tijd_check_uitkomst = mysqli_query($connect, $sqli_tijd_check);
                                    //var_dump($sqli_tijd_check); echo "<br>";
                                    if(mysqli_num_rows($sqli_tijd_check_uitkomst) == 0){
                                        $Alle_Checks_Goed = true;
                                    }
                                    else{
                                        $Alle_Checks_Goed = false;
                                        $Leerling_Kan_Niet_Op_Datum = true;
                                        break;
                                    }
                                }
                                else{
                                    $Alle_Checks_Goed = false;
                                    $Leerling_Kan_Niet_Op_Datum = true;
                                    break;
                                }
                            }
                            else{
                                $Alle_Checks_Goed = false;
                                $Leerling_Kan_Niet_Op_Datum = true;
                                break;
                            }
                            //de volgende is ook 2, die die hoeft niet gecontroleerd te worden. dus ver hoog de X waarde met 2.
                            $X = $X + 2;
                        }
                        else{
                            $Alle_Checks_Goed = false;
                            $Niet_Op_Volgend = true;
                            break;
                        }
                    }
                    else{
                        //er is maar 1 keer de waarde gevonden.
                        //controleerd of de leeling niet op de geselecteerde tijd als is ingeschreven.
                        //om dit te controleren moeten we eerst de gegevens hebben.
                        $sqli_inschrijvings_gegevens = "SELECT Tijd_Slot, Begin_Tijd, Eind_Tijd, Datum, Leerling_ID FROM tijden_binnen_avond WHERE Tijd_Slot = '".$_POST["Tijd_Slot"][$X]."'";
                        $row = mysqli_fetch_array(mysqli_query($connect, $sqli_inschrijvings_gegevens));
                        //contoleerd of de leerling kan op die tijd.
                        $sqli_tijd_check = "SELECT Tijd_Slot FROM tijden_binnen_avond WHERE Leerling_ID='".$_POST["Leerling"][$X]."' AND Begin_Tijd='".$row["Begin_Tijd"]."' AND Datum='".$row["Datum"]."' AND Tijd_Slot!='".$row["Tijd_Slot"]."'";
                        $sqli_tijd_check_uitkomst = mysqli_query($connect, $sqli_tijd_check);
                        if(mysqli_num_rows($sqli_tijd_check_uitkomst) == 0){
                            $Alle_Checks_Goed = true;
                        }
                        else{
                            $Alle_Checks_Goed = false;
                            $Leerling_Kan_Niet_Op_Datum = true;
                            break;
                        }
                    }
                }
                //om te voorkomen dat er een execution time out komt
                set_time_limit(0);
            }

            if(isset($Niet_Op_Volgend)){
                if($Niet_Op_Volgend == true){
                    echo "de leerling: " . $_POST["Leerling"][$X] . " is niet opvolgend ingedeeld.<br>";
                    echo "alle nummer die het zelfde zijn moeten elkaar opvolgen.";
                }
            }

            if(isset($Leerling_Kan_Niet_Op_Datum)){
                if($Leerling_Kan_Niet_Op_Datum == true){
                    echo "de leerling: " . $_POST["Leerling"][$X] . " kan niet op de tijd die u heeft gekozen.<br>";
                    echo "(de tijd die u had gekozen: " . $row["Begin_Tijd"] . ")";
                }
            }

            if(isset($Alle_Checks_Goed)){
                //contoleerd of de check is geweest die alles goed keurt. (geen problemen gevonden.)
                if($Alle_Checks_Goed == true){
                    //past alle sloten aan.
                    for($X = 0; $X < $koekjes; $X++){
                        $sqli_Tijd_Slot_Update = "UPDATE tijden_binnen_avond SET Leerling_ID='".$_POST["Leerling"][$X]."' WHERE Tijd_Slot='".$_POST["Tijd_Slot"][$X]."'";
                        if(mysqli_query($connect, $sqli_Tijd_Slot_Update)){
                            $Update_Gelukt = true;
                        }
                        else{
                            $Update_Gelukt = false;
                            echo"er is een onbekende fout opgetreden, probeer het later op nieuw, of neem contact op met de systeem beheerder.";
                        }
                    }
                    //contoleerd de update is goed gegaan.
                    if(isset($Update_Gelukt)){
                        if($Update_Gelukt == true){
                            echo "<p class='text-center'>";
                                echo "De ouderavond is succes vol opnieuw ingedeelt.";
                            echo "</p>";
                        }
                    }
                }
            }
        }


        function HEEFT_DUPE($X){
            $count = 0;
            foreach($_POST["Leerling"] as $value){
                if($value == $_POST["Leerling"][$X]){
                    $count ++;
                }
            }
            return $count;
        }
        ?>
    </div>
</div>

<div class="footer navbar-fixed-bottom">
    Ouderavond 2016.
    <br/>
    &copy; Koen van Kralingen, Paul Backs, Mike de Decker en Jesse van Bree.
</div>

<!-- de scripte die gebruikt worden voor de sorteer funcite -->
<script type="text/javascript" src="redips-drag-min.js"></script>
<script type="text/javascript">
    // after page is loaded, initialize DIV elements inside table
    window.onload = function () {
        // reference to the REDIPS.drag library
        var	rd = REDIPS.drag;
        // initialization
        rd.init();
        // set hover color
        rd.hover.colorTd = '#E7C7B2';
        // set drop option to 'shift'
        rd.dropMode = 'shift';
        // set shift mode to vertical2
        rd.shift.mode = 'vertical2';
        // enable shift animation
        rd.shift.animation = true;
        // set animation loop pause
        rd.animation.pause = 20;
        // display action in the message line (list of all event handlers can be found at the drag.js bottom)
        rd.event.clicked	= function () {document.getElementById('message').innerHTML = 'Element is clicked'}
        rd.event.moved		= function () {document.getElementById('message').innerHTML = 'Element is moved'}
        rd.event.notMoved	= function () {document.getElementById('message').innerHTML = 'Element is not moved'}
        rd.event.dropped	= function () {document.getElementById('message').innerHTML = 'Element is dropped'}
    }
</script>
</body>
</html>