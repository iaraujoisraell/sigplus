<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/lte/dist/css/adminlte.min.css">
  
  <title><?php echo $form->name; ?></title>
  <?php app_external_form_header($form); ?>
  <?php hooks()->do_action('app_web_to_lead_form_head'); ?>
</head>
<?php
if(!$hash){
$this->session->set_userdata('hash', app_generate_hash());
$hash = $this->session->userdata('hash');
}
?>

<style>
         body {
         font-family:'Open Sans';
         background:#f1f1f1;
         }
         h3 {
         margin-top: 7px;
         font-size: 18px;
         }
        
         .install-row.install-steps {
         margin-bottom:15px;
         box-shadow: 0px 0px 1px #d6d6d6;
         }
        
         .control-label {
         font-size:13px;
         font-weight:600;
         }
         .padding-10 {
         padding:10px;
         }
         .mbot15 {
         margin-bottom:15px;
         }
         .bg-default {
         background: #03a9f4;
         border:1px solid #03a9f4;
         color:#fff;
         }
         .bg-success {
         border: 1px solid #dff0d8;
         }
         .bg-not-passed {
         border:1px solid #f1f1f1;
         border-radius:2px;
         }
         .bg-not-passed {
         border-right:0px;
         }
         .bg-not-passed.finish {
         border-right:1px solid #f1f1f1 !important;
         }
         .bg-not-passed h5 {
         font-weight:normal;
         color:#6b6b6b;
         }
         .form-control {
         box-shadow:none;
         }
         .bold {
         font-weight:600;
         }
         .col-xs-5ths,
         .col-sm-5ths,
         .col-md-5ths,
         .col-lg-5ths {
         position: relative;
         min-height: 1px;
         padding-right: 15px;
         padding-left: 15px;
         }
         .col-xs-5ths {
         width: 20%;
         float: left;
         }
         b {
         font-weight:600;
         }
         
      </style>

<body class="web-to-lead <?php echo $form->form_key; ?> "<?php if(is_rtl(true)){ echo ' dir="rtl"';} ?>>
  <div class="wrapper">  
    
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
          <div class="row mb-2">
         <div class="container-fluid">
            <div class="small-logo">
                 <span class="text-primary">
                   <?php 

                   if ($company_logo != '') {
                    ?>
                     <img src="<?php echo  base_url('uploads/company/' . $company_logo); ?>" width="100px;" heith="100px" class="img-responsive" >
                     <?php
                    } elseif ($company_name != '') {
                        $logo = '<a href="' . $logoURL . '" class="' . $href_class . ' logo logo-text">' . $company_name . '</a>';
                    }
                   //echo $logo;
                   ?>
                 </span>
              </div>
             <?php
             $total_Sessao = 1; 
            $count_filhos = 0; 
             $formulario_pai = $this->Formulario_model->get_form_externo_by_hash($form->form_key); 
             $sessao_atual = $formulario_pai->sessao;
             $form_pai = $formulario_pai->form_pai;
             
             if($sessao_atual > 1){
                 // retorna o form do pai para verificar o total de sessoes
                $formulario_pai = $this->Formulario_model->get_form_externo_by_id($form_pai);
                $formularios_filhos = $this->Formulario_model->get_form_filhos($formulario_pai->id);
                $count_filhos = count($formularios_filhos);
            
             }else{
                $formularios_filhos = $this->Formulario_model->get_form_filhos($form->id);
                $count_filhos = count($formularios_filhos);
             }
            
          
            if($count_filhos > 0){
              $total_Sessao = $count_filhos + 1;
            }
          ?>
                <div class="card card-primary card-outline">
                  <div class="card-header">
                      <h1><?php echo $form->titulo; ?>
                      <div class="float-right"><?php  echo $sessao_atual.'/'.$total_Sessao;  ?></div>
                      </h1>
                  </div>
                  <div class="card-body">
                    <?php echo $form->descricao; ?>
                  </div>
                </div>       
            <div class="row">
              <div class="<?php if($this->input->get('col')){echo $this->input->get('col');} else {echo 'col-md-12';} ?>">
                  <div id="response">

                  </div>
                 
                <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>$form->form_key,'class'=>'disable-on-submit')); ?>
                <?php hooks()->do_action('web_to_lead_form_start'); ?>
                <?php echo form_hidden('key',$form->form_key); ?>
                <?php echo form_hidden('sessao',$sessao_atual); ?>
                <?php echo form_hidden('total_sessao',$total_Sessao); ?>
                <?php echo form_hidden('hash',$hash); ?>
                  <?php
                 // echo $hash;
                    ?>

                  <?php foreach($perguntas as $perg){
                     
                    ?>
                  
                  <div class="row">
                      <div class="col-md-12">
                        <div class="card card-default card-outline">
                      <?php

                      $field['type'] = $perg->tipo;
                      $field['subtype'] = '';

                      if($perg->required == 0){
                          $required = "";
                      }else if ($perg->required == 1){
                          $required = "1";
                      }
                      $field['required'] = $required;
                      $field['className'] = 'form-control';
                      $field['label'] = $perg->title;
                      $field['name'] = $perg->name;
                      $field['placeholder'] = '';
                      $field['description'] = '';
                      $field['value'] = '';
                      $field['multiple'] = 'false';
                      $dados_itens = '';
                      if(($perg->tipo == 'caixa_selecao') || ($perg->tipo == 'select') || ($perg->tipo == 'multiselect')){
                          $dados_itens = $this->Formulario_model->get_item_multiplaescolha_by_pergunta_id($perg->id);
                        }
                     
                      $field['dados_itens'] = $dados_itens;

                   render_formulario_builder_field($field);
                 ?>
                 </div>       
                    </div>
                 </div>     
                <?php   
                 } ?>
                 <?php if(show_recaptcha() && $form->recaptcha == 1){ ?>
                 <div class="col-md-12">
                   <div class="form-group"><div class="g-recaptcha" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
                   <div id="recaptcha_response_field" class="text-danger"></div>
                 </div>
                 <?php } ?>
                 <?php if (is_gdpr() && get_option('gdpr_enable_terms_and_conditions_lead_form') == 1) { ?>
                 <div class="col-md-12">
                  <div class="checkbox chk">
                      <input type="checkbox" name="accept_terms_and_conditions" required="true" id="accept_terms_and_conditions" <?php echo set_checkbox('accept_terms_and_conditions', 'on'); ?>>
                    <label for="accept_terms_and_conditions">
                      <?php echo _l('gdpr_terms_agree', terms_url()); ?>
                    </label>
                  </div>
                </div>
                <?php } ?>
                 <div class="clearfix"></div>
                 <div class="text-left col-md-12 submit-btn-wrapper">
                  <button style="padding: 12px 20px 12px 20px; border-radius: 20px 20px 20px 20px; font-size: 16px; background-color: #20b3e7;" data-loading-text="<?php echo _l('wait_text'); ?>" data-form="#record_payment_form" class="btn btn-success" id="form_submit" type="submit">
                  <?php
                   if($total_Sessao == $sessao_atual){
                  ?>
                    ENVIAR
                  <?php }else{ ?>    
                    PRÓXIMO
                  <?php } ?>
                  </button>
                </div>
              </div>

              <?php hooks()->do_action('web_to_lead_form_end'); ?>
              <?php echo form_close(); ?>

          </div>
        </div>
          </div>
          </div>    
      </div>
     </div>
    
