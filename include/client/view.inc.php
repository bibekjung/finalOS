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

<style>
.ticket-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.ticket-header {
  background: rgba(223, 218, 218, 0.2);
  color: black;
  padding: 25px;
  border-radius: 12px;
  margin-bottom: 25px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.ticket-header h1 {
  margin: 0;
  font-size: 24px;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.ticket-header .ticket-number {
  background: rgba(255,255,255,0.2);
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 14px;
  font-weight: 500;
}

.ticket-header .ticket-actions {
  display: flex;
  gap: 10px;
}

.ticket-header .action-button {
  background: rgba(255,255,255,0.2);
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 13px;
  text-decoration: none;
  transition: all 0.3s ease;
}

.ticket-header .action-button:hover {
  background: rgba(142, 80, 80, 0.3);
  transform: translateY(-1px);
}

.info-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 20px;
  margin-bottom: 25px;
}

.info-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
  overflow: hidden;
  transition: all 0.3s ease;
}

.info-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 20px rgba(0,0,0,0.12);
}

.card-header {
      background: #e6e7e7;
  padding: 15px 20px;
  border-bottom: 1px solid #e9ecef;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: space-between;
  transition: background 0.3s ease;
}

.card-header:hover {
  background: #e9ecef;
}

.card-header h3 {
  margin: 0;
  font-size: 16px;
  font-weight: 600;
    background: #e6e7e7
}

.card-header .toggle-icon {
  font-size: 18px;
  color: #6c757d;
  transition: transform 0.3s ease;
}

.card-header.collapsed .toggle-icon {
  transform: rotate(-90deg);
}

.card-content {
  padding: 20px;
  display: none;
}

.card-content.expanded {
  display: block;
}

.info-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 0;
  border-bottom: 1px solid #f1f3f4;
}

.info-row:last-child {
  border-bottom: none;
}

.info-label {
  font-weight: 600;
  color: #495057;
  font-size: 14px;
}

.info-value {
  color: #6c757d;
  font-size: 14px;
  text-align: right;
}

.chat-section {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
  overflow: hidden;
  margin-bottom: 25px;
}

.chat-header {
    background: rgba(217, 210, 210, 0.2);
  color: black;
  padding: 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.chat-header h2 {
  margin: 0;
  font-size: 18px;
  font-weight: 600;
}

.chat-container {
  max-height: 600px;
  overflow-y: auto;
  padding: 20px;
  background: #f8f9fa;
  border-radius: 12px;
  border: 1px solid #e9ecef;
}

/* Scrollbar styling for chat container */
.chat-container::-webkit-scrollbar {
  width: 6px;
}

.chat-container::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.chat-container::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.chat-container::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

/* Responsive design for mobile */
@media (max-width: 768px) {
  .message-container {
    gap: 8px;
  }
  
  .message-avatar {
    width: 32px;
    height: 32px;
    font-size: 14px;
  }
  
  .message-body {
    max-width: 85%;
    padding: 10px 12px;
  }
  
  .sender-name {
    font-size: 12px;
  }
  
  .message-time {
    font-size: 10px;
  }
  
  .message-text {
    font-size: 13px;
  }
}

/* Modern Chat-like Message Styling */
.thread-entry {
  margin-bottom: 20px;
  padding: 0;
  background: transparent;
  position: relative;
}

.message-container {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  max-width: 100%;
}

.message-avatar {
  flex-shrink: 0;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #ffd700;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  border: 2px solid #f0f0f0;
  overflow: hidden;
}

.message-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 50%;
}

.default-avatar {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
}

.staff-avatar {
  background: #e3f2fd;
  color: #1976d2;
}

.client-avatar {
  background: #f3e5f5;
  color: #7b1fa2;
}

.message-content {
  flex: 1;
  min-width: 0;
}

.message-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 6px;
  font-size: 12px;
}

.sender-name {
  font-weight: 600;
  color: #333;
  font-size: 13px;
}

.message-time {
  color: #999;
  font-size: 11px;
  margin-left: 8px;
}

.message-body {
  background: #ffffff;
  border-radius: 18px;
  padding: 12px 16px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  position: relative;
  max-width: 75%;
}

/* Client messages - different background */
.client-message .message-body {
  background: #e3f2fd;
  margin-right: auto;
}

/* Staff messages - white background */
.staff-message .message-body {
  background: #ffffff;
  margin-left: auto;
}

.message-text {
  color: #333;
  line-height: 1.4;
  font-size: 14px;
  word-wrap: break-word;
}

.message-text p {
  margin: 0 0 8px 0;
}

.message-text p:last-child {
  margin-bottom: 0;
}

