<?php
if(!defined('OSTCLIENTINC')) die('Access Denied!');
$info=array();
if($thisclient && $thisclient->isValid()) {
    $info=array('name'=>$thisclient->getName(),
                'email'=>$thisclient->getEmail(),
                'phone'=>$thisclient->getPhoneNumber());
}

$info=($_POST && $errors)?Format::htmlchars($_POST):$info;

$form = null;
if (!$info['topicId']) {
    if (array_key_exists('topicId',$_GET) && preg_match('/^\d+$/',$_GET['topicId']) && Topic::lookup($_GET['topicId']))
        $info['topicId'] = intval($_GET['topicId']);
    else
        $info['topicId'] = $cfg->getDefaultTopicId();
}

$forms = array();
if ($info['topicId'] && ($topic=Topic::lookup($info['topicId']))) {
    foreach ($topic->getForms() as $F) {
        if (!$F->hasAnyVisibleFields())
            continue;
        if ($_POST) {
            $F = $F->instanciate();
            $F->isValidForClient();
        }
        $forms[] = $F->getForm();
    }
}

?>
<div style="width: 100%; max-width: 700px;  min-height: 400px;  margin: 5px auto;background: white; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); font-family: 'Segoe UI', Arial, sans-serif;">

  <h1 style="font-size: 24px; margin-bottom: 10px; color: #2c3e50; text-align: center; font-weight: 600;">
    <?php echo __('Open a New Ticket here'); ?>
  </h1>

  <p style="font-size: 15px; margin-bottom: 4px; color: #7f8c8d; text-align: center; line-height: 1;">
    <?php echo __('Please fill in the form below to open a new ticket.'); ?>
  </p>

<form id="ticketForm" method="post" action="open.php" enctype="multipart/form-data" style="margin-top: 15px;">
  <?php csrf_token(); ?>
  <input type="hidden" name="a" value="open">
  <table id ='tableLogin' width="100%" cellpadding="10" cellspacing="0" border="0" style="border-collapse: collapse;">
    <tbody id = "tableBody">


