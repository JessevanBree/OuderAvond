<?php
function gen_salt(){
	$random = rand(1111111111, 9999999999);
	return $random;
}

function encryptie($password){
	for($X = 0; $X <= 10; $X++){
		$password = hash('sha512', $password);
	}
	return $password;
}


?>