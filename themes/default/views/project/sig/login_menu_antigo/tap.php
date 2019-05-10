<br>
<div class="box">
    <?php
                    $usuario = $this->session->userdata('user_id');
                    $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                    $perfil_atual = $projetos->group_id;
                    $perfis_user = $this->site->getUserGroupAtual($perfil_atual);

                  
                  
                    ?> 
   
    <div class="box-header">
         <center>
             <h2 style="text-align: center; "><?php echo $documentacao->nome_documento; ?></h2>
             
        </center>
        
    </div>
    <?php
 $projeto_doc = $documentacao->projeto;
 $projetos = $this->projetos_model->getProjetoByID($projeto_doc);
?>
    <div  class="box-content">
       
        
        <div class="row">
           
           
                    <div class="col-lg-6">
                        <p style=" font-size: 16px; background-color: gray; color: #ffffff;" class="text"><?= lang('Projeto :  '); ?><?php echo $projetos->projeto; ?></p>
                        </div>
            
                        <div class="col-lg-6">
                        <p style=" font-size: 16px; background-color: gray; color: #ffffff;" class="text"><?= lang('Gerente do Projeto :  '); ?><?php echo $projetos->gerente_area; ?></p>
                        </div>
                   
                   
            
           
                
                
           
            
                
            <br>
          
                    <div class="col-lg-12">
                       
                            
                                     <table  style="width: 100%;"  class="table ">
                                        
                                        <tbody>
                                             <?php
                                                
                                                $wu4[''] = '';
                                                $cont = 1;
                                                foreach ($taps as $tap) {
                                                       
                                                ?>   
                                                    <tr >
                                                         
                                                        <td style="width: 100%; " >
                                                            
                                                            <div class="col-lg-12"> 
                                                                <p style="font-weight: bold; font-size: 16px; " ><?php echo $tap->titulo; ?></p>
                                                            </div>
                                                        
                                                        </td>
                                                    </tr>
                                                    <?php if($tap->descricao){ ?>
                                                    <tr >
                                                        <td style="text-align: justify;"><?php echo $tap->descricao; ?></td>
                                                    </tr>
                                                    <?php } ?>
                                                    <?php if($tap->anexo){ ?>
                                        
                                                    <tr >
                                                        <td style="margin-top: 10px;">
                                                            <img width="<?php echo $tap->largura; ?>%;" height="<?php echo $tap->altura; ?>%;" src="<?php echo base_url(); ?>assets/uploads/projetos/<?php echo $tap->anexo; ?>">
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                       
                                                     <tr >
                                                         <td style="height: 20px;  ">
                                                            
                                                         </td>
                                                     </tr>    
                                                   
                                                <?php
                                                }
                                                ?>
                                            
                                            
                                           
                                            
                                        </tbody>
                                    </table>
                         
                            <!-- /.portlet-body 
                            
                            src="'. base_url() . 'assets/uploads/logos/'.$logo_top.'"
                            
                            -->
                      
                        <!-- /.portlet -->

                    </div>
            
            
            
                    
                    
                    
           
    </div>
</div>


</div>




