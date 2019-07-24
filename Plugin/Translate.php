<?php

namespace Plugin;

class Translate
{
	public static $langArr = array(
	    "sq", "ar", "am", "az", "ga", "et", "eu", "be", "bg", "is", "pl", "bs", "fa", "af", "da", "de", "ru", "fr", "tl", "fi", 
	    "fy", "km", "ka", "gu", "kk", "ht", "ko", "ha", "nl", "ky", "gl", "ca", "cs", "kn", "co", "hr", "ku", "la", "lv", "lo", 
	    "lt", "lb", "ro", "mg", "mt", "mr", "ml", "ms", "mk", "mi", "mn", "bn", "my", "hmn", "xh", "zu", "ne", "no", "pa", "pt", 
	    "ps", "ny", "ja", "sv", "sm", "sr", "st", "si", "eo", "sk", "sl", "sw", "gd", "ceb", "so", "tg", "te", "ta", "th", "tr", 
	    "cy", "ur", "uk", "uz", "es", "iw", "el", "haw", "sd", "hu", "sn", "hy", "ig", "it", "yi", "hi", "su", "id", "jw", "en", 
	    "yo", "vi", "zh-TW", "zh-CN", 
	);

	public static function api($tranInfo = array('tl' => 'en', 'text' => array('Hello World')), $status = false)
	{
		if (!is_array($tranInfo)) {
			$tranInfo = array('tl' => 'en', 'text' => array($tranInfo));
		} elseif (!isset($tranInfo['tl']) || !in_array($tranInfo['tl'], self::$langArr)) {
			$tranInfo['tl'] = 'en';
		}

		if (!isset($tranInfo['text'])) {
		    return false;
		}

		$text = (array) $tranInfo['text'];
		$tkk = self::TKK();
		$urlInfo = [];
		foreach ($text as $key => $value) {
		    $tk = self::tk($value, $tkk);

		    $urlInfo[$key] = array(
		        'url' => "https://translate.google.cn/translate_a/single",
		        'params' => array(
		            'client' => "t",
		            'sl' => "auto",
		            'tl' => $tranInfo['tl'],
		            'dt' => array(
		                "at", "bd", "ex", "ld", "md",
		                "qca", "rw", "rm", "ss", "t",
		            ),
		            'tk' => $tk,
		            'q' => urlencode($value),
		        ),
		    );
		}

		if (count($text) == 1) {
		    $html = self::curl(reset($urlInfo));
		    $data = json_decode($html);
		} else {
		    $html = self::curlMulti($urlInfo);
		    $data = array_map("json_decode", $html);
		}

		if ($status) {
		    return $data;
		} else {
		    if (count($text) == 1) {
		        $str = "";
		        if (isset($data[0])) {
		            foreach ($data[0] as $row) {
		                $str .= $row[0];
		            }
		        }
		        
		        return $str;
		    } else {
		        foreach ($data as $key => $row) {
		            $str = "";
		            if (isset($row[0])) {
		                foreach ($row[0] as $r) {
		                    $str .= $r[0];
		                }
		            }

		            $data[$key] = $str;
		        }

		        return $data;
		    }
		}
	}

	/**
	 * Get TKK
	 *
	 * @return string
	 */
	private static function TKK() {
	    $preg = array(
	        'tkk' => "/tkk:'(.*?)'/i",
	    );
	    $html = self::curl(array('url' => "https://translate.google.cn"));
	    preg_match($preg['tkk'], $html, $arr);

	    return $arr[1];
	}

