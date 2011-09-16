<?php
// =========================== Notice ===========================//
// Description：dianboom OAuth client class v0.2.0a          
// --------------------------------------------------------------//
// Author: Edison tsai<edison@dianboom.com/dnsing@gmail.com>
// --------------------------------------------------------------//
// Created：10:06 2010/08/19
// --------------------------------------------------------------//
// Link：http://www.dianboom.com
// --------------------------------------------------------------//
// Copyright (C) dianboom Ltd All Rights Reserved.
// ==============================================================//

require_once('OAuth.php');
class dianboomOAuth {

  #Contains the last HTTP status code returned.
  public $http_code;
  #Contains the last API call.
  public $url;
  #Set up the API root URL.
  public $host = 'http://api.dianboom.com/';
  #Set timeout default.
  public $timeout = 30;
  #Set connect timeout.
  public $connecttimeout = 30; 
  #Verify SSL Cert.
  public $ssl_verifypeer = false;
  #Respons format.
  public $format = 'json';
  #Decode returned json data.
  public $decode_json = true;
  #Contains the last HTTP headers returned.
  public $http_info;
  #For tmpFile
  public $file = null;
  #For upload boundary
  public $boundary = '';
  private static $boundary_split = '--';
  private static $CRLF = "\r\n";
  #fileInfo will get three parameters, filename,filetype,filedata
  public $fileInfo = array();
  #Set the useragnet.
  public $useragent = 'dianboomOAuth v0.2.0-alpha';
  #Immediately retry the API call if the response was not successful.
  #public $retry = TRUE;
  #Set API URLs
  private static $requestTokenURL = 'http://api.dianboom.com/oauth/request_token';
  private static $accessTokenURL  = 'http://api.dianboom.com/oauth/access_token';
  private static $authorizeURL    = 'http://api.dianboom.com/oauth/authorize';


  /**
   * Construct dianboomOAuth object
   */
  public function __construct($consumer_key, $consumer_secret, $oauth_token = NULL, $oauth_token_secret = NULL) {
    $this->sha1_method = new OAuthSignatureMethod_HMAC_SHA1();
    $this->consumer = new OAuthConsumer($consumer_key, $consumer_secret);
    if (!empty($oauth_token) && !empty($oauth_token_secret)) {
      $this->token = new OAuthConsumer($oauth_token, $oauth_token_secret);
    } else {
      $this->token = NULL;
    }
  }


  /**
   * Get a request_token from dianboom
   *
   * @return a key/value array containing oauth_token and oauth_token_secret
   */
  public function getRequestToken($oauth_callback = NULL) {
    $parameters = array();
    if (!empty($oauth_callback)) {
      $parameters['oauth_callback'] = $oauth_callback;
    } 
    $request = $this->oAuthRequest(self::$requestTokenURL, 'GET', $parameters);
    $token = OAuthUtil::parse_parameters($request);
    $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
    return $token;
  }

  /**
   * Get the authorize URL
   *
   * @return a string
   */
  public function getAuthorizeURL($token) {
    if(is_array($token)) {
        $token = $token['oauth_token'];
    }
       return self::$authorizeURL.'?oauth_token='.$token;
  }

  /**
   * Exchange request token and secret for an access token and
   * secret, to sign API calls.
   *
   * @return array
   */
  public function getAccessToken($oauth_verifier = FALSE) {
    $parameters = array();
    if (!empty($oauth_verifier)) {
      $parameters['oauth_verifier'] = $oauth_verifier;
    }
    $request = $this->oAuthRequest(self::$accessTokenURL, 'GET', $parameters);
    $token = OAuthUtil::parse_parameters($request);
    $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
    return $token;
  }

  /**
   * One time exchange of username and password for access token and secret.
   *
   * @return array
   */  
  public function getXAuthToken($username, $password) {
    $parameters = array();
    $parameters['x_auth_username'] = $username;
    $parameters['x_auth_password'] = $password;
    $parameters['x_auth_mode'] = 'client_auth';
    $request = $this->oAuthRequest(self::$accessTokenURL, 'POST', $parameters);
    $token = OAuthUtil::parse_parameters($request);
    $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
    return $token;
  }

  /**
   * GET wrapper for oAuthRequest.
   */
  public function GET($url, $parameters = array()) {
    $response = $this->oAuthRequest($url, 'GET', $parameters);
    if ($this->format === 'json' && $this->decode_json) {
      return json_decode($response);
    }
    return $response;
  }
  
  /**
   * POST wrapper for oAuthRequest.
   */
  public function POST($url, $parameters = array()) {
    $response = $this->oAuthRequest($url, 'POST', $parameters);
    if ($this->format === 'json' && $this->decode_json) {
      return json_decode($response);
    }
    return $response;
  }

  /**
   * PUT wrapper for oAuthRequest.
   */
  public function PUT($url, $parameters = array()){
    $response = $this->oAuthRequest($url, 'PUT', $parameters);
    if ($this->format === 'json' && $this->decode_json) {
      return json_decode($response);
    }
    return $response;
  }

  /**
   * DELETE wrapper for oAuthReqeust.
   */
  public function DELETE($url, $parameters = array()) {
    $response = $this->oAuthRequest($url, 'DELETE', $parameters);
    if ($this->format === 'json' && $this->decode_json) {
      return json_decode($response);
    }
    return $response;
  }
 
