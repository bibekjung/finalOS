<?php

require('client.inc.php');
require_once INCLUDE_DIR . 'class.page.php';

$section = 'home';
require(CLIENTINC_DIR.'header.inc.php');
?>
<div id="landing_page" class="landing-page-full">
<style>
  /* Scoped styles for the landing page */
  .lp-container { max-width: 1100px; margin: 0 auto; padding: 24px 16px; }
  .lp-hero { display: grid; grid-template-columns: 1fr; gap: 24px; }
  .lp-welcome { background: #ffffff; border-radius: 12px; box-shadow: 0 3px 12px rgba(0,0,0,0.08); padding: 28px; }
  .lp-title { font-size: 28px; color: #0a4b78; margin: 0 0 12px 0; text-align: center; }
  .lp-text { font-size: 16px; line-height: 1.65; color: #444; text-align: justify; }
  .lp-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
  .lp-card { background: #ffffff; border-radius: 12px; box-shadow: 0 3px 12px rgba(0,0,0,0.08); padding: 22px; display: flex; flex-direction: column; gap: 14px; }
  .lp-card h3 { margin: 0; font-size: 18px; color: #1f2d3d; }
  .lp-card p { margin: 0; color: #4a5568; line-height: 1.6; font-size: 14px; }
  .lp-cta { margin-top: 6px; }
  .lp-btn { display: inline-block; width: 100%; text-align: center; padding: 12px 16px; border-radius: 8px; font-weight: 600; text-decoration: none; transition: transform .15s ease, opacity .15s ease; }
  .lp-btn:hover { transform: translateY(-1px); opacity: 0.95; }
  .lp-btn-primary { background: #1f7aec; color: #fff; }
  .lp-btn-secondary { background: #27ae60; color: #fff; }
  /* Search form spacing on landing */
  .search-form { margin-top: 18px; }
  @media (max-width: 640px) {
    .lp-title { font-size: 22px; }
  }
</style>
<div class="lp-container">
  <div class="lp-hero">
    <div class="lp-welcome">
      <h1 class="lp-title">Welcome to CIB Nepal - Credit Information Bureau</h1>
      <p class="lp-text">
        Welcome to the official Customer Portal of the Credit Information Bureau (CIB) Nepal. Our mission is to provide
        accurate, reliable, and secure credit information services to financial institutions, businesses, and individuals
        across Nepal. Through this portal, you can conveniently submit requests, track their progress, and access updates
        in a transparent manner. Each request is assigned a unique ticket number, allowing you to monitor responses and
        maintain a complete record of your interactions with us. Please ensure you have a valid email address to initiate
        a ticket.
      </p>
    </div>

    <div class="lp-cards">
      <div class="lp-card">
        <h3>Open a New Ticket</h3>
        <p>Create a new request for support or information. You will receive a ticket number to track the progress.</p>
        <div class="lp-cta">
          <?php if ($cfg->getClientRegistrationMode() != 'disabled' || !$cfg->isClientLoginRequired()) { ?>
            <a href="open.php" class="lp-btn lp-btn-primary">Open a New Ticket</a>
          <?php } ?>
        </div>
      </div>

      <div class="lp-card">
        <h3>Check Ticket Status</h3>
        <p>Already submitted a request? Enter your ticket number and email to view updates and add replies.</p>
        <div class="lp-cta">
          <a href="view.php" class="lp-btn lp-btn-secondary">Check Ticket Status</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if ($cfg && $cfg->isKnowledgebaseEnabled()) { ?>
  <div class="lp-container">
    <div class="search-form">
      <form method="get" action="kb/faq.php">
          <input type="hidden" name="a" value="search"/>
          <input type="text" name="q" class="search" placeholder="<?php echo __('Search our knowledge base'); ?>"/>
          <button type="submit" class="green button"><?php echo __('Search'); ?></button>
      </form>
    </div>
  </div>
<?php } ?>

<!-- The welcome content has been moved into the full-width hero section above -->
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
