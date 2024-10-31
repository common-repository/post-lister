<?php
/**
 * @author Darathor <darathor@free.fr> http://wp.darathor.com
 * @package post-lister
 */ 
class WlistBlock extends WlistBlockBase
{
	/**
	 * Singleton handling.
	 * @return WlistBlock
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
	 * @var WlistBlock
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
	
	/**
	 * @var Integer
	 */
	private $id;
	
	/**
	 * @var stdClass
	 */
	private $post;
	
	/**
	 * @var stdClass
	 */
	private $authordata;
	
	/**
	 * Save the current data in order to restore them after the query.
	 * @return void
	 */
	private function saveCurrentdata()
	{
		global $id, $post, $authordata;
		$this->id = $id;
		$id = null;
		$this->post = $post;
		$post = null;
		$this->authordata = $authordata;
		$authordata = null;
	}
	
	/**
	 * Restore the saved data after the query.
	 * @return void
	 */
	private function restoreCurrentdata()
	{
		global $id, $post, $authordata;
		$id = $this->id;
		$post = $this->post;
		$authordata = $this->authordata;
	}
	
	/**
	 * List generation in HTML.
	 * @param String $request
	 * @param Array<String, Mixed> $parameters
	 * @param String $beforeList
	 * @param String $afterList
	 * @param String $beforeItem
	 * @param String $afterItem
	 */
	private function executeQuery($request = array(), $beforeList = '<ul class="wlist">', $afterList = '</ul>', $beforeItem = '<li>', $afterItem = '</li>')
	{
		// OK, WordPress isn't perfect, particularly the post management...
		// So, save all the current data (post, author, etc) in order to 
		// restore them after the query. If this is not done, all links and 
		// data displayed in the page after the query will refer to the last
		// post of the result and not the current one!
		$this->saveCurrentdata();
		
		// Exclude the current post.
		if ($this->id)
		{
			$request['post__not_in'] = array($this->id);
		}		
				
		// Create the query.
		$query = new WP_Query();
		$query->query($request);
		
		$renderedList = $beforeList;
		
		// Add each post to the list.		
		while ($query->have_posts())
		{
			$query->the_post();
			$renderedList .= $beforeItem;
			
			// Avatars.
			if ($request['showauthoravatar'] && $request['showauthoravatar'] != 'none')
			{
				$renderedList .= get_avatar(get_the_ID(), $request['showauthoravatar']);
			}
			
			// Post link.
			if ($request['showtitle'] == 'true')
			{
				$renderedList .= ' <a href="' . get_permalink() . '" rel="bookmark">' . the_title("", "", false) . '</a>';
			}
			
			// Author.
			if ($request['showauthor'] == 'true')
			{
				$renderedList .= ' ' . __('author', self::PLUGIN_INTERNAL_NAME);
				if (get_the_author_url()) {
					$renderedList .= ' <a href="' . get_the_author_url() . '" title="' . sprintf(__("Visit %s's website"), get_the_author()) . '" rel="external">' . get_the_author() . '</a>';
				} 
				else 
				{
					$renderedList .= ' ' . get_the_author();
				}
			}
			
			// Date.
			if ($request['showdate'] == 'true')
			{
				$renderedList .= ' ' . sprintf(__('date', self::PLUGIN_INTERNAL_NAME), get_the_time(get_option('date_format')));
			}
			if ($request['showtime'] == 'true')
			{
				$renderedList .= ' ' . sprintf(__('time', self::PLUGIN_INTERNAL_NAME), get_the_time(get_option('time_format')));
			}
			
			// Comment count.
			if ($request['showcommentcount'] == 'true')
			{
				$renderedList .= ' (' . $this->getCommentCount() . ')';
			}
			
			// Text.
			if ($request['showtext'] && $request['showtext'] != 'none')
			{
				$renderedList .= '<div class="content">';
				// If a limit is set, shorten the text.
				if ($request['showtext'] == 'all')
				{
					$renderedList .= $this->getRederedContent();
				}
				else
				{
					$renderedList .= $this->getShortenContent(intval($request['showtext']));
				}
				$renderedList .= '</div>';
			}
			
			$renderedList .= $afterItem;
		}			
		$renderedList .= $afterList;
		
		// The query result is handled, let's restore the data.
		$this->restoreCurrentdata();
		
		return $renderedList;
	}
	
	/**
	 * @return String
	 */
	private function getRederedContent()
	{
		$content = get_the_content();
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		return $content;
	}
	
	/**
	 * @param Integer $showTextLimit
	 * @return String
	 */
	private function getShortenContent($showTextLimit)
	{
		global $post;
		$postExcerpt = wp_trim_excerpt($post->post_excerpt);

		// Clean html tags and line breaks.
		$postExcerpt = strip_tags($postExcerpt);
		$postExcerpt = str_replace("\n", ' ', $postExcerpt);
		$postExcerpt = str_replace("\r", ' ', $postExcerpt);
		$postExcerpt = str_replace("([ ]+)", ' ', $postExcerpt);

		// Remove the last word if because it may contain an incomplete UTF-8 character.
		if (strlen($postExcerpt) > $showTextLimit)
		{
			$postExcerpt = substr($postExcerpt, 0, $showTextLimit);
			$postExcerpt = substr($postExcerpt, 0, strrpos($postExcerpt, ' '));
			$postExcerpt = trim($postExcerpt) . " [...]";
		}		
						
		// Apply comments filters (smilies, etc).
		$postExcerpt = apply_filters('the_excerpt', apply_filters('get_the_excerpt', $postExcerpt));
		
		return $postExcerpt;
	}
	
	/**
	 * @return String
	 */
	private function getCommentCount() 
	{
		global $id;
		$number = get_comments_number($id);
		switch ($number)
		{
			case 0 :
				$output = __('no-comment', self::PLUGIN_INTERNAL_NAME);
				break;
			
			case 1 :
				$output = __('one-comment', self::PLUGIN_INTERNAL_NAME);
				break;
				
			default :
				$output = sprintf(__('n-comments', self::PLUGIN_INTERNAL_NAME), $number);
				break;
		}
		return apply_filters('comments_number', $output, $number);
	}
	
	/**
	 * @param Array<String, String> $attributes
	 * @param Boolean $evaluate
	 * @return String
	 */
	private function executeQueryFromAttributes($attributes, $evaluate = false)
	{
		// Get the values from attributes.
		$values = $this->getValuesFromAttributes($attributes, $evaluate);
		
		// Construct the request.
		$request = array();
		foreach ($values as $attributeName => $value)
		{
			if (!$value !== null)
			{
				// Some attributes must be "remapped".
				switch ($attributeName)
				{
					case 'limit' : 
						$attributeName = 'showposts';
						break;
						
					default : 
						break;
				}
				
				$request[$attributeName] = $value;
			}
		}
		
		// Get other parameters.
		$beforeList = $this->getAttributeValue($attributes, 'beforelist', '<ul class="wlist">');
		$afterList = $this->getAttributeValue($attributes, 'afterlist', '</ul>');
		$beforeItem = $this->getAttributeValue($attributes, 'beforeitem', '<li>');
		$afterItem = $this->getAttributeValue($attributes, 'afteritem', '</li>');
			
		return $this->executeQuery($request, $beforeList, $afterList, $beforeItem, $afterItem);
	}
}
?>