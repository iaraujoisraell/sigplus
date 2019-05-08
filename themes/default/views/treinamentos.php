<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<!DOCTYPE html>
<html lang="en">

<?php  $this->load->view($this->theme . 'menu_head'); ?>

<body>

    <div id="wrapper">

        <!-- begin TOP NAVIGATION -->
          <?php  $this->load->view($this->theme . 'top'); ?>
        <!-- end TOP NAVIGATION -->

       
        <!-- end SIDE NAVIGATION -->

        <!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content">

                
                <!-- ATALHOS RÁPIDO -->
                <?php  $this->load->view($this->theme . 'atalhos'); ?>
                
                
                
                <?php
                     $usuario = $this->session->userdata('user_id');
                     $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                    
                     /*
                      * VERIFICA SE O USUÁRIO TEM PERMISSAO PARA ACESSAR O MENU EXIBIDO
                      */
                     $permissoes           = $this->projetos_model->getPermissoesByPerfil($projetos->group_id);   
                     $permissao_projetos   = $permissoes->projetos_index;
                     $permissao_atas       = $permissoes->atas_index;
                     $permissao_participantes   = $permissoes->participantes_index;
                     $permissao_eventos    = $permissoes->eventos_index;
                     
                     $permissao_acoes      = $permissoes->acoes_index;
                     $permissao_avalidacao = $permissoes->acoes_aguardando_validacao_index;
                     $permissao_apendentes = $permissoes->acoes_pendentes_index;
                     
                     
                     $permissao_dashboard   = $permissoes->dashboard_index;
                     
                     /*
                      * CADASTRO
                      */
                     $permissao_cadastro              = $permissoes->cadastro;
                     $permissao_pesquisa_satisfacao   = $permissoes->pesquisa_satisfacao_index;
                     $permissao_categoria_financeira  = $permissoes->categoria_financeira_index	;
                     $permissao_setores               = $permissoes->setores_index;
                     $permissao_perfil_acesso         = $permissoes->perfil_acesso;
                     /*
                      * RELATÓRIO
                      */
                     $permissao_relatorios             = $permissoes->relatorios;
                     $permissao_status_report          = $permissoes->status_report;
                     $permissao_users_acoes_atrasadas  = $permissoes->users_acoes_atrasadas;
                     /*
                      * PESSOAS
                      */
                     $permissao_cadastro_pessoas    = $permissoes->cadastro_pessoas;
                     $permissao_usuarios            = $permissoes->users_index;
                     $permissao_gestores            = $permissoes->lista_gestores;
                     $permissao_suporintendentes    = $permissoes->lista_superintendente;
                     $permissao_fornecedor          = $permissoes->fornecedores_index;
                     $lista_participantes          = $permissoes->lista_participantes;
                     
                     
                     /*
                      * GESTAO DE CUSTO
                      */
                     $permissao_gestao_custo          = $permissoes->gestao_custo;
                     $permissao_contas_pagar          = $permissoes->contas_pagar;
                     
                     /*
                      * CALENDÁRIO
                      */
                     $permissao_calendario          = $permissoes->calendario;
                    ?>
                <br><br>
                <!-- /ATALHOS RÁPIDO -->
                <style>
    
    @import url(https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css);
@import url(https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css);

/*#region Organizational Chart*/
.tree * {
    margin: 0; padding: 0;
    height: 100%;
    
}

.tree ul {
    padding-top: 20px; position: relative;

    -transition: all 0.5s;
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
}

.tree li {
    float: left; text-align: center;
    list-style-type: none;
    position: relative;
    padding: 20px 5px 0 5px;

    -transition: all 0.5s;
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
}

/*We will use ::before and ::after to draw the connectors*/

.tree li::before, .tree li::after{
    content: '';
    position: absolute; top: 0; right: 50%;
    border-top: 2px solid #696969;
    width: 50%; height: 20px;
}
.tree li::after{
    right: auto; left: 50%;
    border-left: 2px solid #696969;
}

