<?php
/**
 * @author Darathor <darathor@free.fr> http://wp.darathor.com
 * @package post-lister
 */ 
class ClistBlock extends ClistBlockBase
{	
	/**
	 * Singleton handling.
	 * @return ClistBlock
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * @var ClistBlock
	 */
	private static $instance;
		
	/**
	 * Shortcode conversion.
	 * @param Array $attributes
	 * @param String $content
	 * @return String
	 */
	public function shortcodeHandler($attributes, $content = null)
	{
		return $this->executeQueryFromAttributes($attributes);
	}
	
	/**
	 * Frontoffice widget display.
	 * @param Array $attributes
	 * @param Array $options
	 * @return Void
	 */
	protected function doWidgetHandler($attributes, $options, $number)
	{
		$widget = $this->getAttributeValue($attributes, 'before_widget');
		$widget .= $this->getAttributeValue($attributes, 'before_title');
		$widget .= $this->evaluateValue($options['title']);
		$widget .= $this->getAttributeValue($attributes, 'after_title');
		$widget .= $this->executeQueryFromAttributes($options, true);
		$widget .= $this->getAttributeValue($attributes, 'after_widget');
		echo $widget;
	}
	
	// Private stuff.

	private $comment;
	
	/**
	 * Save the current data in order to restore them after the query.
	 * @return void
	 */
	private function saveCurrentdata()
	{
		global $comment;
		$this->comment = $comment;
		$comment = null;
	}

	/**
	 * Restore the saved data after the query.
	 * @return void
	 */
	private function restoreCurrentdata()
	{
		global $comment;
		$comment = $this->comment;
	}

	/**
	 * List generation in HTML.
	 * @param Array<String, String> $request
	 * @param String $beforeList
	 * @param String $afterList
	 * @param String $beforeItem
	 * @param String $afterItem
	 */
	private function executeQuery($request = array(), $beforeList = '<ul class="wlist">', $afterList = '</ul>', $beforeItem = '<li>', $afterItem = '</li>')
	{
		// Some filters on the comment rendering may refers to the global comment,
		// so copy it and unset it to avoid strange side effects. It will be restored
		// after the rendering.
		$this->saveCurrentdata();
		
		// Get the comments.
		$rows = $this->getComments($request);		

		$renderedList = $beforeList;
		
		// Add each item to the list.		
		foreach ($rows as $row)
		{
			$comment = CommentDocument::getInstanceFromSqlResult($row);
			$renderedList .= $beforeItem;
			
			// Avatars.
			if ($request['showauthoravatar'] && $request['showauthoravatar'] != 'none')
			{
				$renderedList .= get_avatar($comment->getAuthor()->getEmail(), $request['showauthoravatar']);
			}
			
			// Post link.
			$renderedList .= __('posted-in', self::PLUGIN_INTERNAL_NAME);
			$renderedList .= ' <a href="' . $comment->getCommentUrl() . '" rel="bookmark">' . $comment->getPostTitle() . '</a>';
			
			// Author.
			if ($request['showauthor'] == 'true')
			{
				$author = $comment->getAuthor();
				$renderedList .= ' ' . __('author', self::PLUGIN_INTERNAL_NAME); 
				if ($author->hasUrl())
				{
					$renderedList .= ' <a href="' . $author->getUrl() . '" title="' . sprintf(__("Visit %s's website"), $author->getName()) . '" rel="external">' . $author->getName() . '</a>';
				}
				else
				{
					$renderedList .= ' ' . $author->getName();
				}
			}
			
			// Date.
			if ($request['showdate'] == 'true')
			{
				$renderedList .= ' ' . sprintf(__('date', self::PLUGIN_INTERNAL_NAME), $comment->getFormattedDate(get_option('date_format')));
			}
			if ($request['showtime'] == 'true')
			{
				$renderedList .= ' ' . sprintf(__('time', self::PLUGIN_INTERNAL_NAME), $comment->getFormattedDate(get_option('time_format')));
			}
			
			// Text.
			if ($request['showtext'] && $request['showtext'] != 'none')
			{
				$renderedList .= '<div class="content">';
				// If a limit is set, shorten the text.
				if ($request['showtext'] == 'all')
				{
					$renderedList .= $comment->getRenderedContent();
				}
				else
				{
					$renderedList .= $comment->getShortenContent(intval($request['showtext']));
				}
				$renderedList .= '</div>';
			}
			
			$renderedList .= $afterItem;
		}			
		$renderedList .= $afterList;
		
		// The result is rendered, let's restore the data.
		$this->restoreCurrentdata();
		
		return $renderedList;
	}
	
	/**
	 * @param Array<String, String> $attributes
	 * @param Boolean $evaluate
	 * @return String
	 */
	private function executeQueryFromAttributes($attributes, $evaluate = false)
	{
		$request = $this->getValuesFromAttributes($attributes, $evaluate);

		$beforeList = $this->getAttributeValue($attributes, 'beforelist', '<ul class="wlist">');
		$afterList = $this->getAttributeValue($attributes, 'afterlist', '</ul>');
		$beforeItem = $this->getAttributeValue($attributes, 'beforeitem', '<li>');
		$afterItem = $this->getAttributeValue($attributes, 'afteritem', '</li>');

		return $this->executeQuery($request, $beforeList, $afterList, $beforeItem, $afterItem);
	}
	
	/**
	 * @param Array<String, String> $request
	 * @return String
	 */
	private function getComments($request)
	{
		global $wpdb;
		
		// Select only approved comments.
		$sql = "SELECT * FROM $wpdb->comments WHERE comment_approved = '1'";
		
		// Filter by author.
		if (isset($request['author']) && intval($request['author']) > 0)
		{
			$sql .= " AND user_id = " . intval($request['author']);
		}
		else if (isset($request['author_name']) && $request['author_name'] != '')
		{
			$sql .= " AND comment_author LIKE '" . $request['author_name'] . "'";
		}
		else if (isset($request['author_email']) && $request['author_email'] != '')
		{
			$sql .= " AND comment_author_email LIKE '" . $request['author_email'] . "'";
		}
		
		// Filter by the post.
		if (isset($request['post']) && intval($request['post']) > 0)
		{
			$sql .= " AND comment_post_ID = " . intval($request['post']);
		}
		
		// Order.
		$order = (isset($request['order']) && $request['order'] == 'asc') ? 'asc' : 'desc'; 
		$orderBy = (isset($request['orderby']) && $request['orderby'] != '') ? $request['orderby'] : 'comment_date'; 
		switch ($orderBy)
		{
			case 'rand' :
				$orderBy = 'RAND()';
				break;
			
			default :
				break;
		}
		
		$sql .= " ORDER BY $orderBy $order";
		
		// Limit.
		$offset = (isset($request['offset']) && intval($request['offset']) > 0) ? intval($request['offset']) : 0;
		if (isset($request['limit']) && intval($request['limit']) > 0)
		{
			$sql .= " LIMIT $offset, " . intval($request['limit']);
		}
		else if (!isset($request['limit']) || is_null($request['limit']))
		{
			$sql .= " LIMIT $offset, 5";
		}

		// Execute the SQl query.
		$result = $wpdb->get_results($sql, ARRAY_A);
		if (is_null($result))
		{
			$result = array();
		}
		return $result;
	}
}
?>