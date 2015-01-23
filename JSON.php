<?php
namespace moonland\helpers;
/**
 * JSON is a encoder and decoder json format to array or array format to json.
 * @author Moh Khoirul Anam <moh.khoirul.anaam@gmail.com>
 * @copyright 2015
 * @since 1
 *
 */
class JSON
{	
	public static function encode($options)
	{
		//return static::encoder($options);
		return static::json_func_expr(json_encode($options));
	}
	
	protected static function json_func_expr($json)
	{
		return preg_replace_callback(
				'/(?<=:)"function\((?:(?!}").)*}"/',
				'static::json_strip_escape',
				$json
		);
	}
	
	protected static function json_strip_escape($string)
	{
		return str_replace(
				array('\n','\t','\"', '\\\\','\\/'),
				array('','','"','\\','/'),
				substr($string[0],1,-1)
		);
	}
	
	public static function encoder($options)
	{
		if (is_array($options)) {
			$text = [];
			$countData = count($options);
			$data = 1;
			foreach ($options as $key=>$value)
			{
				$keyObject = rand(000000, 999999);
				$text[$keyObject] = '';
				if ($data == 1) {
					if (is_numeric($key)) {
						$text[$keyObject] .= '[';
					} elseif (is_string($key)) {
						$text[$keyObject] .= '{';
					}
				}
				
				$text[$keyObject] .= static::jsEncoder($key) .':';
				
				if (is_array($value)) {
					$text[$keyObject] .= static::encoder($value);
				} elseif (is_string($value)) {
					$text[$keyObject] .= static::jsEncoder($value);
				} elseif (is_numeric($value)) {
					$text[$keyObject] .= $value;
				}
				
				if ($data == $countData) {
					if (is_numeric($key)) {
						$text[$keyObject] .= ']';
					} elseif (is_string($key)) {
						$text[$keyObject] .= '}';
					}
				}
				$data++;
			}
			$text_result = implode(',', $text);
		}
		return $text_result;
	}
	
	protected static function jsEncoder($key)
	{
		if (preg_match('/\bjs:/', $key)) {
			$key = str_replace('js:','',$key);
			return $key;
		} else {
			return '"'.$key.'"';
		}
	}
}