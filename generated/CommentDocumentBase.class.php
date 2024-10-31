<?php
/**
 * @author Darathor <darathor@free.fr> http://wp.darathor.com 
 * @package post-lister 
 */ 
abstract class CommentDocumentBase 
{
	/**
	 * @param Array<String, Mixed> $data
	 */
	public function __construct($data)
	{
		$this->id = $data['id'];
		$this->postId = $data['postId'];
		$this->date = $data['date'];
		$this->content = $data['content'];
		$this->karma = $data['karma'];
		$this->approved = $data['approved'];
		$this->agent = $data['agent'];
		$this->type = $data['type'];
		$this->author = $data['author'];
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
	 * @var Integer
	 */
	private $postId;
	
	/**
	 * @param Integer $value
	 */
	public function setPostId($value)
	{ 
		$this->postId = $value;
	} 

	/**
	 * @return Integer
	 */
	public function getPostId()
	{ 
		return $this->postId;
	} 
	/**
	 * @var String
	 */
	private $date;
	
	/**
	 * @param String $value
	 */
	public function setDate($value)
	{ 
		$this->date = $value;
	} 

	/**
	 * @return String
	 */
	public function getDate()
	{ 
		return $this->date;
	} 
	/**
	 * @var String
	 */
	private $content;
	
	/**
	 * @param String $value
	 */
	public function setContent($value)
	{ 
		$this->content = $value;
	} 

	/**
	 * @return String
	 */
	public function getContent()
	{ 
		return $this->content;
	} 
	/**
	 * @var Integer
	 */
	private $karma;
	
	/**
	 * @param Integer $value
	 */
	public function setKarma($value)
	{ 
		$this->karma = $value;
	} 

	/**
	 * @return Integer
	 */
	public function getKarma()
	{ 
		return $this->karma;
	} 
	/**
	 * @var String
	 */
	private $approved;
	
	/**
	 * @param String $value
	 */
	public function setApproved($value)
	{ 
		$this->approved = $value;
	} 

	/**
	 * @return String
	 */
	public function getApproved()
	{ 
		return $this->approved;
	} 
	/**
	 * @var String
	 */
	private $agent;
	
	/**
	 * @param String $value
	 */
	public function setAgent($value)
	{ 
		$this->agent = $value;
	} 

	/**
	 * @return String
	 */
	public function getAgent()
	{ 
		return $this->agent;
	} 
	/**
	 * @var String
	 */
	private $type;
	
	/**
	 * @param String $value
	 */
	public function setType($value)
	{ 
		$this->type = $value;
	} 

	/**
	 * @return String
	 */
	public function getType()
	{ 
		return $this->type;
	} 
	/**
	 * @var AuthorDocument
	 */
	private $author;
	
	/**
	 * @param AuthorDocument $value
	 */
	public function setAuthor($value)
	{ 
		$this->author = $value;
	} 

	/**
	 * @return AuthorDocument
	 */
	public function getAuthor()
	{ 
		return $this->author;
	} 
}
?>