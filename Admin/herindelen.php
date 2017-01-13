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

    <!-- Bootstrap core CSS -->
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Latest compiled and minified JavaScript -->
    <script src="../bootstrap/js/vendor/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>

    <!-- de scripte die gebruikt worden voor de sorteer funcite -->
    <script type="text/javascript" src="redips-drag-min.js"></script>

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

        <?php
        //geeft alle opties qua leerlingen die moeten worden ingedeelt.
        echo "<div id='redips-drag'>";
        echo "<table class='table table-condensed margin_bottom' id='table1'>";
            echo "<tr>";
                echo "<th colspan='2' class='redips-drag t1'>$Docent_Naam</th>";
            echo "</tr>";
            echo "<tr>";
                echo "<td>";
                    echo "<p>Begin tijd</p>";
                echo "</td>";
                echo "<td>";
                    echo "<p>Leerling</p>";
                echo "</td>";
            echo "</tr>";
        //haalt alle leerlingen op die op die dag staan.
        $sqli_gegevens = "SELECT Tijd_Slot, Datum, Begin_Tijd, Eind_Tijd, Leerling_ID FROM tijden_binnen_avond WHERE Afgerond=0 AND Docent_ID='".$_SESSION["Docent_ID"]."' AND Datum='".$_SESSION["Datum"]."'";//ALLE SLOTEN
        $sqli_gegevens_uitkomst = mysqli_query($connect, $sqli_gegevens);
        while($row = mysqli_fetch_array($sqli_gegevens_uitkomst)){
            echo "<tr>";
                echo "<td>";
                    echo "<p>".$row['Begin_Tijd']."</p>";
                echo "</td>";
                echo "<td>";
                    echo "<div id='link1' class='redips-drag t1'>".$row["Leerling_ID"]."</div>";
                echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";















        echo "<div class='col-md-2' ondrop='drop(event)' ondragover='allowDrop(event)' ondragstart='drag(event)' style='min-height: 100px; min-width: inherit'>";
            //maakt de naam goed.(en haalt hem op) (SLECHT TE LEZEN SORRY)
            $sqli_leerling_naam = "SELECT leerlingen.Leerling_ID, leerlingen.Voornaam, leerlingen.Voorvoegsel, leerlingen.Achternaam FROM tijden_binnen_avond JOIN leerlingen ON tijden_binnen_avond.Leerling_ID = leerlingen.Leerling_ID WHERE tijden_binnen_avond.Docent_ID='".$_SESSION["Docent_ID"]."' AND tijden_binnen_avond.Datum = '".$_SESSION["Datum"]."' AND afgerond='0' ";
            $sqli_leerling_naam_uitkomst = mysqli_query($connect, $sqli_leerling_naam);
            while($row1 = mysqli_fetch_array($sqli_leerling_naam_uitkomst)) {
                if (!empty($row1["Voorvoegsel"])) {$naam = $row1['Voornaam'] . "&nbsp" . $row1["Voorvoegsel"] . "&nbsp" . $row1['Achternaam'];}
                elseif (!empty($row1["Voornaam"])) {$naam = $row1['Voornaam'] . "&nbsp" . $row1['Achternaam'];}
                else {$naam = "Vrij";}
                //geeft de naam als een drag en drop
                echo "<p id='drag' draggable='true' ondragstart='drag(event)'>".$naam."</p>";
            }

        echo "</div>";

        //geeft alle sloten weer.
        echo "<div class='col-md-offset-2 col-md-2'>";
            //maakt de naam goed.(en haalt hem op) (SLECHT TE LEZEN SORRY)
            $sqli_sloten = "SELECT Tijd_Slot, Begin_Tijd, Eind_Tijd FROM tijden_binnen_avond WHERE Afgerond=0 AND Docent_ID='".$_SESSION["Docent_ID"]."' AND Datum='".$_SESSION["Datum"]."'";
            $sqli_sloten_uitkomst = mysqli_query($connect, $sqli_sloten);
            while($row = mysqli_fetch_array($sqli_sloten_uitkomst)){
                echo  "<div class='' ondrop='drop(event)' ondragover='allowDrop(event)' ondragstart='drag(event)' style='height: 100px;width: calc(inherit/2); text-align: center; border: 1px solid black;'></div>";
            }
        echo "</div>";





        //var_dump($row);

        echo "<h3>De planning van : ". strtoupper(substr($row["Voornaam"], 0, 1)) . ". " . $row["Achternaam"] ."</h3>";


            //haalt alle inschrijvings gegevens op (gerelateerd aan de docent die gekozen is bij het verlengen.()
            $sqli_gegevens = "SELECT Tijd_Slot, Datum, Begin_Tijd, Eind_Tijd FROM tijden_binnen_avond WHERE Afgerond=0 AND Docent_ID='".$_SESSION["Docent_ID"]."' AND Datum='".$_SESSION["Datum"]."'";//ALLE SLOTEN
            $sqli_gegevens_uitkomst = mysqli_query($connect, $sqli_gegevens);
            // maatk de hele tabel een list.
            echo "<ul id='List_Sort' class='List_Sort' >";
            while($row = mysqli_fetch_array($sqli_gegevens_uitkomst)){
                        echo $row["Begin_Tijd"];
            }


                echo "<li> " . $naam . "</li >";





        echo "</ul>"; //sluit de lijst af.




        ?>
    </div>
</div>

<div class="footer navbar-fixed-bottom">
    Ouderavond 2016.
    <br/>
    &copy; Koen van Kralingen, Paul Backs, Mike de Decker en Jesse van Bree.
</div>

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