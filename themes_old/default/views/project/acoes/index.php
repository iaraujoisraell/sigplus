

    
    <?php 
    $usuario =  $this->session->userdata('user_id'); 

    ?>
    <div class="col-lg-12">
        <div class="box">
        <section class="content-header">
            <h1>
                <?php echo $titulo; ?>
                <small><?php echo $descricao_titulo; ?> </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?= site_url('project'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Lista de Ações</li>
            </ol>
        </section>
        
        <br>
        </div>    
    </div>
    <div class="col-lg-12">
    <!-- Content Header (Page header) -->
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
    <!-- Main content -->
    </div>
    <section  class="content">
        <div class="col-lg-12">
            <div class="row">
                
                        <div class="box">
                    <br>
                            <div class="table-responsive">
                                <div class="box-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                    <th style="width:5%; "><input class="checkbox checkft" type="checkbox" name="check"/></th>    
                                    <th style="width:5%;" ><?php echo $this->lang->line("Id"); ?></th>
                                    <th style="width:5%;"><?php echo $this->lang->line("Ref."); ?></th>
                                    <th style="width:35%;"><?php echo $this->lang->line("Descrição"); ?></th>
                                    <th style="width:10%;"><?php echo $this->lang->line("Responsável"); ?></th>
                                    <th style="width:5%;"><?php echo $this->lang->line("Início"); ?></th>
                                    <th style="width:5%;"><?php echo $this->lang->line("Término"); ?></th>
                                    
                                     <th style="width:5%;"><?php echo $this->lang->line("Peso"); ?></th>
                                    <th style="width:15%;  text-align: center;" ><?php echo $this->lang->line("Status"); ?></th>
                                    <th style="width:10%;  text-align: center;"><?php echo $this->lang->line("Opções"); ?></th>
                                </tr>
                                </thead>
                                    <tbody>
                                         <?php
                                            $wu4[''] = '';
                                            $cont = 1;
                                            foreach ($planos as $plano) {

                                               $ata = $plano->idatas;
                                               $plano_acao = $plano->idplano;
                                               
                                               if($ata){
                                                   $referencia = 'ATA '.$ata;
                                               }else if($plano_acao){
                                                   $referencia = 'P. Ação '.$plano_acao;
                                               }
                                               
                                               $responsavel_dados = $this->site->geUserByID($plano->responsavel);
                                               $nome_responsavel = $responsavel_dados->first_name;
                                               
                                               $status = $plano->status;
                                                
                                                if($status == 'PENDENTE'){
                                                    
                                                    if($plano->data_termino > date('Y-m-d')){
                                                        $label = "warning";
                                                        $status_desc = "NO PRAZO";
                                                    }else if($plano->data_termino < date('Y-m-d')){
                                                        $label = "danger";
                                                        $status_desc = "ATRASADO";
                                                    }
                                                    
                                                    
                                                }else 
                                                if($status == 'CONCLUÍDO'){
                                                    $status_desc = "CONCLUÍDO";
                                                    $label = "success";
                                                }else 
                                                if($status == 'CANCELADO'){
                                                   $status_desc = "CANCELADO";
                                                    $label = "default";
                                                }
                                                
                                               
                                                
                                                $porc_concluido = $total_peso_concluido * 100/ $total_peso;;
                                                $porc_pendente  = $total_peso_pendente * 100/ $total_peso;;
                                                $porc_atrasado  = $total_peso_atrasado * 100/ $total_peso;

                                            ?>               

                                                <tr  >
                                                    <td style="width: 5%;"><input class="checkbox checkft" type="checkbox" name="check"/></td>
                                                    <td style="width: 5%; text-align: center;"><small   ><?php echo $plano->sequencial; ?></small></td>
                                                    <td style="width: 5%;  font-size: 14px;"><small   ><?php echo $referencia; ?></small></td> 
                                                    <td style="width: 35%; text-align: center;"><small  ><?php echo $plano->descricao; ?></small></td> 
                                                    <td style="width: 10%; "><small  ><?php echo $nome_responsavel; ?></small></td> 
                                                    <td style="width: 5%; "><small   ><?php echo exibirData($plano->data_entrega_demanda); ?></small></td>
                                                    <td style="width: 5%; "><small   ><?php echo exibirData($plano->data_termino); ?></small></td>
                                                   
                                                    <td style="width: 5%; font-size: 12; text-align: center;"><b><?php echo $plano->peso; ?></b></td> 
                                                    <td style="width: 15%;font-size: 12; text-align: center;"><small class="label label-<?php echo $label; ?>" ><?php echo $status_desc; ?></small></td>  
                                                    <td style="width: 10%;"><a title="Editar Registro" class="btn btn-dropbox"  href="<?= site_url('project/manutencao_acao_pendente/' .$plano->idplanos); ?>"><i class="fa fa-edit"></i>  ABRIR </a></td>    


                                                </tr>
                                                <?php
                                            }
                                            ?>
                                    </tbody>
                                </table>
                                    <br><br><br><br>
                                </div>    
                            </div>

                        </div>
                
      <!-- /.row (main row) -->
            </div>
        </div>
    </section>
    <!-- /.content -->
 


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

          
 
 <script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>