.message-attachments {
  margin-top: 8px;
  padding-top: 8px;
  border-top: 1px solid #f0f0f0;
}

.attachment-item {
  display: flex;
  align-items: center;
  gap: 6px;
  margin-bottom: 4px;
  font-size: 12px;
}

.attachment-item:last-child {
  margin-bottom: 0;
}

.attachment-item i {
  color: #666;
  font-size: 12px;
}

.attachment-link {
  color: #0066cc;
  text-decoration: none;
  font-size: 12px;
}

.attachment-link:hover {
  text-decoration: underline;
  color: #0052cc;
}

.filesize {
  color: #999;
  font-size: 11px;
}

.edit-indicator {
  margin-top: 6px;
}

.edit-badge {
  background: #f0f0f0;
  color: #666;
  padding: 2px 6px;
  border-radius: 10px;
  font-size: 10px;
  font-weight: 500;
}



/* Thread event styling */
.thread-event {
    padding: 8px 12px;
    margin: 10px 0;
    font-size: 11px;
    color: #666;
    text-align: center;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.thread-event .type-icon {
    margin-right: 6px;
}

.thread-event .description {
    font-style: italic;
}

#ticketThread {
    z-index: 0;
    position: relative;
}

.reply-form-container {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
  overflow: hidden;
  margin-top: 25px;
}

