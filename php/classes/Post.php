<?php
namespace Edu\Cnm\Petfoster;

require_once("autoload.php");

/**
 * Post Class
 *
 * This class is for posting pets from the organization
 * @author Jabari Farrar <tmafm1@gmail.com>
 * @version 1.0.0
 */

class Post implements \JsonSerializable {
	/**
	 * id for this Post; this is the primary key
	 * @var int $postId
	 **/
	private $postId;

	/**
	 * id for the post organization; this is the foreign key
	 * @var int $postOrganizationId
	 **/
	private $postOrganizationId;

	/**Breed of the animal
	 * @var string $postBreed
	 **/
	private $postBreed;

	/**Description of post
	 * @var string $postDescription
	 * */
	private $postDescription;

	/**Sex of the animal
	 * @var string $postSex
	 * */
	private $postSex;

	/**Type of post
	 * @var string $postType
	 * */
	private $postType;

	/**
	 * Constructor for this post
	 * @param int $newPostId id of this post
	 * @param string $newPostOrganizationId
	 * @param string $newPostBreed breed of the animal
	 * @param string $newPostDescription description of the animal
	 * @param string $newPostSex Sex of the animal
	 * @param string $newPostType Type of post
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings are too long, negative integers)
	 * @throws \TypeError if the data types violate teh hints
	 * @throws \Exception if some other exception occurs
	 * */

	public function __construct(int $newPostId, string $newPostOrganizationId, string $newPostBreed, string $newPostDescription, string $newPostSex, string $newPostType) {
		try {
			$this->setPostId($newPostId);
			$this->setPostOrganizationId($newPostOrganizationId);
			$this->setPostBreed(newPostBreed);
			$this->setPostDescription($newPostDescription);
			$this->setPostSex(newPostSex);
			$this->setPostType($newPostType;)

		}
			//determine what exception type was thrown
		catch(\InvalidArgumentException| \RangeException | \TypeError $exception)); {
			$exceptionType = get_class($exception);
			throw(new $exceptionType ($exceptionType->getMessage(), 0, $exception));
		}

	}

	/** accessor method for post id
	 * @return int value for post id
	/**

	public function getPostId() {
		return ($this->postId);
	}

	/**mutator method for post id
	 *
	 * @param int $newPostId new value for post id
	 * @throws \RangeException if $newPostId is not positive
	 * @throws \TypeError if $newPostId is not an integer
	 **/

		public function setPostId(int $newPostId) {
		//verify that the post id is positive
		if($newPostId <= 0) {
			throw(new \RangeException("please enter a positive value"));
		}
		// convert and store post id
		$this->postId = $newPostId;
			}

		/** accessor method for postBreed
		 *
		 * @return string value of post Breed
		 **/

		public function getPostBreed() :string {
		return ($this->postBreed);
	}
	/**
	 * mutator method for post breed
	 * @param string $newPostBreed
	 * @throws \InvalidArgumentException if $newPostBreed is not a string or insecure
	 * @throws \RangeException if $newPostBreed in > 32 characters
	 * @throws \TypeError if $newPostBreed is not a string
	 **/

			public function setPostBreed(string $newPostBreed) {
			//verify teh post content is secure
			$newPostBreed = trim ($newPostBreed);
			$newPostBreed = filter_var($newPostBreed, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
			if(empty($newPostBreed) === true) {
				throw(new \InvalidArgumentException("Please enter breed type"));
			}
			//verify the post breed will fit into the database
			if(strlen($newPostBreed) > 32) {
				throw(new \RangeException("The breed type is too long"));
			}
			//store this post content
			$this->postBreed = $newPostBreed;
			}

	/** accessor method for post Description
	 *
	 * @return string value of post Description
	 **/
	public function getPostDescription() :string {
		return $this->postDescription;
	}
	/**
	 * mutator method for post Description
	 * @param string $newPostDescription the new value of the post description
	 * @throws \InvalidArgumentException if $newPostDescription is not a string or insecure
	 * @throws \RangeException if $newPostDescription is > 255 characters
	 * @throws \TypeError if $newPostDescription is not string
	 **/

	public function setPostDescription($newPostDescription) : void {
		$this->postDescription = $newPostDescription;
		//verify that the post description is secure
		$newPostDescription = trim($newPostDescription);
		$newPostDescription = filter_var($newPostDescription, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newPostDescription) === true) {
			throw(new \InvalidArgumentException("post description is empty or insecure"));
	}

		//verify the post description will fit in the database
		if(strlen($newPostDescription) > 255)  {
			throw(new \RangeException("Description is too large"));
		}
		// store this description
		$this->postDescription = $newPostDescription;
	}

	/**
	 * accessor method for Post Sex
	 * @return string value of post sex
	 */

	public function getPostSex() {
		return ($this->postSex);
	}
	/**
	 * mutator method for post sex
	 * @param string $newPostSex new value for post sex
	 * @throws \InvalidArgumentException if $newPostSex is insecure
	 * @throws \RangeException if $newPostSex is > 1 character
	 * @throws \TypeError if $newPostSex is not a string
	 **/

	public function setPostSex(string $postSex) {
		$newPostSex = trim ($newPostSex);
		$newPostSex = filter_var($newPostSex, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newPostSex)=== true) {
			throw(new \InvalidArgumentException("The sex is empty or insecure."));
		} if(strlen($newPostSex) > 1) {
			throw(new \RangeException("You can only use one letter"));
		}
		$this->postSex = $newPostSex;
	}
	/** accessor method for post type
	 *
	 * @return string $postType value of post type
	**/

	public function getPostType()  {
		return ($this->postType);
	}
	/**
	 * mutator method for post type
	 * @param string $newPostType new value of post type
	 * @throws \InvalidArgumentException if $newPostType is insecure
	 * @throws \RangeException if $newPostType is > 1 character
	 * @throws |\TypeError if $newPostType is not a string
	 **/

	public function setPostType(string $postType) {
		$newPostType = trim($newPostType);
		$newPostType = filter_var($newPostType, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newPostType) === true) {
			throw(new \InvalidArgumentException("The post type is empty or insecure."));
		}
		if(strlen($newPostType) > 1) {
			throw(new \RangeException("You can only use one letter"));
		}
		$this->postType = $newPostType;
	}



	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		return($fields);
	}
}
