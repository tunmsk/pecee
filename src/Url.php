<?php
namespace Pecee;
class Url {

	public static function getHost($url) {
		$u = parse_url($url);
		return isset($u['host']) ? str_ireplace('www.', '', $u['host']) : null;
	}

	public static function getDomain($host = null) {
		$host = str_ireplace('www.', '', ($host === null) ? $_SERVER['HTTP_HOST'] : $host);
		$pos = strpos($host, '.');
		if($pos > -1 && $pos < strlen($host)-3) {
			return substr($host, strpos($host, '.')+1);
		}
		return $host;
	}

	public static function encode($url) {
		$url = parse_url($url);
		if(isset($url['path'])) {
			$paths = explode('/', $url['path']);
			if($paths) {
				foreach($paths as $key=>$path) {
					$paths[$key] = rawurlencode(rawurldecode($path));
				}
				$url['path'] = join('/', $paths);
			}
		}
		$scheme=(isset($url['scheme'])) ? $url['scheme'] . '://' : '';
		$host=(isset($url['host'] )) ? $url['host']  : '';
		return  $scheme  . $host . $url['path'] . ((!empty($url['query'])) ? '?' . $url['query'] : '');
	}

	public static function path($url) {
		$url = parse_url($url);
		return (isset($url['path'])) ? $url['path'] : '';
	}


	public static function getUrl($relative = true, $includeParams = true) {
		$pageURL = null;
		if(!$relative) {
			$pageURL = 'http';
			if (isset($_SERVER['HTTPS']))
				$pageURL .= 's';
			$pageURL .= '://';
		}

		$url = parse_url($_SERVER['REQUEST_URI']);
		$path = (isset($url['path'])) ? $url['path'] : '';
		if ($_SERVER['SERVER_PORT'] != '80')
			$pageURL .= ((!$relative) ? $_SERVER['SERVER_NAME'] .':'.$_SERVER['SERVER_PORT']: '').$path;
		else
			$pageURL .= ((!$relative) ? $_SERVER['SERVER_NAME'] : '') . $path;

		if($includeParams && isset($url['query'])) {
			$pageURL .= '?'.$url['query'];
		}

		return $pageURL;
	}

	public static function hasParams($url) {
		return (strpos($url, '?') > -1);
	}

	public static function paramsToArray($querystring) {
		if(!empty($querystring)) {
			$output = array();
			if( substr($querystring, 0, 1) == '?' ) {
				$querystring = substr($querystring, 1, strlen($querystring));
			}
			$tmp = @explode( '&', $querystring );
			foreach( $tmp as $q ) {
				$keyValue = @explode('=', $q);
				$output[$keyValue[0]] = $keyValue[1];
			}
			return $output;
		}
		return null;
	}

	public static function getParamsSeperator($url) {
		return (strpos($url, '?') > -1) ? '&' : '?';
	}

	public static function getParams($url) {
		$url = parse_url($url);
		return (isset($url['query'])) ? $url['query'] : '';
	}

	public static function arrayToParams(array $getParams = null, $includeEmpty = true) {
		if(is_array($getParams) && count($getParams) > 0) {
			foreach($getParams as $key=>$val) {
				if(!empty($val) || empty($val) && $includeEmpty) {
					$getParams[$key] = $key.'='.$val;
				}
			}
			return join('&', $getParams);
		}
		return '';
	}

	public static function isValid($url) {
		return (!preg_match('/^(http|https):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $url)) ? false : true;
	}

	public static function isValidHostname($hostname) {
		return preg_match('/^ (?: [a-z0-9] (?:[a-z0-9\\-]* [a-z0-9])? \\. )*  #Subdomains
   							[a-z0-9] (?:[a-z0-9\\-]* [a-z0-9])?            #Domain
   							\\. [a-z]{2,6} $                               #Top-level domain
							/ix', $hostname);
	}

	public static function urlEncodeString($string, $seperator = '-', $maxLength = 50) {
		if(strlen($string) > $maxLength) {
			$string = substr($string, 0, $maxLength);
		}
		$searchMap = array('æ' => 'ae', 'ø' => 'o', 'å' => 'a', ' ' => $seperator);
		foreach($searchMap as $search=>$replace) {
			$string = str_ireplace($search, $replace, $string);
		}
		$s = strtolower(preg_replace('/[^A-Za-z0-9 _\-\+\&'.join(' ', $searchMap).']/is','',$string));
		$pastChar = '';
		$newString = '';
		for($i=0;$i<strlen($s);$i++) {
			if(!$pastChar || $pastChar != $seperator || $pastChar != $s[$i]) {
				$newString .= $s[$i];
			}
			$pastChar = $s[$i];
		}
		return $newString;
	}

	public static function isSecure($url) {
		$url=parse_url($url);
		return (isset($url['scheme']) && strtolower($url['scheme']) == 'https');
	}
}