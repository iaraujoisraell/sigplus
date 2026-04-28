<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<script>
    function somenteNumeros(num) {
        var er = /[^0-9.]/;
        er.lastIndex = 0;
        var campo = num;
        if (er.test(campo.value)) {
          campo.value = "";
        }
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
         <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'expense-form','class'=>'dropzone dropzone-manual')) ;?>
          <?php
            $dados_caixa = $this->caixas_model->get($caixa_id); 
            $nome_caixa = $dados_caixa->caixa;
            $saldo_inicial = $dados_caixa->saldo;
            //echo $saldo_inicial; 
            $dados_ultimo_registro_caixa = $this->caixas_model->get_ultimo_registro_caixa($caixa_id); 
            $caixa_atual = $dados_ultimo_registro_caixa->id;
             /********************************************************************
                        D E S P E S A S    S A Í D A S
            ********************************************************************/
            $valor_despesa = 0;
            $dados_despesas = $this->caixas_model->get_despesas_caixa_registro_atual($caixa_atual); 
            $valor_despesa = $dados_despesas->valor;
        
            /*******************************************************************/
            
            $id_caixa = $dados_caixa->id_caixa;
            if($dados_caixa->entrada_caixa){
            $entrada_caixa = $dados_caixa->entrada_caixa;
            }else{
            $entrada_caixa = 0;    
            }
            $usuario_abertura = $dados_caixa->usuario_abertura;
            $member_caixa = $this->staff_model->get($usuario_abertura);
            $operador = $member_caixa->firstname.' '.$member_caixa->lastname;
            
            $saldo_final = 0;
            $saldo_final += $saldo_inicial - $valor_despesa;
            
            $soma_valor_recebido = 0;
            $quantidade_pgtos = $this->caixas_model->get_caixa_quantidade_pagamento($caixa_atual); 
            foreach ($quantidade_pgtos as $aRow) {
                $tipo_id             = $aRow['tipo_id'];
                $forma_pagamento     = $aRow['forma_pagamento'];
                $qtde_pgto           = $aRow['quantidade'];
                $valor_pgto_recebido = $aRow['valor'];

                $soma_valor_recebido += $valor_pgto_recebido;
                
                if($tipo_id == 1){
                   // $saldo_final += $valor_pgto_recebido;
                }
            }
            
          ?>
          <input type="hidden" name="caixa_id" value="<?php echo $caixa_id; ?>">
          <input type="hidden" name="registro_id" value="<?php echo $caixa_atual; ?>">
          <input type="hidden" name="saldo" value="<?php echo $saldo_final; ?>">
          
         <div class="col-md-6">
            <div class="panel_s">
               <div class="panel-body">
                
                  <h4 class="no-margin"><?php echo $title; ?></h4>
                  <h4><?php echo 'SALDO EM CAIXA : '.app_format_money($saldo_final, 'R$'); ?></h4>
                  <hr class="hr-panel-heading" />
                  <h1>Transferir Saldo do Caixa?</h1>
                  <br>
                  
                  <label>Valor </label>
                  <input type="text" name="valor_transferencia" id="valor_transferencia" onKeyPress="return(moeda(this,'.',',',event))" maxlength="7" value="<?php echo $saldo_final; ?>"  class="form-control">
                  
                  
                  <label>Caixa Destino</label>
                  <select required="true" name="caixa_destino" class="form-control">
                      <option value="">Selecione o Caixa de Destino</option>
                     <?php foreach ($caixas as $caixa){ ?>
                        <option value="<?php echo $caixa['id']; ?>"><?php echo $caixa['name']; ?></option>                 
                    <?php } ?>
                   </select>  
                  <br>
                  <div class="btn-bottom text-right">
                     <button type="submit" class="btn btn-success"><?php echo 'confirmar transferência'; ?></button>
                  </div>
               </div>
            </div>
         </div>
        
         <?php echo form_close(); ?>
      </div>
      <div class="btn-bottom-pusher"></div>
   </div>
</div>

<?php init_tail(); ?>
<script>
   var customer_currency = '';
   Dropzone.options.expenseForm = false;
   var expenseDropzone;
   init_ajax_project_search_by_customer_id();
   var selectCurrency = $('select[name="currency"]');
   <?php if(isset($customer_currency)){ ?>
     var customer_currency = '<?php echo $customer_currency; ?>';
   <?php } ?>
     $(function(){
        $('body').on('change','#project_id', function(){
          var project_id = $(this).val();
          if(project_id != '') {
           if (customer_currency != 0) {
             selectCurrency.val(customer_currency);
             selectCurrency.selectpicker('refresh');
           } else {
             set_base_currency();
           }
         } else {
          do_billable_checkbox();
        }
      });

 

     appValidateForm($('#expense-form'),{
 
      repeat_every_custom: { min: 1},
    },expenseSubmitHandler);

     $('input[name="billable"]').on('change',function(){
       do_billable_checkbox();
     });

  

     // hide invoice recurring options on page load
     $('#repeat_every').trigger('change');

      $('select[name="clientid"]').on('change',function(){
       customer_init();
       do_billable_checkbox();
       $('input[name="billable"]').trigger('change');
     });

    });

</script>
</body>
</html>
