<?php 
  include("inc/class.hub.php");
  $pagename = $siteclass->pageName();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/base.css">
    <title>Trickster Online</title>
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Sora:wght@100..800&display=swap" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="/css/scrollbar.css">
    <script type="text/javascript" src="/js/scrollbar.js"></script>
    <script type="text/javascript" src="/js/config.js"></script>
    <?php 
      if($pagename == "register")
      {
        echo
        '
          <script src="https://www.google.com/recaptcha/api.js"></script>
          <script>function onSubmit( token ) { document.getElementById( "register-form" ).submit(); }</script>
        ';
      }
      else if($pagename == "userarea")
      {
        echo
        '
          <script src="https://www.google.com/recaptcha/api.js"></script>
          <script>function onSubmitAcc( token ) { document.getElementById( "addacc-form" ).submit(); }</script>
        ';
      }
    ?>
    <script type="text/javascript" src="/js/sweetalert2.js"></script>
    <link rel="stylesheet" href="/css/<?php echo $pagename; ?>.css">
  </head>
  <body>
    <div class="menu">
      <a onclick="toggleMenu()">
        <div class="menu-btn">
          <img src="/images/menu.png" alt="" />
        </div>
      </a>
      <div class="logo">
        <img src="/images/logo.png" alt="" />
      </div>
      <ul>
        <li><a href="/home" <?php if( $pagename == "home" ) { echo 'class="active"'; } ?>>HOME</a></li>
        <li><a href="/register" <?php if( $pagename == "register" ) { echo 'class="active"'; } ?>>REGISTER</a></li>
        <li><a href="/download" <?php if( $pagename == "download" ) { echo 'class="active"'; } ?>>DOWNLOAD</a></li>
        <li><a href="/ranking/level/all" <?php if( $pagename == "ranking" ) { echo 'class="active"'; } ?>>RANKING</a></li>
      </ul>
      <div class="login">
        <button class="login-button" id="loginToggle" onclick="toggleLoginForm()"><?php if( !isset( $_SESSION[ "TricksterWebLogin" ] ) ) { echo "🔒︎ LOGIN"; } else { echo "🕵︎ USER AREA"; } ?></button>
        <div class="login-form" id="loginForm">
        <?php
          if( !isset( $_SESSION[ "TricksterWebLogin" ] ) )
          {
            $siteclass->doLogin( $use_master_account, $dbconnacc );
            echo
            '
              <form method="post">
                <input type="text" placeholder="Username" name="login">
                <input type="password" placeholder="Password" name="pass">
                <a href="/recpass"><span class="text-1">Forgot your password?</span></a>
                <input type="submit" value="🔒︎ LOGIN">
              </form>
            ';
          }
          else
          {
            echo
            '
              <span class="text-2">WELCOME</span><br/>
              <span class="text-3">'.$_SESSION[ "TricksterWebLogin" ].'</span>
              <a href="/userarea"><span class="user_area_btn">🕵︎ USER AREA</span></a>
              <a href="/logout"><span class="logout_btn">🔓︎ LOGOUT</span></a>
            ';
          }
        ?>
        </div>
      </div>
    </div>
    <div class="bg-container">
      <div class="button-holder">
        <?php
          if( isset( $_SESSION[ "TricksterWebLogin" ] ) )
          {
            if( $adminclass->checkHaveAccess( $userclass->getUID( $use_master_account, $dbconnacc ) ) == 0 )
            {
              echo '<a href="/admin"><button type="submit" class="admin-button">🖥︎ ADMIN AREA</button></a>';
            }
          }
        ?>
      </div>
      <div class="inside"></div>
    </div>
    <div id="container">
      <?php include($siteclass->pageLoad()); ?>
    </div>
    <div class="footer-container">
      <div class="inside">
        <span class="text-1">Trickster Online © 2024</span><br/>
        <span class="text-2">Website made by MistWisp for Trickster Online</span>
      </div>
    </div>
    <script type="text/javascript" src="/js/carrousel.js"></script>
  </body>
</html>