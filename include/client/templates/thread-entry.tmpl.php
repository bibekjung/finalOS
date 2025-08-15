<?php
global $cfg;
$entryTypes = ThreadEntry::getTypes();
$user = $entry->getUser() ?: $entry->getStaff();
if ($entry->staff && $cfg->hideStaffName())
    $name = __('Staff');
else
    $name = $user ? $user->getName() : $entry->poster;

// Determine if this is a client message or staff message
$isClientMessage = ($entry->type == 'M' && !$entry->staff);
$isStaffMessage = ($entry->staff || $entry->type == 'R');

// Get avatar
$avatar = '';
if ($cfg->isAvatarsEnabled() && $user) {
    $avatar = $user->getAvatar();
} else {
    // Default avatar based on message type
    if ($isStaffMessage) {
        $avatar = '<div class="default-avatar staff-avatar">👨‍💼</div>';
    } else {
        $avatar = '<div class="default-avatar client-avatar">👤</div>';
    }
}

// Format timestamp
$timestamp = Format::datetime($entry->created);
$relativeTime = Format::relativeTime(Misc::db2gmtime($entry->created));

$type = $entryTypes[$entry->type];
?>
<div class="thread-entry <?php echo $type; ?> <?php echo $isClientMessage ? 'client-message' : 'staff-message'; ?> <?php if ($avatar) echo 'avatar'; ?>">
    <div class="message-container">
        <!-- Avatar -->
        <div class="message-avatar">
            <?php echo $avatar; ?>
        </div>
        
        <!-- Message Content -->
        <div class="message-content">
            <!-- Message Header -->
            <div class="message-header">
                <span class="sender-name"><?php echo Format::htmlchars($name); ?></span>
                <span class="message-time" title="<?php echo Format::daydatetime($entry->created); ?>">
                    <?php echo $relativeTime; ?>
                </span>
            </div>
            
            <!-- Message Body -->
            <div class="message-body" id="thread-id-<?php echo $entry->getId(); ?>">
                <div class="message-text"><?php echo $entry->getBody()->toHtml(); ?></div>
                
                <!-- Attachments -->
                <?php if ($entry->has_attachments) { ?>
                <div class="message-attachments">
                    <?php foreach ($entry->attachments as $A) {
                        if ($A->inline) continue;
                        $size = '';
                        if ($A->file->size)
                            $size = sprintf('<small class="filesize">%s</small>', Format::file_size($A->file->size));
                    ?>
                    <div class="attachment-item">
                        <i class="icon-paperclip"></i>
                        <a class="attachment-link" 
                           href="<?php echo $A->file->getDownloadUrl(['id' => $A->getId()]); ?>"
                           download="<?php echo Format::htmlchars($A->getFilename()); ?>"
                           target="_blank">
                            <?php echo Format::htmlchars($A->getFilename()); ?>
                        </a>
                        <?php echo $size; ?>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
                
                <!-- Edit indicator -->
                <?php if ($entry->flags & ThreadEntry::FLAG_EDITED) { ?>
                <div class="edit-indicator">
                    <span class="edit-badge" title="<?php echo sprintf(__('Edited on %s by %s'), Format::datetime($entry->updated), 'You'); ?>">
                        <?php echo __('Edited'); ?>
                    </span>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php if ($urls = $entry->getAttachmentUrls()) { ?>
<script type="text/javascript">
    $('#thread-id-<?php echo $entry->getId(); ?>')
        .data('urls', <?php echo JsonDataEncoder::encode($urls); ?>)
        .data('id', <?php echo $entry->getId(); ?>);
</script>
<?php } ?>