<?php
        if (!$thisclient) {
            $uform = UserForm::getUserForm()->getForm($_POST);
            if ($_POST) $uform->isValid();
            // $uform->render(array('staff' => false, 'mode' => 'create'));
            ?>
            <!-- Modern Registration/Login Style Fields: Two-Column Layout -->
            <div class="modern-form-fields" style="display: flex; flex-direction: column;  margin-top: 2px;">
              <div class="form-row" style="display: flex; gap: 12px;">
                <div class="form-group" style="flex: 1;">
                  <label for="name" class="form-label">Name <span class="required">*</span></label>
                  <input type="text" id="name" name="name" value="<?php echo Format::htmlchars($info['name']); ?>" required
                    class="form-control" autocomplete="name">
                  <?php if ($errors['name']) { ?>
                    <div class="form-error"><?php echo $errors['name']; ?></div>
                  <?php } ?>
                </div>
                <div class="form-group" style="flex: 1;">
                  <label for="email" class="form-label">Email <span class="required">*</span></label>
                  <input type="email" id="email" name="email" value="<?php echo Format::htmlchars($info['email']); ?>" required
                    class="form-control" autocomplete="email">
                  <?php if ($errors['email']) { ?>
                    <div class="form-error"><?php echo $errors['email']; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-row" style="display: flex; gap: 12px;">
                <div class="form-group" style="flex: 1;">
                  <label for="phone" class="form-label">Phone Number</label>
                  <input type="text" id="phone" name="phone" value="<?php echo Format::htmlchars($info['phone']); ?>"
                    class="form-control" autocomplete="tel">
                  <?php if ($errors['phone']) { ?>
                    <div class="form-error"><?php echo $errors['phone']; ?></div>
                  <?php } ?>
                </div>
                <div class="form-group" style="flex: 1;">
                  <label for="ext" class="form-label">Extension</label>
                  <input type="text" id="ext" name="ext" value="<?php echo Format::htmlchars($info['ext']); ?>"
                    class="form-control">
                  <?php if ($errors['ext']) { ?>
                    <div class="form-error"><?php echo $errors['ext']; ?></div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <?php
        }
        else { ?>
            <tr><td colspan="2"></td></tr>
        <tr><td style="padding: 12px 15px; border-bottom: 1px solid #eee; font-weight: 500;"><?php echo __('Email'); ?>:</td><td style="padding: 12px 15px; border-bottom: 1px solid #eee;"><?php
            echo $thisclient->getEmail(); ?></td></tr>
        <tr><td style="padding: 12px 15px; border-bottom: 1px solid #eee; font-weight: 500;"><?php echo __('Client'); ?>:</td><td style="padding: 12px 15px; border-bottom: 1px solid #eee;"><?php
            echo Format::htmlchars($thisclient->getName()); ?></td></tr>
        <?php } ?>
    </tbody>
    <tbody>
    <tr><td colspan="2" style="padding-top: 20px;">
        <div style="font-size: 16px; font-weight: 600; color: #2c3e50; margin-bottom: 8px;">
        <?php echo __('Help Topic'); ?>
        </div>
    </td></tr>
    <tr>
        <td colspan="2" style="padding-bottom: 20px;">
            <select id="topicId" name="topicId" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; background-color: #f9f9f9;" onchange="javascript:
                    var data = $(':input[name]', '#dynamic-form').serialize();
                    $.ajax(
                      'ajax.php/form/help-topic/' + this.value,
                      {
                        data: data,
                        dataType: 'json',
                        success: function(json) {
                          $('#dynamic-form').empty().append(json.html);
                          $(document.head).append(json.media);
                        }
                      });">
                <option value="" selected="selected">&mdash; <?php echo __('Select a Help Topic');?> &mdash;</option>
                <?php
                if($topics=Topic::getPublicHelpTopics()) {
                    foreach($topics as $id =>$name) {
                        echo sprintf('<option value="%d" %s>%s</option>',
                                $id, ($info['topicId']==$id)?'selected="selected"':'', $name);
                    }
                } ?>
            </select>
            <div style="color: #e74c3c; font-size: 13px; margin-top: 5px;"><?php echo $errors['topicId']; ?></div>
        </td>
    </tr>
    </tbody>
    <tbody id="dynamic-form">
        <?php
        $options = array('mode' => 'create');
        foreach ($forms as $form) {
            include(CLIENTINC_DIR . 'templates/dynamic-form.tmpl.php');
        } ?>
    </tbody>
    <tbody>
    <?php
    if($cfg && $cfg->isCaptchaEnabled() && (!$thisclient || !$thisclient->isValid())) {
        if($_POST && $errors && !$errors['captcha'])
            $errors['captcha']=__('Please re-enter the text again');
        ?>
    <tr class="captchaRow">
        <td style="padding: 15px 15px 5px; font-weight: 500;" class="required"><?php echo __('CAPTCHA Text');?>:</td>
        <td style="padding: 15px 15px 5px;">
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                <span class="captcha"><img src="captcha.php" border="0" style="border: 1px solid #ddd; border-radius: 4px;"></span>
                <input id="captcha" type="text" name="captcha" size="6" autocomplete="off" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div style="color: #7f8c8d; font-size: 13px; margin-bottom: 5px;"><?php echo __('Enter the text shown on the image.');?></div>
            <div style="color: #e74c3c; font-size: 13px;"><?php echo $errors['captcha']; ?></div>
        </td>
    </tr>
    <?php
    } ?>
    <tr><td colspan=2>&nbsp;</td></tr>
    </tbody>
  </table>
  <div style="display: flex; justify-content: center; gap: 15px; margin-top: 30px;">
        <input type="submit" value="<?php echo __('Create Ticket');?>" style="padding: 10px 25px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 15px; transition: background 0.3s;" onmouseover="this.style.background='#2980b9'" onmouseout="this.style.background='#3498db'">
        <input type="reset" name="reset" value="<?php echo __('Reset');?>" style="padding: 10px 25px; background: #95a5a6; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 15px; transition: background 0.3s;" onmouseover="this.style.background='#7f8c8d'" onmouseout="this.style.background='#95a5a6'">
        <input type="button" name="cancel" value="<?php echo __('Cancel'); ?>" style="padding: 10px 25px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 15px; transition: background 0.3s;" onmouseover="this.style.background='#c0392b'" onmouseout="this.style.background='#e74c3c'" onclick="javascript:
            $('.richtext').each(function() {
                var redactor = $(this).data('redactor');
                if (redactor && redactor.opts.draftDelete)
                    redactor.plugin.draft.deleteDraft();
            });
            window.location.href='index.php';">
  </div>
</form>
</div>

