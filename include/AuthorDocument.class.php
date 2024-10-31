<?php
/**
 * @author Darathor <darathor@free.fr> http://wp.darathor.com 
 * @package post-lister 
 */ 
class AuthorDocument extends AuthorDocumentBase 
{
	/**
	 * @return Boolean
	 */
	public function hasUrl()
	{
		$url = $this->getUrl();
		return ($url && $url != 'http://');
	}	
}
?>