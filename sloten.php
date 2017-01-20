<?php
//haalt de nodige bestanden op
require_once("database.php");
require_once("inschrijven.function.php");
setlocale(LC_ALL, 'nl_NL');
?>
<div class="col-md-3" style='overflow:auto; max-height:85vh'><!--geeft het overzicht (tabel) van de sloten die nog beschikbaar zijn.-->

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
                            echo "<th>Bechikbaarheid</th>";
                        echo "</tr>";
                        //vraagt de datum op en later het totaal aantal sloten voor die dag.
                        $Proggresbar = "SELECT Datum FROM tijden_binnen_avond WHERE Afgerond=0 AND Docent_ID='$ID' AND Datum='".$row["Datum"]."'";
                        $Proggresbar_Uitvoer = mysqli_query($connect, $Proggresbar);
                        $rowcount = mysqli_num_rows($Proggresbar_Uitvoer);

                        //vraagt het aantal niet gevulde plekken op.
                        $Proggresbar2 = "SELECT Leerling_ID FROM tijden_binnen_avond WHERE Afgerond=0 AND Docent_ID='$ID' AND Datum='".$row["Datum"]."' AND Leerling_ID=0";
                        $Proggresbar2_Uitvoer = mysqli_query($connect, $Proggresbar2);
                        $rowcount2 = mysqli_num_rows($Proggresbar2_Uitvoer);
                        //echo $rowcount;
                        //echo $rowcount2;
                            $row1 = mysqli_fetch_array($Proggresbar_Uitvoer);
                                echo "<tr class='success'>";
                                    echo "<td >";
                                        echo date("D", strtotime($row1["Datum"]));
                                    echo "</td>";
                                    echo "<td>";
                                        echo $row1["Datum"];
                                    echo "</td>";
                                    echo "<td>";
                                        ?>
                                            <div class="Beschikbaarheid">
                                                <div class="Beschikbaarheid-bar text-center" role="progressbar" style="; background-color:#00694b;color:#000000; width:<?PHP echo ($rowcount2/$rowcount)*100; ?>%">
                                                    <p class="Beschikbaarheid-procent"><?PHP echo round(($rowcount2/$rowcount)*100); ?>%</p>
                                                </div>
                                            </div>
                                        <?php
                                    echo "</td>";
                                echo "</tr>";
                    echo "</table>";
                echo  "</div>";
            }
        ?>



</div>