<?php
/**
 * @author Darathor <darathor@free.fr> http://wp.darathor.com
 * @package post-lister
 */ 
abstract class ClistBlockBase
{
	/**
	 * The widget name.
	 */
	const PLUGIN_INTERNAL_NAME = 'post-lister';
	
	/**
	 * The widget name.
	 */
	const BLOCK_LOCALE_GENRATED_NAME = 'post-lister-clist';

	/**
	 * The shortcode tag name.
	 */
	const SHORTCODE_NAME = 'clist';

	/**
	 * The widget name.
	 */
	const WIDGET_NAME = 'clist';
	
	/**
	 * The widget class.
	 */
	const WIDGET_CLASS = 'clist';
	
	/**
	 * Gets the value of a custom field after having applied shortcodes on it.
	 * @param String $fieldName
	 * @return String
	 */
	public static function getShortcodedCustomFieldValue($fieldName)
	{
		$values = get_post_custom_values($fieldName);
		if (is_array($values) && isset($values[0]))
		{
			return do_shortcode($values[0]);
		} 
		return null;
	}
	
	/**
	 * Echoes the value of a custom field after having applied shortcodes on it.
	 * @param String $fieldName
	 */
	public static function echoShortcodedCustomFieldValue($fieldName)
	{
		$value = self::getShortcodedCustomFieldValue($fieldName);
		if ($value !== null)
		{
			echo $value;
		} 
	}
	
	/**
	 * Shortcode conversion.
	 * @param Array $attributes
	 * @param String $content
	 * @return String
	 */
	public abstract function shortcodeHandler($attributes, $content = null);

	/**
	 * Frontoffice widget display.
	 * @param Array $attributes
	 * @param Mixed $widgetArgs
	 * @return Void
	 */
	public function widgetHandler($attributes, $widgetArgs = 1)
	{
		// Get the widget number.
		if (is_numeric($widgetArgs))
		{
			$widgetArgs = array('number' => $widgetArgs);
		}
		$widget_args = wp_parse_args($widget_args, array('number' => -1));
		$number = $widgetArgs['number'];
		
		// Get this widget options.
		$allOptions = get_option(self::WIDGET_NAME);
		if (!isset($allOptions[$number]))
		{
			return;
		}		
		$options = $allOptions[$number];
		if (isset($options[$number]['error']) && $options[$number]['error'])
		{
			return;
		}
		
		$this->doWidgetHandler($attributes, $options, $number);
	}
	
	/**
	 * Frontoffice widget display.
	 * @param Array $attributes
	 * @param Array $options
	 * @return Void
	 */
	protected abstract function doWidgetHandler($attributes, $options, $number);

