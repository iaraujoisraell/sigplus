<?php 
$usuario =  $this->session->userdata('user_id'); 

?>
<div class="col-lg-12">
    <div class="box">
    <section class="content-header">
        <h1>
            <?php echo 'Ações '; ?>
            <?php if($tipo == 2) { // Tipo 1: status da ação (pendente, concluido e cancelado)?>
            <?php echo 'Aguardando Validação'; ?>
            <?php } ?>
            <small><?php echo 'Lista de Ações'; ?> </small>
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
    <div class="col-lg-12">
    <div class="box">
        
        
        <small><?php echo 'Filtro'; ?> </small>
        <section class="content-header">

    </section>
          <?php
            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
            echo form_open_multipart("project/lista_acoes/$menu", $attrib);
            echo form_hidden('idprojeto', $projeto->id);
            ?>
        
        <div class="col-md-3">
          <div class="form-group">
            
            <?php
            $wu_responsaveis[''] = 'Responsáveis';
            foreach ($responsaveis as $user) {
                $wu_responsaveis[$user->id] = $user->nome.' - '.$user->setor;
            }
            echo form_dropdown('responsavel_filtro', $wu_responsaveis, (isset($_POST['responsavel_filtro']) ? $_POST['responsavel_filtro'] : $responsavel_filtro), 'id="responsavel_filtro"  class="form-control selectpicker  select" data-placeholder="' . lang("Responsável") . ' "  style="width:100%;" ');
            ?>
          </div>
        </div>
       <?php if($tipo == 1) { // Tipo 1: status da ação (pendente, concluido e cancelado)?> 
       <div class="col-md-3">
          <div class="form-group">
          <?php $pst[''] = '';
              $pst['PENDENTE'] = lang('PENDENTE');
              $pst['ATRASADO'] = lang('ATRASADO');
              $pst['CONCLUÍDO'] = lang('CONCLUÍDO');
              $pst['CANCELADO'] = lang('CANCELADO');
              ?>
             <?php  
                  echo form_dropdown('status_filtro', $pst, (isset($_POST['status_filtro']) ? $_POST['status_filtro'] : $status_filtro), 'id="tipo"  class="form-control " data-placeholder="' .  lang("Status") . '"    style="width:100%;" ');
                 ?> 
          </div>
        </div>
       <?php } ?>
        
        
        <?php echo form_submit('add_marco', lang("Pesquisar"), 'id="add_item" class="btn btn-primary "  '); ?>
        <a class="btn btn-default " href="<?= site_url('project/lista_acoes/'.$menu); ?>">TODOS</a>
        <?php echo form_close(); ?>
    <br>
    </div>
    </div>
