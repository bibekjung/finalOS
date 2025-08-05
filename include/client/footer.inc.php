        </div>
    </div>
    <div id="footer">
        <div class="footer-content">
            <div class="footer-section">
                <p>&copy; <?php echo date('Y'); ?> <?php echo __('Credit Information Bureau.'); ?></p>
                <p><?php echo __('All rights reserved.'); ?></p>
            </div>
            <div class="footer-section">
                <p><?php echo __('Need Help?'); ?> <a href="<?php echo ROOT_PATH; ?>kb/"><?php echo __('Knowledge Base'); ?></a></p>
            </div>
        </div>
    </div>
<div id="overlay"></div>
<div id="loading">
    <h4><?php echo __('Please Wait!');?></h4>
    <p><?php echo __('Please wait... it will take a second!');?></p>
</div>
<?php
if (($lang = Internationalization::getCurrentLanguage()) && $lang != 'en_US') { ?>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>ajax.php/i18n/<?php
        echo $lang; ?>/js"></script>
<?php } ?>
<script type="text/javascript">
    getConfig().resolve(<?php
        include INCLUDE_DIR . 'ajax.config.php';
        $api = new ConfigAjaxAPI();
        print $api->client(false);
    ?>);
</script>
</body>
</html>