	/**
	 * Backoffice widget preferences.
	 * @param Mixed $widgetArgs
	 * @return Void
	 */
	public function widgetConfigurator($widgetArgs = array())
	{
		$options = get_option(self::WIDGET_NAME);
	
		// Get the widget number.
		if (is_numeric($widgetArgs))
		{
			$widgetArgs = array('number' => $widgetArgs);
		}
		$widgetArgs = wp_parse_args($widgetArgs, array('number' => -1));
		$number = $widgetArgs['number'];

		// Get the options in the post data.
		$fieldNames = array();
		// Set 'global'.
		$fieldNames['title'] = 'title';
		$fieldNames['limit'] = 'limit';
		// Set 'sort'.
		$fieldNames['order'] = 'order';
		$fieldNames['orderby'] = 'orderby';
		// Set 'filter'.
		$fieldNames['author'] = 'author';
		$fieldNames['author_name'] = 'author_name';
		$fieldNames['author_email'] = 'author_email';
		$fieldNames['post'] = 'post';
		// Set 'display'.
		$fieldNames['showauthor'] = 'showauthor';
		$fieldNames['showdate'] = 'showdate';
		$fieldNames['showtime'] = 'showtime';
		$fieldNames['showauthoravatar'] = 'showauthoravatar';
		$fieldNames['showtext'] = 'showtext';
		
		// Update options only if there is a modification.
		if ($this->hasToUpdate()) 
		{
			$options = $this->clearRemovedWidgets($options);
			
			// Update the options with posted data.
			foreach ((array)$_POST['widget-'.self::WIDGET_NAME] as $widgetNumber => $widgetData) 
			{
				if (!isset($options[$widgetNumber]) || $this->hasFieldSet($widgetData, $fieldNames))
				{
					$options[$widgetNumber] = $this->getOptionsFromPostData($options[$widgetNumber], $fieldNames, $widgetData);
				}
			}
		
			update_option(self::WIDGET_NAME, $options);
			$this->updated = true;
		}

		// Get the options for the current widget.
		$thisOptions = $options[$number];
		
		if ($number == -1)
		{
			$number = '%i%';
		}
		
		// Display the form.
		$fieldsetStyle = 'padding: 5px; margin: 3px; border: none; border-top: 1px solid grey; float: left; width: 230px;';
		$legendStyle = 'padding: 5px;';
		$hrStyle = 'clear: both; margin: 0; padding: 0; height: 0; border: none;';
		
		$form = array();
		$form[] = '<div style="overflow-y: auto;">';

		// Set 'global'.		
		$form[] = '<fieldset style="' . $fieldsetStyle . '"><legend style="' . $legendStyle . '">' . __('clist-set-global-label', self::BLOCK_LOCALE_GENRATED_NAME) . '</legend>';
		$form[] = '<p>';
		$form[] = $this->getTextField($number, 'title', 'clist-parameter-title-label', $thisOptions['title']);
		$form[] = '</p>';
		$form[] = '<p>';
		$form[] = $this->getTextField($number, 'limit', 'clist-parameter-limit-label', $thisOptions['limit']);
		$form[] = '</p>';
		$form[] = '</fieldset>';

		// Set 'sort'.		
		$form[] = '<fieldset style="' . $fieldsetStyle . '"><legend style="' . $legendStyle . '">' . __('clist-set-sort-label', self::BLOCK_LOCALE_GENRATED_NAME) . '</legend>';
		$form[] = '<p>';
		$fieldOptions = array();
		$fieldOptions[] = array('value' => 'desc', 'nameKey' => 'clist-parameter-order-option-desc-label');
		$fieldOptions[] = array('value' => 'asc', 'nameKey' => 'clist-parameter-order-option-asc-label');
		$form[] = $this->getSelectField($number, 'order', 'clist-parameter-order-label', $thisOptions['order'], $fieldOptions);
		$form[] = '</p>';
		$form[] = '<p>';
		$fieldOptions = array();
		$fieldOptions[] = array('value' => 'comment_date', 'nameKey' => 'clist-parameter-orderby-option-comment_date-label');
		$fieldOptions[] = array('value' => 'comment_author', 'nameKey' => 'clist-parameter-orderby-option-comment_author-label');
		$fieldOptions[] = array('value' => 'comment_post_ID', 'nameKey' => 'clist-parameter-orderby-option-comment_post_ID-label');
		$fieldOptions[] = array('value' => 'rand', 'nameKey' => 'clist-parameter-orderby-option-rand-label');
		$form[] = $this->getSelectField($number, 'orderby', 'clist-parameter-orderby-label', $thisOptions['orderby'], $fieldOptions);
		$form[] = '</p>';
		$form[] = '</fieldset>';
		$form[] = '<hr style="' . $hrStyle . '" />';

		// Set 'filter'.		
		$form[] = '<fieldset style="' . $fieldsetStyle . '"><legend style="' . $legendStyle . '">' . __('clist-set-filter-label', self::BLOCK_LOCALE_GENRATED_NAME) . '</legend>';
		$form[] = '<p>';
		$form[] = $this->getTextField($number, 'author', 'clist-parameter-author-label', $thisOptions['author']);
		$form[] = '</p>';
		$form[] = '<p>';
		$form[] = $this->getTextField($number, 'author_name', 'clist-parameter-author_name-label', $thisOptions['author_name']);
		$form[] = '</p>';
		$form[] = '<p>';
		$form[] = $this->getTextField($number, 'author_email', 'clist-parameter-author_email-label', $thisOptions['author_email']);
		$form[] = '</p>';
		$form[] = '<p>';
		$form[] = $this->getTextField($number, 'post', 'clist-parameter-post-label', $thisOptions['post']);
		$form[] = '</p>';
		$form[] = '</fieldset>';

		// Set 'display'.		
		$form[] = '<fieldset style="' . $fieldsetStyle . '"><legend style="' . $legendStyle . '">' . __('clist-set-display-label', self::BLOCK_LOCALE_GENRATED_NAME) . '</legend>';
		$form[] = '<p>';
		$form[] = $this->getCheckboxField($number, 'showauthor', 'clist-parameter-showauthor-label', $thisOptions['showauthor']);
		$form[] = '</p>';
		$form[] = '<p>';
		$form[] = $this->getCheckboxField($number, 'showdate', 'clist-parameter-showdate-label', $thisOptions['showdate']);
		$form[] = '</p>';
		$form[] = '<p>';
		$form[] = $this->getCheckboxField($number, 'showtime', 'clist-parameter-showtime-label', $thisOptions['showtime']);
		$form[] = '</p>';
		$form[] = '<p>';
		$fieldOptions = array();
		$fieldOptions[] = array('value' => 'none', 'nameKey' => 'clist-parameter-showauthoravatar-option-none-label');
		$fieldOptions[] = array('value' => '32', 'nameKey' => 'clist-parameter-showauthoravatar-option-32-label');
		$fieldOptions[] = array('value' => '64', 'nameKey' => 'clist-parameter-showauthoravatar-option-64-label');
		$fieldOptions[] = array('value' => '96', 'nameKey' => 'clist-parameter-showauthoravatar-option-96-label');
		$fieldOptions[] = array('value' => '128', 'nameKey' => 'clist-parameter-showauthoravatar-option-128-label');
		$form[] = $this->getSelectField($number, 'showauthoravatar', 'clist-parameter-showauthoravatar-label', $thisOptions['showauthoravatar'], $fieldOptions);
		$form[] = '</p>';
		$form[] = '<p>';
		$fieldOptions = array();
		$fieldOptions[] = array('value' => 'none', 'nameKey' => 'clist-parameter-showtext-option-none-label');
		$fieldOptions[] = array('value' => '25', 'nameKey' => 'clist-parameter-showtext-option-25-label');
		$fieldOptions[] = array('value' => '50', 'nameKey' => 'clist-parameter-showtext-option-50-label');
		$fieldOptions[] = array('value' => '75', 'nameKey' => 'clist-parameter-showtext-option-75-label');
		$fieldOptions[] = array('value' => '100', 'nameKey' => 'clist-parameter-showtext-option-100-label');
		$fieldOptions[] = array('value' => '150', 'nameKey' => 'clist-parameter-showtext-option-150-label');
		$fieldOptions[] = array('value' => '200', 'nameKey' => 'clist-parameter-showtext-option-200-label');
		$fieldOptions[] = array('value' => '300', 'nameKey' => 'clist-parameter-showtext-option-300-label');
		$fieldOptions[] = array('value' => 'all', 'nameKey' => 'clist-parameter-showtext-option-all-label');
		$form[] = $this->getSelectField($number, 'showtext', 'clist-parameter-showtext-label', $thisOptions['showtext'], $fieldOptions);
		$form[] = '</p>';
		$form[] = '</fieldset>';
		$form[] = '<hr style="' . $hrStyle . '" />';

		$form[] = '<input type="hidden" name="widget-' . self::WIDGET_NAME . '[' . $number . '][submit]" value="1" />';
		$form[] = '<input type="hidden" name="widget-id[' . self::WIDGET_NAME . '-' . $number . ']" value="1" />';
		$form[] = '</div>';
		echo implode("\n", $form);
	}
	
