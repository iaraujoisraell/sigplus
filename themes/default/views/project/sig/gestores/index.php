<?php 

           $usuario = $this->session->userdata('user_id');
           $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           $projetos_usuario->projeto;        
           
           
?>
    <div class="box">
        
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-list"></i><?=lang('Lista de Gestores') ;?>
               
        </h2>
        
    </div>
    
    <?php if ($Owner || $GP['bulk_actions']) {?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
    </div>
    <?=form_close()?>
<?php }



?>
        <script>
        $('#idTr').bind('click', function() {
  alert("Linha foi clicada");
});
        </script>
        
        <style>
            table#tableTrClick tr.trClick{background: #000; color: #fff; cursor: pointer;}
table#tableTrClick tr.trClick:hover{background: green; color: #fff; font-weight: bold;}

        </style>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                        <div class="portlet portlet-default">
                         <div style="text-align: right" class="col-lg-12">
                  </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                         <tr>
                                       
                                                <th>ID</th>
                                                <th>USUÁRIO</th>
                                                <th>SETOR(ES)</th>
                                                <th>PROJETO</th>
                                                <th>Editar</th>
                                                
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                             <?php
                                                $wu4[''] = '';
                                                //$cont = 1;
                                                
                                                 
                                                  $usuario = $this->session->userdata('user_id');
                                                 $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                                                 $cadastroUsuario = $this->site->getPerfilAtualByID($usuario);
                                                 $projeto_atual = $cadastroUsuario->projeto_atual;
                                                foreach ($users as $plano) {
                                                
                                                 
                                                    
                                                ?>   
                                           
                                            <tr   class="odd gradeX">
                                                        <td><?php echo $plano->users; ?>   </td>  
                                                        <td><?php echo $plano->fname.' '; ?><?php echo $plano->lname; ?></td>     
                                                        <td class="center">
                                                            <table>
                                                                    <?php
                                                                    $setores_vinculadas = $this->site->getAllSetoresVinculados($plano->users,$projeto_atual );
                                                                    foreach ($setores_vinculadas as $setor_vinculado) {
                                                                        IF($setor_vinculado){
                                                                        ?>
                                                                        <tr>
                                                                            <td class="center">
                                                                                 <?php echo $setor_vinculado->setor; ?> 
                                                                            </td>
                                                                        </tr>
                                                             <?php 
                                                                        }else{
                                                                           
                                                                        }
                                                                    }
                                                             ?>
                                                            </table>            
                                                        </td>
                                                        <td>
                                                             <?php  IF($setor_vinculado){  echo $projetos->projeto; } ?>
                                                        </td>
                                                        <td class="center">
                                                            <a style="background-color: chocolate; color: #ffffff;" class="btn fa fa-edit"  href="<?= site_url('gestores/edit/'.$plano->users); ?>"> </a>
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
                            <!-- /.portlet-body -->
                        </div>
                        <!-- /.portlet -->

                    </div>
        </div>
    </div>
</div>

