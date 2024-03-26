<?php
	class Site extends Connection
	{
		function __construct( $dbinfo )
		{
			parent::__construct( $dbinfo );
		}
		public function checkLoginFields( $login, $pass, $masterAccountConfig, $dbconnacc )
		{
			if( $masterAccountConfig !== FALSE )
			{
				$info = $this->select( 0, "web_user", array( "uid" ), array( "Login" => $login, "Password" => $pass ) );
				if( !empty( $info ) )
				{
					return 0;
				}
				return -1;
			}
			else
			{
				$passQuery = "SELECT dbo.fn_Md5_Encrypt(RTRIM('".$pass."'))";
				$passFinal = $dbconnacc->select( 4, $passQuery );
				$info = $dbconnacc->select( 0, "tbl_account", array( "account_code" ), array( "account_gid" => $login, "account_pw" => $passFinal ) );
				if( !empty( $info ) )
				{
					return 0;
				}
				return -1;
			}
		}
		public function doLogin( $masterAccountConfig, $dbconnacc )
		{
			$title = "";
			$message = "";
			$icon = "";
			$loginPostArray = array( "login","pass" );
			$confirmPostArray = $this->isSetPostArray( $loginPostArray );
			if( $confirmPostArray == 0 )
			{
				$login = $this->getPostFromArray( $loginPostArray, 0 );
				$pass  = $this->getPostFromArray( $loginPostArray, 1 );
				$iOK   = $this->checkLoginFields( $login, $pass, $masterAccountConfig, $dbconnacc );
				if( $iOK == 0 )
				{
					$_SESSION[ "TricksterWebLogin" ] = $login;
					$title = "Success!";
					$message = "You logged in successfully!";
					$icon = "success";
				}
				else
				{
					$title = "Error!";
					$message = "The informed login credentials are incorrect!<br/>Please try again.";
					$icon = "error";
				}
			}
			if( !empty( $title ) && !empty( $message ) && !empty( $icon ) )
			{
				echo 
				'
					<script>
						Swal.fire({ 
							title: "'.$title.'",
							html: "'.$message.'",
							icon: "'.$icon.'",
							confirmButtonText: "Ok",
							allowOutsideClick: false,
							allowEscapeKey: false,
							closeOnClickOutside: false
						}).then((result) => {
							if (result.isConfirmed) {
								window.location.replace("/userarea");
							}
						});
					</script>
				';
			}
		}
		public function doLogout()
		{
			$title = "";
			$message = "";
			$icon = "";
            if( isset( $_SESSION[ "TricksterWebLogin" ] ) )
			{
                unset( $_SESSION[ "TricksterWebLogin" ] );
				$title = "Success!";
				$message = "You logged out successfully!";
				$icon = "success";
            }
			echo 
			'
				<script>
					Swal.fire({ 
						title: "'.$title.'",
						html: "'.$message.'",
						icon: "'.$icon.'",
						confirmButtonText: "Ok",
						allowOutsideClick: false,
						allowEscapeKey: false,
						closeOnClickOutside: false
					}).then((result) => {
						if (result.isConfirmed) {
							window.location.replace("/");
						}
					});
				</script>
			';
        }
		public function registerAccount( $secretKey, $masterAccountConfig, $dbconnacc )
		{
			$genPubID = $this->genPubID();
			$registerPostArray = array( "g-recaptcha-response", "reg-login","reg-email","reg-pass","reg-cpass" );
			$confirmPostArray = $this->isSetPostArray( $registerPostArray );
			if( $confirmPostArray == 0 )
			{
				$title = "";
				$message = "";
				$icon = "";
				$response 	= $this->getPostFromArray( $registerPostArray, 0 );
				$login 		= $this->getPostFromArray( $registerPostArray, 1 );
				$email 		= $this->getPostFromArray( $registerPostArray, 2 );
				$pass  		= $this->getPostFromArray( $registerPostArray, 3 );
				$cpass 		= $this->getPostFromArray( $registerPostArray, 4 );
				$isHuman 	= $this->verifyRecaptcha( $secretKey, $response );
				if( $isHuman == FALSE )
				{
					$title = "Error!";
					$message = "Error when validating reCAPTCHA.<br/>Please try again later.";
					$icon = "error";
				}
				else
				{
					if( $pass != $cpass )
					{
						$title = "Oops!";
						$message = "Both passwords must match.<br/>Please try again.";
						$icon = "error";
					}
					else
					{			
						$info = $this->select( 0, "web_user", array( "uid" ), array( "Login" => $login ) );
						$info_acc = $dbconnacc->select( 0, "tbl_account", array( "account_code" ), array( "account_gid" => $login ) );
						if( !empty( $info ) || !empty( $info_acc ) )
						{
							$title = "Error!";
							$message = "The informed login is already in use.<br/>Please try another one.";
							$icon = "error";
						}
						else
						{
							if( $masterAccountConfig !== FALSE )
							{
								$insertArray = array( "Login" => $login, "Password" => $pass, "Email" => $email );
								$insertResult = $this->insert( 0, "web_user", $insertArray );
								if( $insertResult == 0 )
								{
									$master_uid = $this->select( 0, "web_user", array( "uid" ), array( "Login" => $login ) );
									if( empty( $master_uid ) )
									{
										$title = "Error!";
										$message = "Error when registering a new master account.<br/>Please try again later.";
										$icon = "error";
									}
									else
									{
										$insertQuery = "EXEC usprfw_account_insert_account @pub_id = '".$genPubID."', @account_gid = '".$login."', @account_pw = '".$pass."', @account_cpw = '".$cpass."', @account_channel = 0, @access_ip_addr = '0.0.0.0'";
										$insertQueryResult = $dbconnacc->insert( 1, $insertQuery );
										if( $insertQueryResult == 0 )
										{
											$info_uid = $dbconnacc->select( 0, "tbl_account", array( "account_code" ), array( "account_gid" => $login ) );
											if( empty( $info_uid ) )
											{
												$title = "Error!";
												$message = "Error when registering a new master account.<br/>Please try again later.";
												$icon = "error";
											}
											else
											{
												$myUID = $info_uid->account_code;
												$charUID = $master_uid->uid;
												$insertCharArray = array( "uid" => $charUID, "char_uid" => $myUID );
												$insertCharResult = $this->insert( 0, "web_user_char", $insertCharArray );
												if( $insertCharResult == 0 )
												{
													$title = "Success!";
													$message = "Master account created successfully.<br/><br/>A Game Account with the same login & password<br/>was created as well.";
													$icon = "success";
												}
												else
												{
													$title = "Error!";
													$message = "Error when registering a new master account.<br/>Please try again later.";
													$icon = "error";
												}
											}
										}
										else
										{
											$title = "Error!";
											$message = "Error when registering a new master account.<br/>Please try again later.";
											$icon = "error";
										}
									}	
								}
								else
								{
									$title = "Error!";
									$message = "Error when registering a new master account.<br/>Please try again later.";
									$icon = "error";
								}
							}
							else
							{
								$insertQuery = "EXEC usprfw_account_insert_account @pub_id = '".$genPubID."', @account_gid = '".$login."', @account_pw = '".$pass."', @account_cpw = '".$cpass."', @account_channel = 0, @access_ip_addr = '0.0.0.0'";
								$insertQueryResult = $dbconnacc->insert( 1, $insertQuery );
								if( $insertQueryResult == 0 )
								{
									$info_uid = $dbconnacc->select( 0, "tbl_account", array( "account_code" ), array( "account_gid" => $login ) );
									if( empty( $info_uid ) )
									{
										$title = "Error!";
										$message = "Error when registering a new game account.<br/>Please try again later.";
										$icon = "error";
									}
									else
									{
										$myUID = $info_uid->account_code;
										$insertArray = array( "uid" => $myUID, "email" => $email );
										$insertResult = $this->insert( 0, "user_email", $insertArray );
										if( $insertResult == 0 )
										{
											$insertPointArray = array( "uid" => $myUID );
											$insertPointResult = $this->insert( 0, "user_point", $insertPointArray );
											if( $insertPointResult == 0 )
											{
												$title = "Success!";
												$message = "Game account created successfully.<br/>Log into the game and have fun!";
												$icon = "success";
											}
											else
											{
												$title = "Error!";
												$message = "Error when registering a new game account.<br/>Please try again later.";
												$icon = "error";
											}
										}
										else
										{
											$title = "Error!";
											$message = "Error when registering a new game account.<br/>Please try again later.";
											$icon = "error";
										}
									}
								}
								else
								{
									$title = "Error!";
									$message = "Error when registering a new game account.<br/>Please try again later.";
									$icon = "error";
								}
							}
						}
					}
				}
				if( !empty( $title ) && !empty( $message ) && !empty( $icon ) )
				{
					echo 
					'
						<script>
							Swal.fire({ 
								title: "'.$title.'",
								html: "'.$message.'",
								icon: "'.$icon.'",
								confirmButtonText: "Ok",
								allowOutsideClick: false,
								allowEscapeKey: false,
								closeOnClickOutside: false
							}).then((result) => {
								if (result.isConfirmed) {
									window.location.replace("/");
								}
							});
						</script>
					';
				}
			}
		}
		public function pageName()
		{
			$page = explode( "/", $_SERVER["REQUEST_URI"] );
			if( isset( $page[1] ) && !empty($page[1]) )
			{
				$main_list = array( "register","ranking","download","news","recpass","logout","userarea","bank","market","daily","recharge","admin" );
				if( in_array( strtolower( $page[1] ), $main_list ) )
				{
					return strtolower( $page[1] );
				}
				else
				{
					return "home";
				}
			}
			return "home";
		}
		public function pageLoad()
		{
			$include_page = "";
			$page = $this->pageName();
			$user_area_list = array( "userarea","bank","exchange","daily" ); 
			$admin_area_list = array( "admin" ); 
			if( in_array( $page, $user_area_list ) )
			{
				$include_page = "pages/userarea/".$page.".php";
			}
			else if( in_array( $page, $admin_area_list ) )
			{
				$include_page = "pages/adminarea/".$page.".php";
			}
			else
			{
				$include_page = "pages/".$page.".php";
			}
			return $include_page;
		}
   	}
?>