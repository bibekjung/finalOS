<?php
if(!defined('OSTCLIENTINC') || !$thisclient || !$ticket || !$ticket->checkUserAccess($thisclient)) die('Access Denied!');

$info=($_POST && $errors)?Format::htmlchars($_POST):array();

$type = array('type' => 'viewed');
Signal::send('object.view', $ticket, $type);

$dept = $ticket->getDept();

if ($ticket->isClosed() && !$ticket->isReopenable())
    $warn = sprintf(__('%s is marked as closed and cannot be reopened.'), __('This ticket'));

//Making sure we don't leak out internal dept names
if(!$dept || !$dept->isPublic())
    $dept = $cfg->getDefaultDept();

if ($thisclient && $thisclient->isGuest()
    && $cfg->isClientRegistrationEnabled()) { ?>

<div id="msg_info">
    <i class="icon-compass icon-2x pull-left"></i>
    <strong><?php echo __('Looking for your other tickets?'); ?></strong><br />
    <a href="<?php echo ROOT_PATH; ?>login.php?e=<?php
        echo urlencode($thisclient->getEmail());
    ?>" style="text-decoration:underline"><?php echo __('Sign In'); ?></a>
    <?php echo sprintf(__('or %s register for an account %s for the best experience on our help desk.'),
        '<a href="account.php?do=create" style="text-decoration:underline">','</a>'); ?>
    </div>

<?php } ?>

<table width="800" cellpadding="1" cellspacing="0" border="0" id="ticketInfo">
    <tr>
        <td colspan="2" width="100%">
            <h1>
                <a href="tickets.php?id=<?php echo $ticket->getId(); ?>" title="<?php echo __('Reload'); ?>"><i class="refresh icon-refresh"></i></a>
                <b>
                <?php $subject_field = TicketForm::getInstance()->getField('subject');
                    echo $subject_field->display($ticket->getSubject()); ?>
                </b>
                <small>#<?php echo $ticket->getNumber(); ?></small>
<div class="pull-right">
      <a class="action-button" href="tickets.php?a=print&id=<?php
          echo $ticket->getId(); ?>"><i class="icon-print"></i> <?php echo __('Print'); ?></a>

<?php if ($ticket->hasClientEditableFields()
        // Only ticket owners can edit the ticket details (and other forms)
        && $thisclient->getId() == $ticket->getUserId()) { ?>
                <a class="action-button" href="tickets.php?a=edit&id=<?php
                     echo $ticket->getId(); ?>"><i class="icon-edit"></i> <?php echo __('Edit'); ?></a>
<?php } ?>
</div>
            </h1>
        </td>
    </tr>
    <tr>
        <td width="50%">
            <table class="infoTable" cellspacing="1" cellpadding="3" width="100%" border="0">
                <thead>
                    <tr><td class="headline" colspan="2" text-align="center">
                        <?php echo __('Basic Ticket Information'); ?>
                    </td></tr>
                </thead>
                <tr>
                    <th width="100"><?php echo __('Status');?>:</th>
                    <td><?php echo ($S = $ticket->getStatus()) ? $S->getLocalName() : ''; ?></td>
                </tr>
                <tr>
                    <th><?php echo __('Department');?>:</th>
                    <td><?php echo Format::htmlchars($dept instanceof Dept ? $dept->getName() : ''); ?></td>
                </tr>
                <tr>
                    <th><?php echo __('Create Date');?>:</th>
                    <td><?php echo Format::datetime($ticket->getCreateDate()); ?></td>
                </tr>
           </table>
       </td>
       <td width="50%">
           <table class="infoTable" cellspacing="1" cellpadding="3" width="100%" border="0">
                <thead>
                    <tr><td class="headline" colspan="2">
                        <?php echo __('User Information'); ?>
                    </td></tr>
                </thead>
               <tr>
                   <th width="100"><?php echo __('Name');?>:</th>
                   <td><?php echo mb_convert_case(Format::htmlchars($ticket->getName()), MB_CASE_TITLE); ?></td>
               </tr>
               <tr>
                   <th width="100"><?php echo __('Email');?>:</th>
                   <td><?php echo Format::htmlchars($ticket->getEmail()); ?></td>
               </tr>
               <tr>
                   <th><?php echo __('Phone');?>:</th>
                   <td><?php echo $ticket->getPhoneNumber(); ?></td>
               </tr>
            </table>
       </td>
    </tr>
    <tr>
        <td colspan="2">
