<html>
	<head>
		<meta charset="utf-8">
		<title>Ouderavond</title>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<link rel="icon" href="favicon.ico" type="image/x-icon">
		<!-- Bootstrap core CSS -->
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet">

		<!-- Latest compiled and minified JavaScript -->
		<script src="bootstrap/js/vendor/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
		<!-- Custom styles for this template -->
		<link href="style-aa.css" rel="stylesheet">
	</head>
    <body class="kleur-effect">
      	<nav class="navbar navbar-custom">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="#">Ouderavond</a>
				</div>
				<?php
					session_start();
					if(isset($_SESSION["ingelogt"])){
						echo "<ul class='nav navbar-nav navbar-right'>";
							echo "<li class='nav-item '>";
								echo "<a class='nav-link ' <button href='Inlog/loguit.php'><span class=\"glyphicon glyphicon-log-out\"></span> Uitloggen</button></a>";
							echo "</li>";
						echo "</ul>";

						if(isset($_SESSION["Admin"])){
							echo "<ul class='nav navbar-nav'>";
								echo "<li class='nav-item '>";
									echo "<a class='nav-link ' <buton href='Admin/Adminpanel.php'><span class=\"glyphicon glyphicon-calendar\"></span> Docenten</buton></a>";
								echo "</li>";
							echo "</ul>";
						}
					}
					else{
						echo "<ul class='nav navbar-nav navbar-right'>";
							echo "<li class='nav-item '>";
								echo "<a class='nav-link ' <button href='Inlog/login.php'><span class=\"glyphicon glyphicon-log-in\"></span> login</button></a>";
							echo "</li>";
						echo "</ul>";
					}
				?>
			</div>
    	</nav>
        <div class="container">
            <div class="row">      
                <div class='col col-4'>
                    <div class='modal-dialog'>
                        <div class='modal-content bgcolor'>
                            <div class='modal-header'>
                                <h1 class='text-center'>Welkom</h1>
                            </div>
                           
							<div class='modal-body text-style'>
								<p class="text-center">
									Welkom, op deze website kunt u aangeven waarneer u de Ouderavond komt bezoeken.
									om aantekunnen geven waarneer u komt, moet u eerst inloggen.
								</p>
								<br/>
								<?php
								if(!isset($_SESSION["Admin"])){
									echo "<a href='inschrijven.php' class='btn btn-block btn-lg btn-style'>inschrijven</a>";
								}

								?>
							</div>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
		<div class="footer navbar-fixed-bottom">
            Ouderavond 2016.
            <br/>
            &copy; Koen van Kralingen, Paul Backs, Mike de Decker en Jesse van Bree.
        </div>
  	</body>
</html>