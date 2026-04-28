<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/bower_components/Ionicons/css/ionicons.min.css">
  
<style>
    .color-palette {
      height: 35px;
      line-height: 35px;
      text-align: center;
    }

    .color-palette-set {
      margin-bottom: 15px;
    }

    .color-palette span {
      display: none;
      font-size: 12px;
    }

    .color-palette:hover span {
      display: block;
    }

    .color-palette-box h4 {
      position: absolute;
      top: 100%;
      left: 25px;
      margin-top: -40px;
      color: rgba(255, 255, 255, 0.8);
      font-size: 12px;
      display: block;
      z-index: 7;
    }
  </style>
 
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
         .bootstrap-select .btn-default {
         background: #fff !important;
         border: 1px solid #d6d6d6 !important;
         box-shadow: none;
         color: #494949 !important;
         padding: 6px 12px;
         }
      </style>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<script>
    function mask(o, f) {
        setTimeout(function () {
            var v = mphone(o.value);
            if (v != o.value) {
                o.value = v;
            }
        }, 1);
    }

    function mphone(v) {
        var r = v.replace(/\D/g, "");
        r = r.replace(/^0/, "");
        if (r.length > 10) {
            r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
        } else if (r.length > 5) {
            r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
        } else if (r.length > 2) {
            r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
        } else {
            r = r.replace(/^(\d*)/, "($1");
        }
        return r;
    }
</script>
<div class="content">

    <div class="row">
        <?php echo form_open_multipart($this->uri->uri_string(), array('id' => 'new_ro_form')); ?>
        <div class="col-md-12">

            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/atendimento'); ?>"><i class="fa fa-backward"></i> Registro de Atendimento </a></li> 
                    <li class="">Novo Atendimento </li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-heading">
                    Novo Registro de Atendimento
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            echo render_select('categoria_id', $categorias, array('id', 'titulo'), 'Categoria', [], array('required' => 'true'));
                            ?>
                        </div>
                        <div class="col-md-6">
                            <div class="select-placeholder form-group form-group-select-input-groups_in[] input-group-select">
                                <label for="client_id" class="control-label">Cliente</label>
                                <div class="input-group input-group-select select-groups_in[]" app-field-wrapper="client_id">
                                    <select id="client_id" name="client_id" class="selectpicker  _select_input_group"  data-width="100%" data-none-selected-text="Nada selecionado"></select>
                                    <!--<div class="input-group-addon">
                                        <a href="#" data-toggle="modal" data-target="#add_client_modal"><i class="fa fa-plus"></i></a>
                                    </div>-->
                                    <?php if($this->session->userdata('empresa_id')){?>
                                    <div class="input-group-addon">
                                        <a href="#" data-toggle="modal" data-target="#import_client_modal"><i class="fa fa-download"></i></a>
                                    </div><!-- comment -->
                                    <?php }?>
                                    <div class="input-group-addon">
                                        <a href="#" data-toggle="modal" data-target="#search_client_modal"><i class="fa fa-search"></i></a>
                                    </div>
                                    
                                </div>
                            </div>                        
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo render_select('canal_atendimento_id', $canais_atendimentos, array('id', 'canal'), 'Canal de atendimento', [], array('required' => 'true'));
                            ?>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label">Contato</label>
                            <input style="width: 100%;  " name="contato" id="contato" type="text"  maxlength="15" minlength="15" required="true" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="control-label">E-mail</label>
                            <input style="width: 100%;  " name="email" id="email" type="email"  local="email" class="form-control">
                        </div>


                    </div>
                    <!-- <div class="row attachments">
                                 <div class="attachment">
                                     <div class="col-md-12 col-md-offset12 mbot15">
                                         <div class="form-group">
                                             <label for="attachment" class="control-label">Arquivo</label>
                                             <div class="input-group">
                                                 <input type="file" extension="<?php echo str_replace(['.', ' '], '', get_option('ticket_attachments_file_extensions')); ?>" filesize="1" class="form-control" name="attachments" accept="<?php echo get_ticket_form_accepted_mimes(); ?>">
                                                 <span class="input-group-btn">
                                                     <button class="btn btn-success add_more_attachments p8-half" data-max="1" type="button"><i class="fa fa-plus"></i></button>
                                                 </span>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" id="trocar">

                </div>
            </div>
            <div class="panel_s">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">



                            <div class="btn-group pull-right mleft4 " >
                                <button type="submit" class="btn btn-primary" >
                                    Salvar
                                </button>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php echo form_close(); //<?php //if(!$atendimento == true){?> 
    </div>
</div>
<?php init_tail(); ?>
<script>


    $(document).on('change', "#categoria_id", function () {
        var select = document.getElementById("categoria_id");
        var opcaoValor = select.options[select.selectedIndex].value;
        if (opcaoValor != "") {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('gestao_corporativa/categorias_campos/retorno_categoria_campos?rel_type=atendimento'); ?>",
                data: {
                    categoria_id: opcaoValor
                },
                success: function (data) {
                    $('#trocar').html(data);
                }
            });
        } else {
            alert('Selecione euma categoria!');
        }
    });
</script>
<?php $this->load->view('gestao_corporativa/atendimento/import_client'); ?>
<?php $this->load->view('gestao_corporativa/atendimento/search_client'); ?>

