<?php

class RingCentral{

    const RC_SERVER_PRODUCTION = 'https://platform.ringcentral.com';
    const RC_SERVER_SANDBOX    = 'https://platform.devtest.ringcentral.com/restapi/oauth/token';
    const RC_SERVER_URL_PART   = '/restapi/v1.0';

    protected $appKey          = '';
    protected $appSecret       = '';
    protected $serverUrl       = '';
    protected $username        = '';
    protected $extension       = '';
    protected $accessToken     = '';
    protected $refreshToken    = '';
    protected $authData        = array();

    function __construct($appKey='', $appSecret='', $serverUrl = self::RC_SERVER_PRODUCTION) {
        $this->appKey    = $appKey;
        $this->appSecret = $appSecret;

        if (strtolower($serverUrl)=='production') {
            $this->serverUrl = self::RC_SERVER_PRODUCTION;
        } else if (strtolower($serverUrl)=='sandbox') {
            $this->serverUrl = self::RC_SERVER_SANDBOX;
        } else {
            $this->serverUrl = $serverUrl;
        }
    }
    
    function __destruct() {      
        
    }

    public function authorize($username='', $extension='', $password='') {
        $this->username  = $username;
        $this->extension = $extension;
        $requestData = array(
            'grant_type' => 'password',
            'username'   => $username,
            'extension'  => $extension,
            'password'   => $password
        );
        return $this->authCall($requestData);
    }

    public function refreshToken() {
        $requestData = array(
            'grant_type'    => 'refresh_token',
            'refresh_token' => $this->refreshToken
        );
        return $this->authCall($requestData);
    }

    protected function authCall($requestData = array()) {
        
        
        $ch = curl_init('https://platform.ringcentral.com/restapi/oauth/token');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Basic ' . $this->getApiKey(),
            'Content-Type: ' . 'application/x-www-form-urlencoded',
            'Accept: application/json',
         ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseBody = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $responseData = json_decode($responseBody,true);        
        $this->authData = $responseData;
        $this->inflateAuthData($responseData);
        return $responseData;
    }

    protected function inflateAuthData($authData = array()) {
        if (array_key_exists('access_token', $authData)) {
            $this->accessToken = $this->authData['access_token'];
        }
        if (array_key_exists('refresh_token', $authData)) {
            $this->refreshToken = $this->authData['refresh_token'];
        }
    }

    protected function getApiKey() {
        $apiKey = base64_encode($this->appKey . ':' . $this->appSecret);
        $apiKey = preg_replace('/[\s\t\r\n]/','',$apiKey);
        return $apiKey;
    }

    protected function tokenUrl() {
        $url = $this->serverUrl . '/restapi/oauth/token';
        return $url;
    }

    protected function prepareBody($contentType,$body) {
        if ($contentType == 'application/json') {
            if (is_array($body)) {
                $body = json_encode($body);
            }
        } elseif ($contentType =='application/x-www-form-urlencoded') {
            if (is_array($body)) {
                $body = http_build_query($body);
            }
        }
        return $body;
    }

    public function get($url, $params) {
        return $this->apiCall('GET', $url, $params, 0);
    }

    public function post($url, $params,$token) {
        return $this->apiCall('POST', $url, $params, 0,$token);
    }

    public function put($url, $params) {
        return $this->apiCall('PUT', $url, $params, 0);
    }

    public function delete($url, $params) {
        return $this->apiCall('DELETE', $url, $params, 0);
    }

    protected function apiCall($verb='', $url, $params, $try=0,$token){
        
        $ch = curl_init($this->inflateUrl($url));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: ' . $this->getContentTypeForParams($params),
            'Accept: application/json',
            'Authorization: Bearer ' .$token,
                
         ));
         if (strtoupper($verb)=='POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getBodyForParams($params));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseBody = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($try==0 && preg_match('/^4[0-9][0-9]$/', $httpCode)	) {
            $this->refreshToken();
            return $this->apiCall($verb, $url, $params, $try+1);
        }

        $responseData = json_decode($responseBody, true);        
        return $responseData;
    }

    protected function getContentTypeForParams($params) {
        if (array_key_exists('json', $params)) {
            return 'application/json';
        }
        return '';
    }

    protected function getBodyForParams($params) {
        if (array_key_exists('json', $params)) {
            return $this->prepareBody('application/json', $params['json']);
        }
        return '';
    }

    protected function inflateUrl($urlIn = '') {
        $urlOut = '';
        if (strlen($urlIn)==0) {
            $urlOut = $urlIn;
        } elseif (preg_match('/^https?:\/\//',$urlIn)) {
            $urlOut = $urlIn;
        } elseif (preg_match('/^(\/)?restapi/',$urlIn, $m)) {
            if (length($m)>0 && $m[0] == '/') {
                $urlOut = $this->serverUrl . $urlIn;
            } else {
                $urlOut = $this->serverUrl . '/' . $urlIn;
            }
        } else {
            if (preg_match('/^\//',$urlIn)) {
                $urlOut = $this->serverUrl . self::RC_SERVER_URL_PART . $urlIn;
            } else {
                $urlOut = $this->serverUrl . self::RC_SERVER_URL_PART . '/' . $urlIn;
            }
        }
        return $urlOut;
    }
}