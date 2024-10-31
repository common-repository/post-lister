<?php
/**
 * @author Darathor <darathor@free.fr> http://wp.darathor.com 
 * @package post-lister 
 */ 
class CommentDocument extends CommentDocumentBase
{
	public static function getInstanceFromSqlResult($data)
	{
		// Instanciate the author.
		$authorData = array(
			'id' => $data['user_id'],
			'name' => $data['comment_author'],
			'email' => $data['comment_author_email'],
			'url' => $data['comment_author_url'],
			'ip' => $data['comment_author_IP'],
		);
		$author = new AuthorDocument($authorData);
		
		// Instanciate the comment.
		$commentData = array(
			'id' => $data['comment_ID'],
			'postId' => $data['comment_post_ID'],
			'date' => $data['comment_date'],
			'content' => $data['comment_content'],
			'karma' => $data['comment_karma'],
			'approved' => $data['comment_approved'],
			'agent' => $data['comment_agent'],
			'type' => $data['comment_type'],
			'author' => $author,
		);

		return new CommentDocument($commentData);
	}
	
	/**
	 * @return String
	 */
	public function getPostUrl()
	{
		return get_permalink($this->getPostId());
	}
	
	/**
	 * @return String
	 */
	public function getCommentUrl()
	{
		return get_permalink($this->getPostId()).'#comment-'.$this->getId();
	}
	
	/**
	 * @return String
	 */
	public function getPostTitle()
	{
		return get_the_title($this->getPostId());
	}
	
	/**
	 * @param String $format
	 * @param Boolean $gmt
	 * @return String
	 */
	public function getFormattedDate($format = null, $gmt = false)
	{
		if ($format == null)
		{
			$format = get_option('date_format');
		}
		$time = mysql2date($format, $this->getDate());
		return apply_filters('get_the_time', $time, $format, $gmt);
	}
	
	/**
	 * @return String
	 */
	public function getRenderedContent()
	{
		return apply_filters('comment_text', apply_filters('get_comment_text', $this->getContent()));
	}
	
	/**
	 * @param Integer $showTextLimit
	 * @return String
	 */
	public function getShortenContent($showTextLimit)
	{
		$commentExcerpt = $this->getContent();

		// Clean html tags and line breaks.
		$commentExcerpt = strip_tags($commentExcerpt);
		$commentExcerpt = str_replace("\n", ' ', $commentExcerpt);
		$commentExcerpt = str_replace("\r", ' ', $commentExcerpt);
		$commentExcerpt = str_replace("([ ]+)", ' ', $commentExcerpt);

		// Remove the last word if because it may contain an incomplete UTF-8 character.
		if (strlen($commentExcerpt) > $showTextLimit)
		{
			$commentExcerpt = substr($commentExcerpt, 0, $showTextLimit);
			$commentExcerpt = substr($commentExcerpt, 0, strrpos($commentExcerpt, ' '));
			$commentExcerpt = trim($commentExcerpt) . " [...]";
		}		
						
		// Apply comments filters (smilies, etc).
		$commentExcerpt = apply_filters('comment_excerpt', apply_filters('get_comment_excerpt', $commentExcerpt));
		
		return $commentExcerpt;
	}
}
?>