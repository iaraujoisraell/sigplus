<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="customer-profile-group-heading"><?php echo _l('medico_add_edit_profile').' '._l('medico'); ?></h4>
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

<div class="row">
   <?php echo form_open(admin_url('medicos/medico'),array('id'=>'medico_form')); ?>
    <input type="hidden"  id="id" name="id"  value="<?php echo $medico->medicoid; ?>" class="form-control">
   <div class="additional"></div>
   <div class="col-md-12">
       <div class="modal-body">
      <div class="tab-content mtop15">      
        <div class="row">
            <div class="col-md-6">
                    <label><font style="color: red">*</font> Nome do profissional</label>
                    <input type="text" required="true" title="Somente Números. 11 Caracteres" id="nome_profissional" name="nome_profissional" placeholder="Nome do profissional" value="<?php echo $medico->nome_profissional; ?>" class="form-control">
                  
                    <br>
                    <?php //if(get_option('company_requires_vat_number_field') == 1){ }
                    $value=( isset($medico) ? $medico->cns : '');
                    echo render_input( 'cns', 'cns_medico',$value);
                  ?>
                  <?php $value=( isset($medico) ? $medico->codigo_registro : ''); ?>
                  <?php echo render_input( 'codigo_registro', 'crm_medico',$value); ?>
                
                    <label><font style="color: red">*</font> CPF </label>
                  <input type="text" maxlength="14" minlength="11" onkeypress="mascara_cpf(this);" required="true" title="Somente Números. 11 Caracteres" id="cpf" name="cpf" placeholder="000.000.000-00 (somente números)" value="<?php echo $medico->cpf; ?>" class="form-control" >   
                  <br>
             
               </div>
            <div class="col-md-6">
                 <label> <font style="color: red">*</font> Celular</label>
                  <input type="text" name="celular" id="celular" placeholder="(99) 9 9999-9999" onkeypress="mascara(this);" maxlength="15"  required="true" value="<?php echo $medico->celular; ?>" class="form-control">
                  <br>
                
                <?php $value=( isset($medico) ? $medico->email : ''); ?>
                <?php echo render_input( 'email', 'client_email',$value); ?>   
                <br>
                <label>Repasse</label>
                <br>
                <?php 
                    if($medico->repasse == 1){
                        $checked_on = 'checked';
                        $checked_off = '';
                    }else  if($medico->repasse == 0){
                        $checked_on = '';
                        $checked_off = 'checked';
                    }else{
                        $checked_on = 'checked';
                        $checked_off = '';
                    }
                ?>
                
                <input <?php echo $checked_on; ?> name="repasse" type="radio" value="1"> SIM
                <input <?php echo $checked_off; ?> name="repasse" type="radio" value="0"> NÃO
                
                <br><br>
                <label>Ativo</label>
                <br>
                <?php 
                
               
                    if($medico->active == 1){
                        $checked_on = 'checked';
                        $checked_off = '';
                    }else if($medico->active == 0){
                        $checked_on = '';
                        $checked_off = 'checked';
                    }else{
                        $checked_on = 'checked';
                        $checked_off = '';
                }
                ?>
                <input <?php echo $checked_on; ?> name="active" type="radio" value="1"> SIM
                <input <?php echo $checked_off; ?> name="active" type="radio" value="0"> NÃO
                
                 <br> <br>  <br>  <br>
                
               </div>
             <br> <br> <br>  <br>
               <h4 class="customer-profile-group-heading"><?php echo ('Dados Empresa'); ?></h4>   
               
               <br>
            <div class="col-md-6">
            
             
                  <label>  CNPJ</label>
                  <input type="text" name="cnpj_emp" placeholder="XX. XXX. XXX/0001-XX (somente números)" onkeypress="mascara_cnpj(this);" maxlength="18" value="<?php echo $medico->cnpj_emp; ?>" class="form-control" >   
                  <br>
                  
                  <label>Razão Social</label>
                  <input type="text" name="razao_social" value="<?php echo $medico->razao_social; ?>" class="form-control" >     
                  <br> 
                  <label> Nome fantasia</label>
                  <input type="text" name="fantasia" value="<?php echo $medico->fantasia; ?>" class="form-control" >     
                  <br>
                  
                  <label>Telefone</label>
                  <input type="text" name="telefone_empresa" id="" placeholder="(99) 9 9999-9999" onkeypress="mascara(this);" maxlength="15" value="<?php echo $medico->telefone_empresa; ?>" class="form-control">
                  <br>  
                   
                  <label> E-mail </label>
                  <input type="email" name="email_empresa" value="<?php  echo $medico->email_empresa; ?>" class="form-control">
                  <br>
                  <label> Dados Bancários </label>
                  <textarea type="text" name="dados_bancarios_emp" class="form-control"><?php echo $medico->dados_bancarios_emp;?></textarea>    
                  <br>
                
                  <label> Chave PIX</label>
                  <textarea type="text" name="chave_pix_emp" class="form-control"><?php echo $medico->chave_pix_emp; ?></textarea>    
                  <br>
                
                  </div>
            
                 <div class="col-md-6">
            
                 <label> Cep</label>
                 <input type="text" name="cep_empresa" placeholder="XX.XXX-XXX (somente números)" onkeypress="mascara_cep(this);" maxlength="10" value="<?php echo $medico->cep_empresa; ?>" class="form-control" >   
                 <br>
                 <?php //echo render_input( 'bairro_empresa', 'Bairro',$value = $medico->bairro_empresa); ?>
                 <?php  //echo render_textarea( 'endereco', 'endereco',$value = $unidade_id->endereco); ?> 

                 <label> Bairro</label>
                 <textarea type="text" name="bairro_empresa" class="form-control"><?php echo $medico->bairro_empresa; ?></textarea>    
                 <br>
                 <label> Endereço</label>
                 <textarea type="text" name="endereco_emp" class="form-control"><?php echo $medico->endereco_emp; ?></textarea>    
                 <br>
                 <label> Complemento</label>
                 <textarea type="text" name="complemento_emp" class="form-control"><?php echo $medico->complemento_emp; ?></textarea>    
                 
            
            
        </div>
            
      </div>
     </div>      
    <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
         </div>   
   </div>
   <?php echo form_close(); ?>
</div>


