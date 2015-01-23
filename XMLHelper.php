<?php
namespace moonland\helpers;

use yii\helpers\ArrayHelper;

use yii\helpers\Html;

use yii\base\Object;

/**
 * XML Helper Class File.
 * @author Moh Khoirul Anam <moh.khoirul.anaam@gmail.com>
 * @copyright 2015
 * @since 1
 */
class XMLHelper extends Object
{
	/**
	 * Building the XML Only
	 * @param array $data
	 * @return text
	 */
	public static function build($data = [])
	{
		$text = '<?xml version="1.0" encoding="UTF-8" ?>'.PHP_EOL;
		foreach ($data as $tg => $ct) {
			if (is_array($ct) && isset($ct['content']) && isset($ct['attribute']))
				$text .= self::generateTag($tg, $ct['content'], $ct['attribute']);
			else
				$text .= self::generateTag($tg, $ct);
		}
		return $text;
	}
	
	/**
	 * Building the rss tag
	 * @param array $data to generate rss tag
	 * @return string
	 */
	public static function buildRSS($data = [])
	{
		$text = '<?xml version="1.0" encoding="UTF-8" ?>'.PHP_EOL;
		$text .= '<rss xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/" version="2.0">'.PHP_EOL;
		$text .= self::generateTag('channel', $data);
		$text .= '</rss>'.PHP_EOL;
		return $text;
	}
	
	/**
	 * XML Tag Generator
	 * @param string $tag
	 * @param mixed $content
	 * @param array $attributes
	 * @return string
	 */
	protected static function generateTag($tag, $content, $attributes = [])
	{
		$text = '';
		$attribute = self::attribute($attributes);
		if (is_string($tag))
			$text .= "<{$tag}{$attribute}>".PHP_EOL;
		
		if (is_array($content)) {
			if (isset($content['type']) and $content['type'] = 'cdata') {
				$text .="<![CDATA[".PHP_EOL;
				$text .= $content['data'].PHP_EOL;
				$text .="]]>".PHP_EOL;
			} else {
				foreach ($content as $tg => $ct) {
					if (is_array($ct) && isset($ct['content']) && isset($ct['attribute']))
						$text .= self::generateTag($tg, $ct['content'], $ct['attribute']);
					else
						$text .= self::generateTag($tg, $ct);
				}
			}
		} elseif (is_string($content)) {
			$text .= $content;
		}
		
		if (is_string($tag))
			$text .= "</{$tag}>".PHP_EOL;
		return $text;
	}
	
	/**
	 * Generate Attribute tag
	 * @param array $attributes to generate attribute tag
	 * @return string
	 */
	protected static function attribute($attributes = [])
	{
		$text = '';
		foreach ($attributes as $option => $value) {
			$text = ' ' . $option . '="' . $value . '"';
		}
		return $text;
	}
}