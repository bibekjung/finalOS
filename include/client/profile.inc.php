
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo __('Manage Your Profile Information'); ?></title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      color: #333;
    }

    .profile-wrapper {
      max-width: 1200px;
      margin: 40px auto;
      padding: 20px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    .profile-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .profile-header h1 {
      margin-bottom: 10px;
      font-size: 26px;
    }

    .card-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 20px;
    }

    .card {
      background: #fafafa;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 20px;
      flex: 1 1 48%;
      min-width: 300px;
    }

    .preferences-card {
      flex: 1 1 100%;
    }

    .card h3 {
      margin-top: 0;
      border-bottom: 1px solid #ddd;
      padding-bottom: 8px;
      margin-bottom: 20px;
      color: #444;
    }

    .form-row {
      margin-bottom: 16px;
    }

    .form-row label {
      font-weight: bold;
      margin-bottom: 6px;
      display: block;
    }

    .form-row input[type="text"],
    .form-row input[type="password"],
    .form-row select {
      width: 100%;
      padding: 10px;
      font-size: 14px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    .form-row .error {
      color: red;
      font-size: 12px;
      margin-top: 5px;
    }

    .form-actions {
      text-align: center;
      margin-top: 30px;
    }

    .form-actions input {
      padding: 10px 20px;
      margin: 0 10px;
      font-size: 14px;
      border-radius: 4px;
      border: 1px solid #ccc;
      background: #eee;
      cursor: pointer;
    }

    .form-actions input:hover {
      background: #ddd;
    }
  </style>
</head>
<body>

<div class="profile-wrapper">
  <div class="profile-header">
    <h1><?php echo __('Manage Your Profile Information'); ?></h1>
    <p><?php echo __('Use the forms below to update the information we have on file for your account'); ?></p>
  </div>

  <form action="profile.php" method="post">
    <?php csrf_token(); ?>

    <div class="card-grid">
      <!-- Contact Information -->
      <div class="card">
        <?php
        foreach ($user->getForms() as $f) {
          $f->render(['staff' => false]);
        }
        ?>
      </div>

      <!-- Access Credentials -->
      <?php if ($acct = $thisclient->getAccount()) {
        $info = $acct->getInfo();
        $info = Format::htmlchars(($errors && $_POST) ? $_POST : $info);
        if ($acct->isPasswdResetEnabled()) { ?>
          <div class="card">
            <h3><?php echo __('Access Credentials'); ?></h3>
            <?php if (!isset($_SESSION['_client']['reset-token'])) { ?>
              <div class="form-row">
                <label><?php echo __('Current Password'); ?></label>
                <input type="password" name="cpasswd" value="<?php echo $info['cpasswd']; ?>">
                <div class="error"><?php echo $errors['cpasswd']; ?></div>
              </div>
            <?php } ?>
            <div class="form-row">
              <label><?php echo __('New Password'); ?></label>
              <input type="password" name="passwd1" value="<?php echo $info['passwd1']; ?>">
              <div class="error"><?php echo $errors['passwd1']; ?></div>
            </div>
            <div class="form-row">
              <label><?php echo __('Confirm New Password'); ?></label>
              <input type="password" name="passwd2" value="<?php echo $info['passwd2']; ?>">
              <div class="error"><?php echo $errors['passwd2']; ?></div>
            </div>
          </div>
      <?php }
      } ?>

      <!-- Preferences -->
      <?php if ($acct) { ?>
        <div class="card preferences-card">
          <h3><?php echo __('Preferences'); ?></h3>

          <!-- Timezone -->
          <div class="form-row">
            <label><?php echo __('Time Zone'); ?></label>
            <?php
            $TZ_NAME = 'timezone';
            $TZ_TIMEZONE = $info['timezone'];
            include INCLUDE_DIR . 'staff/templates/timezone.tmpl.php';
            ?>
            <div class="error"><?php echo $errors['timezone']; ?></div>
          </div>

          <!-- Language -->
          <?php if ($cfg->getSecondaryLanguages()) { ?>
            <div class="form-row">
              <label><?php echo __('Preferred Language'); ?></label>
              <select name="lang">
                <option value="">&mdash; <?php echo __('Use Browser Preference'); ?> &mdash;</option>
                <?php
                $langs = Internationalization::getConfiguredSystemLanguages();
                foreach ($langs as $l) {
                  $selected = ($info['lang'] == $l['code']) ? 'selected="selected"' : '';
                ?>
                  <option value="<?php echo $l['code']; ?>" <?php echo $selected; ?>>
                    <?php echo Internationalization::getLanguageDescription($l['code']); ?>
                  </option>
                <?php } ?>
              </select>
              <div class="error"><?php echo $errors['lang']; ?></div>
            </div>
          <?php } ?>
        </div>
      <?php } ?>
    </div>

    <!-- Submit / Reset / Cancel -->
    <div class="form-actions">
      <input type="submit" value="<?php echo __('Update'); ?>" />
      <input type="reset" value="<?php echo __('Reset'); ?>" />
      <input type="button" value="<?php echo __('Cancel'); ?>" onclick="window.location.href='index.php';" />
    </div>
  </form>
</div>

</body>
</html>
