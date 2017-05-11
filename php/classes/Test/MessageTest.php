<?php

namespace Edu\Cnm\PetfosterAbq\Test;

//grab the encrypted property files

use Edu\Cnm\PetRescueAbq\ {Profile, Organization, Message};


//do I need this??
//require_once("/etc/apache/capstone-mysql/encrypted-config.php");
require_once(dirname(__DIR__) . "/autoload.php");

//@author RoLopez <llopez165@cnm.edu>


class MessageTest extends PetRescueAbqTest {

	/**
	 * Organization that created the Message, this is for the foreign key
	 * @var Organization organization
	 */
	protected $organization = null;

	/**
	 * Profile that created the message, this is the foreign key
	 * @var Profile profile
	 */
	protected $profile = null;

	/**
	 * valid hash to create the organization and profile object for the test
	 * @var $VALID_HASH
	 */
	protected $VALID_HASH;

	/**
	 * valid hash to create the organization and profile object for the test
	 * @var string $VALID_SALT
	 */
	protected $VALID_SALT;

	/**
	 * content of message
	 * @var string $VALID_MESSAGECONTENT
	 */
	protected $VALID_MESSAGECONTENT = "PHPUnit test passing for content";

	/**
	 * timestamp of the Message, this starts as null and is assigned later
	 * @var \DateTime $VALID_MESSAGEDATE
	 */
	protected $VALID_MESSAGEDATE = null;

	/**
	 *content of subject
	 * @var string $VALID_SUBJECTCONTENT
	 */
	protected $VALID_MESSAGESUBJECT = "PHPUnit test passing for subject";

	/**
	 * Valid timestamp to use as the sunriseMessageDate
	 */
	protected $VALID_SUNRISEDATE = null;

	/**
	 * Valid timestamp to use as the sunriseMessageDate
	 */
	protected $VALID_SUNSETDATE = null;


	/**
 	* create dependent objects before running each test
 	*/
	public final function setUp() : void {

		// run the default setUp() method first
		parent::setUp();
		$password = "asdfasdf";
		$this->VALID_SALT = bin2hex(random_bytes(32));
		$this->VALID__HASH = hash_pbkdf2("sha512", $password, $this->VALID_SALT, 262144);

	// create and insert a Organization to own the test Message
	$this->organization = new Organization(null, null, "@handle", "test@phpunit.de", 	$this->VALID_HASH, "+15055553333", $this->VALID_SALT);
	$this->organization->insert($this->getPDO());

	// create and insert a Profile to own the test Message
	$this->profile = new Profile(null, null, "@handle", "test@phpunit.de", 	$this->VALID_HASH, "+15055553333", $this->VALID_SALT);
	$this->profile->insert($this->getPDO());

	//calculate the date, just use the time the unit test was setup
	$this->VALID_MESSAGEDATE = new \DateTime();

	//format the sunrise date to use for testing
	$this->VALID_SUNRISEDATE = new\DateTime();
	$this->VALID_SUNRISEDATE->sub(new \DateInterval("P10D"));

	//format the sunset date to use for testing
	$this->VALID_SUNSETDATE = new\DateTime();
	$this->VALID_SUNSETDATE->add(new \DateInterval("P10D"));
	}


/**
 * test inserting a valid Message and verify that the actual mySQL data matches
 */
public function testInsertValidMessage() : void {
	//count the number of rows and save it for later
	$numRows = $this->getConnection()->getRowCount("message");

	//create a new Message and insert into mySQL
	$message = new Message(null, $this->organization->getOrganizationId(), $this->VALID_MESSAGECONTENT, $this->VALID_MESSAGEDATE);
	$message->insert($this->getPDO());

	//grab the data from mySQL and enforce the fields match our expectations
	$pdoMessage = Message::getMessageByMessageId($this->getPDO(), $message->getMessageId());
	$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("message"));

	$this->assertEquals($pdoMessage->getMessageOrganizationId(), $this->organization->getOrganizationId());
	$this->assertEquals($pdoMessage->getMessageProfileId(), $this->profile->getProfileId());

	$this->assertEquals($pdoMessage->getMessageContent(), $this->VALID_MESSAGECONTENT);

	//date to seconds since the beginning of time to avoid round off error
	$this->assertEquals($pdoMessage->getMessageDate()->getTimestamp(), $this->VALID_MESSAGEDATE->getTimestamp());

	$this->assertEquals($pdoMessage->getMessageSubject(), $this->VALID_MESSAGESUBJECT);
}


/**
 * test inserting a Message that already exists
 * @expectedException \PDOException
 */
public function testInsertInvalidMessage() : void {
	//create a Message with a non null message id and watch it fail

	$message = new Message(DataDesignTest::INVALID_KEY, $this->organization->getOrganizationId(), $this->profile->getProfileId(), $this->VALID_MESSAGECONTENT, $this->VALID_MESSAGEDATE, $this->VALID_MESSAGESUBJECT);
	$message->insert($this->getPDO());
}


/**
 * test inserting a Message, editing it, and then updating it
 */
public function testUpdateValidMessage() : void {
	//count the number of rows and save it for later
	$numRows = $this->getConnection()->getRowCount("message");

	//create a new Message and insert it into mySQL
	$message = new Message(null, $this->organization->getOrganizationId(), $this->profile->getProfileId(), $this->VALID_MESSAGECONTENT, $this->VALID_MESSAGEDATE, $this->VALID_MESSAGESUBJECT);)
	$message->insert($this->getPDO());

	//edit the Message and update it in mySQL
	$message->setMessageContent($this->VALID_MESSAGECONTENT);

	//DO I NEED THIS???
	$message->setMessageSubject($this->VALID_MESSAGESUBJECT);

	$message->update($this->getPDO());

	//grab the data from mySQL and enforce the fields match our expectations
	$pdoMessage = Message::getMessageByMessageId($this->getPDO(), $message->getMessageId());
	$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("message"));

	$this->assertEquals($pdoMessage->getMessageOrganizationId(), $this->organization->getOrganizationId());
	$this->assertEquals($pdoMessage->getMessageProfileId(), $this->profile->getProfileId());

	$this->assertEquals($pdoMessage->getMessageContent(), $this->VALID_MESSAGECONTENT);

	//date to seconds since the beginning of time to avoid round off error
	$this->assertEquals($pdoMessage->getMessageDate()->getTimestamp(), $this->VALID_MESSAGEDATE->getTimestamp());

	$this->assertEquals($pdoMessage->getMessageSubject(), $this->VALID_MESSAGESUBJECT);
}


/**
 * test updating a Message that already exists
 * @expectedException \PDOException
 */
public function testUpdateInvalidMessage() : void {

	//create a Message with a non null message id and watch it fail
	$message = new Message(null, $this->organization->getOrganizationId(), $this->profile->getProfileId(), $this->VALID_MESSAGECONTENT, $this->VALID_MESSAGEDATE, $this->VALID_MESSAGESUBJECT);)
	$message->insert($this->getPDO());
}






}

