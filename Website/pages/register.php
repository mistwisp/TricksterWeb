<?php $siteclass->registerAccount( $captcha_secret_key, $use_master_account, $dbconnacc ); ?>
<div class="register-container">
    <div class="title">
        <div class="name">
            REGISTER
        </div>
    </div>
    <div class="separator"></div>
    <div class="content">
        <form id="register-form" method="post">
            <input type="text" name="reg-login" placeholder="Login" />
            <input type="text" name="reg-email" placeholder="Email" />
            <input type="password" name="reg-pass" placeholder="Password" />
            <input type="password" name="reg-cpass" placeholder="Password" />
            <button class="g-recaptcha" data-sitekey="<?php echo $captcha_site_key; ?>" data-callback='onSubmit' data-action='submit'>REGISTER</button>
        </form>
    </div>
</div>
<div class="separator-2"></div>