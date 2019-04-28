<?php

namespace Plugin;

class Http
{
	public static function headerStatus($status = 200, $replace = true)
	{
	    if (is_numeric($status)) {
	        $statusCodes = array (
	            100 => 'Continue',
	            101 => 'Switching Protocols',
	            200 => 'OK',
	            201 => 'Created',
	            202 => 'Accepted',
	            203 => 'Non-Authoritative Information',
	            204 => 'No Content',
	            205 => 'Reset Content',
	            206 => 'Partial Content',
	            300 => 'Multiple Choices',
	            301 => 'Moved Permanently',
	            302 => 'Found',
	            303 => 'See Other',
	            304 => 'Not Modified',
	            305 => 'Use Proxy',
	            307 => 'Temporary Redirect',
	            400 => 'Bad Request',
	            401 => 'Unauthorized',
	            402 => 'Payment Required',
	            403 => 'Forbidden',
	            404 => 'Not Found',
	            405 => 'Method Not Allowed',
	            406 => 'Not Acceptable',
	            407 => 'Proxy Authentication Required',
	            408 => 'Request Timeout',
	            409 => 'Conflict',
	            410 => 'Gone',
	            411 => 'Length Required',
	            412 => 'Precondition Failed',
	            413 => 'Request Entity Too Large',
	            414 => 'Request-URI Too Long',
	            415 => 'Unsupported Media Type',
	            416 => 'Requested Range Not Satisfiable',
	            417 => 'Expectation Failed',
	            500 => 'Internal Server Error',
	            501 => 'Not Implemented',
	            502 => 'Bad Gateway',
	            503 => 'Service Unavailable',
	            504 => 'Gateway Timeout',
	            505 => 'HTTP Version Not Supported',
	        );
	        if (array_key_exists($status, $statusCodes)) {
	            $status = $status . ' ' . $statusCodes[$status];
	        }
	    }

	    if (false !== stripos(php_sapi_name(), 'cgi')) {
	        header('Status: ' . $status, $replace);
	    } else {
	        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $status, $replace);
	    }
	}

	public static function isHttps()
	{
	    return (isset($_SERVER['HTTP_X_SERVER_PORT']) && $_SERVER['HTTP_X_SERVER_PORT'] == 443) || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
	}

	public static function getIp()
	{
	    if ( isset($_SERVER['HTTP_X_REAL_IP']) && preg_match('/^[\d\.]{7,15}$/', $_SERVER['HTTP_X_REAL_IP'])) {
	        return $_SERVER['HTTP_X_REAL_IP'];
	    } else {
	    	return $_SERVER["REMOTE_ADDR"];
	    }
	}

	public static function redirect($url = '', $status = 302)
	{
	    if (302 != $status) {
	        self::headerStatus($status);
	    }

	    header('Location: ' . $url);
	    exit;
	}

	public static function cache($lifeTime = 60)
	{
	    if ($lifeTime) {
	        header("Cache-Control: cache"); // HTTP/1.1
	        header("Pragma: cache"); // HTTP/1.0
	        header("Date: " . gmdate("D, j M Y H:i:s") . ' GMT');
	        header("Expires: " . gmdate("D, j M Y H:i:s", time() + $lifeTime) . " GMT");
	    } else {
	        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP/1.1
	        header("Pragma: no-cache"); // HTTP/1.0
	        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	        header("Last-Modified: " . gmdate("D, j M Y H:i:s") . " GMT"); // always modified
	    }
	}

	public static function isPost()
	{
	    return isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST';
	}

	public static function isAjax()
	{
	    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
	}

	public static function mimeType($type = 'html', $charset = null)
	{
	    $types = array(
	        'html'      => 'text/html',
	        'xml'       => 'text/xml',
	        'css'       => 'text/css',
	        'txt'       => 'text/plain',
	        'json'      => 'application/json',
	        'jsonp'     => 'application/javascript',
	        'js'        => 'application/javascript',
	        'javascript'=> 'application/javascript',
	        'rss'       => 'application/rss+xml',
	        'atom'      => 'application/atom+xml',
	        'xhtml'     => 'application/xhtml+xml',
	        'gif'       => 'image/gif',
	        'jpg'       => 'image/jpeg',
	        'png'       => 'image/png',
	        'ico'       => 'image/ico',
	        'bmp'       => 'image/x-ms-bmp',
	        'flv'       => 'video/x-flv',
	        'doc'       => 'application/msword',
	        'xls'       => 'application/vnd.ms-excel',
	        'csv'       => 'application/vnd.ms-excel',
	        'pdf'       => 'application/pdf',
	        'zip'       => 'application/zip',
	        'rar'       => 'application/x-rar-compressed',
	        'swf'       => 'application/x-shockwave-flash',
	        '*'         => 'application/octet-stream',
	    );

	    if (!isset($types[$type])) {
	        $type = '*';
	    }

	    $contentType = 'Content-Type: ' . $types[$type];
	    if ($charset) {
	        $contentType .= '; charset=' . $charset;
	    }
	    header($contentType);
	}

	public static function disposition($filename = "")
	{
	    header('Content-Disposition: attachment; filename=' . $filename);
	}
}