<?php
/*********************************************************************
    index.php

    Helpdesk landing page. Please customize it to fit your needs.

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2013 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/
require('client.inc.php');

require_once INCLUDE_DIR . 'class.page.php';

$section = 'home';
require(CLIENTINC_DIR.'header.inc.php');
?>
<div id="landing_page">
<?php include CLIENTINC_DIR.'templates/sidebar.tmpl.php'; ?>
<div class="main-content">
    
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title"><?php echo __('Welcome to Our Support Center'); ?></h1>
            <p class="hero-subtitle"><?php echo __('Get the help you need, when you need it. Our team is here to assist you with any questions or issues.'); ?></p>
            
            <?php if ($cfg && $cfg->isKnowledgebaseEnabled()) { ?>
            <div class="hero-search">
                <form method="get" action="kb/faq.php" class="search-form">
                    <input type="hidden" name="a" value="search"/>
                    <input type="text" name="q" class="search" placeholder="<?php echo __('Search our knowledge base...'); ?>"/>
                    <button type="submit" class="search-button"><?php echo __('Search'); ?></button>
                </form>
            </div>
            <?php } ?>
        </div>
    </div>

    <!-- Quick Stats Section -->
    <div class="stats-section">
        <div class="stat-card">
            <div class="stat-icon">📞</div>
            <div class="stat-number">24/7</div>
            <div class="stat-label"><?php echo __('Support Available'); ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">⚡</div>
            <div class="stat-number"><?php echo __('< 2hrs'); ?></div>
            <div class="stat-label"><?php echo __('Response Time'); ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-number">98%</div>
            <div class="stat-label"><?php echo __('Satisfaction Rate'); ?></div>
        </div>
    </div>

    <!-- Welcome Content -->
    <div class="welcome-section">
        <div class="thread-body">
        <?php
            if($cfg && ($page = $cfg->getLandingPage()))
                echo $page->getBodyWithImages();
            else { ?>
                <div class="welcome-content">
                    <h2><?php echo __('How Can We Help You?'); ?></h2>
                    <p><?php echo __('Our comprehensive support system is designed to provide you with quick and effective solutions to your questions and concerns. Whether you need technical assistance, have billing questions, or require general information, we\'re here to help.'); ?></p>
                    
                    <div class="help-options">
                        <div class="help-option">
                            <div class="help-icon">🎫</div>
                            <h3><?php echo __('Create a Ticket'); ?></h3>
                            <p><?php echo __('Submit a new support ticket for personalized assistance from our team.'); ?></p>
                        </div>
                        <div class="help-option">
                            <div class="help-icon">📋</div>
                            <h3><?php echo __('Check Status'); ?></h3>
                            <p><?php echo __('Track the progress of your existing tickets and view responses.'); ?></p>
                        </div>
                        <div class="help-option">
                            <div class="help-icon">📚</div>
                            <h3><?php echo __('Knowledge Base'); ?></h3>
                            <p><?php echo __('Browse our comprehensive knowledge base for instant answers.'); ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Featured Knowledge Base -->
    <?php if($cfg && $cfg->isKnowledgebaseEnabled()){ ?>
    <div class="featured-section">
        <h2 class="section-title"><?php echo __('Popular Articles'); ?></h2>
        <div class="featured-grid">
        <?php
        $cats = Category::getFeatured();
        if ($cats->all()) {
            foreach ($cats as $C) { ?>
            <div class="featured-category">
                <div class="category-header">
                    <i class="icon-folder-open"></i>
                    <h3 class="category-name"><?php echo $C->getName(); ?></h3>
                </div>
                <div class="category-articles">
                <?php foreach ($C->getTopArticles() as $F) { ?>
                    <div class="article-item">
                        <a href="<?php echo ROOT_PATH; ?>kb/faq.php?id=<?php echo $F->getId(); ?>" class="article-link">
                            <div class="article-title"><?php echo $F->getQuestion(); ?></div>
                            <div class="article-teaser"><?php echo $F->getTeaser(); ?></div>
                        </a>
                    </div>
                <?php } ?>
                </div>
            </div>
            <?php }
        } ?>
        </div>
    </div>
    <?php } ?>

    <!-- Contact Information -->
    <div class="contact-section">
        <h2 class="section-title"><?php echo __('Get in Touch'); ?></h2>
        <div class="contact-grid">
            <div class="contact-card">
                <div class="contact-icon">📧</div>
                <h3><?php echo __('Email Support'); ?></h3>
                <p><?php echo __('support@example.com'); ?></p>
                <p class="contact-note"><?php echo __('Response within 2 hours'); ?></p>
            </div>
            <div class="contact-card">
                <div class="contact-icon">📞</div>
                <h3><?php echo __('Phone Support'); ?></h3>
                <p><?php echo __('+1 (555) 123-4567'); ?></p>
                <p class="contact-note"><?php echo __('Available 24/7'); ?></p>
            </div>
            <div class="contact-card">
                <div class="contact-icon">💬</div>
                <h3><?php echo __('Live Chat'); ?></h3>
                <p><?php echo __('Chat with us online'); ?></p>
                <p class="contact-note"><?php echo __('Instant response'); ?></p>
            </div>
        </div>
    </div>

</div>
</div>

<?php require(CLIENTINC_DIR.'footer.inc.php'); ?>
