<?php
include_once('../app/Database.php');
include_once('../app/App.php');
include_once('../app/User.php');
$app = new \app\App();
$user = new \app\User();

require '../vendor/autoload.php';


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

    //var_dump($authUrl);
    header('Location: ' . $authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    echo $_SESSION['oauth2state'] . " get oauth<br>";
    echo $_SESSION['state'] . " get state: <br>";
    unset($_SESSION['oauth2state']);

    exit('Invalid state, make sure HTTP sessions are enabled.');

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

        //var_dump($user->sso_isExist($user_sso));
        //die();

        if ($user->sso_isExist($user_sso)) {
            $user_sso = $user_sso->toArray();
            $oauth = $user_sso['sub'];
            $_SESSION['mrms_auth'] = $oauth;
            //IPASA SA LOGIN NGA FUNCTION FOR  SESSION
        } else {
            $user->register_sso($user_sso);
            $user_sso = $user_sso->toArray();
            $oauth = $user_sso['sub'];
            $_SESSION['mrms_auth'] = $oauth;
        }


            var_dump($_SESSION['mrms_auth']);
           die();
    } catch (Exception $e) {
        exit('Failed to get resource owner: ' . $e->getMessage());
    }

    // Use this to interact with an API on the users behalf

}