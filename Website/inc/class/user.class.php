<?php
	class User extends Connection
	{
		function __construct( $dbinfo )
		{
			parent::__construct( $dbinfo );
		}
        public function getUID( $masterAccountConfig, $dbconnacc )
		{
			if( $masterAccountConfig !== FALSE )
			{
				$info = $this->select( 0, "web_user", array( "uid" ), array( "Login" => $_SESSION[ "TricksterWebLogin" ] ) );
				if( !empty( $info ) )
				{
					return $info->uid;
				}
			}
			else
			{
				$info = $dbconnacc->select( 0, "tbl_account", array( "account_code" ), array( "account_gid" => $_SESSION[ "TricksterWebLogin" ] ) );
				if( !empty( $info ) )
				{
					return $info->account_code;
				}
			}

			return 0;     
        }
		public function addSubAccounts( $isHuman, $login, $masterAccountConfig, $dbconnacc )
		{
			$title = "";
			$message = "";
			$icon = "";
			if( !$isHuman )
			{
				$title = "Error!";
				$message = "Error when validating reCAPTCHA.<br/>Please try again later.";
				$icon = "error";
			}
			else
			{
				$getMasterUID = $this->getUID( $masterAccountConfig, $dbconnacc );
				$getNewSubUID = $this->registerSubAccount( $login, $dbconnacc, $getMasterUID );
				if( $getNewSubUID > 0 )
				{
					$insertCharArray = array( "uid" => $getMasterUID, "char_uid" => $getNewSubUID );
					$insertCharResult = $this->insert( 0, "web_user_char", $insertCharArray );
					if( $insertCharResult == 0 )
					{
						$title = "Success!";
						$message = "Sub account created successfully.<br/>The password is the same one as your Master Account.";
						$icon = "success";
					}
					else
					{
						$title = "Error!";
						$message = "Error when registering a new sub account.<br/>Please try again later.";
						$icon = "error";
					}
				}
				else
				{
					$title = "Error!";
					$message = "Error when registering a new sub account.<br/>Please try again later.";
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
		public function registerSubAccount( $login, $dbconnacc, $masterUID )
		{
			$genPubID = $this->genPubID();
			$info = $this->select( 0, "web_user", array( "uid" ), array( "Login" => $login ) );
			$info_acc = $dbconnacc->select( 0, "tbl_account", array( "account_code" ), array( "account_gid" => $login ) );
			if( !empty( $info_acc ) || !empty( $info ) )
			{
				return 0;
			}
			else
			{
				$masterPassQ = "SELECT Password from web_user WHERE Login = ".$login."";
				$masterPassF = $this->select( 3, $masterPassQ );
				$insertQuery = "EXEC usprfw_account_insert_account @pub_id = '".$genPubID."', @account_gid = '".$login."', @account_pw = '".$masterPassF."', @account_cpw = '".$masterPassF."', @account_channel = 0, @access_ip_addr = '0.0.0.0'";
				$insertQueryResult = $dbconnacc->insert( 1, $insertQuery );
				if( $insertQueryResult == 0 )
				{
					$info_uid = $dbconnacc->select( 0, "tbl_account", array( "account_code" ), array( "account_gid" => $login ) );
					if( empty( $info_uid ) )
					{
						return 0;
					}
					else
					{
						$myUID = $info_uid->account_code;
						return $myUID;
					}
				}
				else
				{
					return 0;
				}
			}
		}
		public function getAllAccountsUID( $uid )
		{
			$info = $this->select( 1, "web_user_char", array( "char_uid" ), array( "uid" => $uid ) );
			return $info;
		}
		public function displaySubAccounts( $masterAccountConfig, $dbconnacc )
		{
			$numerator = 0;
			$uid = $this->getUID( $masterAccountConfig, $dbconnacc );
			$info = $this->getAllAccountsUID( $uid );
			if( !empty( $info ) )
			{
				foreach( $info as $subInfoAcc )
				{
					$infoAcc = $dbconnacc->select( 0, "tbl_account", array( "account_gid" ), array( "account_code" => $subInfoAcc->char_uid ) );
					if( !empty( $infoAcc ) )
					{
						$separateAcc = "";
						if($_SESSION[ "TricksterWebLogin" ]  != $infoAcc->account_gid)
						{
							$separateAcc = 
							'
								<form method="post" id="separateacc-'.$numerator.'">
									<input type="hidden" name="separate-uid" value="'.$subInfoAcc->char_uid.'"/>
								</form>
								<button onclick="submitForm'.$numerator.'()" class="fix">SEPARATE ACCOUNT</button>
								<script>
									function submitForm'.$numerator.'() 
									{
										Swal.fire
										({ 
											title: "WARNING!",
											html: "The Game Account <b>'.$infoAcc->account_gid.'</b> will be separated<br/>from this Master Account!<br/><br/>A new Master Account with the same <b>login</b> and <b>password</b> will be created, and this Game Account will be associated with it.<br/><br/>Do you still want to proceed?",
											icon: "warning",
											showCancelButton: true,
											confirmButtonText: "Confirm"
										}).then((result) => 
										{
											if (result.isConfirmed) 
											{
												document.getElementById("separateacc-'.$numerator.'").submit();
											}
										});
									}
								</script>
							';
						}
						echo
						'
							<div class="subacc-unit">
								<div class="inside">
									<div class="acclogin">
										'.$infoAcc->account_gid.'
									</div>
									<form method="post"><input type="hidden" name="fix-uid" value="'.$subInfoAcc->char_uid.'"/><input type="submit" class="fix" value="FIX LOGIN"/></form>
									'.$separateAcc.'
								</div>
							</div>
						';
						$numerator++;
					}
				}
			}   
        }
		public function fixLogin( $uid, $dbconnacc )
		{
			$title = "Error!";
			$message = "Error when fixing the login of this account!<br/>Please try again later.";
			$icon = "error";
			$info = $dbconnacc->update( "tbl_account_game", array( "acc_game_state" => 0 ), array( "account_code" => $uid ) );
			if( $info == 0 )
			{
				$title = "Success!";
				$message = "Login was fixed successfully.<br/>You can log in the game now.";
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
							window.location.replace("/userarea");
						}
					});
				</script>
			';
        }
		public function accountChangePass( $pass, $masterAccountConfig, $dbconnacc, $type )
		{
			$uid = $this->getUID( $masterAccountConfig, $dbconnacc );
			$passQuery = "SELECT dbo.fn_Md5_Encrypt(RTRIM('".$pass."'))";
			$masterPass = $dbconnacc->select( 4, $passQuery );
			if( $type == 0 )
			{
				$info = $this->update( "web_user", array( "Password" => $pass ), array( "uid" => $uid ) );
				if( $info < 0)
				{
					return $info;
				}
				else
				{
					$accountList = $this->getAllAccountsUID( $uid );
					foreach( $accountList as $accountUnit )
					{
						$infoAcc = $dbconnacc->update( "tbl_account", array( "account_pw" => $masterPass, "account_cpw" => $masterPass ), array( "account_code" => $accountUnit->char_uid ) );
						if( $infoAcc < 0 )
						{
							return $infoAcc;
						}
					}
				}
			}
			else
			{
				$info = $dbconnacc->update( "tbl_account", array( "account_pw" => $masterPass, "account_cpw" => $masterPass ), array( "account_code" => $uid ) );
				if( $info < 0 )
				{
					return $info;
				}
			}
			return 0;
		}
		public function doChangePassword( $pass, $confirmPass, $masterAccountConfig, $dbconnacc )
		{
			$title = "";
			$message = "";
			$icon = "";
			if( $pass != $confirmPass )
			{
				$title = "Error!";
				$message = "Both passwords must match!<br/>Please try again...";
				$icon = "error";
			}
			else
			{
				if( $masterAccountConfig !== FALSE )
				{
					$changePassResult = $this->accountChangePass( $pass, $masterAccountConfig, $dbconnacc, 0 );
					if( $changePassResult > -1 )
					{
						unset( $_SESSION[ "TricksterWebLogin" ] );
						$title = "Success!";
						$message = "Master Account password changed successfully.";
						$icon = "success";
					}
					else
					{
						$title = "Error!";
						$message = "Error when changing your Master Account password.<br/>Please try again later.";
						$icon = "error";
					}
				}
				else
				{
					$changePassResult = $this->accountChangePass( $pass, $masterAccountConfig, $dbconnacc, 1 );
					if( $changePassResult > -1 )
					{
						unset( $_SESSION[ "TricksterWebLogin" ] );
						$title = "Success!";
						$message = "Account password changed successfully.";
						$icon = "success";
					}
					else
					{
						$title = "Error!";
						$message = "Error when changing your password.<br/>Please try again later.";
						$icon = "error";
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
		public function doSeparateAccount( $uid, $dbconnacc )
		{
			$title = "";
			$message = "";
			$icon = "";
			$info = $this->select( 0, "web_user", array( "Password", "Email" ), array( "Login" => $_SESSION[ "TricksterWebLogin" ] ) );
			$info_acc = $dbconnacc->select( 0, "tbl_account", array( "account_gid" ), array( "account_code" => $uid ) );
			if( empty( $info_acc ) || empty( $info ) )
			{
				$title = "Error!";
				$message = "Error when separating accounts!<br/>Please try again...";
				$icon = "error";
			}
			else
			{
				$accPass = $info->Password;
				$accLogin = $info_acc->account_gid;
				$accEmail = $info->Email;
				$insertArray = array( "Login" => $accLogin, "Password" => $accPass, "Email" => $accEmail );
				$insertResult = $this->insert( 0, "web_user", $insertArray );
				if( $insertResult == 0 )
				{
					$master_uid = $this->select( 0, "web_user", array( "uid" ), array( "Login" => $accLogin ) );
					if( empty( $master_uid ) )
					{
						$title = "Error!";
						$message = "Error when separating accounts!<br/>Please try again...";
						$icon = "error";
					}
					else
					{
						$masterUID = $master_uid->uid;
						$updateChar = $this->update( "web_user_char", array( "uid" => $masterUID ), array( "char_uid" => $uid ) );
						if( $updateChar == 0 )
						{
							$title = "Success!";
							$message = "Account ".$accLogin." was separated successfully!<br/><br/>A new Master Account with the same Login & password<br/>was created, and this Game Account was<br/>added to it.";
							$icon = "success";
						}
						else
						{
							$title = "Error!";
							$message = "Error when separating accounts!<br/>Please try again...";
							$icon = "error";
						}
					}
				}
				else
				{
					$title = "Error!";
					$message = "Error when separating accounts!<br/>Please try again...";
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
		public function getItemData( $itemID, $dbconntrickster )
		{
			$itemName = $dbconntrickster->select( 0, "ini_item_index", array( "item_name", "item_price" ), array( "item_index" => $itemID ) );
			if( !empty( $itemName ) )
			{
				return $itemName;
			}
			return "";
		}
		public function getDailyPrizes()
		{
			$dailyQuery = "SELECT item_id, amount, rate FROM game_prizes ORDER BY rate ASC";
			$dailyPrizes = $this->select( 2, $dailyQuery );
			if( !empty( $dailyPrizes ) )
			{
				return $dailyPrizes;
			}
			return array();
		}
		public function getPrizeDataFromID( $itemID )
		{
			$prizeData = $this->select( 0, "game_prizes", array( "amount", "rate" ), array( "item_id" => $itemID ) );
			if( !empty( $prizeData ) )
			{
				return $prizeData;
			}
			return array();
		}
		public function showPrizeList( $dbconntrickster )
		{
			$dailyPrizes = $this->getDailyPrizes();
			if( !empty( $dailyPrizes ) )
			{
				echo
				"
					<table>
						<thead>
							<tr>
								<th>Image</th>
								<th>Name</th>
								<th>Amount</th>
								<th>Drop%</th>
							</tr>
						</thead>
						<tbody>
				";
				foreach( $dailyPrizes as $prize )
				{
					$itemData = $this->getItemData( $prize->item_id, $dbconntrickster );
					echo
					"
						<tr>
							<td><img src='/images/items/".$prize->item_id.".gif' alt=''/></td>
							<td>".$itemData->item_name."</td>
							<td>".$prize->amount."</td>
							<td>".$prize->rate."%</td>
						</tr>
					";
				}
				echo 
				"
						</tbody>
					</table>
				";
			}
		}
		public function getAccountSelectList( $masterAccountConfig, $dbconnacc )
		{
			$myUID = $this->getUID( $masterAccountConfig, $dbconnacc );
			$accountList = $this->getAllAccountsUID( $myUID );
			$numerator = 0;
			if( !empty( $accountList ) )
			{
				foreach( $accountList as $account )
				{
					$infoAcc = $dbconnacc->select( 0, "tbl_account", array( "account_gid" ), array( "account_code" => $account->char_uid ) );
					if( !empty( $infoAcc ) )
					{
						echo '<option value="'.$account->char_uid.'">'.$infoAcc->account_gid.'</option>';
					}
					$numerator++;
				}
			}
		}
		public function getRewardRoll()
		{
			$dailyPrizes = $this->getDailyPrizes();
			$weightedItems = [];
			foreach ( $dailyPrizes as $item ) 
			{
				$itemRate = ceil( $item->amount * ( $item->rate * 1/100 ) );
				$weightedItems = array_merge( $weightedItems, array_fill( 0, $itemRate, $item->item_id ) );
			}
			$randomIndex = array_rand( $weightedItems );
			return $weightedItems[ $randomIndex ];
		}
		public function doInsertItem( $itemID, $uid, $dbconntrickster )
		{
			$now = new DateTime();
			$smalldatetime = $now->format('Y-m-d H:i:s');
			$login = $_SESSION[ "TricksterWebLogin" ];
			$itemData = $this->getPrizeDataFromID( $itemID );
			$insertQuery = "EXEC uspt_item_all_insert @char_uid = '-1', @where = '4', @item_index = '".$itemID."', @item_count = '".$itemData->amount."' , @user_uid = '".$uid."', @item_use_time = '0'";
			$insertQueryResult = $dbconntrickster->insert( 1, $insertQuery );
			if( $insertItemResult == 0 )
			{
				return 0;
			}
			return -1;
		}
		public function doCheckPlayLimit( $uid )
		{
			$now = new DateTime();
			$smalldatetime = $now->format('Y-m-d H:i:s');
			$playQuery = "SELECT TOP 1 item_date FROM game_play_log ORDER BY item_date DESC";
			$infoPlay = $this->select( 6, $playQuery );
			if( !empty( $infoPlay ) )
			{
				$hourdiff = round((strtotime($smalldatetime) - strtotime($infoPlay->item_date))/3600, 1);
				if( $hourdiff >= 24 )
				{
					return 0;
				}
				return -1;
			}
			return 0;
		}
		public function doGetDailyReward( $uid, $dbconnacc, $dbconntrickster )
		{
			$title = "";
			$message = "";
			$icon = "";
			$itemID = $this->getRewardRoll();
			$itemData = $this->getItemData( $itemID, $dbconntrickster );
			if( $itemData->item_name != "" )
			{
				if( $this->doCheckPlayLimit( $uid ) != 0 )
				{
					$title = "Error!";
					$message = "You already claimed the Daily Reward today!<br/>Please come back again in 24 hours...";
					$icon = "error";
				}
				else
				{
					if( $this->doInsertItem( $itemID, $uid, $dbconntrickster ) == 0 )
					{
						$insertPlayArray = array( "uid" => $uid, "item_id" => $itemID );
						$insertPlayResult = $this->insert( 0, "game_play_log", $insertPlayArray );
						if( $insertPlayResult == 0 )
						{
							$title = "Success!";
							$message = "Congratulations! You got:<br/><br/>".$itemData->item_name."<br/><br/>Please come back again, after 24 hours!";
							$icon = "success";
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
								window.location.replace("/userarea");
							}
						});
					</script>
				';
			}
		}
		public function doPostCheck( $secretKey, $masterAccountConfig, $dbconnacc, $dbconntrickster )
		{
			$fixLoginArray = array( "fix-uid" );
			$newGameAccountrArray = array( "g-recaptcha-response", "addacc-login" );
			$changePassArray = array( "change-pass", "change-cpass" );
			$fixLoginSingleArray = array( "fixsingle-uid" );
			$separateAccounArray = array( "separate-uid" );
			$dailyRewardArray = array( "dailyreward-uid" );
			if( $this->isSetPostArray( $fixLoginArray ) == 0 )
			{
				$uid = $this->getPostFromArray( $fixLoginArray, 0 );
				$this->fixLogin( $uid, $dbconnacc );
			}
			else if( $this->isSetPostArray( $newGameAccountrArray ) == 0 )
			{
				$response = $this->getPostFromArray( $newGameAccountrArray, 0 );
				$login = $this->getPostFromArray( $newGameAccountrArray, 1 );
				$isHuman = $this->verifyRecaptcha( $secretKey, $response );
				$this->addSubAccounts( $isHuman, $login, $masterAccountConfig, $dbconnacc );
			}
			else if( $this->isSetPostArray( $changePassArray ) == 0 )
			{
				$pass = $this->getPostFromArray( $changePassArray, 0 );
				$confirmPass = $this->getPostFromArray( $changePassArray, 1 );
				$this->doChangePassword( $pass, $confirmPass, $masterAccountConfig, $dbconnacc );
			}
			else if( $this->isSetPostArray( $fixLoginSingleArray ) == 0 )
			{
				$uid = $this->getPostFromArray( $fixLoginSingleArray, 0 );
				$this->fixLogin( $uid, $dbconnacc );
			}
			else if( $this->isSetPostArray( $separateAccounArray ) == 0 )
			{
				$uid = $this->getPostFromArray( $separateAccounArray, 0 );
				$this->doSeparateAccount( $uid, $dbconnacc );
			}
			else if( $this->isSetPostArray( $dailyRewardArray ) == 0 )
			{
				$uid = $this->getPostFromArray( $dailyRewardArray, 0 );
				$this->doGetDailyReward( $uid, $dbconnacc, $dbconntrickster );
			}
		}
   	}
?>