<?php
$sections = $forms = array();
foreach (DynamicFormEntry::forTicket($ticket->getId()) as $i=>$form) {
    $answers = $form->getAnswers()->exclude(Q::any(array(
        'field__flags__hasbit' => DynamicFormField::FLAG_EXT_STORED,
        'field__name__in' => array('subject', 'priority'),
        Q::not(array('field__flags__hasbit' => DynamicFormField::FLAG_CLIENT_VIEW)),
    )));
    foreach ($answers as $j=>$a) {
        if ($v = $a->display())
            $sections[$i][$j] = array($v, $a);
    }
    $forms[$i] = $form->getTitle();
}
foreach ($sections as $i=>$answers) {
    ?>
        <table class="custom-data" cellspacing="0" cellpadding="4" width="100%" border="0">
        <tr><td colspan="2" class="headline flush-left"><?php echo $forms[$i]; ?></th></tr>
<?php foreach ($answers as $A) {
    list($v, $a) = $A; ?>
        <tr>
            <th><?php
echo $a->getField()->get('label');
            ?>:</th>
            <td><?php
echo $v;
            ?></td>
        </tr>
<?php } ?>
        </table>
    <?php
} ?>
    </td>
</tr>
</table>
<br>
<div class="ticket-chatthread">

  <?php
    $email = $thisclient->getUserName();
    $clientId = TicketUser::lookupByEmail($email)->getId();

    $ticket->getThread()->render(array('M', 'R', 'user_id' => $clientId), array(
                    'mode' => Thread::MODE_CLIENT,
                    'html-id' => 'ticketThread')
                );
if ($blockReply = $ticket->isChild() && $ticket->getMergeType() != 'visual')
    $warn = sprintf(__('This Ticket is Merged into another Ticket. Please go to the %s%d%s to reply.'),
        '<a href="tickets.php?id=', $ticket->getPid(), '" style="text-decoration:underline">Parent</a>');
  ?>



</div>















