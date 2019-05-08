
<?php
    $dados_acao = $this->atas_model->getPlanoByID($acao);
    $id = $dados_acao->idplanos;
    $desc = $dados_acao->descricao;
   
    ?>   

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    
    <section class="content-header">
      <h1>
        Atualizar Ação 
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
    
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
       
        <br><br>
            <div class="row">
                
                
          <div class="col-lg-6">
              <div class="box box-primary">
            <div class="box-header">
              <i class="ion ion-clipboard"></i>

            
              <h3 class="box-title">Dados da Ação : <?php echo $id; ?></h3>
              
            
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="col-md-12">
                    <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
                    echo form_open_multipart("AudCon/editar_regra", $attrib);
                    ?>
                    <input name="id" type="hidden" value="<?php echo $id; ?>">
                    <div class="form-group person">
                        <?= lang("Ação : ", "name"); ?>
                        <?php echo form_input('numero', $desc, 'class="form-control tip" readonly="true" id="name"'); ?>
                    </div>
                    <div class="form-group person">
                        <?= lang("Observação : ", "name"); ?>
                        <?php echo form_textarea('numero', $dados_acao->observacao, 'class="form-control tip" id="name"'); ?>
                    </div>
                    <div class="form-group person">
                        <?= lang("Onde : ", "name"); ?>
                        <?php echo form_input('numero', $regra, 'class="form-control tip" id="name"'); ?>
                    </div>
                    <div class="form-group person">
                        <?= lang("Como : ", "name"); ?>
                        <?php echo form_input('descricao', $desc, 'class="form-control tip" id="name"'); ?>
                    </div>
                    <div class="form-group person">
                        <?= lang("Porque : ", "name"); ?>
                        <?php echo form_input('descricao', $desc, 'class="form-control tip" id="name"'); ?>
                    </div>
                    
                    
                 

                    <div class="modal-footer">
                    <?php echo form_submit('add_customer', lang('Atualizar'), 'class="btn btn-primary"'); ?>
                    </div>
                <?php echo form_close(); ?>

                </div>
              
            </div>
            <!-- /.box-body -->
            
          </div>
          </div>
                
                
          <div class="col-lg-6">
              <div class="box box-primary">
            <div class="box-header">
              <i class="ion ion-clipboard"></i>

              <h3 class="box-title">Condições para aplicação da regra</h3>
            </div>
                  
            <!-- /.box-header -->
            <div class="box-body">
                <div class="col-md-12">
                    <button style="margin-bottom: 20px;" type="button"  data-id="100" data-toggle="modal" data-target="#modal-insert">
                        <i class="fa fa-plus"></i> Novo Valor
                        </button>
                    <a href="<?= site_url('AudCon/valoresRegras'); ?>"><i class="fa fa-cog"></i> Gerenciar Valores</a>
                    <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
                    echo form_open_multipart("AudCon/add_condicao", $attrib);
                    ?>
                    <input name="id_regra" type="hidden" value="<?php echo $id; ?>">
                    <div class="form-group person">
                        <?= lang("Valor Cliente : ", "name"); ?>
                        <?php
                            $valor[""] = " ";
                            $clientes = $this->AudCon_model->getOpcoesValores();
                            foreach ($clientes as $cli) {
                                $valor[$cli->id] = $cli->valor.' - '.$cli->descricao;
                            }
                            echo form_dropdown('cliente', $valor, '', 'class="form-control tip select" id="customer_group" style="width:100%;" required="required"');
                            ?>
                    </div>
                    <div class="form-group person">
                        <?= lang("Condição : ", "name"); ?>
                        <select name="condicao"  class="form-control tip select">
                                <option value="N/A"> N/A </option>
                                <option value="="> = </option>
                                <option value="!="> != </option>
                                <option value=">="> >= </option>
                                <option value="<="> <= </option>
                                <option value=">"> > </option>
                                <option value="<"> < </option>
                            </select>
                    </div>
                    <div class="form-group person">
                        <?= lang("Valor Base : ", "name"); ?>
                       <?php
                       $valor2[""] = " ";
                            $clientes = $this->AudCon_model->getOpcoesValores();
                            foreach ($clientes as $cli) {
                                $valor2[$cli->id] = $cli->valor.' - '.$cli->descricao;
                            }
                            echo form_dropdown('valor2', $valor2, '', 'class="form-control tip select" id="customer_group" style="width:100%;" required="required"');
                            ?>
                    </div>
                    <div class="form-group company">
                            <?= lang("Relação coluna estrutura cliente", "company"); ?>
                        <div class="controls"> 
                            <select name="resultado" required="true" class="form-control tip select">
                                <option value="1">CONSISTÊNTE</option>
                                <option value="0">INCONSISTÊNTE</option>
                            </select>
                            
                        </div>
                    </div>
                    
                 

                    <div class="modal-footer">
                    <?php echo form_submit('add_customer', lang('Cadastrar'), 'class="btn btn-primary"'); ?>
                    </div>
                <?php echo form_close(); ?>

                </div>
              
                
                <table  class="table table-bordered table-striped">
                <thead>
                <tr>
                
                  <th>Valor Cliente</th>
                  <th>Condição</th>
                  <th>Valor Base </th>
                  <th>Condição</th>
                  <th>Opções</th>
                </tr>
                </thead>
                <tbody>
                
                <?php
                    $cont = 1;                            
                   $analises = $this->AudCon_model->getCondicaoRegraByRegra($id);
                    foreach ($analises as $cli) {
                     
                     $resultado = $cli->resultado;
                     if($resultado == 1){
                         $resul = "CONSISTENTE";
                     }else if($resultado == 0){
                         $resul = "INCONSISTENTE";
                     }
                     $valor1 = $this->AudCon_model->getOpcoesValorById($cli->valor_cliente);
                     $valor2 = $this->AudCon_model->getOpcoesValorById($cli->valor_base);
                ?>   
                
                <tr>
                 
                  <td><?php echo $valor1->valor; ?></td>
                  <td><?php echo $cli->condicao; ?></td>
                  <td><?php echo $valor2->valor; ?></td>
                  <td><?php echo $resul; ?></td>
                  <td> <a href="../delete_condicao/<?php echo $cli->id.'/'.$id; ?>"><i class="fa fa-trash-o"></i></a></td>
                                          
                </tr>
                <?php
                }
                ?>
               
               
                
                </tbody>
                <tfoot>
                <tr>
                 
                  <th>Valor Cliente</th>
                  <th>Condição</th>
                  <th>Valor Base </th>
                  <th>Condição</th>
                  <th>Opções</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
            
          </div>
        </div>

          </div>
     

    </section>
    <!-- /.content -->
  </div>


<div class="modal fade" id="modal-insert">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" class="abrirModal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo lang('Cadastrar Nova Opção de Valor'); ?></h4>
            </div>
            <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
            echo form_open_multipart("AudCon/add_valor_condicao", $attrib);
            ?>
            <input name="id_regra" type="hidden" value="<?php echo $id; ?>">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group person">
                        <?= lang("Valor ", "name"); ?>
                        <?php echo form_input('valor', '', 'class="form-control tip" required="true" id="name"'); ?>
                        </div>
                        <div class="form-group">
                            <?= lang("Descrição ", "vat_no"); ?>
                        <?php echo form_input('descricao', '', 'class="form-control" required="true" id="vat_no"'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
<?php echo form_submit('add_customer', lang('Cadastrar'), 'class="btn btn-primary"'); ?>
            </div>
        </div>
<?php echo form_close(); ?>
        <!-- /.modal-content -->
    </div>
</div>
