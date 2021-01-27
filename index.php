<?php
include_once('app/Database.php');
include_once('app/App.php');
include_once('app/Auth.php');
include_once('app/User.php');
include_once('app/City.php');
include_once('app/Ceac.php');
include_once('app/Dqa.php');
$app = new \app\App();
$authen = new \app\Auth();
$users = new \app\User();
$city = new \app\City();
$ceac = new \app\Ceac();
$dqa = new \app\Dqa();
session_start();
require 'vendor/autoload.php';

if(!$_SESSION['mrms_auth']){
    $provider = new \Stevenmaguire\OAuth2\Client\Provider\Keycloak([
        'authServerUrl' => 'http://auth.caraga.dswd.gov.ph:8080/auth',
        'realm' => 'entdswd.local',
        'clientId' => 'kalahi-apps',
        'clientSecret' => '07788f27-8e6a-4729-a033-0eb5cb7c7389',
        'redirectUri' => 'http://crg-kcapps-svr/mrms/index.php'
    ]);

    if (!isset($_GET['code'])) {
        // If we don't have an authorization code then get one
        $authUrl = $provider->getAuthorizationUrl();
        $_SESSION['oauth2state'] = $provider->getState();

        header('Location: '.$authUrl);
        exit;
// Check given state against previously stored one to mitigate CSRF attack
    } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
        echo 'session: '.$_SESSION['oauth2state'];
        unset($_SESSION['oauth2state']);
        exit('Invalid state, make sure HTTP sessions are enabled.');
        die();
    } else {
        // Try to get an access token (using the authorization coe grant)
        try {
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);
        } catch (Exception $e) {
            exit('Failed to get access token: ' . $e->getMessage());
        }

        // Optional: Now you have a token you can look up a users profile data
        try {

            // We got an access token, let's now get the user's details
            $user_sso = $provider->getResourceOwner($token);
            /*echo 'ok siya<br>';
            var_dump($user_sso);*/

            if ($users->sso_isExist($user_sso)) {
                echo 'wow';
                /*$user_sso = $user_sso->toArray();
                $oauth = $user_sso['sub'];
                $_SESSION['mrms_auth'] = $oauth;
                $app->login_sso($user_sso['preferred_username']);*/
            } else {
                /*$user->register_sso($user_sso);
                $user_sso = $user_sso->toArray();
                $oauth = $user_sso['sub'];
                $_SESSION['mrms_auth'] = $oauth;
                $app->login_sso($user_sso['preferred_username']);*/
            }

            //echo $_SESSION['mrms_auth'];

        } catch (Exception $e) {
            exit('Failed to get resource owner: ' . $e->getMessage());
        }
        // Use this to interact with an API on the users behalf
    }
}

?>
