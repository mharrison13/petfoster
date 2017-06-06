<?php

require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/etc/apache2/capstone-mysql/encrypted-config.php";

//Cloudinary?

use Edu\Cnm\PetRescueAbq\{
	Post, Image, Organization
};

/**
 * Api for Post/Image Class
 *
 * @author Amy Skidmore <askidmore1@cnm.edu>
 *
 */

// Verify the session, start if inactive

if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();

}

//Prepare an empty reply

$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {

	//grab the connection to mySQL
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/petRescueAbq.ini");

	/** Cloudinary API  */
	$config = readConfig("/etc/apache2/capstone-mysql/petRescueAbq.ini");
	$cloudinary = json_encode($config["cloudinary"]);
	\Cloudinary::config(["cloud_name" => $cloudinary->cloudName, "api_key" => $cloudinary->apiKey, "api_secret" => $cloudinary->apiSecret]);

	// Determine the HTTP method
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	// sanitize input
	$id = filter_input(INPUT_GET, "post", FILTER_VALIDATE_INT);
	$postOrganizationId = filter_input(INPUT_GET, "organization", FILTER_VALIDATE_INT);
	$postBreed = filter_input(INPUT_GET, "postBreed", FILTER_SANITIZE_STRING);
	$postDescription = filter_input(INPUT_GET, "postDescription", FILTER_SANITIZE_STRING);
	$imageId = filter_input(INPUT_GET, "imageId", FILTER_VALIDATE_INT);
	$imagePostId = filter_input(INPUT_GET, "imagePostId", FILTER_VALIDATE_INT);
	$imageCloudinaryId = filter_input(INPUT_GET, "imageCloudinaryId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	// Validate Id per methods required
	if(($method === "DELETE") && (empty($postId) === true || $id < 0)) {
		throw (new InvalidArgumentException("id can't be negative or empty", 405));
	}

	// Handle get requests
	if($method === "GET") {

		// set the XSRF cookie
		setXsrfCookie();

		//get image/all images then update reply

		//if(empty($id) === false) {
		//	$profile = Profile::getProfileByProfileId($pdo, $id);
		//	if($profile !== null) {
			//	$reply->data = $profile;
	}
		if(empty($id) === false) {
			$organization = Organization::getOrganizationByOrganizationId($pdo, $id);
			if($organization !== null) {
			$reply->data = $organization;

		}
		if(empty($id) === false) {
			$post = Post::getPostByPostId($pdo, $id);
			if($post !== null) {
				$reply->data = $post;
			}

		} elseif(empty($postOrganization) === false) {
			$post = Post::getPostsByPostOrganizationId($pdo, $postOrganizationId)->toArray();
			if($posts !== null) {
				$reply->data = $posts;
			}

		} elseif(empty($postBreed) === false) {
			$post = Post::getPostsByPostBreed($pdo, $postBreed)->toArray();
			if($posts !== null) {
				$reply->data = $posts;

			}

		} elseif(empty($postDescription) === false) {
			$post = Post::getPostByPostDescription($pdo, $postDescription)->toArray();
			if($posts !== null) {
				$reply->data = $posts;
			}

		} elseif(empty($postSex) === false) {
			$post = Post::getPostByPostSex($pdo, $postSex)->toArray();
			if($posts !== null) {
				$reply->data = $posts;
			}

		} elseif(empty($postType) === false) {
			$post = Post::getPostByPostType($pdo, $postType)->toArray();
			if($posts !== null) {
				$reply->data = $posts;
			}

		}
		if(empty($imageId) === false) {
			$image = Image::getImageByImageId($pdo, $imageId);
			if($image !== null) {
				$reply->data = $image;
			}

		} elseif(empty($imagePostId) === false) {
			$image = Image::getImageByImagePostId($pdo, $imagePostId);
			if($image !== null) {
				$reply->data = $image;

			}

		} elseif(empty($imageCloudinaryId) === false) {
			$image = Image::getImageByImageCloudinaryId($pdo, $imageCloudinaryId);
			if($image !== null) {
				$reply->data = $image;
			}
		} elseif($method === "PUT" || $method === "POST") {
			verifyXsrf();
			$requestContent = file_get_contents("php://input");
			// Retrieves the JSON package that the front end sent, and stores it in $requestContent. Here we are using file_get_contents("php://input") to get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. The argument for the function, here, is "php://input". This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
			$requestObject = json_decode($requestContent);
			// This Line Then decodes the JSON package and stores that result in $requestObject

			//TODO enforce that all the needed variables to create both post and image are present
			//took out profile, added org have Q's about profile id or $id
			//verifying that the user is logged in before they can insert an post/image

		}
			If(empty($requestObject->OrganizationId) === true) {
				throw (new\InvalidArgumentException("You must have an Organization Id to post"));

			}
			//not sure should i use this method?
			//if($empty($_SESSION["organization"]) === true) {
				//throw (new\InvalidArgumentException(("User must be logged in to Post."), 401));

			}
			if($empty($requestObject->postId) === true){
				throw (new\InvalidArgumentException("You must be logged in to post"));

			}
			if ($empty($requestObject->postBreed) === true){
				throw (new\InvalidArgumentException("You must specify breed to Post"));

			}
			if($empty($requestObject->postDescription) === true){
				throw (new\InvalidArgumentException("You must place a description to Post"));

			}
			if($empty($requestObject->postSex) === true){
				throw (new\InvalidArgumentException("You must specify animals sex to Post"));

			}
			if($empty($requestObject->postType) === true){
				throw (new\InvalidArgumentException("You must specify type to Post"));

			}
			if($empty($requestObject->imageId) === true){
				throw (new\InvalidArgumentException("You must place an image in the Post"));

			}
			if(empty($requestObject->imagePostId) === true){
				throw (new\InvalidArgumentException("You must be logged in to Post"));

			}
			if(empty($requestObject->imageCloudinaryId) === true){
				throw (new\InvalidArgumentException("You must be logged in to Post"));

			}
			//Variable assignment for the users image name, MIME type, and image extension
			//ask about image id below ""
			$tempUserFileName = $_FILES["file"]["tmp_name"];
			$userFileType = $_FILES["file"]["type"];
			$userFileExtension = strtolower(strrchr($_FILES["file"]["name"], "."));

			//upload the image to Cloudinary and get the public id
			$cloudinaryResult = \Cloudinary\Uploader::upload($_FILES["file"]["tmp_name"], array("width" => 500, "crop" => "scale"));

			//TODO get the post and grab the image object (use the $post->getPostId() for the imagePostId)
		$post = new Post(null,)

			// After the sending the image to Cloudinary, grab the public id and create the new image

			$image->insert($pdo);

//Push the data to the imageId, upload the new image, and notify user.
			$reply->data = $image->getImageId();
			$reply->message = "Image upoad successful.";

		} elseif($method === "DELETE") {
			verifyXsrf();

			//retrieve the postid to delete
			$post = Post::getPostByPostId($pdo, $id);
			if($post === null) {
				throw (new RuntimeException("Post does not exist", 404));
			}

			//TODO grab the organization by the postOrganizationId
			//TODO grab the profile by the organizationProfileId
			//TODO use the profile object's profile Id to insure the user logged in can  delete what he actually created

			//verify user is logged in to delete post/image
			if(empty($_SESSION ["profile"]) === true || $_SESSION ["profile"]->getProfileId() !== $post->getPostId()) {
				throw (new\InvalidArgumentException("You must be logged in to delete."));
			}

			// TODO kill the children fisrt (delete the image object in the database before deleting the actual post)


			$post->delete($pdo);
			$reply->message = "Post successfully deleted.";

		} else {
			throw (new InvalidArgumentException("Invalid HTTP method request"));
		}

	} catch(Exception $exception) {
			$reply->status = $exception->getCode();
			$reply->message = $exception->getMessage();
	} catch(TypeError $typeError){
					$reply->status = $typeError->getCode();
					$reply->message = $typeError->getMessage();
}
	header("Content-type: application/json");

	//encode and return reply to front end caller
echo json_encode($reply);














