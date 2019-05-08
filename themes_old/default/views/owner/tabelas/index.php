
<div class="content-wrapper">
    
    <?php 
    
    $usuario =  $this->session->userdata('user_id'); 

    ?>
  
    <!-- Content Header (Page header) -->
     <section class="content-header">
          <h1>
            <?php echo $titulo; ?>
            <small><?php echo $descricao_titulo; ?> </small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= site_url('owner'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Empresa</li>
          </ol>

        </section>
    <br>
    <!-- Main content -->
    <section class="content">
        <?php if ($Settings->mmode) { ?>
                        <div class="alert alert-warning">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <?= lang('site_is_offline') ?>
                        </div>
                    <?php }
                    if ($error) { ?>
                        <div class="alert alert-danger">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $error; ?></ul>
                        </div>
                    <?php }
                    if ($message) { ?>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $message; ?></ul>
                        </div>
                    <?php } ?>
        
             <div class="box">
                <div class="box-header">
                    <span class="pull-right-container">
                       <div class=" clearfix no-border">
                            <button  type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#modal-insert">
                                <i class="fa fa-plus"></i>  Novo Cadastro </button>
                                <a  title="Atualizar Registro" class="btn  btn-default pull-right" href="<?= site_url('owner/atualizaTabelas'); ?>" data-toggle="modal" data-target="#myModal"><i class="fa fa-refresh"></i> Atualizar </a>
                           
                        </div>
                        
                    </span>
                </div>
           
                  <br>
                    <div class="box-body">
                       <table  class="table table-bordered table-striped">
                            <thead>
                             <tr style=" width: 100%;">

                                <th style="width: 5%;">Id</th>
                                <th style="width: 65%;">Tabela</th>
                                
                            </tr>
                            </thead>
                            <tbody>
                                 <?php
                                    $wu4[''] = '';
                                    $cont = 1;
                                    foreach ($tabelas as $tabela) {
                                    ?>               

                                        <tr  >

                                            <td style="width: 5%;"><?php echo $cont++; ?> </td> 
                                            <td style="width: 65%;"><?php echo $tabela->tabela; ?> </td>
                                                </tr>
                                        <?php
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                
      </div>
        
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
    
 </div>

<div  class="modal fade" id="modal-insert">
          <div  class="modal-dialog">
            <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Cadastro de Módulo'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
        echo form_open_multipart("owner/modulos", $attrib); ?>
        <div class="modal-body">
           

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group company">
                        <?= lang("Descrição", "company"); ?>
                        <?php echo form_input('descricao', '', 'class="form-control tip" maxlength="300" id="descricao" required="true" data-bv-notempty="true"'); ?>
                    </div>
                    <div class="form-group company">
                        <?= lang("Fa icon", "fantazia"); ?>
                        <?php echo form_input('icon', '', 'class="form-control tip" maxlength="150" id="icon" '); ?>
                    </div>
                    
                    
                </div>
                
            </div>


        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_customer', lang('Salvar'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
            <!-- /.modal-content -->
          </div>
        </div>

        