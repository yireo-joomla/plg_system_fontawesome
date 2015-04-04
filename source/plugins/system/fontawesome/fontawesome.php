<?php
/**
 * Joomla! System plugin - FontAwesome tags
 *
 * @author    Yireo (info@yireo.com)
 * @copyright Copyright 2015
 * @license   GNU Public License
 * @link      http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Import the parent class
jimport('joomla.plugin.plugin');

/**
 * Font Awesome System Plugin
 */
class plgSystemFontAwesome extends JPlugin
{
	/**
	 * Event onAfterDispatch
	 *
	 * @access public
	 *
	 * @param null
	 *
	 * @return null
	 */
	public function onAfterInitialise()
	{
		// Only continue in the frontend
		$application = JFactory::getApplication();

		if ($application->isSite() == false)
		{
			return false;
		}

		$document = JFactory::getDocument();

		$path = $this->params->get('path');

		if (!empty($path))
		{
			$document->addStylesheet($path);
		}
		else
		{
			$document->addStylesheet('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
		}
	}

	/**
	 * Event onAfterRender
	 *
	 * @param null
	 *
	 * @return bool
	 */
	public function onAfterRender()
	{
		// Only continue in the frontend
		$application = JFactory::getApplication();

		if (!$application->isSite())
		{
			return false;
		}

		// @todo: Skip when editing in frontend

		// Get the body and fetch a list of files
		$body = JResponse::getBody();
		$body = $this->replaceTags($body);
		JResponse::setBody($body);

		return true;
	}

	/**
	 * Method to replace {fa ...} tags in the body
	 *
	 * @param string $body HTML body
	 *
	 * @return string
	 */
	protected function replaceTags($body)
	{
		if (preg_match_all('/\{fa\ ([^\}]+)\}/', $body, $matches))
		{
			$prefix = $this->params->get('prefix', 'fa');

			foreach ($matches[0] as $index => $match)
			{
				$tags = array();
				$stackedTags = array();

				$arguments = $matches[1][$index];
				$aliases = $this->getAliases();

				if (isset($aliases[$arguments]))
				{
					$arguments = $aliases[$arguments];
				}

				// First detect the stacked items
				if (preg_match_all('/\[([^\]]+)\]/', $arguments, $stackMatches))
				{
					foreach ($stackMatches[1] as $stackIndex => $stackMatch)
					{
						$newStackedTags = $this->filterTags($stackMatch);
						$stackedTags[] = $newStackedTags;
						$arguments = str_replace($stackMatches[0][$stackIndex], '', $arguments);
					}
				}

				$tags = $this->filterTags($arguments);
				$html = array();

				if (!empty($stackedTags))
				{
					$html[] = '<span class="' . implode(' ', $tags) . '">';

					foreach ($stackedTags as $stackedTag)
					{
						$html[] = '<i class="' . $prefix . ' ' . implode(' ', $stackedTag) . '"></i>';
					}

					$html[] = '</span>';
				}
				else
				{
					$html[] = '<i class="' . $prefix . ' ' . implode(' ', $tags) . '"></i>';
				}

				$html = implode('', $html);

				$body = str_replace($match, $html, $body);
			}
		}

		return $body;
	}

	/**
	 * Method to filter a fa tag
	 *
	 * @param array $tags
	 *
	 * @return array
	 */
	protected function filterTags($tags)
	{
		if (!is_array($tags))
		{
			$tags = explode(' ', $tags);
		}

		$prefix = $this->params->get('prefix', 'fa');

		$newTags = array();

		foreach ($tags as $tag)
		{
			$tag = trim($tag);

			if (empty($tag))
			{
				continue;
			}

			if (!empty($prefix) && preg_match('/^' . $prefix . '-/', $tag) == false)
			{
				$tag = $prefix . '-' . $tag;
			}

			$newTags[] = $tag;
		}

		return $newTags;
	}

	/**
	 * Method to fetch a listing of all aliases
	 *
	 * @return array
	 */
	protected function getAliases()
	{
		if (empty($this->aliases))
		{
			$this->aliases = array();

			$aliases = $this->params->get('aliases');
			$aliases = trim($aliases);

			if (!empty($aliases))
			{
				$aliasValues = explode(',', $aliases);

				foreach ($aliasValues as $alias)
				{
					$alias = trim($alias);

					if (empty($alias))
					{
						continue;
					}

					$alias = explode('=', $alias);
					$name = $alias[0];
					$value = $alias[1];
					$value = str_replace('"', '', $value);

					$this->aliases[$name] = $value;
				}
			}
		}

		return $this->aliases;
	}
}