	// Constuctor.
	
	protected function __construct()
	{
		// Include the locales.		
		load_plugin_textdomain(self::BLOCK_LOCALE_GENRATED_NAME, 'wp-content/plugins/'.self::PLUGIN_INTERNAL_NAME.'/languages');
		load_plugin_textdomain(self::PLUGIN_INTERNAL_NAME, 'wp-content/plugins/'.self::PLUGIN_INTERNAL_NAME.'/languages');

		// Add the shortcode.
		add_shortcode(self::SHORTCODE_NAME, array($this, 'shortcodeHandler'));

		// Add the widget.
		if (function_exists('register_sidebar_widget') && function_exists('register_widget_control'))
		{
			$this->registerWidget();
		}
	}


	// Shortcode attributes management.

	/**
	 * @param Array<String, String> $attributes
	 * @param String $attributeName
	 * @param Mixed $defaultValue
	 */
	protected function getAttributeValue($attributes, $attributeName, $defaultValue = null)
	{
		if (isset($attributes[$attributeName]))
		{
			return $attributes[$attributeName];
		}
		else
		{
			return $defaultValue;
		}
	}
	
	/**
	 * @param Array<String, String> $attributes
	 * @param String $attributeName
	 * @param Mixed $defaultValue
	 */
	protected function getBooleanAttributeValue($attributes, $attributeName, $defaultValue = false)
	{
		if (isset($attributes[$attributeName]))
		{
			return ($attributes[$attributeName] == 'true') ? true : false;
		}
		else
		{
			return $defaultValue;
		}
	}


	// Back-end fields generation.
	
