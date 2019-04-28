<?php

namespace Plugin;

class Func
{
	/**
	 * Whether or not the POST request
	 *
	 * @return boolean
	 */
	public static function isPost()
	{
	    return isset($_SERVER['REQUEST_METHOD']) && 'POST' === $_SERVER['REQUEST_METHOD'];
	}

	/**
	 * Whether or not the AJAX request
	 *
	 * @return boolean
	 */
	public static function isAjax()
	{
	    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'XMLHttpRequest' === $_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_REQUEST['X-Requested-With']) && 'XMLHttpRequest' === $_REQUEST['X-Requested-With']);
	}

	/**
	 * Whether or not the JSON
	 *
	 * @param string $string
	 * @return boolean
	 */
	public static function isJson($string = "")
	{
	    if (!is_string($string)) {
	        return false;
	    }

	    $result = json_decode($string);
	    if (is_object($result) || is_array($result)) {
	        return true;
	    } else {
	        return false;
	    }
	}

	/**
	 * 正则表达式验证email格式
	 *
	 * @param string $str    所要验证的邮箱地址
	 * @return boolean
	 */
	public static function isEmail($str = "") {
	    if (!$str) {
	        return false;
	    }
	    return preg_match('#[a-z0-9&\-_.]+@[\w\-_]+([\w\-.]+)?\.[\w\-]+#is', $str) ? true : false;
	}

	/** 
	 * Check string if is phone
	 * @param string  $phone
	 * @return boolean
	 */  
	public static  function isPhone($phone = "")
	{
	    if(preg_match("/^((0\d{2,3}-\d{7,8})|(1[35847]\d{9}))$/", $phone)) {  
	        return true;  
	    } else {  
	        return false;  
	    }  
	}

	/**
	 * [is_idcard 验证身份证号码是否正确函数]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2018-03-27
	 * @param    [type]                  $id [description]
	 * @return   boolean                     [description]
	 */
	public static function isCard($id = '') 
	{
	    $id = strtoupper($id); 
	    $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/"; 
	    $arr_split = array(); 
	    if (!preg_match($regx, $id)) { 
	        return false; 
	    } 
	    if (15 == strlen($id)) { //检查15位 
	        $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/"; 

	        @preg_match($regx, $id, $arr_split); 
	        //检查生日日期是否正确 
	        $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4]; 
	        if (!strtotime($dtm_birth)) { 
	            return false; 
	        } else { 
	            return true; 
	        } 
	    } else { //检查18位 
	        $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/"; 
	        @preg_match($regx, $id, $arr_split); 
	        $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4]; 
	        if(!strtotime($dtm_birth)) { //检查生日日期是否正确 
	        return false; 
	        }

	        //检验18位身份证的校验码是否正确。 
	        //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。 
	        $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); 
	        $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'); 
	        $sign = 0; 
	        for ( $i = 0; $i < 17; $i++ ) { 
	            $b = (int) $id{$i}; 
	            $w = $arr_int[$i]; 
	            $sign += $b * $w; 
	        } 
	        $n = $sign % 11; 
	        $val_num = $arr_ch[$n]; 
	        if ($val_num != substr($id,17, 1)) { 
	            return false; 
	        } else { 
	            return true; 
	        }
	    }
	}

	/**
	 * [bankBaseList 银行卡]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2018-04-26
	 * @param    string                  $card [description]
	 * @return   [type]                        [description]
	 */
	public static function bankCardInfo($card = '')
	{
	    if (!preg_match("/^[\d]{4,20}$/", $card)) {
	        return NULL;
	    }

	    $json = file_get_contents(__DIR__ . "/Bank/info.json");
	    $bank_data = json_decode($json, true);

	    for ($i=8; $i > 3; $i--) { 
	    	$card = substr($card, 0, $i);
	    	if (!isset($bank_data[$card])) {
	    		continue;
	    	}

	    	return $bank_data[$card];
	    }

	    return NULL;
	}

	/** 
	 * Check string if is url
	 * @param string  $url
	 * @return boolean
	 */  
	public static function isUrl($url = "")
	{  
	    if(filter_var($url,FILTER_VALIDATE_URL)) {  
	        return true;  
	    } else {  
	        return false;  
	    }  
	}

	/** 
	 * Check string if is IP
	 * @param string  $ip
	 * @return boolean
	 */  
	public static function isIp($ip = "")
	{  
	    if(filter_var($ip,FILTER_VALIDATE_IP)) {  
	        return true;  
	    } else {  
	        return false;  
	    }  
	}

	/**
	 * [cropImage 裁剪图片]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-28
	 * @param    [type]                  $path   [description]
	 * @param    string                  $dir    [description]
	 * @param    integer                 $width  [description]
	 * @param    integer                 $height [description]
	 * @return   [type]                          [description]
	 */
	public static function cropImage($path = null, $width = 750, $height = 380)
	{
		$result = array('status' => false, 'mag' => '');

	    $insInit = array(
	        'width' => $width,
	        'height' => $height,
	    );

	    $data = array(
	        'dir' => null, 'src' => null,
	        'dir_x' => 0, 'dir_y' => 0,
	        'src_x' => 0, 'src_y' => 0,
	        'dir_w' => 0, 'dir_h' => 0,
	        'src_w' => 0, 'src_h' => 0,
	    );

	    if (!file_exists($path)) {
	    	$result['msg'] = "图片不存在";
	        return $result;
	    }

	    $pathinfo = getimagesize($path);
	    if (!in_array($pathinfo['mime'], array('image/jpeg', 'image/x-png', 'image/pjpeg', 'image/png'))) {
	    	$result['msg'] = "图片类型必须为 JPG, JPEG, PNG";
	        return $result;
	    }

	    if (in_array($pathinfo['mime'], array('image/x-png', 'image/png'))) {
	        $key = array('imagecreatefrompng', 'imagepng');
	    } elseif (in_array($pathinfo['mime'], array('image/gif'))) {
	        $key = array('imagecreatefromgif', 'imagegif');
	    } else {
	        $key = array('imagecreatefromjpeg', 'imagejpeg');
	    }
	    
	    $pathinfo = array_merge($pathinfo, pathinfo($path));

	    // new file path
	    $fileName = explode("_{$width}-{$height}", $pathinfo['filename']);
	    $name = $fileName[0] . "_{$width}-{$height}." . $pathinfo['extension'];
	    $dirFile = $pathinfo['dirname'] . "/" . $name;

	    // init image
	    $srcImg = $key[0]($path);
	    $dirImg = imagecreatetruecolor($insInit['width'], $insInit['height']);
	    $color = imagecolorallocate($dirImg, 255, 255, 255);
	    imagefill($dirImg, 0, 0, $color);

	    if ($pathinfo[0] <= $insInit['width'] && $pathinfo[1] <= $insInit['height']) { //1 2 3 7
	        $data = array(
	            'dir' => $dirImg, 'src' => $srcImg,
	            'dir_x' => ($insInit['width'] - $pathinfo[0]) / 2, 'dir_y' => ($insInit['height'] - $pathinfo[1]) / 2,
	            'src_x' => 0, 'src_y' => 0,
	            'dir_w' => $pathinfo[0], 'dir_h' => $pathinfo[1],
	            'src_w' => $pathinfo[0], 'src_h' => $pathinfo[1],
	        );
	    } elseif ($pathinfo[0] == $insInit['width'] || $pathinfo[1] == $insInit['height']) { //4 5
	        $data = array(
	            'dir' => $dirImg, 'src' => $srcImg,
	            'dir_x' => 0, 'dir_y' => 0,
	            'src_x' => ($pathinfo[0] - $insInit['width']) / 2, 'src_y' => ($pathinfo[1] - $insInit['height']) / 2,
	            'dir_w' => $insInit['width'], 'dir_h' => $insInit['height'],
	            'src_w' => $insInit['width'], 'src_h' => $insInit['height'],
	        );
	    } elseif ($pathinfo[0] == $pathinfo[1]) { //6
	        $data = array(
	            'dir' => $dirImg, 'src' => $srcImg,
	            'dir_x' => 0, 'dir_y' => 0,
	            'src_x' => 0, 'src_y' => 0,
	            'dir_w' => $insInit['width'], 'dir_h' => $insInit['height'],
	            'src_w' => $pathinfo[0], 'src_h' => $pathinfo[1],
	        );
	    } else {
	        $min = min($pathinfo[0], $pathinfo[1]);
	        if ($min == $pathinfo[0]) {
	            $width = $insInit['width'];
	            $height = $pathinfo[1] * $insInit['width'] / $pathinfo[0];
	        } else {
	            $width = $pathinfo[0] * $insInit['height'] / $pathinfo[1];
	            $height = $insInit['height'];
	        }

	        $abbreviationsImg = imagecreatetruecolor($width, $height);

	        // copy abbreviations image
	        imagecopyresampled($abbreviationsImg, $srcImg, 0, 0, 0, 0, $width, $height, $pathinfo[0], $pathinfo[1]);
	        $key[1]($abbreviationsImg, $dirFile);
	        imagedestroy($abbreviationsImg);

	        return self::cropImage($dirFile, $insInit['width'], $insInit['height']);
	    }

	    // copy image
	    imagecopyresampled($data['dir'], $data['src'], $data['dir_x'], $data['dir_y'], $data['src_x'], $data['src_y'], $data['dir_w'], $data['dir_h'], $data['src_w'], $data['src_h']);

	    // save image
	    $key[1]($dirImg, $dirFile);

	    // close image
	    imagedestroy($srcImg);
	    imagedestroy($dirImg);

	    $result['status'] = true;
	    $result['path'] = $dirFile;
	    return $result;
	}

	/**
	 * Create new file
	 *
	 * @param string $path
	 * @return boolean
	 */
	public static function createFile($path = "")
	{
	    $path = trim($path);
	    if (!$path) {
	        return false;
	    }

	    $path = preg_replace("/\\\\/", "/", $path);

	    $filename = substr($path, strripos($path, "/") + 1);
	    $ext = substr($filename, strripos($filename, ".") + 1);
	    if (!$ext) {
	        $filename = "";
	    }

	    $dirPathInfo = explode("/{$filename}", $path);
	    array_pop($dirPathInfo);
	    $dirPath = implode("/", $dirPathInfo);

	    if ($filename) {
	        if (is_dir($path)) {
	            return false;
	        }

	        if (file_exists($path)) {
	            return true;
	        }
	    } else {
	        if (is_dir($path)) {
	            return true;
	        }
	    }

	    // make dir
	    if (!is_dir($dirPath)) {
	        if (file_exists($dirPath)) {
	            return false;
	        }

	        if (!@mkdir($dirPath, 0777, true)) {
	            if (!is_dir($dirPath)) {
	                return false;
	            }
	        }
	    }

	    // make file
	    if ($filename) {
	        $handle = fopen($path, 'a');
	        fclose($handle);
	    }

	    if (file_exists($path)) {
	        return true;
	    } else {
	        return false;
	    }
	}

	/**
	 * Get the file information
	 *
	 * @param string $filename
	 * @return array
	 */
	public static function fileInfo($filename = "")
	{
	    $pregArr = array(
	        "/\f/i" => "/f", "/\n/i" => "/n", "/\r/i" => "/r",
	        "/\t/i" => "/t", "/\v/i" => "/v", "/\\\\/" => "/",
	    );
	    $imgType = array("jpg", "gif", "png");

	    // init file path
	    foreach ($pregArr as $key => $value) {
	        $filename = preg_replace($key, $value, $filename);
	    }

	    if (!file_exists($filename)) {
	        return false;
	    }

	    $pathinfo = pathinfo($filename);
	    if (isset($pathinfo['extension']) && in_array(strtolower($pathinfo['extension']), $imgType)) {
	        $imgInfo = getimagesize($filename);
	        $pathinfo = array_merge($imgInfo, $pathinfo);
	    }

	    return $pathinfo;
	}

	/**
	 * Read the file directory
	 * 
	 */
	public static function searchDir($path = "", &$data = array())
	{
	    if (is_dir($path)) {
	        $handle = opendir($path);
	        while ($re = readdir($handle)) {
	            if (in_array($re, array(".", ".."))) {
	                continue;
	            }
	            self::searchDir($path . '/' . $re, $data);
	        }
	        closedir($handle);
	    } else {
	        $data[] = $path;
	    }
	}

	/**
	 * Read the file directory
	 * 
	 * @param string $dir = ""
	 * @return array
	 */
	public static function dirInfo($dir = "")
	{
	    $data = array();
	    self::searchDir($dir, $data);

	    return $data;
	}


	/**
	 * [setData 处理json中信息]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-18
	 * @param    array                   $data [description]
	 */
	public static function processData($data = array())
	{
		if (is_array($data)) {
			foreach ($data as $key => $row) {
				$data[$key] = self::processData($row);
			}

			return $data;
		} else {
			if ($data !== false && $data !== true) {
				return urlencode($data);
			} else {
				return $data;
			}
		}
	}

	/**
	 * [param 传入参数处理]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-18
	 * @param    string                  $name [description]
	 * @return   [type]                        [description]
	 */
	public static function param($name = '')
	{
		$name = trim($name);

		if (isset($_GET[$name])) {
			return (string) trim($_GET[$name]);
		}

		if (isset($_POST[$name])) {
			if (is_array($_POST[$name])) {
				return $_POST[$name];
			} else {
				return (string) trim($_POST[$name]);
			}
		}

		return NULL;
	}

	/**
	 * [appJson 信息返回接口]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-18
	 * @param    array                   $data [description]
	 * @return   [type]                        [description]
	 */
	public static function appJson($data = array())
	{
		$data = self::processData($data);
		$str = urldecode(json_encode($data));

		return $str;
	}

	public static function log($data = array(), $filename = '')
	{
	    $data = self::appJson($data);

	    $str = sprintf("[%s]:", date("Y-m-d H:i:s")) . PHP_EOL;
	    $str .= sprintf("	%s", $data) . PHP_EOL . PHP_EOL . PHP_EOL;

	    if (!$filename) {
	        $filename = 'sys.log';
	    }

	    $dir = __DIR__ . '/../Log/' . date('Y/m/d/');
	    $logFilePath = $dir . $filename;
	    if (self::createFile($logFilePath) && $handle = fopen($logFilePath, 'a')) {
	        fwrite($handle,  $str);
	        fclose($handle);

	        return true;
	    } else {
	    	return false;
	    }
	}

	/**
	 * Generate a random password
	 *
	 * @param $length
	 *
	 * @return string
	 */
	public static function randStr($length = '')
	{
	    $length = (int) $length;
	    if ($length < 8) {
	        $length = mt_rand(8, 32);
	    }

	    $data = array(
	        'n' => ceil($length * 0.3),
	        'l' => ceil($length * 0.4),
	        'u' => ceil($length * 0.1),
	    );
	    $o = $length - $data['n'] - $data['l'] - $data['u'];
	    if ($o) {
	        $data['o'] = $o;
	    } else {
	        $data['l'] -= 1;
	        $data['o'] = 1;
	    }

	    $str = "";
	    for ($i=0; $i<$length; $i++) {
	        foreach ($data as $key => $value) {
	            if ($value <= 0) {
	                unset($data[$key]);
	            }
	        }

	        $n = chr(mt_rand(48, 57));
	        $l = chr(mt_rand(97, 122));
	        $u = chr(mt_rand(65, 90));

	        $oArr = array(
	            mt_rand(33, 47), mt_rand(58, 64), 
	            mt_rand(92, 96), mt_rand(123, 125),
	        );
	        $o = chr($oArr[array_rand($oArr, 1)]);

	        $ke = array_rand($data, 1);

	        $str .= $$ke;
	        $data[$ke] -= 1;
	    }

	    return $str;
	}

	/**
	 * [css description]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-28
	 * @param    array                   $data [description]
	 * @return   [type]                        [description]
	 */
	public static function css($data = array())
	{
		if (!is_array($data)) {
			$data = explode(",", $data);
		}

		$str = '';
		foreach ($data as $value) {
			if (!preg_match("/\.css$/i", $value)) {
				$value .= ".css";
			}

			$str .= sprintf("<link rel=\"stylesheet\" type=\"text/css\" href=\"%s?v=1.0\" />\n", $value);
		}

	    return $str;
	}

	/**
	 * [js description]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-28
	 * @param    array                   $data [description]
	 * @return   [type]                        [description]
	 */
	public static function js($data = array())
	{
		if (!is_array($data)) {
			$data = explode(",", $data);
		}

		$str = '';
		foreach ($data as $value) {
			if (!preg_match("/\.js$/i", $value)) {
				$value .= ".js";
			}
			
			$str .= sprintf("<script type=\"text/javascript\" src=\"%s?v=1.0\" ></script>\n", $value);
		}

	    return $str;
	}

	/**
	 * Use the curl virtual browser
	 *
	 * @param array $urlInfo = array('url' => "https://www.baidu.com/", 'params' => array('key' => 'test'), 'cookie' => 'cookie')
	 * @param string $type = 'GET|POST'
	 * @param boolean $info = false|true
	 * @return string|array
	 */
	public static function curl($urlInfo, $type = "GET", $info = false)
	{
	    $type = strtoupper(trim($type));

	    if (isset($urlInfo['cookie'])) {
	        $cookie = $urlInfo['cookie'];
	        unset($urlInfo['cookie']);
	    }

	    if ($type == "POST") {
	        $url = $urlInfo['url'];
	        $data = $urlInfo['params'];
	    } else {
	        $urlArr = parse_url($urlInfo['url']);

	        if (isset($urlInfo['params'])) {
	            $params = "";
	            foreach ($urlInfo['params'] as $key => $row) {
	                if (is_array($row)) {
	                    foreach ($row as $value) {
	                        if ($params) {
	                            $params .= "&" . $key . "=" . $value;
	                        } else {
	                            $params .= $key . "=" . $value;
	                        }
	                    }
	                } else {
	                    if ($params) {
	                        $params .= "&" . $key . "=" . $row;
	                    } else {
	                        $params .= $key . "=" . $row;
	                    }
	                }
	            }
	            
	            if (isset($urlArr['query'])) {
	                if (preg_match("/&$/", $urlArr['query'])) {
	                    $urlArr['query'] .= $params;
	                } else {
	                    $urlArr['query'] .= "&" . $params;
	                }
	            } else {
	                $urlArr['query'] = $params;
	            }
	        }

	        if (isset($urlArr['host'])) {
	            if (isset($urlArr['scheme'])) {
	                $url = $urlArr['scheme'] . "://" . $urlArr['host'];
	            } else {
	                $url = $urlArr['host'];
	            }

	            if (isset($urlArr['port'])) {
	                $url .= ":" . $urlArr['port'];
	            }
	            if (isset($urlArr['path'])) {
	                $url .= $urlArr['path'];
	            }
	            if (isset($urlArr['query'])) {
	                $url .= "?" . $urlArr['query'];
	            }
	            if (isset($urlArr['fragment'])) {
	                $url .= "#" . $urlArr['fragment'];
	            }
	        } else {
	            $url = $urlInfo['url'];
	        }
	    }
	    
	    $httpHead = array(
	        "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
	        "Cache-Control:no-cache",
	        "Connection:keep-alive",
	        "Pragma:no-cache",
	        "Upgrade-Insecure-Requests:1",
	    );
	    
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    if (isset($cookie)) {
	        curl_setopt($ch, CURLOPT_COOKIE , $cookie);
	    }
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHead);
	    curl_setopt($ch, CURLOPT_ENCODING , "gzip");
	    if ($type == "POST") {
	        curl_setopt($ch, CURLOPT_POST, 1);
	        @curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    } else {
	        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	    }
	    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36");
	    @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_NOBODY, 0);
	    $result = curl_exec($ch);
	    $curlInfo = curl_getinfo($ch);
	    curl_close($ch); 
	    
	    if ($info) {
	        return $curlInfo;
	    } else {
	        return $result;
	    }
	}

	/**
	 * Use the curl multi virtual browser
	 *
	 * @param array $urlInfos = array(
	 *     array('url' => "https://www.baidu.com/", 'params' => array('key' => 'test'), 'cookie' => 'cookie', 'type' => 'GET'),
	 *     array('url' => "https://www.google.com/", 'params' => array('key' => 'test'), 'cookie' => 'cookie', 'type' => 'POST'),
	 * )
	 * @param string $type = 'GET|POST'
	 * @param boolean $info = false|true
	 * @return string|array
	 */
	public static function curlMulti($urlInfos = array()) {
	    $curlArray = $data =  array();
	    $httpHead = array(
	        "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
	        "Cache-Control:no-cache",
	        "Connection:keep-alive",
	        "Pragma:no-cache",
	        "Upgrade-Insecure-Requests:1",
	    );
	    $mh = curl_multi_init();

	    foreach($urlInfos as $key => $urlInfo) {
	        if (isset($urlInfo['type'])) {
	            $type = strtoupper(trim($urlInfo['type']));
	            unset($urlInfo['type']);
	        } else {
	            $type = 'GET';
	        }

	        if (isset($urlInfo['cookie'])) {
	            $cookie = $urlInfo['cookie'];
	            unset($urlInfo['cookie']);
	        }

	        if ($type == "POST") {
	            $url = $urlInfo['url'];
	            $data = $urlInfo['params'];
	        } else {
	            $urlArr = parse_url($urlInfo['url']);

	            if (isset($urlInfo['params'])) {
	                $params = "";
	                foreach ($urlInfo['params'] as $ke => $row) {
	                    if (is_array($row)) {
	                        foreach ($row as $value) {
	                            if ($params) {
	                                $params .= "&" . $ke . "=" . $value;
	                            } else {
	                                $params .= $ke . "=" . $value;
	                            }
	                        }
	                    } else {
	                        if ($params) {
	                            $params .= "&" . $ke . "=" . $row;
	                        } else {
	                            $params .= $ke . "=" . $row;
	                        }
	                    }
	                }

	                if (isset($urlArr['query'])) {
	                    if (preg_match("/&$/", $urlArr['query'])) {
	                        $urlArr['query'] .= $params;
	                    } else {
	                        $urlArr['query'] .= "&" . $params;
	                    }
	                } else {
	                    $urlArr['query'] = $params;
	                }
	            }

	            if (isset($urlArr['host'])) {
	                if (isset($urlArr['scheme'])) {
	                    $url = $urlArr['scheme'] . "://" . $urlArr['host'];
	                } else {
	                    $url = $urlArr['host'];
	                }

	                if (isset($urlArr['port'])) {
	                    $url .= ":" . $urlArr['port'];
	                }
	                if (isset($urlArr['path'])) {
	                    $url .= $urlArr['path'];
	                }
	                if (isset($urlArr['query'])) {
	                    $url .= "?" . $urlArr['query'];
	                }
	                if (isset($urlArr['fragment'])) {
	                    $url .= "#" . $urlArr['fragment'];
	                }
	            } else {
	                $url = $urlInfo['url'];
	            }
	        }

	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, $url);
	        if (isset($cookie)) {
	            curl_setopt($ch, CURLOPT_COOKIE , $cookie);
	        }
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHead);
	        curl_setopt($ch, CURLOPT_ENCODING , "gzip");
	        if ($type == "POST") {
	            curl_setopt($ch, CURLOPT_POST, 1);
	            @curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	        } else {
	            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	        }
	        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36");
	        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
	        curl_setopt($ch, CURLOPT_HEADER, 0);
	        curl_setopt($ch, CURLOPT_NOBODY, 0);

	        $curlArray[$key] = $ch;
	        curl_multi_add_handle($mh, $curlArray[$key]);
	    }

	    $running = 0;
	    do {
	        usleep(10000);
	        curl_multi_exec($mh, $running);
	    } while($running > 0);

	    foreach($urlInfos as $key => $urlInfo) {
	        $data[$key] = curl_multi_getcontent($curlArray[$key]);
	        curl_multi_remove_handle($mh, $curlArray[$key]);
	    }
	    curl_multi_close($mh);

	    return $data;
	}

	/**
	 * Show time
	 * @param int $time
	 * @return string
	 */
	public static function remainingTime($time = 0) {
	    $time = (int) trim($time);
	    $init = [
	        'year' => [31536000, '年'],
	        'month' => [2592000, '月'],
	        'day' => [86400, '日'],
	        'hour' => [3600, '时'],
	        'minute' => [60, '分'],
	        'second' => [1, '秒'],
	    ];

	    $str = '';
	    foreach ($init as $key => $row) {
	        $num = floor($time / $row[0]);
	        if (!$num) {
	            continue;
	        }
	        
	        $str .= $num . $row[1];
	        $time -= $num * $row[0];
	    }

	    return $str;
	}

	/**
	 * Read xml file
	 *
	 * @param string $path
	 * @return array
	 */
	public static function xml($path = "")
	{
	    if (!file_exists($path) || is_dir($path)) {
	        return false;
	    }

	    $ext = pathinfo($path, PATHINFO_EXTENSION);
	    if (strtolower(trim($ext)) !== "xml") {
	        return false;
	    }

	    $xml = simplexml_load_file($path);
	    if (is_object($xml)) {
	        $xml = json_decode(json_encode($xml), true);
	    }

	    return $xml;
	}
}