<?php
if(!defined('OSTCLIENTINC')) die('Access Denied');

$email = Format::input($_POST['lemail'] ? $_POST['lemail'] : $_GET['e']);
$ticketid = Format::input($_POST['lticket'] ? $_POST['lticket'] : $_GET['t']);

if ($cfg->isClientEmailVerificationRequired())
    $button = __("Email Access Link");
else
    $button = __("View Ticket");
?>

<!-- Centering wrapper -->
<div style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
    <form action="login.php" method="post" id="clientLogin"
        style="width: 100%; max-width: 500px; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 16px rgba(0,0,0,0.1);">

        <?php csrf_token(); ?>

        <h1 style="font-size: 24px; color: #333; margin-bottom: 15px;"><?php echo __('Check Ticket Status'); ?></h1>

        <p style="color: #555; font-size: 15px; margin-bottom: 25px;">
            <?php
            echo __('Please provide your email address and a ticket number.');
            if ($cfg->isClientEmailVerificationRequired())
                echo ' ' . __('An access link will be emailed to you.');
            else
                echo ' ' . __('This will sign you in to view your ticket.');
            ?>
        </p>

        <?php if ($errors['login']) { ?>
            <div style="color: red; font-weight: bold; margin-bottom: 15px;">
                <?php echo Format::htmlchars($errors['login']); ?>
            </div>
        <?php } ?>

        <div style="margin-bottom: 20px;">
            <label for="email" style="display: block; font-weight: bold; margin-bottom: 6px; color: #333;"><?php echo __('Email Address'); ?></label>
            <input id="email" name="lemail" type="text" size="30" value="<?php echo $email; ?>" class="nowarn"
                   placeholder="<?php echo __('e.g. john.doe@osticket.com'); ?>"
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="ticketno" style="display: block; font-weight: bold; margin-bottom: 6px; color: #333;"><?php echo __('Ticket Number'); ?></label>
            <input id="ticketno" name="lticket" type="text" size="30" value="<?php echo $ticketid; ?>" class="nowarn"
                   placeholder="<?php echo __('e.g. 051243'); ?>"
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;">
        </div>

        <div style="margin-top: 10px;">
            <input class="btn" type="submit" value="<?php echo $button; ?>"
                   style="width: 100%; padding: 12px; font-size: 15px; background-color: #007BFF; color: #fff; border: none; border-radius: 5px; cursor: pointer;">
        </div>

        <!-- Registration section -->
        <div class="instructions" style="margin-top: 20px; font-size: 13px; color: #555;">
            <?php if ($cfg && $cfg->getClientRegistrationMode() !== 'disabled') { ?>
                <?php echo __('Have an account with us?'); ?>
                <a href="login.php" style="color: #007BFF; text-decoration: none;"><?php echo __('Sign In'); ?></a>
                <?php
                if ($cfg->isClientRegistrationEnabled()) {
                    echo '<br>' . sprintf(__('or %s register for an account %s to access all your tickets.'),
                        '<a href="account.php?do=create" style="color: #007BFF;">', '</a>');
                }
            } ?>
        </div>
        <p style="text-align: center; font-size: 14px; margin-top: 20px; color: #444;">
    <?php
    if ($cfg->getClientRegistrationMode() != 'disabled'
        || !$cfg->isClientLoginRequired()) {
        echo sprintf(
            __("If this is your first time contacting us or you've lost the ticket number, please %s open a new ticket %s"),
            '<a href="open.php" style="color: #007BFF;">', '</a>'
        );
    }
    ?>
</p>
    </form>
</div>







<!-- <?php
if(!defined('OSTCLIENTINC')) die('Access Denied');

$email=Format::input($_POST['lemail']?$_POST['lemail']:$_GET['e']);
$ticketid=Format::input($_POST['lticket']?$_POST['lticket']:$_GET['t']);

if ($cfg->isClientEmailVerificationRequired())
    $button = __("Email Access Link");
else
    $button = __("View Ticket");
?>
<h1><?php echo __('Check Ticket Status'); ?></h1>
<p><?php
echo __('Please provide your email address and a ticket number.');
if ($cfg->isClientEmailVerificationRequired())
    echo ' '.__('An access link will be emailed to you.');
else
    echo ' '.__('This will sign you in to view your ticket.');
?></p>
<form action="login.php" method="post" id="clientLogin">
    <?php csrf_token(); ?>
<div style="display:table-row">
    <div class="login-box">
    <div><strong><?php echo Format::htmlchars($errors['login']); ?></strong></div>
    <div>
        <label for="email"><?php echo __('Email Address'); ?>:
        <input id="email" placeholder="<?php echo __('e.g. john.doe@osticket.com'); ?>" type="text"
            name="lemail" size="30" value="<?php echo $email; ?>" class="nowarn"></label>
    </div>
    <div>
        <label for="ticketno"><?php echo __('Ticket Number'); ?>:
        <input id="ticketno" type="text" name="lticket" placeholder="<?php echo __('e.g. 051243'); ?>"
            size="30" value="<?php echo $ticketid; ?>" class="nowarn"></label>
    </div>
    <p>
        <input class="btn" type="submit" value="<?php echo $button; ?>">
    </p>
    </div>
    <div class="instructions">
<?php if ($cfg && $cfg->getClientRegistrationMode() !== 'disabled') { ?>
        <?php echo __('Have an account with us?'); ?>
        <a href="login.php"><?php echo __('Sign In'); ?></a> <?php
    if ($cfg->isClientRegistrationEnabled()) { ?>
<?php echo sprintf(__('or %s register for an account %s to access all your tickets.'),
    '<a href="account.php?do=create">','</a>');
    }
}?>
    </div>
</div>
</form>
<br>
<p>
<?php
if ($cfg->getClientRegistrationMode() != 'disabled'
    || !$cfg->isClientLoginRequired()) {
    echo sprintf(
    __("If this is your first time contacting us or you've lost the ticket number, please %s open a new ticket %s"),
        '<a href="open.php">','</a>');
} ?>
</p> -->
