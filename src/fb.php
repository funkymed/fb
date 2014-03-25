<?php
include "facebook/src/facebook.php";

/**
 * By Cyril PEREIRA in June 2013
 * Facebook helper
 */
class fb {

  private $facebook=null;
  private $apiKey='';
  private $secretKey='';
  public  $like_status=false;
  public  $signed_request=array();

  /**
   * @param $apiKey
   * @param $secretKey
   */
  public function __construct($apiKey,$secretKey)
  {

    $this->apiKey = $apiKey;
    $this->secretKey = $secretKey;

    $this->facebook = new Facebook(array(
        'appId' => $apiKey,
        'secret' => $secretKey,
    ));

    if (!empty($_REQUEST['signed_request'])) {
      $_SESSION['signed_request'] = $this->facebook->getSignedRequest();
    }
    $this->signed_request = isset($_SESSION['signed_request']) ? $_SESSION['signed_request'] : array();
    $this->like_status = isset($this->signed_request["page"]) ? $this->signed_request["page"]["liked"] : false;
  }

  /**
   * @return Facebook|null
   */
  public function getFacebook()
  {
    return $this->facebook;
  }

  /**
   * @return bool|string
   */
  public function getUser()
  {
    $FBUser = $this->facebook->getUser();
    if(!$FBUser)
    {
      return false;
    }else{
      return $FBUser;
    }
  }

  /**
   * @param $fbuid
   * @return bool|mixed
   */
  public function getUserInfo($fbuid)
  {
    if(trim($fbuid)=='') return false;
    return $this->facebook->api('/'.$fbuid);
  }

  /**
   * @param $fbuid
   * @return bool|mixed
   */
  public function getAlbums($fbuid)
  {
    if(trim($fbuid)=='') return false;
    return $this->facebook->api('/'.$fbuid.'/albums?fields=name');
  }

  /**
   * @return bool|string
   */
  public function getLoginUrl()
  {
    if(isset($this->signed_request['page']) && isset($this->signed_request['page']['id']))
    {
      $tabId = $this->signed_request['page']['id'];
      $url= "http://www.facebook.com/pages/".$tabId."/".$tabId."?sk=app_".$this->apiKey;
      $login_url = $this->facebook->getLoginUrl(array( 'scope'=>'email,user_about_me','display'=>'page','redirect_uri' => $url,'next' => $url));
      return $login_url;
    }else{
      return false;
    }
  }
}