  /**
   * UPFILE wrapper for oAuthReqeust.
   */
  public function UPFILE($url, $parameters = array(), $fileInfo = array()){
	$this->fileInfo = $fileInfo;
    $response = $this->oAuthRequest($url, 'UPFILE', $parameters);
    if ($this->format === 'json' && $this->decode_json) {
      return json_decode($response);
    }
    return $response;
  }


  /**
   * Format and sign an OAuth / API request
   */
  public function oAuthRequest($url, $method, $parameters) {
    if (strrpos($url, 'https://') !== 0 && strrpos($url, 'http://') !== 0) {
      $url = "{$this->host}{$url}.{$this->format}";
    }
	#Modified by Edison tsai on 13:01 2010/09/17 for avoid the informal method 'UPFILE'
	$method2 = $method=='UPFILE' ? 'POST' : $method;
    $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method2, $url, $parameters);
	#$method=='UPFILE' && file_put_contents('testOAuthRequest001.txt',$method.'---'.print_r($parameters,true)."\n",FILE_APPEND);
    $request->sign_request($this->sha1_method, $this->consumer, $this->token);
    switch ($method) {
    case 'GET':
      return $this->http($request->to_url(), 'GET');
    default:
      return $this->http($request->get_normalized_http_url(), $method, $request->to_postdata());
    }
  }

  /**
   * Make an HTTP request
   *
   * @return API results
   */
  public function http($url, $method, $postfields = NULL) {
    $this->http_info = array();
    $ci = curl_init();
    /* Curl settings */
    curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
    curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
    curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
    curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
    curl_setopt($ci, CURLOPT_HEADER, FALSE);

    switch ($method) {
      case 'POST':
        curl_setopt($ci, CURLOPT_POST, TRUE);
        if (!empty($postfields)) {
          curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
        }
        break;
      case 'DELETE':
        curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
        if (!empty($postfields)) {
          $url = "{$url}?{$postfields}";
        }
		break;
	  case 'PUT': #Added by Edison tsai on 15:16 2010/08/27 for PUT method
	    curl_setopt($ci, CURLOPT_PUT, TRUE);
		if (!empty($postfields)) {
		   $this->file = tmpFile();
		   fwrite($this->file, $postfields);
		   rewind($this->file);
          curl_setopt($ci, CURLOPT_INFILE,$this->file);
          curl_setopt($ci, CURLOPT_INFILESIZE,strlen($postfields));
		  //curl_setopt($ci, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		}
		break;
	  case 'UPFILE': #Added by Edison tsai on 18:23 2010/09/15 for upload file method
        curl_setopt($ci, CURLOPT_POST, TRUE);
        if (!empty($postfields) && is_array($this->fileInfo) && count($this->fileInfo)>=3) {
		    $postArray = OAuthUtil::parse_parameters($postfields);

             if(is_array($postArray) && count($postArray)>0){
			   
			   $this->getBoundary();

               $multiPart  = $this->getMidBoundary().self::$CRLF;

			   $multiPart .= 'Content-Disposition: form-data; name="productImage";filename="'.$this->fileInfo['filename'].'"'.self::$CRLF.'Content-Type: '.$this->fileInfo['filetype'].self::$CRLF.self::$CRLF.$this->fileInfo['filedata'].self::$CRLF;

		       foreach($postArray AS $pk=>$pa){
		         $multiPart .= $this->getMidBoundary().self::$CRLF.'Content-Disposition: form-data;name="'.$pk.'"'.self::$CRLF.self::$CRLF.$pa.self::$CRLF;
		      } #end foreach

			     //$multiPart .= self::$CRLF.$this->getEndBoundary();
               
			 } #end if

          curl_setopt($ci, CURLOPT_POSTFIELDS, $multiPart);
        }
		curl_setopt($ci, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data; boundary='.$this->boundary));
	
		$this->fileInfo = array();
        break;
    }

    curl_setopt($ci, CURLOPT_URL, $url);
    $response = curl_exec($ci);
    $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
    $this->http_info = array_merge($this->http_info, curl_getinfo($ci));
    $this->url = $url;
    curl_close ($ci);
	is_object($this->file) && fclose($this->file);
    return $response;
  }

  /**
   * Get some debug info
   */
  public function getLastStatusCode() { return $this->http_status;}
  public function getLastAPICall()    { return $this->last_api_call;}

  /**
   * Get the header info to store.
   */
  public function getHeader($ch, $header) {
    $i = strpos($header, ':');
    if (!empty($i)) {
      $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
      $value = trim(substr($header, $i + 2));
      $this->http_header[$key] = $value;
    }
    return strlen($header);
  }
  
  /**
   * Generate boundary
   * @return string 
   */
  public function getBoundary(){
     $this->boundary = uniqid('------------------');
	 return $this->boundary;
  }

  /**
   * Generate middle boundary
   * @return string 
   */
  public function getMidBoundary(){
	 return self::$boundary_split.$this->boundary;
  }

  /**
   * Generate end boundary
   * @return string 
   */
  public function getEndBoundary(){
	 return $this->boundary.self::$boundary_split;
  }

}
?>