<div class="clear" style="padding-bottom:10px;"></div>
<?php if($errors['err']) { ?>
    <div id="msg_error"><?php echo $errors['err']; ?></div>
<?php }elseif($msg) { ?>
    <div id="msg_notice"><?php echo $msg; ?></div>
<?php }elseif($warn) { ?>
    <div id="msg_warning"><?php echo $warn; ?></div>
<?php }
if ((!$ticket->isClosed() || $ticket->isReopenable()) && !$blockReply) { ?>
<style>
.reply-form-container {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  margin: 20px 0;
  overflow: hidden;
}

.reply-form-header {
  background: #f8f9fa;
  padding: 15px 20px;
  border-bottom: 1px solid #e9ecef;
}

.reply-form-header h2 {
  margin: 0;
  color: #2c3e50;
  font-size: 18px;
  font-weight: 600;
}

.reply-form-content {
  display: flex;
  min-height: 400px;
}

.reply-comment-section {
  flex: 1;
  padding: 20px;
  border-right: 1px solid #e9ecef;
}

.reply-attachment-section {
  flex: 1;
  padding: 20px;
  background: #f8f9fa;
}

.section-label {
  display: flex;
  align-items: center;
  margin-bottom: 12px;
  font-weight: 600;
  color: #2c3e50;
  font-size: 14px;
}

.section-label .required {
  color: #e74c3c;
  margin-left: 4px;
}

.section-label .info-icon {
  margin-left: 8px;
  color: #6c757d;
  font-size: 12px;
}

.comment-textarea {
  width: 100%;
  min-height: 300px;
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-family: inherit;
  font-size: 14px;
  line-height: 1.5;
  resize: vertical;
  background: #fff;
}

.comment-textarea:focus {
  outline: none;
  border-color: #3498db;
  box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
}

.comment-note {
  margin-top: 8px;
  font-size: 12px;
  color: #6c757d;
  font-style: italic;
}

.attachment-zone {
  border: 2px dashed #ddd;
  border-radius: 6px;
  padding: 30px 20px;
  text-align: center;
  background: #fff;
  margin-bottom: 15px;
  min-height: 200px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  position: relative;
  transition: all 0.3s ease;
}

.attachment-zone:hover {
  border-color: #3498db;
  background: #f8f9ff;
}

.attachment-zone .attachment-drop-zone {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  cursor: pointer;
}

.upload-icon {
  font-size: 48px;
  color: #6c757d;
  margin-bottom: 15px;
}

.attachment-text {
  color: #6c757d;
  font-size: 13px;
  line-height: 1.4;
  max-width: 280px;
  margin-bottom: 15px;
}

.drag-text {
  color: #3498db;
  font-weight: 600;
  font-size: 14px;
  margin-bottom: 10px;
}

.attachment-zone .file-input {
  display: none;
}

.attachment-zone .file-input-label {
  background: #6c757d;
  color: #fff;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 13px;
  margin-top: 15px;
  transition: background 0.2s;
  display: inline-block;
}

.attachment-zone .file-input-label:hover {
  background: #5a6268;
}

.attachment-zone .attachments {
  margin-top: 15px;
  width: 100%;
  text-align: left;
}

.attachment-zone .attachments .attachment {
  background: #f8f9fa;
  border: 1px solid #e9ecef;
  border-radius: 4px;
  padding: 8px 12px;
  margin-bottom: 8px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 13px;
}

.attachment-zone .attachments .attachment .filename {
  color: #495057;
  flex: 1;
}

.attachment-zone .attachments .attachment .remove {
  color: #e74c3c;
  cursor: pointer;
  font-weight: bold;
  margin-left: 10px;
}

.attachment-zone .attachments .attachment .remove:hover {
  color: #c0392b;
}

.reply-form-actions {
  background: #f8f9fa;
  padding: 15px 20px;
  border-top: 1px solid #e9ecef;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.action-buttons {
  display: flex;
  gap: 10px;
}

.btn-cancel {
  color: #6c757d;
  text-decoration: none;
  font-size: 14px;
}

.btn-cancel:hover {
  color: #495057;
  text-decoration: underline;
}

.btn-save-draft {
  background: #6c757d;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  transition: background 0.2s;
}

.btn-save-draft:hover {
  background: #5a6268;
}

.btn-submit {
  background: #e74c3c;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  transition: background 0.2s;
}

.btn-submit:hover {
  background: #c0392b;
}

.warning-banner {
  background: #fff3cd;
  border: 1px solid #ffeaa7;
  color: #856404;
  padding: 12px 20px;
  border-radius: 4px;
  margin: 15px 20px;
  font-size: 14px;
}

/* Main Ticket Thread Message Container */
.thread-entry.message.avatar {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 14px;
    border: 1px solid #ddd;
    border-radius: 6px;
    margin-bottom: 12px;
    background-color: #fff;
    font-family: Arial, sans-serif;
}

/* Different background for alternating messages */
#thread-entry-1 {
    background-color: #f9fafc;
}

#thread-entry-2 {
    background-color: #fdfdfd;
}

/* Header inside message */


.thread-entry .header {
    padding: 8px 0.9em;
    /* border: 1px solid #ccc; */
    /* border-color: rgba(0, 0, 0, 0.2); */
    border-radius: 5px 5px 0 0;
}

.thread-entry.message.avatar header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 10px;
    color: #555;
    border-bottom: 1px solid #eee;
    padding-bottom: 4px;
}

/* Span in header (timestamp, author info) */
.thread-entry.message.avatar header span {
    font-size: 13px;
    color: red;
}

/* Main body div */
.thread-entry.message.avatar > div {
    padding-top: 6px;
    font-size: 12px;
    color: #333;
    line-height: 1;
}

/* Bold text inside messages */
.thread-entry.message.avatar b {
    color: #222;
    font-weight: 600;
}

/* Optional: hover effect for better UX */
.thread-entry.message.avatar:hover {
    border-color: #bbb;
    background-color: #fefefe;
}

</style>

