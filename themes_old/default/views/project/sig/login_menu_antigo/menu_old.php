




        <div  class="col-lg-12">
                <center>
                    <h2 style="color: #0000000">MENUS</h2>
                </center>
            </div>
        <!-- /.row -->
        
        <div class="box-content">
                <div  class="col-lg-3 col-md-3 col-xs-6">
                    <a style="background-color:#950707 ; "  class=" white quick-button small" href="<?= site_url('projetos') ?>">
                        <i class="fa fa-bookmark fa-fw fa-3x"></i>

                        <p><?= lang('PROJETOS') ?></p>
                    </a>
                </div>
                

                <div  class="col-lg-3 col-md-3 col-xs-6">
                    <a style="background-color: #004E8F ; "  class=" white quick-button small" href="<?= site_url('projetos/eventos_index') ?>">
                        <i class="fa fa-calendar-o"></i>

                        <p><?= lang('EVENTOS') ?></p>
                    </a>
                </div>

                <div  class="col-lg-2 col-md-2 col-xs-6">
                    <a style="background-color: #da8c10 ; " class=" white quick-button small" href="<?= site_url('atas') ?>">
                        <i class="fa fa-book"></i>

                        <p><?= lang('ATAS') ?></p>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 col-xs-6">   
                    <a style="background-color:  #CB3500;"  class="white quick-button small" href="<?= site_url('planos/planosAguardandoValidacao') ?>">
                        <i class="fa fa-clock-o"></i>

                        <p><?= lang('AÇÕES AGUARDANDO VALIDAÇÃO') ?></p>
                    </a>
                </div>
            
                <div class="col-lg-2 col-md-2 col-xs-6">   
                    <a style="background-color:  gray;"  class="white quick-button small" href="<?= site_url('planos') ?>">
                        <i class="fa fa-list"></i>

                        <p><?= lang('AÇÕES') ?></p>
                    </a>
                </div>

                
                <div class="clearfix"></div>
            </div>
          
        <?php
                     $usuario = $this->session->userdata('user_id');
                     $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                    
                        
                    ?>
          
        <div style="margin-top: 50px;"  class="col-lg-12">
                <center>
                    <h2 style="color: #000000">GESTÃO</h2>
                </center>
            </div>
              <div class="box-content">
                <div  class="col-lg-3 col-md-3 col-xs-6">
                    <a style="background-color:#007F64 ; "  class=" white quick-button small" href="<?= site_url('Login_Projetos/projeto_menu') ?>">
                        <i class="fa  fa-exchange"></i>

                        <p><?= lang('TROCAR DE PROJETO') ?></p>
                    </a>
                </div>
                

                <div  class="col-lg-3 col-md-3 col-xs-6">
                    <a style="background-color: #007F64 ; "  class=" white quick-button small" href="<?= site_url('projetos/dashboard/'.$projetos->projeto_atual) ?>">
                        <i class="fa fa-dashboard"></i>

                        <p><?= lang('DASHBOARD') ?></p>
                    </a>
                </div>

                <div  class="col-lg-3 col-md-2 col-xs-6">
                    <a style="background-color: #007F64 ; " class=" white quick-button small" href="<?= site_url('users') ?>">
                        <i class="fa fa-users"></i>

                        <p><?= lang('USUÁRIOS') ?></p>
                    </a>
                </div>

                

                
                <div class="clearfix"></div>
            </div>
       
        <div style="margin-top: 50px;"  class="row col-lg-12">
             <div class="box-content">
                 <div   class=" col-lg-12">
                    
                <div style="background-color:#398439 ; " class="col-lg-3 col-md-3 ">
                    <h2 style="color: #ffffff"><center>GESTÃO DOS CUSTOS</center></h2>
                    <br>
                    <a class=" white " href="<?= site_url('financeiro') ?>">
                        <i class="fa fa-money"></i> <?= lang('CONTROLE DE CUSTOS') ?>
                    </a>
                   
                   
                    <br><br>
                </div>
                 </div>
              </div>   
        </div>