<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- <div class="pusher"></div>
<footer class="navbar-fixed-bottom footer">
<div class="container">
<div class="row">
<div class="col-md-12 text-center">
<span class="copyright-footer"><?php echo date('Y'); ?> <?php echo _l('clients_copyright',get_option('companyname')); ?></span>
<?php if(is_gdpr() && get_option('gdpr_show_terms_and_conditions_in_footer') == '1') { ?>
- <a href="<?php echo terms_url(); ?>" class="terms-and-conditions-footer"><?php echo _l('terms_and_conditions'); ?></a>
<?php } ?>
<?php if(is_gdpr() && is_client_logged_in() && get_option('show_gdpr_link_in_footer') == '1') { ?>
- <a href="<?php echo site_url('clients/gdpr'); ?>" class="gdpr-footer"><?php echo _l('gdpr_short'); ?></a>
<?php } ?>
</div>
</div>
</div>
</footer> -->

<?php if(is_client_logged_in() && $this->uri->segment(1) != 'invoice') { ?>
    <!-- begin:: Footer -->
    <div class="kt-footer  kt-footer--extended  kt-grid__item" id="kt_footer">
        <div class="kt-footer__top">
            <div class="kt-container">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="kt-footer__section">
                            <h3 class="kt-footer__title">Sobre nós - About</h3>
                            <div class="kt-footer__content">
                                Somos o grupo Weboox, seja bem-vindo! <br>
                                We are the Weboox group, we develop web applications,<br>
                                mobile applications and new ideas.<br>
                                We want to know your dreams.
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="kt-footer__section">
                            <h3 class="kt-footer__title">Links Úteis</h3>
                            <div class="kt-footer__content">
                                <div class="kt-footer__nav">
                                    <div class="kt-footer__nav-section">
                                        <a href="#">Nossos Produtos</a>
                                        <a href="#">Onde Estamos</a>
                                        <a href="#">Trabalhe Conosco</a>
                                    </div>
                                    <div class="kt-footer__nav-section">
                                        <a href="#">Conta PayBoox</a>
                                        <a href="#">Conta Get ERP</a>
                                        <a href="#">Seu Gerente Web</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if((get_option('use_knowledge_base') == 1 )) { ?>
                        <div class="col-lg-4">
                            <div class="kt-footer__section">
                                <h3 class="kt-footer__title"><?php echo _l('knowledge_base'); ?></h3>
                                <div class="kt-footer__content">
                                    <?php echo form_open(site_url('knowledge-base/search'),array('method'=>'GET','id'=>'kb-search-form', 'class' => 'kt-footer__subscribe')); ?>
                                    <div class="input-group">
                                        <input type="text" name="q" class="form-control" placeholder="<?php echo _l('have_a_question'); ?>">
                                        <div class="input-group-append">
                                            <button class="btn btn-brand" type="submit"><?php echo _l('kb_search'); ?></button>
                                        </div>
                                    </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="kt-footer__bottom">
            <div class="kt-container">
                <div class="kt-footer__wrapper">
                    <div class="kt-footer__logo">
                        <a class="kt-header__brand-logo" href="<?php echo site_url('clients'); ?>">
                            <img alt="Logo" src="<?php echo base_url('uploads/company/logo.png'); ?>" class="kt-header__brand-logo-sticky">
                        </a>
                        <div class="kt-footer__copyright">
                            <?php echo date('Y'); ?>&nbsp;&copy;&nbsp;
                            <a href="https://weboox.com.br" target="_blank">Weboox</a>
                        </div>
                    </div>
                    <div class="kt-footer__menu">
                        <?php if(is_gdpr() && get_option('gdpr_show_terms_and_conditions_in_footer') == '1') { ?>
                            <a href="<?php echo terms_url(); ?>"><?php echo _l('terms_and_conditions'); ?></a>
                        <?php } ?>
                        <?php if(is_gdpr() && is_client_logged_in() && get_option('show_gdpr_link_in_footer') == '1') { ?>
                            <a href="<?php echo site_url('clients/gdpr'); ?>"><?php echo _l('gdpr_short'); ?></a>
                        <?php } ?>

                        <a href="https://weboox.com.br" target="_blank">Nosso Time</a>
                        <a href="40031614" target="_blank">SAC 4003-1614</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- end:: Footer -->

<?php } ?>