<form id="reply" action="tickets.php?id=<?php echo $ticket->getId(); ?>#reply" name="reply" method="post" enctype="multipart/form-data">
    <?php csrf_token(); ?>
    <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
    <input type="hidden" name="a" value="reply">
    
    <div class="reply-form-container">
        <div class="reply-form-header">
            <h2><?php echo __('Post a Reply');?></h2>
        </div>
        
        <div class="reply-form-content">
            <!-- Comment Section -->
            <div class="reply-comment-section">
                <div class="section-label">
                    <?php echo __('COMMENT'); ?>
                    <span class="required">*</span>
                    <span class="info-icon" title="<?php echo __('Required field'); ?>">ⓘ</span>
                </div>
                <textarea name="<?php echo $messageField->getFormName(); ?>" id="message" 
                    class="comment-textarea <?php if ($cfg->isRichTextEnabled()) echo 'richtext'; ?> draft" 
                    placeholder="<?php echo __('Enter your message here...'); ?>"
                    <?php
                    list($draft, $attrs) = Draft::getDraftAndDataAttrs('ticket.client', $ticket->getId(), $info['message']);
                    echo $attrs; ?>><?php echo $draft ?: $info['message']; ?></textarea>
                <div class="comment-note">
                    <?php echo __('NOTE: The maximum characters system allow to be entered here is 8000.'); ?>
                </div>
                <?php if ($errors['message']) { ?>
                    <div style="color: #e74c3c; font-size: 13px; margin-top: 8px;"><?php echo $errors['message']; ?></div>
                <?php } ?>
            </div>
            
            <!-- Attachment Section -->
            <div class="reply-attachment-section">
                <div class="section-label">
                    <?php echo __('ATTACHMENT'); ?>
                </div>
                <div class="attachment-zone">
                    <div class="upload-icon">☁️</div>
                    <div class="drag-text">Drop files here or click to upload</div>
                    <div class="attachment-text">
                        <?php echo __('You specify the uploaded file\'s storage type by selecting it from the drop-down list. Files stored in \'Temporary Storage\' will be deleted once the ticket is closed. The option \'Keep the file\' means that the file will be retained after ticket closure. Virus samples and large files (>5MB) are always saved in temporary storage.'); ?>
                    </div>
                    <?php
                    if ($messageField->isAttachmentsEnabled()) {
                        print $attachments->render(array('client'=>true));
                    } ?>
                </div>
            </div>
        </div>
        
        <?php if ($ticket->isClosed() && $ticket->isReopenable()) { ?>
            <div class="warning-banner">
                <?php echo __('Ticket will be reopened on message post'); ?>
            </div>
        <?php } ?>
        
        <div class="reply-form-actions">
            <a href="javascript:history.go(-1)" class="btn-cancel">Cancel</a>
            <div class="action-buttons">
                <button type="button" class="btn-save-draft">Save Draft</button>
                <button type="submit" class="btn-submit">Submit</button>
            </div>
        </div>
    </div>
</form>
<?php
} ?>
<script type="text/javascript">
<?php
// Hover support for all inline images
$urls = array();
foreach (AttachmentFile::objects()->filter(array(
    'attachments__thread_entry__thread__id' => $ticket->getThreadId(),
    'attachments__inline' => true,
)) as $file) {
    $urls[strtolower($file->getKey())] = array(
        'download_url' => $file->getDownloadUrl(['type' => 'H']),
        'filename' => $file->name,
    );
} ?>
showImagesInline(<?php echo JsonDataEncoder::encode($urls); ?>);

// Hook up Save Draft button to Redactor draft plugin
(function() {
  function trySaveDraft() {
    var saved = false;
    if (window.jQuery) {
      try {
        jQuery('.richtext').each(function() {
          var redactor = jQuery(this).data('redactor');
          if (redactor && redactor.plugin && redactor.plugin.draft && typeof redactor.plugin.draft.saveDraft === 'function') {
            redactor.plugin.draft.saveDraft();
            saved = true;
          }
        });
      } catch (e) {
        // ignore
      }
    }
    return saved;
  }

  if (window.document) {
    document.addEventListener('click', function(ev) {
      var target = ev.target;
      if (target && target.classList && target.classList.contains('btn-save-draft')) {
        ev.preventDefault();
        var ok = trySaveDraft();
        if (!ok) {
          // Fallback note if rich text / draft plugin is not available
          // You can enable Rich Text in Admin Panel -> Settings -> Tickets
          console && console.warn && console.warn('Save Draft requires Rich Text editor with draft plugin enabled.');
        }
      }
    }, false);
  }
})();
</script>
