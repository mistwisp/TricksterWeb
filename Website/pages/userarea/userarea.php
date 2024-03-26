<?php
    if( !isset( $_SESSION[ "TricksterWebLogin" ] ) )
    {
        echo '<script>window.location.replace("/");</script>';
    }
    $userclass->doPostCheck( $captcha_secret_key, $use_master_account, $dbconnacc, $dbconntrickster );
?>
<div class="userarea-container">
    <div class="title">
        <div class="name">
            USER AREA
        </div>
        <div class="link">
			<a href="/userarea"><button class="userarea_category userarea_active">ACCOUNT</button></a>
            <a href="/daily"><button class="userarea_category">DAILY REWARD</button></a>
            <a href="/recharge"><button class="userarea_category">DONATE</button></a>
        </div>
    </div>
    <div class="separator"></div>
    <div class="content">
        <?php if( $use_master_account !== FALSE ) { ?>
        <div class="content-square">
            <span class="text-1">MY GAME ACCOUNTS</span>
            <div class="subacc-square">
                <div id="square-scroll">
                    <?php echo $userclass->displaySubAccounts( $use_master_account, $dbconnacc ); ?>
                </div>
            </div>
        </div>
        <div class="content-square content-square-right">
            <span class="text-2">REGISTER NEW GAME ACCOUNT</span>
            <form id="addacc-form" method="post">
                <input type="text" name="addacc-login" placeholder="Login" /><br/><br/>
                <span class="text-3">All of your Game Accounts's passwords will <span class="yellow-text">ALWAYS</span> be<br/>the same password as your Master Account!</span><br/><br/>
                <button class="g-recaptcha" data-sitekey="<?php echo $captcha_site_key; ?>" data-callback='onSubmitAcc' data-action='submit'>REGISTER GAME ACCOUNT</button>
            </form>
        </div>
        <?php } ?>
    </div>
    <div class="separator"></div>
    <div class="content">
        <div class="content-square">
            <span class="text-1">CHANGE PASSWORD</span>
            <form id="change-form" method="post">
                <input type="password" name="change-pass" placeholder="New Password" />
                <input type="password" name="change-cpass" placeholder="Confirm New Password" />
                <?php
                    if( $use_master_account !== FALSE ) 
                    { 
                        echo
                        "
                            <br/><br/>
                            <span class='text-4'>Your Master Account and <span class='yellow-text'>ALL</span> of your<br/>Game Accounts's passwords will be changed!</span><br/><br/>
                        ";
                    }
                ?>
                <button type='submit'>CHANGE PASSWORD</button>
            </form>
        </div>
        <?php
            if( $use_master_account == FALSE ) 
            {
                $getUID = $userclass->getUID( $use_master_account, $dbconnacc );
                echo
                '
                    <div class="content-square content-square-right">
                        <span class="text-2">FIX GAME ACCOUNT LOGIN</span>
                        <form id="fixsingle-form" method="post">
                            <input type="hidden" name="fixsingle-uid" value="'.$getUID.'"/>
                            <span class="text-5">If you are unable to login into the game,<br/> click the button below and then try again!<br/><br/>
                            <button type="submit">FIX GAME ACCOUNT LOGIN</button>
                        </form>
                    </div>
                ';
            }
        ?>
    </div>
</div>
<div class="separator-2"></div>