	/**
	 * @param Integer $number
	 * @param String $id
	 * @param String $nameKey
	 * @param String $value
	 * @return String
	 */
	protected function getCheckboxField($number, $id, $nameKey, $value)
	{
		return '<label for="widget-' . self::WIDGET_NAME . '-' . $id . '-' . $number . '"><input type="checkbox" class="checkbox" id="widget-' . self::WIDGET_NAME . '-' . $id . '-' . $number . '" name="widget-' . self::WIDGET_NAME . '[' . $number . '][' . $id . ']"' . (($value == 'true') ? ' checked="checked"' : '') . ' value="true" /> ' . __($nameKey, self::BLOCK_LOCALE_GENRATED_NAME) . '</label>';
	}

	/**
	 * @param Integer $number
	 * @param String $id
	 * @param String $nameKey
	 * @param String $value
	 * @return String
	 */
	protected function getTextField($number, $id, $nameKey, $value)
	{
		return '<label for="widget-' . self::WIDGET_NAME . '-' . $id . '-' . $number . '">' . __($nameKey, self::BLOCK_LOCALE_GENRATED_NAME) . ' <input type="text" class="widefat" style="width: 100%;" id="widget-' . self::WIDGET_NAME . '-' . $id . '-' . $number . '" name="widget-' . self::WIDGET_NAME . '[' . $number . '][' . $id . ']" value="' . $value . '" /></label>';
	}
	
	/**
	 * @param Integer $number
	 * @param String $id
	 * @param String $nameKey
	 * @param String $value
	 * @param Array<Integer, Array<String, String>> $options
	 * @return String
	 */
	protected function getSelectField($number, $id, $nameKey, $value, $options)
	{
		$field = array();		
		$field[] = '<label for="widget-' . self::WIDGET_NAME . '-' . $id . '-' . $number . '">' . __($nameKey, self::BLOCK_LOCALE_GENRATED_NAME) . ' <select class="widefat" style="width: 100%;" id="widget-' . self::WIDGET_NAME . '-' . $id . '-' . $number . '" name="widget-' . self::WIDGET_NAME . '[' . $number . '][' . $id . ']">';
		foreach ($options as $option)
		{
			$field[] = '<option value="' . $option['value'] . '"' . (($value == $option['value']) ? ' selected="selected"' : '') . '>' . __($option['nameKey'], self::BLOCK_LOCALE_GENRATED_NAME) . ' </option>';
		}
		$field[] = '</select></label>';
		return implode("\n", $field);
	}
	
	// Widget configuration.
	
	/**
	 * @var Boolean
	 */
	private $updated = false;
	
	/**
	 * @return Boolean
	 */
	protected function hasToUpdate()
	{
		return (!$this->updated && 'POST' == $_SERVER['REQUEST_METHOD'] && !empty ($_POST['sidebar']));
	}
	
	/**
	 * @param Array<Integer, Array<String, String>> $options
	 * @return Array<Integer, Array<String, String>>
	 */
	protected function clearRemovedWidgets($options)
	{
		global $wp_registered_widgets;
		
		// Get current sidebar name.
		$sidebar = (string)$_POST['sidebar'];
		
		// Get widgets id for this sidebar.		
		$sidebarsWidgets = wp_get_sidebars_widgets();
		if (isset($sidebarsWidgets[$sidebar]))
		{
			$widgetIds = $sidebarsWidgets[$sidebar];
		}
		else
		{
			$widgetIds = array ();
		}
		
		// Do not update the removed widgets.
		foreach ($widgetIds as $widgetId) 
		{
			// Check only for this type of widgets.
			if (self::WIDGET_CLASS == $wp_registered_widgets[$widgetId]['callback'] && isset($wp_registered_widgets[$widgetId]['params'][0]['number']))
			{
				$widgetNumber = $wp_registered_widgets[$widgetId]['params'][0]['number'];
				
				// If the widget has been removed, unset its options.
				if (!in_array(self::WIDGET_NAME . '-' . $widgetNumber, $_POST['widget-id']))
				{
					unset($options[$widgetNumber]);
				}
			}
		}
		
		return $options;
	}
	
