<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

  <div class="content">
      
    <div class="row">
             <!---------- <div class="col-md-12">--->
        <div class="panel_s">
          <div class="panel-body">
               
           
               <?php hooks()->do_action('before_unidade_hospitalar_page_content'); ?>
               <?php if(has_permission('unidade_hospitalar','','create')){ ?>
               <h3>ADD ESCALA FIXA PARA : <?php echo $info->unidade.'/'.$info->setor;  ?> </h3>
               <h2><?php echo $info->hora_inicio.'h  - '.$info->hora_fim.'h';  ?></h2>
               <div class="clearfix"></div>
               <hr class="hr-panel-heading" />
               <?php } ?>
              <!-- form inicio -->
              <?php echo form_open('admin/escala_fixa/add_medicos_horarios_disponiveis'); ?>
              <input type="hidden" name="config_id" class="form-control" value="<?php echo $info->id; ?>">
              <input type="hidden" name="unidade_id" class="form-control" value="<?php echo $unidade_id; ?>">
               
              <!----SEGUNDA FEIRA--->
               <div class="col-lg-offset-md2 col-lg-3 text-left">  
                <?php if ($info->segunda > 0) { ?>
                 <label><font style="color: red">*</font>  Segunda-Feira <?php echo '('.$info->segunda.') Vagas' ?> </label>
                 <br><br>
                 <?php //for($seg = 1; $seg <= $info->segunda; $seg++){ ?>                 
                      
                 <select style="height: 400px;" name="segunda[]" multiple="true" class="form-control">
                           <option value="">Selecione o(s) Médico(s)</option>  
                        <?php foreach($medicos as $medico){ ?>   
                            <option value="<?php echo $medico['medicoid']; ?>"><?php echo $medico['nome_profissional'].' ['.$medico['cpf'].']'; ?></option>
                        <?php } ?>
                      </select>
                      <br>   
                 <?php //} ?>
                <?php } ?>
               </div>
              
              <!----TERÇA FEIRA--->
              <div class="col-lg-offset-md2 col-lg-3 text-left">  
                <?php if ($info->terca > 0) { ?>
                 <label><font style="color: red">*</font>  Terça-Feira <?php echo '('.$info->terca.') Vagas' ?> </label>
                 <br><br>
                 <?php //for($seg = 1; $seg <= $info->terca; $seg++){ ?>                 
                      
                       
                       <select style="height: 400px;" name = "terca[]" multiple="true" class="form-control">
                         <option value="">Selecione um Médico</option>        
                        <?php foreach($medicos as $medico){ ?>   
                        <option value="<?php echo $medico['medicoid']; ?>"><?php echo $medico['nome_profissional'].' ['.$medico['cpf'].']'; ?></option>
                        <?php } ?>
                      </select>
                      <br>   
                 <?php //} ?>
                <?php } ?>
               </div>
              
              <!----QUARTA FEIRA--->
              <div class="col-lg-offset-md2 col-lg-3 text-left"> 
                <?php if ($info->quarta > 0) { ?>
                 <label><font style="color: red">*</font>  Quarta-Feira <?php echo '('.$info->quarta.') Vagas' ?> </label>
                 <br><br>
                 <?php //for($seg = 1; $seg <= $info->quarta; $seg++){ ?>                 
                      
                       <select style="height: 400px;" name = "quarta[]" multiple="true" class="form-control">
                         <option value="">Selecione um Médico</option>       
                        <?php foreach($medicos as $medico){ ?>   
                        <option value="<?php echo $medico['medicoid']; ?>"><?php echo $medico['nome_profissional'].' ['.$medico['cpf'].']'; ?></option>
                        <?php } ?>
                      </select>
                      <br>   
                 <?php //} ?>
                <?php } ?>
               </div>
              
              <!----QUINTA FEIRA--->
              <div class="col-lg-offset-md2 col-lg-3 text-left">  
                <?php if ($info->quinta > 0) { ?>
                 <label><font style="color: red">*</font>  Quinta-Feira <?php echo '('.$info->quinta.') Vagas' ?> </label>
                 <br><br>
                 <?php //for($seg = 1; $seg <= $info->segunda; $seg++){ ?>                 
                       <select style="height: 400px;" name = "quinta[]" multiple="true" class="form-control">
                         <option value="">Selecione um Médico</option>
                        <?php foreach($medicos as $medico){ ?>                          
                        <option value="<?php echo $medico['medicoid']; ?>"><?php echo $medico['nome_profissional'].' ['.$medico['cpf'].']'; ?></option>
                        <?php } ?>
                      </select>
                      <br>   
                 <?php //} ?>
                <?php } ?>
               </div>
              
              <div class="col-lg-offset-md2 col-lg-12 text-left">
                  <br><br>
              </div>
              
              <!----SEXTA FEIRA--->
              <div class="col-lg-offset-md2 col-lg-3 text-left">  
                <?php if ($info->sexta > 0) { ?>
                 <label><font style="color: red">*</font>  Sexta-Feira <?php echo '('.$info->sexta.') Vagas' ?> </label>
                 <br><br>
                 <?php //for($seg = 1; $seg <= $info->sexta; $seg++){ ?>                 
                       <select style="height: 400px;" name = "sexta[]" multiple="true" class="form-control">
                        <option value="">Selecione um Médico</option>        
                        <?php foreach($medicos as $medico){ ?>
                        <option value="<?php echo $medico['medicoid']; ?>"><?php echo $medico['nome_profissional'].' ['.$medico['cpf'].']'; ?></option>
                        <?php } ?>
                      </select>
                      <br>   
                 <?php //} ?>
                <?php } ?>
               </div>
              
              <!----SÁBADO--->
              <div class="col-lg-offset-md2 col-lg-3 text-left">  <!----dados ppp--->
                <?php if ($info->sabado > 0) { ?>
                 <label><font style="color: red">*</font>  Sábado <?php echo '('.$info->sabado.') Vagas' ?> </label>
                 <br><br>
                 <?php //for($seg = 1; $seg <= $info->sabado; $seg++){ ?>                 
                       <select style="height: 400px;" name = "sabado[]" multiple="true" class="form-control">
                        <option value="">Selecione um Médico</option>      
                        <?php foreach($medicos as $medico){ ?>
                        <option value="<?php echo $medico['medicoid']; ?>"><?php echo $medico['nome_profissional'].' ['.$medico['cpf'].']'; ?></option>
                        <?php } ?>
                      </select>
                      <br>   
                 <?php //} ?>
                <?php } ?>
               </div>
              
              <!----DOMINGO--->
              <div class="col-lg-offset-md2 col-lg-3 text-left">  <!----dados ppp--->
                <?php if ($info->domingo > 0) { ?>
                 <label><font style="color: red">*</font>  Domingo <?php echo '('.$info->domingo.') Vagas' ?> </label>
                 <br><br>
                 <?php //for($seg = 1; $seg <= $info->domingo; $seg++){ ?>                 
                       <select style="height: 400px;" name = "domingo[]" multiple="true" class="form-control">
                           <option value="">Selecione um Médico</option>     
                        <?php foreach($medicos as $medico){ ?>
                        <option value="<?php echo $medico['medicoid']; ?>"><?php echo $medico['nome_profissional'].' ['.$medico['cpf'].']'; ?></option>
                        <?php } ?>
                      </select>
                      <br>   
                 <?php //} ?>
                <?php } ?>
               </div>
         
          
         
       <!---------- --->
            <div class="col-lg-offset-md4 col-lg-6 text-left">
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
              </div>
           </div>   
                      
           <!-- /.modal-content -->

 
             <!-------------------------------fimmm ---------------------------------------------------->
                  

                  
      
      
      
      </div>
   </div>

<!----------   </div> col 12---------------------------------->
          
          
            
        <?php// echo form_close(); ?>
    </div>
        </div>          