	/**
	 * 获取 Google 翻译 tk 值
	 *
	 * @param string $a (要翻译的内容)
	 * @param string $b
	 * @return string
	 */
	private static function tk($a = "", $TKK = "") {
	    $e = explode(".", $TKK);
	    if (isset($e[0])) {
	        $h = floatval($e[0]);
	    } else {
	        $h = 0;
	    }
	    $g = array();
	    $d = 0;
	    for ($f = 0; $f < mb_strlen($a, "UTF-8"); $f++) {
	        $c = self::unicodeEncode(mb_substr($a, $f, 1, "UTF-8"));
	        if (128 > $c) {
	            $g[$d++] = $c;
	        } else {
	            if (2048 > $c) {
	                $g[$d++] = $c >> 6 | 192;
	            } else {
	                if (55296 == ($c & 64512) 
	                    && $f + 1 < mb_strlen($a, "UTF-8") 
	                    && 56320 == (unicodeEncode(mb_substr($a, $f + 1, 1, "UTF-8")) & 64512)) {
	                    $c = 65536 + (($c & 1023) << 10) + (unicodeEncode(mb_substr($a, ++$f, 1, "UTF-8")) & 1023);
	                    $g[$d++] = $c >> 18 | 240;
	                    $g[$d++] = $c >> 12 & 63 | 128;
	                } else {
	                    $g[$d++] = $c >> 12 | 224;
	                }

	                $g[$d++] = $c >> 6 & 63 | 128;
	            }

	            $g[$d++] = $c & 63 | 128;
	        }
	    }

	    $a = $h;
	    for ($d = 0; $d < count($g); $d++) {
	        $a += $g[$d];
	        $a = self::b($a, "+-a^+6");
	    }

	    $a = self::b($a, "+-3^+b+-f");
	    if (isset($e[1])) {
	        $a = floatval($a) ^ floatval($e[1]);
	    } else {
	        $a ^= 0;
	    }
	    if (0 > $a) {
	        $a = ($a & 2147483647) + 2147483648;
	    }
	    $a = fmod(floatval($a), 1E6);


	    return (string) $a . "." . ($a ^ $h);
	}

	/**
	 * 汉字 UNICODE编码
	 *
	 * @param string $name
	 * @return int
	 */
	private static function unicodeEncode($name = "")  
	{
	    if (ord($name) > 127) {
	        $name = iconv('UTF-8', 'UCS-2', $name);
	        $str = '';  
	        for($i = 0; $i < strlen($name) - 1; $i += 2) {
	            $c = $name[$i];
	            $c2 = $name[$i + 1];
	            if (ord($c) > 0) {
	                $str .= str_pad(base_convert(ord($c), 10, 16), 2, 0, STR_PAD_LEFT) . str_pad(base_convert(ord($c2), 10, 16), 2, 0, STR_PAD_LEFT);
	            } else {  
	                $str .= $c2;  
	            }  
	        }

	        $str = hexdec($str);
	    } else {
	        $str = ord($name);
	    }

	    return $str;  
	}

	/**
	 * 服务于 Google 翻译 tk 值
	 *
	 * @param int $a
	 * @param string $b
	 * @return int
	 */
	private static function b($a = "", $b = "") {
	    for ($d = 0; $d < mb_strlen($b, "UTF-8") - 2; $d += 3) {
	        $c = mb_substr($b, $d + 2, 1);
	        if ("a" <= $c) {
	            $c = ord(mb_substr($c, 0, 1, "UTF-8")) - 87;
	        } else {
	            $c = (int) $c;
	        }

	        if ("+" == mb_substr($b, $d + 1, 1, "UTF-8")) {
	            $c = self::zeroFill($a, $c);
	        } else {
	            $c = $a << $c;
	        }

	        if ("+" == mb_substr($b, $d, 1, "UTF-8")) {
	            $a = $a + $c & 4294967295;
	        } else {
	            $a = $a ^ $c;
	        }
	    }
	        
	    return $a;
	}

	/**
	 * PHP >>> ( 100 >>> 2 => 25)
	 *
	 * @param int $a
	 * @param int $b
	 * @return int
	 */
	private static function zeroFill($a = "", $b = "") 
	{ 
	    $z = hexdec(80000000); 
	    if ($z & $a) { 
	        $a = $a >> 1; 
	        $a &= ~$z; 
	        $a |= 0x40000000; 
	        $a = $a >> ($b - 1); 
	    } else { 
	        $a = $a >> $b; 
	    } 
	    return $a; 
	}

	/**
	 * Use the curl virtual browser
	 *
	 * @param array $urlInfo = array('url' => "https://www.baidu.com/", 'params' => array('key' => 'test'), 'cookie' => 'cookie')
	 * @param string $type = 'GET|POST'
	 * @param boolean $info = false|true
	 * @return string|array
	 */
	private static function curl($urlInfo, $type = "GET", $info = false)
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
	private static function curlMulti($urlInfos = array()) {
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
}