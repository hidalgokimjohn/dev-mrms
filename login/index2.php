<?php
session_start();
	include_once('../app/Database.php');
	include_once('../app/App.php');
	include_once('../app/Auth.php');
	include_once('../app/User.php');
	$auth = new app\Auth();
	if ($auth->loggedIn()) {
        $auth->redirectTo('../index.php');
	}
	$app = new \app\App();
	$user = new \app\User();
	//$auth->maintenance();

//	require_once ('../../vendor/stevenmaguire/oauth2-keycloak/src/Provider/Keycloak.php');
    require '../vendor/autoload.php';

    $provider = new \Stevenmaguire\OAuth2\Client\Provider\Keycloak([
        'authServerUrl'         => 'http://auth.caraga.dswd.gov.ph:8080/auth',
        'realm'                 => 'entdswd.local',
        'clientId'              => 'kalahi-apps',
        'clientSecret'          => '07788f27-8e6a-4729-a033-0eb5cb7c7389',
        'redirectUri'           => 'http://crg-kcapps-svr/dev-mrms/login/index2.php',
        'encryptionAlgorithm'   => 'RS256', // optional
    ]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state, make sure HTTP sessions are enabled.');

} else {

    // Try to get an access token (using the authorization coe grant)
    try {
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);
    } catch (Exception $e) {
        exit('Failed to get access token: '.$e->getMessage());
    }

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $user_sso = $provider->getResourceOwner($token);

        if($user->sso_isExist($user_sso)){
            header("location: ../Nccdp/dashboard.php");
        }else{
            $user->register_sso($user_sso);
        }

        //1. check nya ang naka session database

        //2. pag walay user unya oauth wala nag exist, e create nya

        //3. go to urlshit

        // Use these details to create a new profile
        printf('Hello %s!', $user->getName());

    } catch (Exception $e) {
        exit('Failed to get resource owner: '.$e->getMessage());
    }

    // Use this to interact with an API on the users behalf
    echo $token->getToken();
}