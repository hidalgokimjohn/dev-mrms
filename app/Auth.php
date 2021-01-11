<?php

	namespace app;

	class Auth
	{

		public function connectDatabase()
		{
			$database = Database::getInstance();
			return $database->getConnection();
		}

		public function __construct()
		{
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}
		}

		public function loggedIn()
		{

			if (isset($_SESSION['login']) && $_SESSION['login'] == 'logged_in') {
				return true;
			} else {
				return false;
			}
		}

		public function redirectTo($directory)
		{
			header('location: ' . $directory);

		}

		public function maintenance()
		{
			$mysql = $this->connectDatabase();
			$q = "SELECT
				app_setting.setting,
				app_setting.`function`
				FROM
				app_setting
				WHERE `function`='maintenance' AND `setting`='on'";
			$result = $mysql->query($q) or die($mysql->error);
			if ($result->num_rows > 0) {
				$this->redirectTo('../login/maintenance.php');
			}

		}
		public function maintenance_off()
		{
			$mysql = $this->connectDatabase();
			$q = "SELECT
				app_setting.setting,
				app_setting.`function`
				FROM
				app_setting
				WHERE `function`='maintenance' AND `setting`='off'";
			$result = $mysql->query($q) or die($mysql->error);
			if ($result->num_rows > 0) {
				$this->redirectTo('index.php');
			}
		}

		public function gate()
		{
			if (isset($_SESSION['login']) && $_SESSION['login'] == 'logged_in') {
				return true;
			} else {
				$this->redirectTo('/login/logout.php');
				return false;
			}
		}
	}