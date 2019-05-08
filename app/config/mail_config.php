<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Mailer Configuration
$config['mail_mailer']          = 'PHPMailer';
$config['mail_debug']           = 0; // default: 0, debugging: 2, 'local'
$config['mail_debug_output']    = 'html';
$config['mail_smtp_auth']       = true;
$config['mail_smtp_secure']     = ''; // default: '' | tls | ssl |
$config['mail_charset']         = 'utf-8';

// Templates Path and optional config
$config['mail_template_folder'] = 'templates/email';
$config['mail_template_options'] = array(
                                       'SITE_NAME' => 'Codeigniter Mail Plugin',
                                       'SITE_LOGO' => 'http://localhost/images/logo.jpg',
                                       'BASE_URL'  => 'http://localhost',
                                    );
// Server Configuration



$config['mail_smtp'] =  'smtp.office365.com';
$config['smtp_user'] = 'webmaster@unimedmanaus.coop.br';
$config['smtp_pass'] = '@unimed*';
$config['smtp_port'] = 587;
$config['smtp_auth'] = true;
$config['smtp_crypto'] = 'tls';
            

// Mailer config Sender/Receiver Addresses
$config['mail_admin_mail']      = 'israel.araujo@unimedmanaus.coop.br';
$config['mail_admin_name']      = 'WebMaster';

$config['mail_from_mail']       = 'israel.araujo@unimedmanaus.coop.br';
$config['mail_from_name']       = 'Gestor de Projetos - EDP <Unimed Manaus>';

$config['mail_replyto_mail']    = '';
$config['mail_replyto_name']    = '';

// BCC and CC Email Addresses
$config['mail_bcc']             = '';
$config['mail_cc']              = '';

// BCC and CC enable config, default disabled;
$config['mail_setBcc']          = false;
$config['mail_setCc']           = false;


/* End of file mail_config.php */
/* Location: ./application/config/mail_config.php */
