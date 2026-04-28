<div class="card ">

    <div class="card-body " style="margin-left: auto; margin-right: auto; padding-top: 3px; padding-bottom: 3px;" >
        <!--
        <?php
        if ($pendente) {
            foreach ($pendente as $doc) {
                ?>
                                                                    <div class="alert alert-warning alert-dismissible">
                                                                        <h6 style="color: white;"><i class="icon fas fa-exclamation"></i> Você tem um documento pendente de avalização!</h6>
                                                                        <a target="_blank" href="<?php echo base_url('gestao_corporativa/intra/Documentos/see?id=' . $doc['id']); ?>" type="button" class="btn btn-default" style="color: black;">Acessar documento</a>
                                                                    </div>
                <?php
            }
        }
        ?>
        <a href="<?php echo base_url('gestao_corporativa/Intranet/comunicados_internos') ?>" style='background-size: 100% 100%; background-image: url("<?php echo base_url(); ?>assets/intranet/icons_modals/comunicado.png");
          background-color: #11ffee00;' class="btn btn-app">ddddddddd
            </a>
        -->
        <?php if (has_permission_intranet('modules', '', 'view_cis') || is_admin()) { ?>

            <a style="height: 90px; width: 110px;  background-color: #ffffff;" " href="<?php echo base_url('gestao_corporativa/intra/comunicado') ?>"  class="btn btn-app ">
                <i > <img width="50px" height="50px;" src="<?php echo base_url() . 'assets/intranet/icons_modals/comunicado.png' ?>" >  </i> <br>  CI's
            </a>

        <?php } ?>
        <?php
        if (has_permission_intranet('modules', '', 'view_docs') || is_admin()) {
            if ($this->session->userdata('empresa_id') == 2) {
                ?>

                <a style="height: 90px; width: 110px;  background-color: #ffffff;" href="<?php echo base_url('gestao_corporativa/intra/documentos/list_') ?>" class="btn btn-app">
                    <span class="badge bg-info"><?php echo $qtd_docs; ?></span>
                    <i >  <img width="50px" height="50px;" src="<?php echo base_url() . 'assets/intranet/icons_modals/documento.png' ?>" ></i> <br> Documentos
                </a>
            <?php } else { ?>
                <a style="height: 90px; width: 110px;  background-color: #ffffff;" href="<?php echo base_url('gestao_corporativa/cdc/list_') ?>" class="btn btn-app" disabled>
                    <span class="badge bg-info"><?php echo $qtd_docs; ?></span>
                    <i >  <img width="50px" height="50px;" src="<?php echo base_url() . 'assets/intranet/icons_modals/documento.png' ?>" ></i> <br> Documentos
                </a>
            <?php } ?>

        <?php } ?>



        <?php if (has_permission_intranet('modules', '', 'view_projects') || is_admin()) { ?>
            <a style="height: 90px; width: 110px;  background-color: #ffffff;" href="<?php echo base_url('admin/projects') ?>" class="btn btn-app">
                <span class="badge bg-purple"></span>
                <i ><img width="50px" height="50px;" src="<?php echo base_url() . 'assets/intranet/icons_modals/projeto.png' ?>" ></i> <br> Projetos
            </a>
        <?php } ?>

        <?php if (has_permission_intranet('modules', '', 'view_tasks') || is_admin()) { ?>
            <a style="height: 90px; width: 110px;  background-color: #ffffff;" href="<?php echo base_url('admin/tasks/list_tasks') ?>" style="color: white; background-color: #FF8C00;" class="btn btn-app">
                <span class="badge bg-teal"><?php echo count($tarefas); ?></span>
                <i><img width="50px" height="50px;" src="<?php echo base_url() . 'assets/intranet/icons_modals/tarefa.png' ?>" ></i> <br> Ações
            </a>

        <?php } ?>
        <?php if (has_permission_intranet('modules', '', 'view_forms') || is_admin()) { ?>
            <a style="height: 90px; width: 110px;  background-color: #ffffff;" href="<?php echo base_url('gestao_corporativa/Formularios') ?>" class="btn btn-app ">
                <span class="badge bg-danger"><?php echo count($forms); ?></span>
                <i ><img width="50px" height="50px;" src="<?php echo base_url() . 'assets/intranet/icons_modals/formulario.png' ?>" ></i> <br> Formulário
            </a>
        <?php } ?>
        <?php if (has_permission_intranet('modules', '', 'view_ros') || is_admin()) { ?>
            <a style="height: 90px; width: 110px;  background-color: #ffffff;" href="<?php echo base_url('gestao_corporativa/Registro_ocorrencia'); ?>" class="btn btn-app " target="_blank">
                <i ><img width="50px" height="50px;" src="<?php echo base_url() . 'assets/intranet/icons_modals/registro.png' ?>" ></i> <br> R.O
            </a>
        <?php } ?>
        <?php if (has_permission_intranet('modules', '', 'view_geds') || is_admin()) { ?>
            <a style="height: 90px; width: 110px;  background-color: #ffffff;" href="<?php echo base_url('gestao_corporativa/Ged/index'); ?>"  class="btn btn-app">
                <span class="badge bg-danger"></span>
                <i ><img width="50px" height="50px;" src="<?php echo base_url() . 'assets/intranet/icons_modals/ged.png' ?>" ></i> <br> GED
            </a>
        <?php } ?>

        <?php if (has_permission_intranet('modules', '', 'view_ras') || is_admin()) { ?>
            <a style="height: 90px; width: 110px;  background-color: #ffffff; " href="<?php echo base_url('gestao_corporativa/Atendimento/index'); ?>" class="btn btn-app">
                <span class="badge bg-danger"></span>
                <i><img width="50px" height="50px;" src="<?php echo base_url() . 'assets/intranet/icons_modals/atendimento.png' ?>" ></i> <br> Atendimentos
            </a>
        <?php } ?>

        <?php if (has_permission_intranet('modules', '', 'view_workflows') || is_admin()) { ?>
            <a style="height: 90px; width: 110px;  background-color: #ffffff;" href="<?php echo base_url('gestao_corporativa/Workflow/index'); ?>"  class="btn btn-app">
                <span class="badge bg-danger"></span>
                <i><img width="50px" height="50px;" src="<?php echo base_url() . 'assets/intranet/icons_modals/workflow.png' ?>" ></i> <br> Workflow
            </a>
        <?php } ?>


    </div> 

    <?php /* ?>
      <div class="card-body" style="margin-left: auto; margin-right: auto;" >
      <!--
      <?php
      if ($pendente) {
      foreach ($pendente as $doc) {
      ?>
      <div class="alert alert-warning alert-dismissible">
      <h6 style="color: white;"><i class="icon fas fa-exclamation"></i> Você tem um documento pendente de avalização!</h6>
      <a target="_blank" href="<?php echo base_url('gestao_corporativa/intra/Documentos/see?id='.$doc['id']);?>" type="button" class="btn btn-default" style="color: black;">Acessar documento</a>
      </div>
      <?php
      }
      }
      ?>
      <a href="<?php echo base_url('gestao_corporativa/Intranet/comunicados_internos') ?>" style='background-size: 100% 100%; background-image: url("<?php echo base_url();?>assets/intranet/icons_modals/comunicado.png");
      background-color: #11ffee00;' class="btn btn-app">ddddddddd
      </a>
      -->
      <?php if (has_permission_intranet('modules', '', 'view_cis') || is_admin()) { ?>

      <a style="height: 100px; width: 150px; background-color: cornflowerblue; color: #ffffff" " href="<?php echo base_url('gestao_corporativa/Intranet/comunicados_internos') ?>"  class="btn btn-app ">
      <i > <img width="50px" height="50px;" src="<?php echo base_url().'assets/intranet/icons_modals/comunicado.png' ?>" >  </i> <br>  CI's
      </a>

      <?php } ?>
      <?php if (has_permission_intranet('modules', '', 'view_docs') || is_admin()) { ?>

      <a style="height: 100px; width: 150px; background-color: #FFD700; color: #ffffff" href="<?php echo base_url('gestao_corporativa/Intranet/documentos') ?>" class="btn btn-app">
      <span class="badge bg-info"><?php echo $qtd_docs; ?></span>
      <i >  <img width="50px" height="50px;" src="<?php echo base_url().'assets/intranet/icons_modals/documento.png' ?>" ></i> <br> Documentos
      </a>

      <?php } ?>



      <?php if (has_permission_intranet('modules', '', 'view_projects') || is_admin()) { ?>
      <a style="height: 100px; width: 150px; " href="<?php echo base_url('admin/projects') ?>" class="btn btn-app bg-success">
      <span class="badge bg-purple"></span>
      <i ><img width="50px" height="50px;" src="<?php echo base_url().'assets/intranet/icons_modals/projeto.png' ?>" ></i> <br> Projetos
      </a>
      <?php } ?>

      <?php if (has_permission_intranet('modules', '', 'view_tasks') || is_admin()) { ?>
      <a style="height: 100px; width: 150px; color: white; background-color: firebrick; " href="<?php echo base_url('admin/tasks/list_tasks') ?>" style="color: white; background-color: #FF8C00;" class="btn btn-app">
      <span class="badge bg-teal"><?php echo count($tarefas); ?></span>
      <i><img width="50px" height="50px;" src="<?php echo base_url().'assets/intranet/icons_modals/tarefa.png' ?>" ></i> <br> Ações
      </a>

      <?php } ?>

      <a style="height: 100px; width: 150px; color: white; background-color: indigo; " href="<?php echo base_url('gestao_corporativa/Formularios') ?>" class="btn btn-app ">
      <span class="badge bg-danger"><?php echo count($forms); ?></span>
      <i ><img width="50px" height="50px;" src="<?php echo base_url().'assets/intranet/icons_modals/formulario.png' ?>" ></i> <br> Formulário
      </a>

      <?php if (has_permission_intranet('modules', '', 'view_ros') || is_admin()) { ?>
      <a style="height: 100px; width: 150px; " href="<?php echo base_url('gestao_corporativa/Registro_ocorrencia'); ?>" class="btn btn-app bg-danger">
      <i ><img width="50px" height="50px;" src="<?php echo base_url().'assets/intranet/icons_modals/registro.png' ?>" ></i> <br> R.O
      </a>
      <?php } ?>
      <?php if (has_permission_intranet('ged', '', 'view_ged') || is_admin()) { ?>
      <a style="height: 100px; width: 150px; color: white; background-color: silver; " href="<?php echo base_url('gestao_corporativa/Ged/index'); ?>"  class="btn btn-app">
      <span class="badge bg-danger"></span>
      <i ><img width="50px" height="50px;" src="<?php echo base_url().'assets/intranet/icons_modals/ged.png' ?>" ></i> <br> GED
      </a>
      <?php } ?>

      <?php if (has_permission_intranet('ged', '', 'view_ged') || is_admin()) { ?>
      <a style="height: 100px; width: 150px; color: #000000; background-color: yellow; " href="<?php echo base_url('gestao_corporativa/Atendimento/index'); ?>" class="btn btn-app">
      <span class="badge bg-danger"></span>
      <i><img width="50px" height="50px;" src="<?php echo base_url().'assets/intranet/icons_modals/atendimento.png' ?>" ></i> <br> Atendimentos
      </a>
      <?php } ?>

      <?php if (has_permission_intranet('ged', '', 'view_workflow') || is_admin()) { ?>
      <a style="height: 100px; width: 150px; color: white; background-color: #9400D3; " href="<?php echo base_url('gestao_corporativa/Workflow/index'); ?>"  class="btn btn-app">
      <span class="badge bg-danger"></span>
      <i><img width="50px" height="50px;" src="<?php echo base_url().'assets/intranet/icons_modals/workflow.png' ?>" ></i> <br> Workflow
      </a>
      <?php } ?>


      </div>
      <?php */ ?>
</div>