</div>   
<?php app_external_form_footer($form); ?>
<script>
 var form_id = '#<?php echo $form->form_key; ?>';
 $(function() {
   $(form_id).appFormValidator({

    onSubmit: function(form) {

     $("input[type=file]").each(function() {
          if($(this).val() === "") {
              $(this).prop('disabled', true);
          }
      });

     var formURL = $(form).attr("action");
     var formData = new FormData($(form)[0]);

     $.ajax({
       type: $(form).attr('method'),
       data: formData,
       mimeType: $(form).attr('enctype'),
       contentType: false,
       cache: false,
       processData: false,
       url: formURL
     }).always(function(){
      $('#form_submit').prop('disabled', false);
     }).done(function(response){
      response = JSON.parse(response);
                 // In case action hook is used to redirect
                 if (response.redirect_url) {
                     window.top.location.href = response.redirect_url;
                     return;
                 }
                 if (response.success == false) {
                     $('#recaptcha_response_field').html(response.message); // error message
                   } else if (response.success == true) {
                    //   window.location.href = "https://sigplus.app.br/forms/msgObrigado";
                      
                     $(form_id).remove();
                     $('#response').html('<div class="alert alert-success">'+response.message+'</div>');
                     $('html,body').animate({
                       scrollTop: $("#online_payment_form").offset().top
                     },'slow');
                     
                   } else {
                     $('#response').html('Something went wrong...');
                   }
                   if (typeof(grecaptcha) != 'undefined') {
                     grecaptcha.reset();
                   }
                 }).fail(function(data){
                 if (typeof(grecaptcha) != 'undefined') {
                   grecaptcha.reset();
                 }
                 if(data.status == 422) {
                    $('#response').html('<div class="alert alert-danger">Some fields that are required are not filled properly.</div>');
                 } else {
                    $('#response').html(data.responseText);
                 }
               });
                 return false;
               }
             });
 });
</script>
<?php hooks()->do_action('app_web_to_lead_form_footer'); ?>


</body>
</html>
