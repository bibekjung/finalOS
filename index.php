<?php

require('client.inc.php');
require_once INCLUDE_DIR . 'class.page.php';

$section = 'home';
require(CLIENTINC_DIR.'header.inc.php');
?>
<div id="landing_page">
<?php include CLIENTINC_DIR.'templates/sidebar.tmpl.php'; ?>
<div class="main-content">
<?php
if ($cfg && $cfg->isKnowledgebaseEnabled()) { ?>
<div class="search-form">
    <form method="get" action="kb/faq.php">
        <input type="hidden" name="a" value="search"/>
        <input type="text" name="q" class="search" placeholder="<?php echo __('Search our knowledge base'); ?>"/>
        <button type="submit" class="green button"><?php echo __('Search'); ?></button>
    </form>
</div>
<?php } ?>

<div class="thread-body">
    <div style="max-width:900px;margin:0 auto;padding:30px;background:#fff;border-radius:12px;box-shadow:0 3px 10px rgba(0,0,0,0.1);font-family:Arial, sans-serif;">
        <h1 style="font-size:28px;color:#0a4b78;margin-bottom:20px;text-align:center;">
            Welcome to CIB Nepal - Credit Information Bureau
        </h1>
        <p style="font-size:16px;line-height:1.6;color:#444;text-align:justify;margin-bottom:30px;">
            Welcome to the official Customer Portal of the Credit Information Bureau (CIB) Nepal.  
            Our mission is to provide accurate, reliable, and secure credit information services 
            to financial institutions, businesses, and individuals across Nepal.  
            Through this portal, you can conveniently submit requests, track their progress, 
            and access updates in a transparent manner.  
            Each request is assigned a unique ticket number, allowing you to monitor responses 
            and maintain a complete record of your interactions with us.  
            Please ensure you have a valid email address to initiate a ticket.
        </p>

       
    </div>


</div>
</div>
<div class="clear"></div>

<div>
<?php
if($cfg && $cfg->isKnowledgebaseEnabled()){
    $cats = Category::getFeatured();
    if ($cats->all()) { ?>
        <br/><br/>
        <h1><?php echo __('Featured Knowledge Base Articles'); ?></h1>
    <?php }
    foreach ($cats as $C) { ?>
        <div class="featured-category front-page">
            <i class="icon-folder-open icon-2x"></i>
            <div class="category-name">
                <?php echo $C->getName(); ?>
            </div>
            <?php foreach ($C->getTopArticles() as $F) { ?>
                <div class="article-headline">
                    <div class="article-title"><a href="<?php echo ROOT_PATH; ?>kb/faq.php?id=<?php echo $F->getId(); ?>"><?php echo $F->getQuestion(); ?></a></div>
                    <div class="article-teaser"><?php echo $F->getTeaser(); ?></div>
                </div>
            <?php } ?>
        </div>
    <?php }
}
?>
</div>
</div>

<?php require(CLIENTINC_DIR.'footer.inc.php'); ?>
