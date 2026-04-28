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
               <h3>ADD UNIDADE SETOR</h3>
               <div class="clearfix"></div>
               <hr class="hr-panel-heading" />
               <?php } ?>
               
           
              <!-- form inicio -->
              <?php echo form_open('admin/unidades_hospitalares/update_setor'); ?>
              <input type="hidden" id="id" name="id" class="form-control" value="<?php echo $setor_id->id; ?>">
      
               
               <div class="col-lg-offset-md2 col-lg-6 text-left">  <!----dados ppp--->
            
                 <label><font style="color: red">*</font>  Nome</label>
                 <input type="text" name="nome" required="true" value="<?php echo $setor_id->nome; ?>" class="form-control" >     
                  <br> 
                 <label> Descrição</label>
                 <input type="text" name="descricao" value="<?php echo $setor_id->descricao; ?>" class="form-control" >     
                  <br> 
                 
              
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
