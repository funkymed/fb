<?php


include "fb.php";

$configFB = array(
    'apiKey'=>'azeazeaze',
    'secretKey'=>'xxxxxxxx',
);

$uri = 'current_url';

$fb             = new fb($configFB['apiKey'],$configFB['secretKey']);
$like_status    = $fb->like_status;
$signed_request = $fb->signed_request;

//Si on aime pas la page
if(!$like_status)
{
  include 'templates/forcelike.html';
}else{

  $fbuid=isset($signed_request['user_id']) ? $signed_request['user_id'] : null;

  //Si pas de compte FB alors on va lui demander de se connecter
  if(!$fbuid)
  {
    if(isset($signed_request['page']) && isset($signed_request['page']['id']))
    {
      $tabId = $signed_request['page']['id'];
      $url= "http://www.facebook.com/pages/".$tabId."/".$tabId."?sk=app_".$configFB['apiKey'];
    }else{
      $url = "url_courrante";
    }
    $login_url = $fb->getLoginUrl(array( 'req_perms'=>'publish_stream','scope'=>'email,user_about_me','redirect_uri' => $url,'next' => $url));
    echo '<script type="text/javascript">top.location.href = "'.$login_url.'";</script>';
    exit;
  //Si compte FB Alors on recupe le token
  }else{
    $accessToken = $signed_request['oauth_token'];
    $fb->getFacebook()->setAccessToken($accessToken);
  }

  $fbuid = !empty($fbuid) ? $fbuid : null;

  include 'templates/welcome.html';
}



