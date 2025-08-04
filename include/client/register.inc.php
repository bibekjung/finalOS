<?php
$info = $_POST;
if (!isset($info['timezone'])) {
    $info += array('backend' => null);
}

if (isset($user) && $user instanceof ClientCreateRequest) {
    $bk = $user->getBackend();
    $info = array_merge($info, array(
        'backend' => $bk->getBkId(),
        'username' => $user->getUsername(),
    ));
}

$info = Format::htmlchars(($errors && $_POST) ? $_POST : $info);
?>

<div style="
    width: 100%;
    min-height: 600px;
    background: #f5f5f5;
    /* padding: 5vh 5vw; */
    box-sizing: border-box;
    overflow-y: auto;
    font-family: 'Segoe UI', sans-serif;
">
    <div style="
        max-width: 800px;
        margin: 0 auto;
        background: #fff;
        padding: 20px 20px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        color: #333;
    ">
        <h1 style="font-size: 18px; font-weight: 700; text-align: center; margin-bottom: 10px; color: #2196f3;">
            <?php echo __('Account Registration'); ?>
        </h1>
        <!-- <p style="text-align: center; font-size: 14px; margin-bottom: 25px;">
            <?php echo __('Use the forms below to create or update the information we have on file for your account'); ?>
        </p> -->

        <form action="account.php" method="post">
            <?php csrf_token(); ?>
            <input type="hidden" name="do" value="<?php echo Format::htmlchars($_REQUEST['do']
                ?: ($info['backend'] ? 'import' : 'create')); ?>" />

            <!-- Contact Info Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="font-weight: 500;">Email Address <span style="color: red;">*</span></label>
                    <input type="email" name="email" value="<?php echo $info['email']; ?>" required
                        style="width: 100%; padding: 5px; margin-top: 5px; border: 1px solid #ccc; border-radius: 6px;" />
                </div>
                <div>
                    <label style="font-weight: 500;">Full Name <span style="color: red;">*</span></label>
                    <input type="text" name="name" value="<?php echo $info['name']; ?>" required
                        style="width: 100%; padding: 5px; margin-top: 5px; border: 1px solid #ccc; border-radius: 6px;" />
                </div>
                <div>
                    <label style="font-weight: 500;">Phone Number</label>
                    <input type="tel" name="phone" value="<?php echo $info['phone']; ?>"
                        style="width: 100%; padding: 5px; margin-top: 5px; border: 1px solid #ccc; border-radius: 6px;" />
                </div>
                <div style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label style="font-weight: 500;">Ext:</label>
                        <input type="text" name="ext" value="<?php echo $info['ext']; ?>"
                            style="width: 100%; padding: 5px; margin-top: 5px; border: 1px solid #ccc; border-radius: 6px;" />
                    </div>
                </div>
            </div>

            <!-- Preferences -->
            <div style="margin-top: 20px; margin-bottom: 10px;">
                <h3 style="font-size: 18px; font-weight: 600; border-bottom: 1px solid #ddd; padding-bottom: 6px;">
                    <?php echo __('Preferences'); ?>
                </h3>
            </div>

            <div style="margin: 15px 0;">
                <label style="font-weight: 500;"><?php echo __('Time Zone'); ?>:</label>
                <div style="margin-top: 5px;">
                    <?php
                    $TZ_NAME = 'timezone';
                    $TZ_TIMEZONE = $info['timezone'];
                    include INCLUDE_DIR . 'staff/templates/timezone.tmpl.php';
                    ?>
                </div>
                <div style="color: #e53935; font-size: 9px;"><?php echo $errors['timezone']; ?></div>
            </div>

            <!-- Credentials -->
            <div style="margin-top: 25px; margin-bottom: 10px;">
                <h3 style="font-size: 18px; font-weight: 600; border-bottom: 1px solid #ddd; padding-bottom: 6px;">
                    <?php echo __('Access Credentials'); ?>
                </h3>
            </div>

            <?php if ($info['backend']) { ?>
                <div style="margin: 15px 0;">
                    <label style="font-weight: 500;"><?php echo __('Login With'); ?>:</label><br>
                    <input type="hidden" name="backend" value="<?php echo $info['backend']; ?>"/>
                    <input type="hidden" name="username" value="<?php echo $info['username']; ?>"/>
                    <span style="margin-top: 4px; display: inline-block; font-size: 14px;">
                    <?php foreach (UserAuthenticationBackend::allRegistered() as $bk) {
                        if ($bk->getBkId() == $info['backend']) {
                            echo $bk->getName();
                            break;
                        }
                    } ?>
                    </span>
                </div>
            <?php } else { ?>
                <div style="margin: 15px 0;">
                    <label style="font-weight: 500;"><?php echo __('Create a Password'); ?>:</label><br>
                    <input type="password" name="passwd1" maxlength="128" value="<?php echo $info['passwd1']; ?>"
                        style="width: 100%; padding: 5px; border: 1px solid #ccc; border-radius: 6px; margin-top: 5px;" />
                    <div style="color: #e53935; font-size: 13px;"><?php echo $errors['passwd1']; ?></div>
                </div>

                <div style="margin: 15px 0;">
                    <label style="font-weight: 500;"><?php echo __('Confirm New Password'); ?>:</label><br>
                    <input type="password" name="passwd2" maxlength="128" value="<?php echo $info['passwd2']; ?>"
                        style="width: 100%; padding: 5px; border: 1px solid #ccc; border-radius: 6px; margin-top: 5px;" />
                    <div style="color: #e53935; font-size: 13px;"><?php echo $errors['passwd2']; ?></div>
                </div>
            <?php } ?>

            <!-- Buttons -->
            <div style="margin-top: 30px; text-align: center;">
                <input type="submit" value="<?php echo __('Register'); ?>"
                    style="background-color: #4CAF50; color: white; padding: 5px 8px; font-size: 15px; border: none; border-radius: 6px; cursor: pointer; margin-right: 10px;" />
                <input type="button" value="<?php echo __('Cancel'); ?>"
                    onclick="window.location.href='index.php';"
                    style="background-color: #f44336; color: white; padding: 5px 8px; font-size: 15px; border: none; border-radius: 6px; cursor: pointer;" />
            </div>
        </form>
    </div>
</div>

<?php if (!isset($info['timezone'])) { ?>
<script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/jstz.min.js?53339df"></script>
<script type="text/javascript">
    $(function () {
        var zone = jstz.determine();
        $('#timezone-dropdown').val(zone.name()).trigger('change');
    });
</script>
<?php } ?>
