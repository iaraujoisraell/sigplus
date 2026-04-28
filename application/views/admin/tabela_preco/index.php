<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php

init_head();

?>
<script> 
function Mudarestado(el) {
    var display = document.getElementById(el).style.display;
    if(display == "none")
        document.getElementById(el).style.display = 'block';
    else
        document.getElementById(el).style.display = 'none';
}

 function moeda(a, e, r, t) {
    let n = ""
      , h = j = 0
      , u = tamanho2 = 0
      , l = ajd2 = ""
      , o = window.Event ? t.which : t.keyCode;
    if (13 == o || 8 == o)
        return !0;
    if (n = String.fromCharCode(o),
    -1 == "0123456789".indexOf(n))
        return !1;
    for (u = a.value.length,
    h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
        ;
    for (l = ""; h < u; h++)
        -1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
    if (l += n,
    0 == (u = l.length) && (a.value = ""),
    1 == u && (a.value = "0" + r + "0" + l),
    2 == u && (a.value = "0" + r + l),
    u > 2) {
        for (ajd2 = "",
        j = 0,
        h = u - 3; h >= 0; h--)
            3 == j && (ajd2 += e,
            j = 0),
            ajd2 += l.charAt(h),
            j++;
        for (a.value = "",
        tamanho2 = ajd2.length,
        h = tamanho2 - 1; h >= 0; h--)
            a.value += ajd2.charAt(h);
        a.value += r + l.substr(u - 2, u)
    }
    return !1
}
</script>
<div id="wrapper">
   <div class="content">
       <div class="row">
               <div class="col-md-12" id="small-table">
                  <div class="panel_s">
                     <div class="panel-body">
                        <div class="clearfix"></div>
                        <h3 class="customer-profile-group-heading"> <?php echo $title; ?> </h3>
                            <div class="col-md-6">
                             <?php if(has_permission('tesouraria','','repasses')){ ?>   
                            <a href="<?php echo admin_url('tabela_preco/add_tabela_preco'); ?>" class="btn btn-info pull-left mleft5" ><?php echo 'Novo Registro'; ?></a>
                            <!-- <a href="#" class="btn btn-success "  onclick="Mudarestado('minhaDiv')"><?php echo 'Novo Registro +'; ?></a> -->

                             <br><br><br>
                             <div class="clearfix"></div>
                             <div style="display: none" id="minhaDiv">
                                 
                                
                                <?php /* echo 'Convênio'; ?> 
                                    <select name="convenio" id="convenio" class="selectpicker"  onchange="filtraConvenio();" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option>SELECIONE O CONVENIO</option>
                                        <?php
                                         foreach($convenios as $convenio){
                                            if($invoice->convenio == $convenio['id']) {
                                                 $selected = 'selected="true" ';
                                                echo '<option value="'.$convenio['id'].'" selected="true" >'.$convenio['name'].'</option>';    
                                            }
                                            echo '<option value="'.$convenio['id'].'" >'.$convenio['name'].'</option>';
                                         }
                                       ?>
                                   </select> */ ?>
                                   
                               
                              
                                 
                                <?php echo form_open(admin_url('tabela_preco/add_repasse/')); ?>
                                <hr class="hr-panel-heading" />
                                
                                <div id="procedimentos">
                                   <?php $this->load->view('admin/invoice_items/select_item_padrao'); ?>
                                </div>
                               
                                <br>                                

                                <?php $hoje = date('Y-m-d'); ?>
                                
                                 <label>Data Início da Vigência</label>
                                 <input type="date" required="true" name="data_inicio" value="<?php echo $hoje; ?>" class="form-control">
                                
                               <!--  <label>Data Fim da Vigência</label>
                                 <input type="date" name="data_fim"  class="form-control"> -->
                                 <?php

                                 ?>
                                 
                                 <br>
                                 <?php
                                $amount = '0.00';
                                 //  echo render_input('valor','valor_repasse',$amount,'text',array('max'=>$saldo_final)); ?> 
                                <div class="col-md-6">
                                <label>Valor 1 da Vigência (principal)</label>
                                <input type="text" name="valor" id="valor" onKeyPress="return(moeda(this,'.',',',event))" maxlength="7" value="<?php echo $amount; ?>"  class="form-control">
                                </div>
                                 <div class="col-md-6">
                                <div class="form-group">
                        
                                    <label for="paymentmode" class="control-label"><?php echo _l('payment_mode'); ?></label>

                                    <select class="selectpicker" name="paymentmode[]" multiple="true" data-width="100%" data-none-selected-text="<?php echo 'NÃO SE APLICA'; ?>">
                                        <option value=""></option>
                                        <?php

                                        foreach($payment_modes as $mode){ ?>
                                        <option selected="true" value="<?php echo $mode['id']; ?>"><?php echo $mode['name']; ?></option>
                                       
                                        <?php } ?>
                                    </select>
                                </div>
                                </div>
                                 
                                 <div class="col-md-6">
                                <label>Valor 2 da Vigência</label>
                                <input type="text" name="valor2" id="valor2" onKeyPress="return(moeda(this,'.',',',event))" maxlength="7" value="<?php echo $amount; ?>"  class="form-control">
                                </div>
                                 <div class="col-md-6">
                                <div class="form-group">
                        
                                    <label for="paymentmode" class="control-label"><?php echo _l('payment_mode'); ?></label>

                                    <select class="selectpicker" name="paymentmode2[]" multiple="true" data-width="100%" data-none-selected-text="<?php echo 'NÃO SE APLICA'; ?>">
                                        <option value=""></option>
                                        <?php

                                        foreach($payment_modes as $mode){ ?>
                                        
                                        <option value="<?php echo $mode['id']; ?>"><?php echo $mode['name']; ?></option>
                                        
                                        <?php } ?>
                                    </select>
                                </div>
                                </div>
                                <?php echo render_textarea( 'observacao', 'observacao', '',array( 'rows'=>5)); ?>
                                <button class="btn btn-info pull-right mbot15">
                                    <?php echo _l( 'submit'); ?>
                                </button>
                                <?php echo form_close(); ?>
                            </div>

                             <?php
                            
                           } ?>
                           </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        
                        <?php echo form_open(admin_url('tabela_preco/index')); ?>
                        <!-- CONVENIO -->
                          <div class="col-md-4">
                              <div class="form-group">
                                   <label for="convenios"><?php echo _l('convenio'); ?></label>
                                   <select onchange="procedimentos_table_reload()" class="selectpicker"
                                           data-live-search="true"
                                           name="convenios_procedimentos"
                                           id="convenios_procedimentos"
                                           data-actions-box="true"
                                           
                                           data-width="100%"
                                           data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                               
                                               <?php
                                               foreach ($convenios as $convenio) {
                                                  if($convenio_filtro == $convenio['id']){
                                                       $selected = 'selected="true"';
                                                   }
                                                   ?>
                                                 <option   value="<?php echo $convenio['id']; ?>" <?php echo $selected; ?>><?php echo $convenio['name']; ?></option>

                                        <?php } ?>
                                   </select>
                                </div>
                          </div>
                          <!-- CATEGORIA -->
                          <div class="col-md-4">
                              <div class="form-group">
                                   <label for="categorias"><?php echo _l('expense_dt_table_heading_category'); ?></label>

                                    <select onchange="procedimentos_table_reload()" class="selectpicker"
                                       data-live-search="true"
                                       name="categorias_procedimentos"
                                       id="categorias_procedimentos"
                                       data-actions-box="true"
                                      
                                       data-width="100%"
                                       data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                       <?php foreach ($categorias as $categoria) {
                                           
                                           if($categoria_filtro == $categoria['id']){
                                               $selected = 'selected="true"';
                                           }
                                           
                                           ?>
                                        <option <?php echo $selected; ?> value="<?php echo $categoria['id']; ?>" ><?php echo $categoria['name']; ?></option>
                                    <?php } ?>
                                </select>
                              </div>
                          </div>  

                          
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data Início da Vigência</label>
                                <input type="date" name="inicio_vigencia" id="inicio_vigencia" data-live-search="true" data-width="100%" value="<?php echo $data_filtro; ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                        <button class="btn btn-info pull-right mbot15">
                            <?php echo 'Filtrar'; ?>
                        </button>
                        </div>      
                        <?php echo form_close(); ?>  
                          
                          
                        <div class="clearfix"></div>
                          <hr class="hr-panel-heading" />
                          <br><br>
                        <table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
                            <thead>

                                <tr>
                                    <th>
                                        <?php echo 'ID'; ?>
                                    </th>
                                    <th >
                                        <?php echo 'Procedimento'; ?>
                                    </th>
                                    <th >
                                        <?php echo 'Convênio'; ?>
                                    </th>
                                    <th >
                                        <?php echo 'Dt Início Vigência'; ?>
                                    </th>
                                    <th >
                                        <?php echo 'Dt Fim Vigência'; ?>
                                    </th>
                                    
                                    <th >
                                        <?php echo 'Valor 1'; ?>
                                    </th>
                                    <th >
                                        <?php echo 'Valor 2'; ?>
                                    </th>
                                    <th >
                                        <?php echo 'Observação'; ?>
                                    </th>
                                    <th >
                                        <?php echo 'Log'; ?>
                                    </th>
                                </tr>

                            </thead>
                            <tbody>
                                <?php
                                $soma_total = 0;
                                $soma_total_saida = 0;
                                
                                foreach($tabelas_preco as $tab){

                               
                                $usuario = get_staff_full_name($tab['usuario_log']);
                             
                                ?>
                            <tr>
                                <td>
                                  <?php echo $tab['item_id'];   ?>
                                </td>
                                <td>
                                  <?php echo $tab['codigo_tuss'].' - '.$tab['procedimento'];   ?>
                                </td>
                                <td>
                                  <?php echo $tab['convenio'];   ?>
                                </td>
                                <td>
                                  <?php echo _d($tab['data_inicio']);   ?>
                                </td>
                                
                                <td>
                                  <?php echo _d($tab['data_fim']);   ?>
                                </td>
                                
                                <td>
                                  <?php echo app_format_money($tab['valor'], 'R$');  ?>
                                </td>
                                
                                <td>
                                  <?php echo app_format_money($tab['valor2'], 'R$');  ?>
                                </td>
                                
                                <td>
                                  <?php echo $tab['observacao'];   ?>
                                </td>
                                <td>
                                  <?php echo _d($tab['data_log']).'<br>'.$usuario;   ?>
                                </td>


                            </tr>

                            <?php } ?>
                            
                
                            </tbody>
                                              
                        </table>
                     </div>
                  </div>
               </div>
               
            </div>
    </div>
</div>
<?php init_tail(); ?>

<script>
    
    function filtraConvenio() {
      $.ajax({
        type: "POST",
        url: "<?php echo admin_url("tabela_preco/retorno_procedimentos"); ?>",
        data: {
          convenio: $('#convenio').val()
        },
        success: function(data) {
          $('#procedimentos').html(data);
        }
      });
    }
     
</script>