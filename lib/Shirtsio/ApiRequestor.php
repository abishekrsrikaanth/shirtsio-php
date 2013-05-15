<?php

class Shirtsio_ApiRequestor
{
    public function __construct($apiKey = null)
    {
        $this->_apiKey = $apiKey;
    }

    public function get_headers()
    {
        $langVersion = phpversion();
        $uname = php_uname();
        $ua = array('bindings_version' => Shirtsio::VERSION,
            'lang' => 'php',
            'lang_version' => $langVersion,
	            'publisher' => 'shirts.io',
            'uname' => $uname);
        $headers = array('X-Shirtsio-Client-User-Agent: ' . json_encode($ua),
            'User-Agent: Shirtsio/v1 PhpBindings/' . Shirtsio::VERSION,
            'Authorization: Bearer ' . $this->_apiKey);
        if (Shirtsio::$apiVersion)
            $headers[] = 'Shirts.io-Version: ' . Shirtsio::$apiVersion;

        return $headers;
    }

    public static function apiUrl($url = '')
    {
        $apiBase = Shirtsio::$apiBase;
        return "$apiBase$url";
    }

    public function handleApiError($rbody, $rcode, $resp)
    {
        if (!is_array($resp) || !isset($resp['error']))
            throw new Shirtsio_ApiError("Invalid response object from API: $rbody (HTTP response code was $rcode)", $rcode, $rbody, $resp);
        $error = $resp['error'];
        switch ($rcode) {
            case 400:
                throw new Shirtsio_InvalidRequestError($error, $rcode, $rbody, $resp);
            case 404:
                throw new Shirtsio_InvalidRequestError($error, $rcode, $rbody, $resp);
            case 401:
                throw new Shirtsio_AuthenticationError($error, $rcode, $rbody, $resp);
            default:
                throw new Shirtsio_ApiError($error, $rcode, $rbody, $resp);
        }
    }

    public static function utf8($value)
    {
        if (is_string($value) && mb_detect_encoding($value, "UTF-8", TRUE) != "UTF-8")
            return utf8_encode($value);
        else
            return $value;
    }

    private static function _encodeObjects($d)
    {
        if ($d === true) {
            return 'true';
        } else if ($d === false) {
            return 'false';
        } else if (is_array($d)) {
            $res = array();
            foreach ($d as $k => $v)
                $res[$k] = self::_encodeObjects($v);
            return $res;
        } else {
            return self::utf8($d);
        }
    }

    public static function encode($arr, $prefix = null)
    {
        if (!is_array($arr))
            return $arr;

        $r = array();
        foreach ($arr as $k => $v) {
            if (is_null($v))
                continue;

            if ($prefix && $k && !is_int($k))
                $k = $prefix . "[" . $k . "]";
            else if ($prefix)
                $k = $prefix . "[]";

            if (is_array($v)) {
                $r[] = self::encode($v, $k, true);
            } else {
                $r[] = urlencode($k) . "=" . urlencode($v);
            }
        }

        return implode("&", $r);
    }

    public function request($url, $params, $meth)
    {
    	
        $headers = $this->get_headers();
        if (!$params)
            $params = array();
        $params = self::_encodeObjects($params);
        list($rbody, $rcode) = $this->_curlRequest($meth, $url, $headers, $params);   
        $resp = $this->_interpretResponse($rbody, $rcode);
        return $resp;
    }

    private function _interpretResponse($rbody, $rcode)
    {
        try {
            $resp = json_decode($rbody, true);
        } catch (Exception $e) {
            throw new Shirtsio_ApiError("Invalid response body from API: $rbody (HTTP response code was $rcode)", $rcode, $rbody);
        }

        if ($rcode < 200 || $rcode >= 300) {
            $this->handleApiError($rbody, $rcode, $resp);
        }
        return $resp['result'];
    }

    private function _curlRequest($meth, $absUrl, $headers, $params)
    {
        $curl = curl_init();
        $meth = strtolower($meth); 
        $opts = array();
        if ($meth == 'get') {
            $opts[CURLOPT_HTTPGET] = 1;
            if (count($params) > 0) {
                $encoded = self::encode($params);
                $absUrl = "$absUrl?$encoded";
            #https://www.shirts.io/api/v1/internal/integration/auth/?username=damon.kong&password=kcchy4205                
            }
        } else if ($meth == 'post') {
            $opts[CURLOPT_POST] = 1;
//          $opts[CURLOPT_POSTFIELDS] = array_merge($params,$files);
            $opts[CURLOPT_POSTFIELDS] = $params;         
            
        } else if ($meth == 'delete') {
            $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
            if (count($params) > 0) {
                $encoded = self::encode($params);
                $absUrl = "$absUrl?$encoded";
            }
        } else {
            throw new Shirtsio_ApiError("Unrecognized method $meth", $params);
        }
        $absUrl = self::utf8($absUrl);
        $opts[CURLOPT_URL] = $absUrl;
        $opts[CURLOPT_RETURNTRANSFER] = true;
        $opts[CURLOPT_CONNECTTIMEOUT] = 30;
        $opts[CURLOPT_TIMEOUT] = 80;
        $opts[CURLOPT_RETURNTRANSFER] = true;
        $opts[CURLOPT_HTTPHEADER] = $headers;
        #ignored SSL
        $opts[CURLOPT_SSL_VERIFYPEER] = false;
        #Proxy setting
        $opts[CURLOPT_PROXY] ='172.17.15.15:8080';
        curl_setopt_array($curl, $opts);
        $rbody = curl_exec($curl);
        $errno = curl_errno($curl);
        if ($errno == CURLE_SSL_CACERT ||
            $errno == CURLE_SSL_PEER_CERTIFICATE
        ) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $rbody = curl_exec($curl);
        }

        if ($rbody === false) {
            $errno = curl_errno($curl);
            $message = curl_error($curl);
            curl_close($curl);
            $this->handleCurlError($errno, $message);
        }

        $rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return array($rbody, $rcode);
    }

    public function handleCurlError($errno, $message)
    {
        $apiBase = Shirtsio::$apiBase;
        switch ($errno) {
            case CURLE_COULDNT_CONNECT:
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_OPERATION_TIMEOUTED:
                $msg = "Could not connect to Shirts.io ($apiBase).  Please check your internet connection and try again.  If this problem persists, please let us know at support@shirts.io.";
                break;
            default:
                $msg = "Unexpected error communicating with Shirts.io.  If this problem persists, let us know at support@shirts.io.";
        }

        $msg .= "\n\n(Network error [errno $errno]: $message)";
        throw new Shirtsio_ApiConnectionError($msg);
    }
}
