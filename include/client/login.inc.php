<?php
if(!defined('OSTCLIENTINC')) die('Access Denied');

$email=Format::input($_POST['luser']?:$_GET['e']);
$passwd=Format::input($_POST['lpasswd']?:$_GET['t']);

$content = Page::lookupByType('banner-client');

if ($content) {
    list($title, $body) = $ost->replaceTemplateVariables(
        array($content->getLocalName(), $content->getLocalBody()));
} else {
    $title = __('Sign In');
    $body = __('To better serve you, we encourage our clients to register for an account and verify the email address we have on record.');
}

?>
<div style="max-width: 900px; margin: 0 auto; padding: 20px;">
    <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 30px;">
        <h1 style="text-align: center; margin-top: 0; color: #333;"><?php echo Format::display($title); ?></h1>
        <p style="text-align: center; color: #666;"><?php echo Format::display($body); ?></p>
        
        <form action="login.php" method="post" id="clientLogin" style="margin: 0 auto;">
            <?php csrf_token(); ?>
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px;">
                <div class="login-box" style="flex: 1; min-width: 300px;">
                    <strong style="color: #d00; display: block; text-align: center; margin-bottom: 15px;"><?php echo Format::htmlchars($errors['login']); ?></strong>
                    <div style="margin-bottom: 15px;">
                        <input id="username" placeholder="<?php echo __('Email or Username'); ?>" type="text" name="luser" size="30" value="<?php echo $email; ?>" class="nowarn" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <input id="passwd" placeholder="<?php echo __('Password'); ?>" type="password" name="lpasswd" size="30" maxlength="128" value="<?php echo $passwd; ?>" class="nowarn" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px;">
                    </div>
                    <div style="text-align: center;">
                        <input class="btn" type="submit" value="<?php echo __('Sign In'); ?>" style="background: #009688; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px;">
                        <?php if ($suggest_pwreset) { ?>
                        <a style="padding-top: 10px; display: inline-block; color: #009688; text-decoration: none; margin-left: 15px;" href="pwreset.php"><?php echo __('Forgot My Password'); ?></a>
                        <?php } ?>
                    </div>
                </div>
                
                <div style="flex: 1; min-width: 300px; padding: 15px; border-left: 1px solid #eee;">
                    <?php
                    $ext_bks = array();
                    foreach (UserAuthenticationBackend::allRegistered() as $bk)
                        if ($bk instanceof ExternalAuthentication)
                            $ext_bks[] = $bk;

                    if (count($ext_bks)) {
                        foreach ($ext_bks as $bk) { ?>
                    <div class="external-auth" style="margin-bottom: 15px;"><?php $bk->renderExternalLink(); ?></div>
                    <?php }
                    }
                    if ($cfg && $cfg->isClientRegistrationEnabled()) {
                        if (count($ext_bks)) echo '<hr style="width:70%; margin: 20px auto; border: 0; border-top: 1px solid #eee;"/>'; ?>
                        <div style="margin-bottom: 15px; text-align: center;">
                            <?php echo __('Not yet registered?'); ?> <a href="account.php?do=create" style="color: #009688; text-decoration: none;"><?php echo __('Create an account'); ?></a>
                        </div>
                    <?php } ?>
                    <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                        <b style="color: #333;"><?php echo __("I'm an agent"); ?></b> —
                        <a href="<?php echo ROOT_PATH; ?>scp/" style="color: #009688; text-decoration: none;"><?php echo __('sign in here'); ?></a>
                    </div>
                </div>
            </div>
        </form>
        
        <br>
        <p style="text-align: center; color: #666;">
        <?php
        if ($cfg->getClientRegistrationMode() != 'disabled'
            || !$cfg->isClientLoginRequired()) {
            echo sprintf(__('If this is your first time contacting us or you\'ve lost the ticket number, please %s open a new ticket %s'),
                '<a href="open.php" style="color: #009688; text-decoration: none;">', '</a>');
        } ?>
        </p>
    </div>
</div>


<!-- <?php
if(!defined('OSTCLIENTINC')) die('Access Denied');

$email=Format::input($_POST['luser']?:$_GET['e']);
$passwd=Format::input($_POST['lpasswd']?:$_GET['t']);

$content = Page::lookupByType('banner-client');

if ($content) {
    list($title, $body) = $ost->replaceTemplateVariables(
        array($content->getLocalName(), $content->getLocalBody()));
} else {
    $title = __('Sign In');
    $body = __('To better serve you, we encourage our clients to register for an account and verify the email address we have on record.');
}

?>
<h1><?php echo Format::display($title); ?></h1>
<p><?php echo Format::display($body); ?></p>
<form action="login.php" method="post" id="clientLogin">
    <?php csrf_token(); ?>
<div style="display:table-row">
    <div class="login-box">
    <strong><?php echo Format::htmlchars($errors['login']); ?></strong>
    <div>
        <input id="username" placeholder="<?php echo __('Email or Username'); ?>" type="text" name="luser" size="30" value="<?php echo $email; ?>" class="nowarn">
    </div>
    <div>
        <input id="passwd" placeholder="<?php echo __('Password'); ?>" type="password" name="lpasswd" size="30" maxlength="128" value="<?php echo $passwd; ?>" class="nowarn"></td>
    </div>
    <p>
        <input class="btn" type="submit" value="<?php echo __('Sign In'); ?>">
<?php if ($suggest_pwreset) { ?>
        <a style="padding-top:4px;display:inline-block;" href="pwreset.php"><?php echo __('Forgot My Password'); ?></a>
<?php } ?>
    </p>
    </div>
    <div style="display:table-cell;padding: 15px;vertical-align:top">
<?php

$ext_bks = array();
foreach (UserAuthenticationBackend::allRegistered() as $bk)
    if ($bk instanceof ExternalAuthentication)
        $ext_bks[] = $bk;

if (count($ext_bks)) {
    foreach ($ext_bks as $bk) { ?>
<div class="external-auth"><?php $bk->renderExternalLink(); ?></div><?php
    }
}
if ($cfg && $cfg->isClientRegistrationEnabled()) {
    if (count($ext_bks)) echo '<hr style="width:70%"/>'; ?>
    <div style="margin-bottom: 5px">
    <?php echo __('Not yet registered?'); ?> <a href="account.php?do=create"><?php echo __('Create an account'); ?></a>
    </div>
<?php } ?>
    <div>
    <b><?php echo __("I'm an agent"); ?></b> —
    <a href="<?php echo ROOT_PATH; ?>scp/"><?php echo __('sign in here'); ?></a>
    </div>
    </div>
</div>
</form>
<br>
<p>
<?php
if ($cfg->getClientRegistrationMode() != 'disabled'
    || !$cfg->isClientLoginRequired()) {
    echo sprintf(__('If this is your first time contacting us or you\'ve lost the ticket number, please %s open a new ticket %s'),
        '<a href="open.php">', '</a>');
} ?>
</p> -->