/*We need to remove left-right connectors from elements without 
any siblings*/
.tree li:only-child::after, .tree li:only-child::before {
    display: none;
}

/*Remove space from the top of single children*/
.tree li:only-child{ padding-top: 0;}

/*Remove left connector from first child and 
right connector from last child*/
.tree li:first-child::before, .tree li:last-child::after{
    border: 0 none;
}
/*Adding back the vertical connector to the last nodes*/
.tree li:last-child::before{
    border-right: 2px solid #696969;
    border-radius: 0 5px 0 0;
    -webkit-border-radius: 0 5px 0 0;
    -moz-border-radius: 0 5px 0 0;
}
.tree li:first-child::after{
    border-radius: 5px 0 0 0;
    -webkit-border-radius: 5px 0 0 0;
    -moz-border-radius: 5px 0 0 0;
}

/*Time to add downward connectors from parents*/
.tree ul ul::before{
    content: '';
    position: absolute; top: 0; left: 50%;
    border-left: 2px solid #696969;
    width: 0; height: 100%;
    text-decoration: none;
}

.tree li a{
    height: 120px;
    width: 140px;
    padding: 5px 10px;
    text-decoration: none;
    background-color: white;
    color: #8b8b8b;
    font-family: arial, verdana, tahoma;
    font-size: 11px;
    display: inline-block;  
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    background: #cbcbcb; color: #000;
    -transition: all 0.5s;
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
}

