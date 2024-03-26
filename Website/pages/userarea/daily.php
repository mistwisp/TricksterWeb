<?php
    if( !isset( $_SESSION[ "TricksterWebLogin" ] ) )
    {
        echo '<script>window.location.replace("/");</script>';
    }
    $userclass->doPostCheck( $captcha_secret_key, $use_master_account, $dbconnacc, $dbconntrickster );
?>
<div class="daily-container">
    <div class="title">
        <div class="name">
            DAILY REWARD
        </div>
        <div class="link">
			<a href="/userarea"><button class="userarea_category">ACCOUNT</button></a>
            <a href="/daily"><button class="userarea_category userarea_active">DAILY REWARD</button></a>
            <a href="/recharge"><button class="userarea_category">DONATE</button></a>
        </div>
    </div>
    <div class="separator"></div>
    <div class="content">
        <div class="square-container">
            <div class="daily-text">Below is the prize table with the drop % ratio of the <span class="yellow-text">DAILY REWARDS</span>!</div>
            <?php $userclass->showPrizeList( $dbconntrickster ); ?>
            <form method="post">
                <?php
                    if( $use_master_account !== FALSE ) 
                    {
                        echo
                        '
                            <div class="daily-text-2">Select a Game Acccount below, and get your today\'s <span class="yellow-text">Daily Reward</span>!<br/>Once you get for the selected account, you will only be able to get it again<br/>after <span class="yellow-text">24 HOURS</span>!</div>
                            <div class="daily-text-3">Select one of your Game Accounts:</div>
                            <select name="dailyreward-uid">
                        ';
                        $userclass->getAccountSelectList( $use_master_account, $dbconnacc );
                        echo
                        '
                            </select>
                        ';
                    }
                    else
                    {
                        echo
                        '
                            <div class="daily-text-2">Click the button below, and get your today\'s <span class="yellow-text">Daily Reward</span>!<br/>Once you get a reward, you will only be able to get it again<br/>after <span class="yellow-text">24 HOURS</span>!</div>
                            <input type="hidden" name="dailyreward-uid" value="'.$userclass->getUID( $use_master_account, $dbconnacc ).'" />
                        ';
                    }
                ?>
                <input class="daily-button" type="submit" value="GET DAILY REWARD" />
            </form>
        </div>
    </div>
</div>
<div class="separator-2"></div>