<br>


    <section  class="content">
        <div class="col-lg-12">
            <div class="row">
                
                        <div class="box">
                            <?php
                            $cont_atrasadas = 0;
                            $cont_pendentes = 0;
                            $cont_concluidas = 0;
                            $cont_cancelado = 0;
                            $cont_avalidacao = 0;
                            $total_pendentes = 0;
                            $wu4[''] = '';
                            $cont = 1;
                            foreach ($planos as $plano) {

                               $data_prazo = $plano->data_termino;
                               $status = $plano->status;
                               
                                if ($status == 'PENDENTE') {
                                $dataHoje = date('Y-m-d');

                                if ($dataHoje <= $data_prazo) {
                                //    $novo_status = 'PENDENTE';
                                    $cont_pendentes++;
                                }

                                if ($dataHoje > $data_prazo) {
                                //    $novo_status = 'ATRASADO';
                                    $cont_atrasadas++;
                                }


                                }  if ($status == 'CONCLUÍDO') {
                                //    $novo_status = 'AGUARDANDO VALIDAÇÃO';
                                    $cont_concluidas++;
                                }else if ($status == 'CANCELADO') {
                                //    $novo_status = 'CANCELADO';
                                    $cont_cancelado++;
                                }
                            }
                            ?>        
                            <?php if($tipo != 2) { // Tipo 1: status da ação (pendente, concluido e cancelado)?>
                        <br>
                            <div class="col-lg-12">
                            <table>
                                <thead>
                                    <tr >
                                        <th >  <font class="label bg-green-active" style="font-size: 12px; font-weight: bold"> Concluídas : <?php echo $cont_concluidas; ?> </font> </th>
                                        <th >  <font class="label bg-orange-active" style="font-size: 12px; font-weight: bold"> Pendentes : <?php echo $cont_pendentes; ?></font> </th>
                                        <th >  <font class="label bg-red-active" style="font-size: 12px; font-weight: bold"> Atrasadas : <?php echo $cont_atrasadas; ?></font> </th>
                                        <th >  <font class="label bg-gray" style="font-size: 12px; font-weight: bold"> Total : <?php echo $cont_concluidas + $cont_pendentes + $cont_atrasadas; ?></font> </th>
                                        <th >  <font class="label bg-black-active" style="font-size: 12px; font-weight: bold"> Canceladas : <?php echo $cont_cancelado; ?></font> </th>
                                        
                                    </tr>
                                </thead> 
                            </table>
                            </div>    
                            <br>
                            <br>
                            <?php } ?>
                                  
                            <div class="table-responsive">
                                <div class="box-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                    <th style="width:5%; "><input class="checkbox checkft" type="checkbox" name="check"/></th>    
                                   <th style="width:45%;"><?php echo $this->lang->line("Descrição"); ?></th>
                                    <th style="width:10%;"><?php echo $this->lang->line("Responsável"); ?></th>
                                    <th style="width:5%;"><?php echo $this->lang->line("Início"); ?></th>
                                    <th style="width:5%;"><?php echo $this->lang->line("Término"); ?></th>
                                    
                                     <th style="width:5%;"><?php echo $this->lang->line("Peso"); ?></th>

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
                                               
                                               if($ata > 0){
                                                   $dados_ata  = $this->atas_model->getAtaByID($ata);
                                                   $sequencial_ata = $dados_ata->sequencia;
                                                   $referencia = 'ATA: '.$sequencial_ata;
                                                   $url = 'atas/plano_acao/'.$ata;
                                               }else if($plano_acao > 0){
                                                   $dados_pa  = $this->atas_model->getPlanoAcaoByID($plano_acao);
                                                   $sequencial_pa = $dados_pa->sequencial;
                                                   $referencia = 'Plano de Ação: '.$sequencial_pa;
                                                   $url = 'project/plano_acao_detalhes/'.$plano_acao;
                                               }
                                               
                                               $responsavel_dados = $this->site->geUserByID($plano->responsavel);
                                               $nome_responsavel = $responsavel_dados->first_name;
                                               
                                               $status = $plano->status;
                                                
                                                if($status == 'PENDENTE'){
                                                    
                                                    if($plano->data_termino >= date('Y-m-d')){
                                                        $label = "orange";
                                                        $status_desc = "PENDENTE";
                                                    }else if($plano->data_termino < date('Y-m-d')){
                                                        $label = "red";
                                                        $status_desc = "ATRASADO";
                                                    }
                                                    
                                                    
                                                }else 
                                                if($status == 'CONCLUÍDO'){
                                                    $status_desc = "CONCLUÍDO";
                                                    $label = "green";
                                                }else 
                                                if($status == 'CANCELADO'){
                                                   $status_desc = "CANCELADO";
                                                    $label = "black";
                                                }else 
                                                if($status == 'AGUARDANDO VALIDAÇÃO'){
                                                   $status_desc = "AGUARDANDO VALIDAÇÃO";
                                                    $label = "orange";
                                                }
                                                
                                               
                                                
                                                $porc_concluido = $total_peso_concluido * 100/ $total_peso;;
                                                $porc_pendente  = $total_peso_pendente * 100/ $total_peso;;
                                                $porc_atrasado  = $total_peso_atrasado * 100/ $total_peso;

                                            ?>               

                                                <tr  >
                                                    <td style="width: 5%;"><input class="checkbox checkft" type="checkbox" name="check"/></td>
                                                   <td style="width: 60%; "> 
                                                        <a title="Editar Registro"   href="<?= site_url('project/manutencao_acao_pendente/' .$plano->idplanos); ?>">
                                                           <small class="label bg-black"><?php echo 'Ação : '. $plano->sequencial; ?></small>
                                                        </a>   
                                                       <small class="label bg-<?php echo $label; ?>" ><?php echo $status_desc; ?></small>
                                                       <a title="Acessar Referência de Origem" target="_blank" href="<?= site_url($url); ?>">
                                                        <small  class="label bg-blue-active" ><?php echo $referencia; ?></small>
                                                        </a>
                                                        <br>
                                                        <h2><?php echo $plano->descricao; ?></h2></td> 
                                                    <td style="width: 10%; "><small  ><?php echo $nome_responsavel; ?></small></td> 
                                                    <td style="width: 5%; "><small   ><?php echo exibirData($plano->data_entrega_demanda); ?></small></td>
                                                    <td style="width: 5%; "><small   ><?php echo exibirData($plano->data_termino); ?></small></td>
                                                   
                                                    <td style="width: 5%; font-size: 12; text-align: center;"><small class="label bg-red"><?php echo $plano->peso; ?></small></td> 
                                                   
                                                    <td style="width: 10%;"><a title="Editar Registro" class="btn btn-dropbox"  href="<?= site_url('project/manutencao_acao_pendente/' .$plano->idplanos.'/'.$retorno); ?>"><i class="fa fa-edit"></i>  ABRIR </a></td>    


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