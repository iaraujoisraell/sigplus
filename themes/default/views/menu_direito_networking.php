<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

    <script>
        function exibe_chat() {
              $.ajax({
                type: "POST",
                url: "themes/default/views/networking/colaboradores/exibe_chat.php",
                
                success: function(data) {
                  $('#exibe_chat').html(data);
                 
                }

              });

            }
            
            
    </script>

    <?php
    $usuario = $this->session->userdata('user_id');
    $dados_user = $this->site->getUser($usuario);

    //retorna o projeto atual
    $projetos = $this->projetos_model->getProjetoAtualByID_completo();
    $status_projeto = $projetos->status;

    $empresa = $this->session->userdata('empresa');
    $empresa_dados = $this->owner_model->getEmpresaById($empresa);
    $nome_empresa = $empresa_dados->razaoSocial;
    ?>
    
    
<!-- Control Sidebar -->
  <aside style="height: 100%;"  class="control-sidebar control-sidebar-dark active">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        <li title="Chat" class="active"><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-wechat"></i></a></li>
        <li title="Aniversariantes do Mês"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-birthday-cake"></i></a></li>
        <li title="Aniversariantes do Mês"><a href="#control-sidebar-inf-tab" data-toggle="tab"><i class="fa fa-info-circle"></i></a></li>
      
    </ul>
    <!-- Tab panes -->
    <div  class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane " id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Aniversariantes do Mës</h3>
        <ul class="control-sidebar-menu">
           <?php
            $aniversariantes = $this->networking_model->getAllAniversariantesMes();
            $cont_anive = 0;
            foreach ($aniversariantes as $aniversariante) {
                $id_aniversariante = $aniversariante->id;
                $nome = $aniversariante->first_name;
                $data_aniversario = $aniversariante->data_aniversario;
                $genero = $aniversariante->gender;
                
                $date_hoje = date('Y-m-d');    
                $dia_hoje = date('d');
                $mes_atual = date('M');
                
                
                $partes_data_de = explode("-", $data_aniversario);
                $ano_de = $partes_data_de[0];
                $mes_de = $partes_data_de[1];
                $dia_de = $partes_data_de[2];
                
                if($genero == "male"){
                    $bg = "blue";
                }else if($genero == "female"){
                    $bg = "maroon-active";
                }else{
                    $bg = "red";
                }
                
                
                if($dia_de == $dia_hoje){
                    $bg = "yellow";
                    $text = "<b>É Hoje</b> dia";
                }else if($dia_de > $dia_hoje){
                    $text = "Será dia";
                }else if($dia_de < $dia_hoje){
                    $text = "Foi dia";
                }
                
                 

                $mes_extenso = array(
                    'Jan' => 'Janeiro',
                    'Feb' => 'Fevereiro',
                    'Mar' => 'Março',
                    'Apr' => 'Abril',
                    'May' => 'Maio',
                    'Jun' => 'Junho',
                    'Jul' => 'Julho',
                    'Aug' => 'Agosto',
                    'Nov' => 'Novembro',
                    'Sep' => 'Setembro',
                    'Oct' => 'Outubro',
                    'Dec' => 'Dezembro'
                );
    
                
                
                
                
                $cont_anive++;
             ?>
            <li  >
            <a  href="<?= site_url('welcome/novaMensagemAniversariante/'. $id_aniversariante); ?>" data-toggle="modal" data-target="#myModal" >
              <i class="menu-icon fa fa-birthday-cake bg-<?php echo $bg; ?> "></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading"><?php echo $nome; ?></h4>

                <p><?php echo $text.' '.$dia_de.' de '.$mes_extenso["$mes_atual"]; ?> </p>
              </div>
            </a>
          </li>
            <?php } ?>
          
        </ul>
        <!-- /.control-sidebar-menu -->
    </div>
      <!-- /.tab-pane -->
   
      <div  class="tab-pane active" id="control-sidebar-settings-tab">
          <h3 class="control-sidebar-heading">Colaboradores</h3>
          
           <style>
            /* width */
            ::-webkit-scrollbar {
              width: 10px;
            }

            /* Track */
            ::-webkit-scrollbar-track {
              background: #f1f1f1; 
            }

            /* Handle */
            ::-webkit-scrollbar-thumb {
              background: #888; 
            }

            /* Handle on hover */
            ::-webkit-scrollbar-thumb:hover {
              background: #555; 
            }
            </style>
          
          <div style="max-height: 70%; height: 70%;  overflow-x: hidden; overflow-y: scroll; white-space:nowrap;">
              <!-- USERS LIST -->
                  <ul  class="control-sidebar-menu">
                    <?php
                    $colaboradores = $this->networking_model->getAllColaboradoresByEmpresa();
                    $cont_colaborador = 0;
                    foreach ($colaboradores as $colaborador) {
                        $id_colaborador = $colaborador->id;
                        $nome_colaborador = $colaborador->first_name;
                        $generoc = $colaborador->gender;
                        $avatar = $colaborador->avatar;

                        ?>
                      <li  >
                          <a  href="<?= site_url('welcome/novaMensagemAniversariante/'. $id_aniversariante); ?>" data-toggle="modal" data-target="#myModal" >
                          <img style="width: 30px; height: 30px;" class="menu-icon" src="<?= $avatar ? $assets . '../../../assets/uploads/avatars/thumbs/' . $avatar : $assets . 'images/' . $generoc . '.png'; ?>" alt="User Avatar">
                          <div class="menu-info">
                            <h4 class="control-sidebar-subheading"><?php echo $nome_colaborador; ?></h4>
                        </div>
                          </a>
                      </li>
                    <?php
                    }
                    ?>
                      
                  </ul>
          <!-- /.form-group -->
        </div>
      </div>
      <!-- /.tab-pane -->
      
      <div class="tab-pane " id="control-sidebar-inf-tab">
        <h3 class="control-sidebar-heading">Informações de Acesso</h3>
     
       <div class="box-footer no-padding">
              <ul class="nav nav-stacked">
                  <li><a ><small><b>Empresa</b></small>        <span class="pull-right badge bg-blue"><?php echo $nome_empresa; ?>          </span></a></li>
                  <br>
                <li><a ><small><b>Usuário Atual </b></small> <span class="pull-right badge bg-red"><?php echo $dados_user->email; ?>      </span></a></li>
                <br>
                <li><a ><small><b>Último Acesso</b></small>  <span class="pull-right badge bg-aqua"><?php echo  date($dateFormats['php_ldate'], $this->session->userdata('old_last_login')) . " <br> " . ($this->session->userdata('last_ip') != $ip_address ? lang('ip:') . ' ' . $this->session->userdata('last_ip') : ''); ?>      </span></a></li>
                <br>
                <li><a ><small><b>Meu IP </b></small>        <span class="pull-right badge bg-green"><?php echo $ip_address; ?></span></a></li>
                
              </ul>
            </div>  
        
         </div>
        
      
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
  
  
 