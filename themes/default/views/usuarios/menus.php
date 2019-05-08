 <div class="row">
        <div class="col-lg-12">
           
            <div class="col-lg-4">
                <a href="<?= site_url('welcome'); ?>" class="btn btn-block btn-social btn-lg btn-orange">
                                <i class="fa fa-exclamation fa-fw fa-3x"></i>
                                Ações Pendentes   </a>
            </div>
             <div class="col-lg-4"> 
                 <a  href="<?= site_url('welcome/acoesConcluidas'); ?>"  class="btn btn-block btn-social btn-lg btn-green">
                                <i class="fa fa-check fa-fw fa-3x"></i>
                              Ver  Ações Concluídas
                            </a>
            </div>
            
             <?php
            $usuario = $this->session->userdata('user_id'); 
            $facilitador_user = $this->atas_model->getAtaUserFacilitadorByUser($usuario);
            $result = $facilitador_user->id;
            
            if($result > 0){
            ?>
            <div class="col-lg-4"> 
                 <a  href="<?= site_url('welcome/treinamentos'); ?>"  class="btn btn-block btn-social btn-lg btn-bitbucket">
                                <i class="fa fa-book fa-fw fa-3x"></i>
                              Meus Treinamentos
                            </a>
            </div>
            
            <?php } ?>
            
            </div> 
     
     </div>

<br><br>
      <div class="row">
     <div class="col-lg-12">
             <?php
            $usuario = $this->session->userdata('user_id'); 
            $facilitador_user = $this->atas_model->getAtaUserFacilitadorByUser($usuario);
            $result = $facilitador_user->id;
            
           // if($usuario == 2){
            ?>
            
            
            <div class="col-lg-4"> 
                 <a  href="<?= site_url('welcome/lista_rat'); ?>"  class="btn btn-block btn-social btn-lg " style="background-color: gray; color: #ffffff;">
                                <i class="fa fa-pencil fa-fw fa-3x"></i>
                              RAT'S
                            </a>
            </div>
            
            <?php //} ?>
           
            
        </div> 
</div>