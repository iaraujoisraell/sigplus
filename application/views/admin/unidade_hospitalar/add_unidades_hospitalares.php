<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

   <script type="text/javascript">
        function mascara(telefone){ 
            if(telefone.value.length == 0)
                telefone.value = '(' + telefone.value; 
            if(telefone.value.length == 3)
                telefone.value = telefone.value + ') '; 
 
            if(telefone.value.length == 10)
                telefone.value = telefone.value + '-';
        }
        
        function mascara_cpf(cpf){ 
            if(cpf.value.length == 3)
                cpf.value = cpf.value + '.'; 
            if(cpf.value.length == 7)
                cpf.value = cpf.value + '.'; 
 
            if(cpf.value.length == 11)
                cpf.value = cpf.value + '-';
        }
        
        function mascara_cnpj(cnpj){ 
           if(cnpj.value.length == 2)
                cnpj.value = cnpj.value + '.'; 
           if(cnpj.value.length == 6)
                cnpj.value = cnpj.value + '.'; 
            
            if(cnpj.value.length == 10)
                cnpj.value = cnpj.value + '/'; 
 
            if(cnpj.value.length == 15)
                cnpj.value = cnpj.value + '-';
        }
        
         
        function mascara_cep(cep){ 
            if(cep.value.length == 2)
                cep.value = cep.value + '.'; 
            if(cep.value.length == 6)
                cep.value = cep.value + '-'; 
            if(cep.value.length == 11)
                cep.value = cep.value + '';
        }

    </script>
    
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
      
    <div class="row">
             <!---------- <div class="col-md-12">--->
        <div class="panel_s">
          <div class="panel-body">
               
           
               <?php hooks()->do_action('before_unidade_hospitalar_page_content'); ?>
               <?php if(has_permission('unidade_hospitalar','','create')){ ?>
               <h3>ADD UNIDADE HOSPITALAR</h3>
               <div class="clearfix"></div>
               <hr class="hr-panel-heading" />
               <?php } ?>
              <!-- form inicio -->
              <?php echo form_open('admin/unidades_hospitalares/add_edit_unidades_hospitalares'); ?>
              <input type="hidden" id="id" name="id" class="form-control" value="<?php echo $unidade_id->id; ?>">
               
               <div class="col-lg-offset-md2 col-lg-6 text-left">  <!----dados ppp--->
            
                 <label><font style="color: red">*</font>  Razão Social</label>
                 <input type="text" name="razao_social" required="true" value="<?php echo $unidade_id->razao_social; ?>" class="form-control" >     
                  <br> 
                 <label><font style="color: red">*</font>  Nome Fantasia</label>
                 <input type="text" name="fantasia" required="true" value="<?php echo $unidade_id->fantasia; ?>" class="form-control" >     
                  <br> 
                 
              
                 <label><font style="color: red">*</font>  Nome Responsável</label>
                 <input type="text" name="nome_responsavel" required="true" value="<?php echo $unidade_id->nome_responsavel; ?>" class="form-control" >     
                 <br> 
               
                  <label><font style="color: red">*</font>  CNPJ</label>
                  <input type="text" name="cnpj" placeholder="XX. XXX. XXX/0001-XX (somente números)" onkeypress="mascara_cnpj(this);" maxlength="18" required="true" value="<?php echo $unidade_id->cnpj; ?>" class="form-control" >   
                  <br>
               
                    <label><font style="color: red">*</font>Telefone Responsável</label>
                  <input type="text" name="telefone_responsavel" id="" placeholder="(99) 9 9999-9999" onkeypress="mascara(this);" maxlength="15" required="true" value="<?php echo $unidade_id->telefone_responsavel; ?>" class="form-control">
                  <br>
                 
                  <label> Email </label>
                  <input type="email" name="email" value="<?php  echo $unidade_id->email; ?>" class="form-control">
                 
               </div>
         
          
               <div class="col-lg-offset-md2 col-lg-6 text-left">   
               <label> Cep</label>
               <input type="text" name="cep" placeholder="XX.XXX-XXX (somente números)" onkeypress="mascara_cep(this);" maxlength="10" value="<?php echo $unidade_id->cep; ?>" class="form-control" >   
               <br>
               <?php echo render_input( 'cidade', 'cidade',$value = $unidade_id->cidade); ?>
                <label> UF </label>
                <input type="text" name="uf" value="<?php echo $unidade_id->uf; ?>" class="form-control">
                <br>
                <?php echo render_input( 'bairro', 'bairro',$value = $unidade_id->bairro); ?>
                <?php  //echo render_textarea( 'endereco', 'endereco',$value = $unidade_id->endereco); ?> 
                <label> Endereço </label>
                <textarea type="text" name="endereco" class="form-control"><?php echo $unidade_id->endereco; ?></textarea>    
                <br>
                 <label> Complemento </label>
                <textarea type="text" name="complemento" class="form-control"><?php echo $unidade_id->complemento; ?></textarea>    
                 
               </div>
            
               <div class="col-lg-offset-md4 col-lg-6 text-left"> 
              <label>Situação</label>
              <select name= "situacao" value="<?php echo $unidade_id->situacao; ?>" class="form-control">
              <option>Ativa</option>
              <option>Inativa</option>
              </select>   
              <br> 
       
              <label>Tipo de Contrato</label>
              <select name= "tipo_contrato" value="<?php echo $unidade_id->tipo_contrato; ?>" class="form-control">
              <option>Ativa</option>
              <option>Inativa</option>
              </select> 
              <br>
         
           
              <label>Gera Escala</label>
              <select name = "gera_escala" value="<?php echo $unidade_id->gera_escala; ?>"class="form-control">
              <option>1</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
              <option>5</option>
              </select>
              <br>     
            </div>
         
            <div class="col-lg-offset-md2 col-lg-6 text-left">    
              <label>Entra na Folha</label>
              <select name= "entra_folha_atual" value="<?php echo $unidade_id->entra_folha_atual; ?>" class="form-control">
              <option>1</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
              <option>5</option>
              </select>   
              <br>
    
             <label> Codigo rubrica </label>
             <input type="number" name="codigo_rubrica" value="<?php echo $unidade_id->codigo_rubrica; ?>" class="form-control">
             <br>
        
      
             <label>Unidade Principal</label>
             <select name= "unidade_principal" value="<?php echo $unidade_id->unidade_principal; ?>" class="form-control">
             <option>1</option>
             <option>2</option>
             <option>3</option>
             <option>4</option>
             <option>5</option>
             </select>   
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
          <!-- class content -->
    </div>
  




<?php init_tail(); ?>

</body>
</html>