/*Time for some hover effects*/
/*We will apply the hover effect the the lineage of the element also*/
.tree li a:hover, .tree li a:hover+ul li a {
    background: #cbcbcb; color: #000;
}
/*Connector styles on hover*/
.tree li a:hover+ul li::after, 
.tree li a:hover+ul li::before, 
.tree li a:hover+ul::before, 
.tree li a:hover+ul ul::before{
    border-color:  #94a0b4;
}
/*#endregion*/
</style>
                <div class="row">
                    
                   <!-- LADO ESQUERDO -->
                    <div class="col-lg-12">
                        <!-- MEUS PROJETOS -->
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>TREINAMENTOS</h4>
                                </div>
                                <div class="portlet-widgets">
                                    <a href="#"><i class="fa fa-gear"></i></a>
                                    <span class="divider"></span>
                                    <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#buttons"><i class="fa fa-chevron-down"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="buttons" class="panel-collapse collapse in">
                                <div class="portlet-body">
                                   <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                            <tr style="background-color: orange;">
                                                <th>ID</th>
                                                <th>PROJETO</th>
                                                <th>ATA</th>
                                                <th>DATA</th>
                                                <th>LOCAL</th>
                                                <th>PAUTA</th>
                                                <th>RESPONSAVEL</th>
                                                <th>PARTICIPANTES</th>
                                                <th>FACILITADOR(ES)</th>
                                                <th>STATUS</th>
                                               
                                                <th>ABRIR</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                              <?php

                                function geraTimestamp($data) {
                                    $partes = explode('/', $data);
                                    return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
                                }
                                ?>
                                            <?php
                                                $wu4[''] = '';
                                                $cont = 1; 
                                               
                                                foreach ($treinamentos as $treinamento) {
                                                    
                                                  //  $ata_user = $this->atas_model->getAtaUserByAtaUser($plano->idatas, $usuario);
                                                   // $result = $ata_user->id;
                                                     $status_facilitador = $this->atas_model->getFacilitador_Treinamento($treinamento->ata);
                                                     $status = $status_facilitador->status;
                                                     
                                                     if($status == 1){
                                                         $status_t = "";
                                                         $icon = "lock";
                                                         $btn = "red";
                                                     }else if($status == 0){
                                                         $status_t = "";
                                                         $icon = "unlock-alt";
                                                         $btn = "green";
                                                     }
                                                                          
                                                     /*
                                                      * PARTICIPANTES
                                                      */
                                                     $quantidade_participante = $this->atas_model->getParticipante_Treinamento($treinamento->ata);
                                                     $qtde = $quantidade_participante->quantidade;
                                                     
                                                     
                                                      $projetos_usuario = $this->atas_model->getAtaProjetoByID_ATA($treinamento->id);
                                                ?>   
                                           
                                            <tr   class="odd gradeX">
                                                        <td><?php echo $cont++; ?>   </td> 
                                                        <td><?php echo $projetos_usuario->projetos; ?></td> 
                                                        <td><?php echo $treinamento->id; ?></td>     
                                                        <th><?php echo $this->sma->hrld($treinamento->data_ata); ?></th> 
                                                        <td><?php echo $treinamento->local; ?></td>
                                                         <td><p><font  style="font-size: 14px; "><?php echo $treinamento->pauta; ?> </font></p></td>     
                                                         <td class="center"><?php echo $treinamento->responsavel_elaboracao; ?></td>     
                                                         <td class="center"><?php echo $qtde; ?></td> 
                                                         
                                                          
                                                           
                                                       
                                                    
                                                        <td class="center">
                                                            <table>
                                                                    <?php
                                                                    $facilitadores_cadastrados_ata = $this->atas_model->getAtaFacilitadores_ByID_ATA($treinamento->id);
                                                                                    foreach ($facilitadores_cadastrados_ata as $facilitador) {
                                                                                      $facilitador_id_usuario = $facilitador->usuario;
                                                                                        
                                                                                        $facilitador = $this->site->geUserByID($facilitador_id_usuario);
                                                                                        
                                                                                    
                                                                        ?>
                                                                        <tr>
                                                                            <td class="center">
                                                                               <?php echo $facilitador->first_name.' '.$facilitador->last_name; ?>
                                                                            </td>
                                                                        </tr>
                                                                    
                                                             <?php 
                                                             
                                                                        
                                                                    }
                                                             ?>
                                                            </table>            
                                                        </td>
                                                          <td class="center "><i style="margin-right: 10px;" class="btn btn-<?php echo $btn; ?> fa fa-<?php echo $icon; ?>"></i><?php echo $status_t; ?></td>    
                                                       
                                                        <td class="center">
                                                            <a style="background-color: chocolate; color: #ffffff;" class="btn fa fa-folder-open-o" href="<?= site_url('Login_Projetos/ver_dados_treinamento/'.$treinamento->id); ?>"> </a>
                                                        </td>
                                                         
                                            </tr>
                                                <?php
                                                
                                                }
                                                ?>
                                            
                                            
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                                </div>
                            </div>
                         </div>
                     </div>
                 </div>   
    <!-- /#wrapper -->

    <!-- GLOBAL SCRIPTS -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/jquery.popupoverlay.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/defaults.js"></script>
    <!-- Logout Notification Box -->
   
    <!-- /#logout -->
    <!-- Logout Notification jQuery -->
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/logout.js"></script>
    <!-- HISRC Retina Images -->
    <script src="<?= $assets ?>dashboard/js/plugins/hisrc/hisrc.js"></script>

    <!-- PAGE LEVEL PLUGIN SCRIPTS -->
    <script src="<?= $assets ?>dashboard/js/plugins/ladda/spin.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/ladda/ladda.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/bootstrap-multiselect/bootstrap-multiselect.js"></script>

    <!-- THEME SCRIPTS -->
    <script src="<?= $assets ?>dashboard/js/flex.js"></script>
    <script src="<?= $assets ?>dashboard/js/demo/buttons-demo.js"></script>

    <script src="<?= $assets ?>dashboard/js/demo/calendar-demo.js"></script>
        <script src="<?= $assets ?>dashboard/js/plugins/fullcalendar/fullcalendar.min.js"></script>
</body>

</html>