	/**
	 * @return void
	 */
	protected function registerWidget()
	{
		$widgetOptions = array(
			'classname' => self::WIDGET_CLASS,
			'description' => __(self::WIDGET_NAME . '-widget-description', self::BLOCK_LOCALE_GENRATED_NAME)
		);

		$formWidth = 2 * 250;

		$controlOptions = array('width' => $formWidth, 'id_base' => self::WIDGET_NAME);
			
		$widgetname = __(self::WIDGET_NAME . '-widget-name', self::BLOCK_LOCALE_GENRATED_NAME);
		
		$options = get_option(self::WIDGET_NAME);
		
		// If there are existing instances, register them.
		if (is_array($options) && count($options) > 0)
		{
			foreach (array_keys($options) as $index)
			{
				$id = self::WIDGET_NAME . '-' . $index;			
				wp_register_sidebar_widget($id, $widgetname, array($this, 'widgetHandler'), $widgetOptions, array('number' => $index));
				wp_register_widget_control($id, $widgetname, array($this, 'widgetConfigurator'), $controlOptions, array('number' => $index));
			}
		}
		// If there is no existing instance, register the widget's existance.
		else
		{
			// Warning: here, the number is set as -1 but the widget id is "<widgetName>-1"
			//          and not "<widgetName>--1".
			$id = self::WIDGET_NAME . '-1';			
			wp_register_sidebar_widget($id, $widgetname, array($this, 'widgetHandler'), $widgetOptions, array('number' => -1));
			wp_register_widget_control($id, $widgetname, array($this, 'widgetConfigurator'), $controlOptions, array('number' => -1));
		}
	}
	
	/**
	 * @param Array<String, String> $oldOptions
	 * @param Array<String, String> $fieldNames
	 */
	protected function getOptionsFromPostData($oldOptions, $fieldNames, $postedData = null)
	{
		// If there is no given data, get the global post data.
		if (is_null($postedData))
		{
			$postedData = $_POST;
		}

		// Update options.
		foreach ($fieldNames as $fieldName => $optionName)
		{
			if (isset($postedData[$fieldName])) 
			{
				$oldOptions[$optionName] = strip_tags(stripslashes($postedData[$fieldName]));
			}
			else
			{
				$oldOptions[$optionName] = '';
			}
		}

		return $oldOptions;
	}
	
	/**
	 * @param Array<String, String> $attributes
	 * @param Boolean $evaluate
	 * @return Array<String, String>
	 */
	protected function getValuesFromAttributes($attributes, $evaluate = false)
	{
		// Set default attributes.
		$values = array();
		$values['title'] = null;
		$values['author'] = null;
		$values['author_name'] = null;
		$values['author_email'] = null;
		$values['post'] = null;
		$values['order'] = 'desc';
		$values['orderby'] = 'comment_date';
		$values['offset'] = null;
		$values['limit'] = '5';
		$values['beforelist'] = null;
		$values['afterlist'] = null;
		$values['beforeitem'] = null;
		$values['afteritem'] = null;
		$values['showauthor'] = 'false';
		$values['showdate'] = 'false';
		$values['showtime'] = 'false';
		$values['showauthoravatar'] = 'none';
		$values['showtext'] = 'none';

		// Alias attributes.
		$aliases = array();
		$aliases['showpost'] = 'limit';
		foreach ($aliases as $aliasName => $attributeName)
		{
			$value = $this->getAttributeValue($attributes, $aliasName);
			if (!is_null($value))
			{
				$values[$attributeName] = $value;
			}
		}
		
		// Get values in attributes.
		foreach (array_keys($values) as $attributeName)
		{
			$value = $this->getAttributeValue($attributes, $attributeName);
			if ($evaluate)
			{
				$value = $this->evaluateValue($value);
			}
			
			if (!is_null($value))
			{
				$values[$attributeName] = $value;
			}
		}
		return $values;
	}
	
	/**
	 * @param String $value
	 */
	protected function evaluateValue($value)
	{
		if (substr($value, 0, 2) == '${' && substr($value, -1) == '}')
		{
			$expression = substr($value, 2, -1);
			// Function or method.
			if (substr($expression, -2) == '()')
			{
				$functionName = substr($expression, 0, -2);
				if (strpos($functionName, '::'))
				{
					$functionName = explode('::', $functionName);
				}
				
				if (is_callable($functionName))
				{
					$value = call_user_func($functionName);
				}
			}
			// Variable or field of a variable.
			else
			{
				$varName = $expression;
				if (strpos($varName, '->'))
				{
					list($varName, $fieldName) = explode('->', $varName);
				}
				
				global $$varName;
				if (isset($$varName))
				{
					if (isset($fieldName) && $fieldName)
					{
						$value = $$varName->$fieldName;
					}
					else
					{
						$value = $$varName;
					}
				}
			}
		}
		return $value;
	}
	
	// Private stuff.
	
	/**
	 * @param Array $widgetData
	 * @param Array $fieldNames
	 */
	private function hasFieldSet($widgetData, $fieldNames)
	{
		foreach ($fieldNames as $fieldName => $optionName)
		{
			if (isset($widgetData[$fieldName]))
			{
				return true;
			}
		}
		return false;
	}
}
?>