<?php

require_once dirname(__DIR__, 3) . "vendor/autoload.php";
require_once dirname(_DIR_, 3) . "php/classes/autoload.php";
require_once dirname(_DIR_, 3) . "php/lib/xsrf.php";
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");
use Edu\Cnm\PetRescueAbq\Profile;



/** api for signing out
 *
 * @author Jfarrar
 * @version 1.0
 */

// verify the xsrf challenge
	if(session_status() !== PHP_SESSION_ACTIVE) {
			session_start();
	}

	//prepare default error message
	$reply = new  stdClass();
	$reply->status = 200;
	$reply->data = null;

	try {

	//grap the mySQL connection
		$pdo = connectedToEncyrptedMySQL("/etc/apache2/capstone-mysql/petfoster.ini");

		//determine which HTTP methods was used
		$method = arrary_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

		if($method === "GET") {
			$_SESSION = [];
				$reply->message = "You are signed out.";

		}

		else {
				throw (new \InvalidArgumentException("Invalid HTTP method request"));

		}

	} catch(Exception $exception) {
		$reply->status = $exception->getCode();
		$reply->message = $exception->getMessage();
	} catch(TypeError $typeError) {
		$reply->status = $exception->getCode();
		$reply->message = $exception->getMessage();
	}
header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}
//encode and return reply to front end caller
echo json_encode($reply);


