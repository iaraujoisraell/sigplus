
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
                        </div>
                    </span>
                </div>
           
                  <br>
                    <div class="box-body">
                       <table id="example1" class="table table-bordered table-striped">
                            <thead>
                             <tr style=" width: 100%;">

                                <th style="width: 5%;">Id</th>
                                <th style="width: 45%;">Razão Social</th>
                                <th style="width: 5%;"> CNPJ</th>
                                <th style="width: 5%;"> Telefone</th>
                                <th style="width: 10%;"> E-mail</th>
                                <th style="width: 10%;">Módulos</th>
                                <th style="width: 10%;">Contrato</th>
                                <th style="width: 10%;">Editar</th>
                            </tr>
                            </thead>
                            <tbody>
                                 <?php
                                    $wu4[''] = '';
                                    $cont = 1;
                                    foreach ($empresas as $empresa) {
                                    ?>               

                                        <tr  >

                                            <td style="width: 5%;"><?php echo $cont++; ?> </td> 
                                            <td style="width: 45%;"><?php echo $empresa->razaoSocial; ?><small style="color: #000000;" class="label pull-right" > <?php echo $empresa->fantazia; ?></small> </td>
                                            <td style="width: 5%;"><small   ><?php echo $empresa->cnpj; ?></small></td>
                                            <td style="width: 5%;"><small   ><?php echo $empresa->telefone; ?></small></td> 
                                            <td style="width: 10%;"><small  ><?php echo $empresa->emailResponsavel; ?></small></td> 
                                            
                                            <td style="width: 10%; "><small  ><a title="Módulos Acesso"  class="btn btn-dropbox fa fa-puzzle-piece" href="<?= site_url('owner/editarEmpresaModulo/'.$empresa->id); ?>" data-toggle="modal" data-target="#myModal">  </a></small></td>
                                            <td style="width: 10%; "><small  ><a title="Adminstrar Contrato" class="btn btn-flickr fa fa-list" href="<?= site_url('welcome/editarRequisicaoHorasDetalhes/'.$periodo->id); ?>" data-toggle="modal" data-target="#myModal">  </a></small></td>
                                            <td style="width: 10%; "><small  ><a style="background-color: chocolate; color: #ffffff;" title="Editar Registro" class="btn fa fa-edit" href="<?= site_url('welcome/editarRequisicaoHorasDetalhes/'.$periodo->id); ?>" data-toggle="modal" data-target="#myModal">  </a></small></td>
                                            

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
          <div style="width: 800px; " class="modal-dialog">
            <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Cadastro de Empresa'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
        echo form_open_multipart("owner/empresas", $attrib); ?>
        <div class="modal-body">
           

         

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group ">
                        <?= lang("Tipo", "tipo"); ?>
                        <select name="tipo" class="form-control ">
                            <option value="Jurídica">Jurídica</option>
                            <option value="Física">Física</option>
                        </select>
                    </div>
                    <div class="form-group company">
                        <?= lang("Empresa *", "company"); ?>
                        <?php echo form_input('company', '', 'class="form-control tip" maxlength="300" id="company" required="true" data-bv-notempty="true"'); ?>
                    </div>
                    <div class="form-group company">
                        <?= lang("Fantasia ", "fantazia"); ?>
                        <?php echo form_input('fantazia', '', 'class="form-control tip" maxlength="150" id="fantazia" required="true" data-bv-notempty="true"'); ?>
                    </div>
                    <div class="form-group person">
                        <?= lang("Nome Contato", "name"); ?>
                        <?php echo form_input('name', '', 'class="form-control tip" maxlength="150" id="name" data-bv-notempty="true"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("vat_no", "vat_no"); ?>
                        <?php echo form_input('cnpj', '', 'class="form-control" maxlength="20" id="vat_no"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("Inscrição Estadual", "ie"); ?>
                        <?php echo form_input('ie', '', 'class="form-control" maxlength="20" id="ie"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("Inscrição Municipal", "im"); ?>
                        <?php echo form_input('im', '', 'class="form-control" maxlength="20" id="im"'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("E-mail *", "email_address"); ?>
                        <input type="email" name="email" class="form-control" maxlength="150"  required="true"  id="email_address"/>
                    </div>
                    <div class="form-group">
                        <?= lang("phone", "phone"); ?>
                        <input type="tel" name="phone" maxlength="15" class="form-control"  id="phone"/>
                    </div>
                    <div class="form-group">
                        <?= lang("address", "address"); ?>
                        <?php echo form_input('address', '', 'class="form-control" maxlength="150" id="address" '); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("city", "city"); ?>
                        <?php echo form_input('city', '', 'class="form-control" maxlength="45" id="city" '); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("state", "state"); ?>
                        <?php echo form_input('state', '', 'class="form-control" maxlength="2" id="state"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("postal_code", "postal_code"); ?>
                        <?php echo form_input('postal_code', '', 'class="form-control" maxlength="9" id="postal_code"'); ?>
                    </div>
                    
                    <div class="form-group company">
                        <?= lang("Status", "company"); ?>
                        <select name="status" class="form-control tip">
                            <option value="1">Ativo</option>
                            <option value="2">Pendente</option>
                            <option value="0">Cencelado</option>
                        </select>
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
          
 
 