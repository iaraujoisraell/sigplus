<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
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
                            
                             <a href="#" class="btn btn-success "  onclick="Mudarestado('minhaDiv')"><?php echo 'Novo Registro +'; ?></a>

                             <br><br><br>
                             <div class="clearfix"></div>
                             <div style="display: none" id="minhaDiv">
                                 
                                
                                <?php 
                                $medicos   = $this->Medicos_model->get();
                                //$items = $this->invoice_items_model->get_grouped_all_procedimentos(); 
                                ?>
                                   
                               
                              
                                 
                                <?php echo form_open(admin_url('tabela_preco/add_repasse/')); ?>
                                <hr class="hr-panel-heading" />
                                
                                <div id="procedimentos">
                                   <?php //$this->load->view('admin/invoice_items/select_item_padrao'); ?>
                                </div>
                                
                                <div class="col-md-12">
                                    <label>Médicos</label>
                                    <div style="width: 100%" class="items-wrapper select-placeholder<?php if(has_permission('items','','create')){ echo ' input-group-select'; } ?>">
                                        <div class="items-select-wrapper">
                                            <select multiple="true" required="true" style="width: 100%" name="medicoid[]" class="selectpicker no-margin<?php if($ajaxItems == true){echo ' ajax-search';} ?><?php if(has_permission('items','','create')){ echo ' _select_input_group'; } ?>" data-width="false" id="item_select" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
                                              <option value="">Selecione o profissional</option>
                                                <?php foreach($medicos as $item){ ?>
                                               <option value="<?php echo $item['medicoid']; ?>" > <?php echo $item['nome_profissional']; ?></option>
                                               <?php } ?>
                                            </select>
                                         </div>
                                    </div>  
                                    <br>
                                </div>

                                <div class="col-md-12">
                                    <label>Procedimentos</label>
                                    <div style="width: 100%" class="items-wrapper select-placeholder<?php if(has_permission('items','','create')){ echo ' input-group-select'; } ?>">
                                        <div class="items-select-wrapper">
                                            <select  required="true" style="width: 100%" name="item_id" class="selectpicker no-margin<?php if($ajaxItems == true){echo ' ajax-search';} ?><?php if(has_permission('items','','create')){ echo ' _select_input_group'; } ?>" data-width="false" id="item_select" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
                                              <option value=""></option>
                                              <?php foreach($items as $group_id=>$_items){ ?>
                                              <optgroup data-group-id="<?php echo $group_id; ?>" label="<?php echo $_items[0]['group_name']; ?>">
                                               <?php foreach($_items as $item){ ?>
                                               <option value="<?php echo $item['id']; ?>" data-subtext="<?php echo strip_tags(mb_substr($item['long_description'],0,200)).'...'; ?>">(<?php echo app_format_number($item['rate']); ; ?>) <?php echo $item['description']; ?></option>
                                               <?php } ?>
                                             </optgroup>
                                             <?php } ?>
                                           </select>
                                         </div>
                                    </div>  
                                    <br>
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
                                <label>Valor do Procedimento</label>
                                <input type="text" name="valor" id="valor" onKeyPress="return(moeda(this,'.',',',event))" maxlength="7" value="<?php echo $amount; ?>"  class="form-control">
                                 
                                <?php echo render_textarea( 'observacao', 'observacao', '',array( 'rows'=>5)); ?>
                                <button class="btn btn-info pull-right mbot15">
                                    <?php echo _l( 'submit'); ?>
                                </button>
                                <?php echo form_close(); ?>
                            </div>

                             <?php
                            
                           } ?>
                           </div>
                        
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
                                        <?php echo 'Valor'; ?>
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
                                $tabelas_preco = $this->Invoice_items_model->get_tabela_preco(); 
                                foreach($tabelas_preco as $tab){

                               
                                $usuario = get_staff_full_name($tab['usuario_log']);
                             
                                ?>
                            <tr>
                                <td>
                                  <?php echo $tab['item_id'];   ?>
                                </td>
                                <td>
                                  <?php echo $tab['procedimento'];   ?>
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