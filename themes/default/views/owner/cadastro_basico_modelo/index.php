
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
                           <a style="background-color: green; color: #ffffff;" title="Editar Registro" class="btn btn-default pull-right" href="<?= site_url('owner/novoCadastroBasico/'.$tabela_id."/".$menu_id); ?>" data-toggle="modal" data-target="#myModal">  
                           <i class="fa fa-plus"></i>  Novo Cadastro 
                           </a> 
                         
                        </div>
                    </span>
                </div>
           
                  <br>
                    <div class="box-body">
                       <table id="lista_cadastro" class="table table-bordered table-striped">
                            <thead>
                             <tr style=" width: 100%;">

                                <th style="width: 5%;">Id</th>
                                <?php
                                    $wu4[''] = '';
                                    $cont = 1;
                                    foreach ($campos as $campo) {
                                    ?> 
                                    <th style=""><?php echo $campo->nome_campo; ?></th>
                                
                                    <?php } ?>
                                <?php 
                                            
                                            foreach ($botoes_menu as $botao) {
                                                $botao_id = $botao->botao_id;
                                                $controle = $botao->controle;
                                                $funcao = $botao->funcao;
                                                
                                                $dados_botao =  $this->owner_model->getBotaoCadastroById($botao_id);
                                                $nome_botao = $dados_botao->descricao;
                                                $icon_botao = $dados_botao->icon;
                                                $background_botao = $dados_botao->background;
                                                $cor_botao = $dados_botao->cor;
                                                $observacao_botao = $dados_botao->observacao;
                                                
                                            ?>
                                            <th style="width: 10%;"><?php echo $nome_botao; ?></th>
                                     <?php } ?>
                                
                                
                            </tr>
                            </thead>
                            <tbody>
                                 <?php
                                    $wu4[''] = '';
                                    $cont_lista = 1;
                                    foreach ($cadastros as $cadastro) {
                                    ?>               

                                        <tr  >

                                            <td style="width: 5%;"><?php echo $cont_lista++; ?> </td> 
                                            <?php
                                            foreach ($campos as $campo) {
                                                $nome_campo = $campo->campo;
                                                $tabela_fk = $campo->fk;
                                                
                                                if($tabela_fk != 0){
                                                    if($cadastro->$nome_campo > 0){
                                                    
                                                    
                                                    
                                                    $tabelas_fk = $this->owner_model->getTableById($tabela_fk);//tabela relacionamento
                                                    $nome_tabela = $tabelas_fk->tabela;//nome da tabela
                                                    
                                                    $colunas_tabela_fk = $this->owner_model->getAllCamposDropdownFK($tabela_id,$tabela_fk); //colunas que sera(o) visualizadas
                                                    $dados_tabelas_fk = $this->owner_model->getTableByNameAndId($nome_tabela, $cadastro->$nome_campo);
                                                    
                                                          $cont_col = 1;
                                                          foreach ($colunas_tabela_fk as $coluna) {
                                                          $id_coluna = $coluna->campo_id;
                                                          $campo_id_fk = $this->owner_model->getCampoById($id_coluna);
                                                          
                                                        //  $descricao_campo = $campo_id_fk->campo;
                                                          
                                                          if($cont_col <= 1){
                                                              $descricao_campo = $campo_id_fk->campo;
                                                          }else if($cont_col > 1){
                                                              $descricao_campo .= ' - '.$campo_id_fk->campo; 
                                                              //echo $descricao_campo; exit;
                                                          }
                                                           
                                                          
                                                           //echo $cont_col;

                                                          $cont_col++; 
                                                         }
                                                         $descricao_campo = $dados_tabelas_fk->$descricao_campo;
                                                         //echo $nome_campo;
                                                     
                                                    }else{
                                                        $descricao_campo =  "";
                                                    }
                                                }else{
                                                    $descricao_campo = $cadastro->$nome_campo;
                                                }    
                                                 
                                            ?> 
                                            <td style="width: <?php echo $campo->width; ?>;"><?php echo $descricao_campo; ?></td>
                                            <?php } ?>
                                            
                                            <?php 
                                            
                                            foreach ($botoes_menu as $botao) {
                                                $botao_id = $botao->botao_id;
                                                $controle_bd = $botao->controle;
                                                $funcao_bt = $botao->funcao;
                                                
                                                $dados_botao =  $this->owner_model->getBotaoCadastroById($botao_id);
                                                $nome_botao = $dados_botao->descricao;
                                                $icon_botao = $dados_botao->icon;
                                                $background_botao = $dados_botao->background;
                                                $cor_botao = $dados_botao->cor;
                                                $observacao_botao = $dados_botao->observacao;
                                                
                                                //PEDA OS DADOS DO CONTROLE
                                                 $dados_controle = $this->owner_model->getControleById($controle_bd);//tabela relacionamento
                                                 $nome_controle = $dados_controle->descricao;
                                                 
                                                 //PEDA OS DADOS DA FUNÇÃO
                                                 $dados_funcao = $this->owner_model->getFuncaoById($funcao_bt);//tabela relacionamento
                                                 $nome_funcao = $dados_funcao->funcao;
                                            ?>
                                             <td style="width: 10%; "><small  ><a style="background-color: <?php echo $background_botao; ?>; color: <?php echo $cor_botao; ?>;" title="<?php echo $nome_botao.' '.$observacao_botao; ?>" class="btn fa fa-<?php echo $icon_botao; ?>" href="<?= site_url($nome_controle.'/'.$nome_funcao.'/'.$tabela_id.'/'.$cadastro->id.'/'.$menu_id); ?>" data-toggle="modal" data-target="#myModal">  </a></small></td>
                                          
                                            <?php } ?>
                                            

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

<script>
  $(function () {

    $('#lista_cadastro').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>