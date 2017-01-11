<?php
	require_once("SQLi_login.php");



?>
<html>
	<head>
		<meta charset="utf-8">
		<title>Ouderavond</title>
        <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
        <link rel="icon" href="../favicon.ico" type="image/x-icon">
		<!-- Bootstrap core CSS -->
		<link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="style-aa.css" rel="stylesheet">
	</head>
    <body class="kleur-effect">
        <nav class="navbar navbar-custom">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="../index.php">Ouderavond</a>
				</div>
			</div>
        </nav>
        <div class="container">
            <div class="row">      
                <div class='col col-4'>
                    <div class='modal-dialog'>
                        <div class='modal-content bgcolor'>
                            <div class='modal-header'>
                                <h1 class='text-center'>Login</h1>
                            </div>
                            <form action='' method='post'>
                                <div class='modal-body'>
                                    <div class='form-group'>
                                        <input type='text' name='inlog' placeholder='Gebruikersnaam' class='form-control input-lg' required>
                                    </div>
                                    <div class='form-group'>
                                        <input type='password' name='wachtwoord' class='form-control input-lg' placeholder='Wachtwoord' required>
                                    </div>
                                    <div class='form-group'>									
                                        <Button type='submit' class='btn btn-block btn-lg btn-style'>
											<span class="glyphicon glyphicon-log-in"></span> log in
										</button>									
                                    </div>
                                </div>
                            </form>
							
							<?php
								//controleert of de post is geweest.
								if(isset($_POST["inlog"]) && isset($_POST["wachtwoord"])){
									inloggen($_POST["inlog"], $_POST["wachtwoord"]);
								}
							?>
							
                        </div>
                    </div>
                </div>    
            </div>
        </div>
		<div class="footer navbar-fixed-bottom">
            Docent informatie 2016.
            <br/>
            &copy; Koen van Kralingen, Paul Backs, Mike de Decker en Jesse van Bree.
        </div>
  	</body>
</html>