.reply-form-header {
  background: #f8f9fa;
  padding: 20px;
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
  border-radius: 8px;
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
  border-radius: 8px;
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

.reply-form-actions {
  background: #f8f9fa;
  padding: 20px;
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
  padding: 12px 24px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn-save-draft:hover {
  background: #5a6268;
  transform: translateY(-1px);
}

.btn-submit {
  background: #e74c3c;
  color: #fff;
  border: none;
  padding: 12px 24px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn-submit:hover {
  background: #c0392b;
  transform: translateY(-1px);
}

.warning-banner {
  background: #fff3cd;
  border: 1px solid #ffeaa7;
  color: #856404;
  padding: 15px 20px;
  border-radius: 8px;
  margin: 20px;
  font-size: 14px;
}

.status-badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
}

.status-open {
  background: #d4edda;
  color: #155724;
}

.status-closed {
  background: #f8d7da;
  color: #721c24;
}

.status-pending {
  background: #fff3cd;
  color: #856404;
}
</style>

<div class="ticket-container">
  <!-- Ticket Header -->
  <div class="ticket-header">
    <h1>
      <div>
        <a href="tickets.php?id=<?php echo $ticket->getId(); ?>" title="<?php echo __('Reload'); ?>" style="color: white; text-decoration: none; margin-right: 15px;">
          <i class="refresh icon-refresh"></i>
        </a>
        <?php $subject_field = TicketForm::getInstance()->getField('subject');
            echo $subject_field->display($ticket->getSubject()); ?>
      </div>
      <div class="ticket-actions">
        <span class="ticket-number">#<?php echo $ticket->getNumber(); ?></span>
        <a class="action-button" href="tickets.php?a=print&id=<?php echo $ticket->getId(); ?>">
          <i class="icon-print"></i> <?php echo __('Print'); ?>
        </a>
        <?php if ($ticket->hasClientEditableFields() && $thisclient->getId() == $ticket->getUserId()) { ?>
          <a class="action-button" href="tickets.php?a=edit&id=<?php echo $ticket->getId(); ?>">
            <i class="icon-edit"></i> <?php echo __('Edit'); ?>
          </a>
        <?php } ?>
      </div>
    </h1>
  </div>

  <!-- Information Cards -->
  <div class="info-cards">
    <!-- Basic Ticket Information Card -->
    <div class="info-card">
      <div class="card-header" onclick="toggleCard(this)">
        <h3><?php echo __('Basic Ticket Information'); ?></h3>
        <span class="toggle-icon">▼</span>
      </div>
      <div class="card-content expanded">
        <div class="info-row">
          <span class="info-label"><?php echo __('Status');?>:</span>
          <span class="info-value">
            <?php 
            $status = $ticket->getStatus();
            $statusClass = 'status-open';
            if ($status) {
              $statusName = $status->getLocalName();
              if (strpos(strtolower($statusName), 'closed') !== false) {
                $statusClass = 'status-closed';
              } elseif (strpos(strtolower($statusName), 'pending') !== false) {
                $statusClass = 'status-pending';
              }
            }
            ?>
            <span class="status-badge <?php echo $statusClass; ?>">
              <?php echo $status ? $status->getLocalName() : ''; ?>
            </span>
          </span>
        </div>
        <div class="info-row">
          <span class="info-label"><?php echo __('Department');?>:</span>
          <span class="info-value"><?php echo Format::htmlchars($dept instanceof Dept ? $dept->getName() : ''); ?></span>
        </div>
        <div class="info-row">
          <span class="info-label"><?php echo __('Create Date');?>:</span>
          <span class="info-value"><?php echo Format::datetime($ticket->getCreateDate()); ?></span>
        </div>
      </div>
    </div>

    <!-- User Information Card -->
    <div class="info-card">
      <div class="card-header" onclick="toggleCard(this)">
        <h3><?php echo __('User Information'); ?></h3>
        <span class="toggle-icon">▼</span>
      </div>
      <div class="card-content expanded">
        <div class="info-row">
          <span class="info-label"><?php echo __('Name');?>:</span>
          <span class="info-value"><?php echo mb_convert_case(Format::htmlchars($ticket->getName()), MB_CASE_TITLE); ?></span>
        </div>
        <div class="info-row">
          <span class="info-label"><?php echo __('Email');?>:</span>
          <span class="info-value"><?php echo Format::htmlchars($ticket->getEmail()); ?></span>
        </div>
        <div class="info-row">
          <span class="info-label"><?php echo __('Phone');?>:</span>
          <span class="info-value"><?php echo $ticket->getPhoneNumber(); ?></span>
        </div>
      </div>
    </div>

    <!-- Post Ticket Information Card -->
  
  </div>

  <!-- Chat/Thread Section -->
  <div class="chat-section">
    <div class="chat-header">
      <h2><?php echo __('Ticket Conversation'); ?></h2>
    </div>
    <div class="chat-container">
      <?php
        $email = $thisclient->getUserName();
        $clientId = TicketUser::lookupByEmail($email)->getId();

        $ticket->getThread()->render(array('M', 'R', 'user_id' => $clientId), array(
                        'mode' => Thread::MODE_CLIENT,
                        'html-id' => 'ticketThread')
                    );
      ?>
    </div>
  </div>

  <!-- Reply Form -->
  <?php if ($blockReply = $ticket->isChild() && $ticket->getMergeType() != 'visual')
      $warn = sprintf(__('This Ticket is Merged into another Ticket. Please go to the %s%d%s to reply.'),
          '<a href="tickets.php?id=', $ticket->getPid(), '" style="text-decoration:underline">Parent</a>');
  ?>

  <div class="clear" style="padding-bottom:10px;"></div>
  <?php if($errors['err']) { ?>
      <div id="msg_error"><?php echo $errors['err']; ?></div>
  <?php }elseif($msg) { ?>
      <div id="msg_notice"><?php echo $msg; ?></div>
  <?php }elseif($warn) { ?>
      <div id="msg_warning"><?php echo $warn; ?></div>
  <?php }
  if ((!$ticket->isClosed() || $ticket->isReopenable()) && !$blockReply) { ?>

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
  <?php } ?>
</div>

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

// Toggle card sections
function toggleCard(header) {
    const content = header.nextElementSibling;
    const icon = header.querySelector('.toggle-icon');
    
    if (content.classList.contains('expanded')) {
        content.classList.remove('expanded');
        header.classList.add('collapsed');
    } else {
        content.classList.add('expanded');
        header.classList.remove('collapsed');
    }
}

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
          console && console.warn && console.warn('Save Draft requires Rich Text editor with draft plugin enabled.');
        }
      }
    }, false);
  }
})();

// Chat functionality
(function() {
  // Auto-scroll to bottom of chat
  function scrollToBottom() {
    var chatContainer = document.querySelector('.chat-container');
    if (chatContainer) {
      chatContainer.scrollTop = chatContainer.scrollHeight;
    }
  }

  // Add fade-in animation to new messages
  function addMessageAnimation() {
    var messages = document.querySelectorAll('.thread-entry');
    messages.forEach(function(message, index) {
      message.style.opacity = '0';
      message.style.transform = 'translateY(20px)';
      message.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
      
      setTimeout(function() {
        message.style.opacity = '1';
        message.style.transform = 'translateY(0)';
      }, index * 100);
    });
  }

  // Initialize chat functionality
  function initChat() {
    scrollToBottom();
    addMessageAnimation();
  }

  // Run when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initChat);
  } else {
    initChat();
  }

  // Auto-scroll when new content is loaded (for AJAX updates)
  var observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
        setTimeout(scrollToBottom, 100);
      }
    });
  });

  var chatContainer = document.querySelector('.chat-container');
  if (chatContainer) {
    observer.observe(chatContainer, { childList: true, subtree: true });
  }
})();
</script>
