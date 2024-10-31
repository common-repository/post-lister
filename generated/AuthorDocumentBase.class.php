<?php
/**
 * @author Darathor <darathor@free.fr> http://wp.darathor.com 
 * @package post-lister 
 */ 
abstract class AuthorDocumentBase 
{
	/**
	 * @param Array<String, Mixed> $data
	 */
	public function __construct($data)
	{
		$this->id = $data['id'];
		$this->name = $data['name'];
		$this->email = $data['email'];
		$this->url = $data['url'];
		$this->ip = $data['ip'];
	} 

	/**
	 * @var Integer
	 */
	private $id;
	
	/**
	 * @param Integer $value
	 */
	public function setId($value)
	{ 
		$this->id = $value;
	} 

	/**
	 * @return Integer
	 */
	public function getId()
	{ 
		return $this->id;
	} 
	/**
	 * @var String
	 */
	private $name;
	
	/**
	 * @param String $value
	 */
	public function setName($value)
	{ 
		$this->name = $value;
	} 

	/**
	 * @return String
	 */
	public function getName()
	{ 
		return $this->name;
	} 
	/**
	 * @var String
	 */
	private $email;
	
	/**
	 * @param String $value
	 */
	public function setEmail($value)
	{ 
		$this->email = $value;
	} 

	/**
	 * @return String
	 */
	public function getEmail()
	{ 
		return $this->email;
	} 
	/**
	 * @var String
	 */
	private $url;
	
	/**
	 * @param String $value
	 */
	public function setUrl($value)
	{ 
		$this->url = $value;
	} 

	/**
	 * @return String
	 */
	public function getUrl()
	{ 
		return $this->url;
	} 
	/**
	 * @var String
	 */
	private $ip;
	
	/**
	 * @param String $value
	 */
	public function setIp($value)
	{ 
		$this->ip = $value;
	} 

	/**
	 * @return String
	 */
	public function getIp()
	{ 
		return $this->ip;
	} 
}
?>