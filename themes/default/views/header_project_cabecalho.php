<?php
$projetos = $this->projetos_model->getProjetoAtualByID_completo();
$id_projeto = $projetos->id;
$nome_projeto = $projetos->nome_projeto;
$gerente = $projetos->gerente_area;
$resp_tecnico_fase = $this->atas_model->getUserSetorByUserSetor($gerente);
$gerente_projeto = $resp_tecnico_fase->nome;
    $status = $projetos->status;
    if($status == 'ATIVO'){
       $status_label = 'success'; 
    }else if($status == 'CANCELADO'){
        $status_label = 'danger'; 
    }else if($status == 'EM AGUARDO'){
        $status_label = 'warning'; 
    }else if($status == 'CONCLUÍDO'){
        $status_label = 'primary'; 
    }
?>


<div class="col-lg-12 ">
          <ol  class="breadcrumb">
              <li ><i class="fa fa-bookmark"></i>    <?php echo $nome_projeto.' '; ?> </li> <small class="label label-<?php echo $status_label; ?>" ><?php echo $status; ?></small>
              <li ><i class="fa fa-user"></i>  Gerente:    <?php echo $gerente_projeto; ?></li>
              <li  ><i class="fa fa-calendar"></i> Início : <?php echo date("d/m/Y", strtotime($projetos->dt_inicio)); ?> </li>
              <li ><i class="fa fa-flag-checkered"></i> Fim : <?php echo date("d/m/Y", strtotime($projetos->dt_final)); ?> </li>
        <?php if($status == 'EM AGUARDO'){ ?> 
                     <li class="pull-right"> <a title="O Projeto deve estar ativo para que se possa cadastrar ações e gerencia-lo." href="<?= site_url('Project/ativarProjeto/'.$projetos->id); ?>" ><small class="btn btn-success btn-sm" > ATIVAR PROJETO <i class="fa fa-check"></i></small></a>  </li>
            <?php }  ?>   
          </ol>
            <?php
            $dt_inicio = $projetos->dt_inicio;
            $dt_fim = $projetos->dt_final;
            $hoje = date("Y-m-d");
            $dias_projeto = $this->projetos_model->getDiferencaDiasProjeto($dt_inicio, $dt_fim);
            $dias_projeto_hoje = $this->projetos_model->getDiferencaDiasProjeto($dt_inicio, $hoje);
            $total_dias = $dias_projeto->dias;
            $andamento_dias = $dias_projeto_hoje->dias;
            ?>
            </div>