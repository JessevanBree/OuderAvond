<?php
//haalt de nodige bestanden op
require_once("database.php");
require_once("inschrijven.function.php");
?>
<div class="col-md-4" style='overflow:auto; max-height:85vh'><!--geeft het overzicht (tabel) van de sloten die nog beschikbaar zijn.-->

        <?php
            //vraagt alle mogelijke sloten op van de docent uit de database
            $sqli_Docent = "SELECT DISTINCT Datum FROM tijden_binnen_avond WHERE Afgerond=0";
            $sqli_Docent_Uitkomst = mysqli_query($connect, $sqli_Docent);
            //zet het aantal dagen dat de ouderavond is in een variable
            $Aantal_Dagen = mysqli_num_rows($sqli_Docent_Uitkomst);
            while($row = mysqli_fetch_array($sqli_Docent_Uitkomst)){
                //berekent de breete van de div aan de hand van hoeveel dagen er zijn
                $Width = round(12/$Aantal_Dagen, 0, PHP_ROUND_HALF_DOWN);
                echo "<div class='col-md-'".$Width."''>";
                    echo "<table class='table'>";
                        echo "<tr>";
                            echo "<th>Dag</th>";
                            echo "<th>Datum</th>";
                            echo "<th>Tijd</th>";
                        echo "</tr>";
                        //vraagt de gegevens van de docent op.
                        $sqli_Sloten = "SELECT Datum, Begin_Tijd FROM tijden_binnen_avond WHERE Afgerond=0 AND Docent_ID='$ID' AND Datum='".$row["Datum"]."'";
                        $sqli_Sloten_Uitkomst = mysqli_query($connect, $sqli_Sloten);
                        //een while loop die alle sloten van de docent weergeeft.
                        while($row1 = mysqli_fetch_array($sqli_Sloten_Uitkomst)){
                            echo "<tr>";
                                echo "<td>";
                                    echo date("l", strtotime($row1["Datum"]));
                                echo "</td>";
                                echo "<td>";
                                    echo $row1["Datum"];
                                echo "</td>";
                                echo "<td>";
                                    echo $row1["Begin_Tijd"];
                                echo "</td>";
                            echo "</tr>";
                        }
                    echo "</table>";
                echo  "</div>";
            }
        ?>



</div>