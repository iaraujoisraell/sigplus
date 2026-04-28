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
                            <?php
                            $member = $this->staff_model->get(get_staff_user_id());
                            $caixa_atual = $member->caixa_id;

                            if($caixa_atual){

                                $dados_caixa = $this->caixas_model->get_caixa_registro_atual($caixa_atual); 
                                $nome_caixa = $dados_caixa->caixa;
                                $caixa_id = $dados_caixa->id_caixa;
                                $saldo_inicial = $dados_caixa->saldo;

                                /********************************************************************
                                            D E S P E S A S    S A Í D A S
                                ********************************************************************/
                                $valor_despesa = 0;
                                $dados_despesas = $this->caixas_model->get_despesas_caixa_registro_atual($caixa_atual); 
                                $valor_despesa = $dados_despesas->valor;
                                
                                 /********************************************************************
                                            R E P A S S E S    S A Í D A S
                                ********************************************************************/
                                $valor_repasse = 0;
                                $dados_repasse = $this->Repasses_model->get_repasses_caixa_registro_atual($caixa_atual); 
                                $valor_repasse = $dados_repasse->valor;

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
                                $saldo_final += $saldo_inicial - $valor_despesa - $valor_repasse;

                                $soma_valor_recebido = 0;
                                $quantidade_pgtos = $this->caixas_model->get_caixa_quantidade_pagamento($caixa_atual); 
                                foreach ($quantidade_pgtos as $aRow) {
                                    $tipo_id             = $aRow['tipo_id'];
                                    $forma_pagamento     = $aRow['forma_pagamento'];
                                    $qtde_pgto           = $aRow['quantidade'];
                                    $valor_pgto_recebido = $aRow['valor'];

                                    $soma_valor_recebido += $valor_pgto_recebido;

                                  //  if($tipo_id == 1){
                                        $saldo_final += $valor_pgto_recebido;
                                  //  }
                                }  


                            ?>
                             <a href="#" class="btn btn-success "  onclick="Mudarestado('minhaDiv')"><?php echo 'Novo Repasse'; ?></a>

                             <br><br><br>
                             <div class="clearfix"></div>
                             <div style="display: none" id="minhaDiv">
                                <?php echo form_open(admin_url('repasses/add_repasse/')); ?>
                                 <input type="hidden" required="true" value="<?php echo $caixa_atual; ?>" name="registro_id" >
                                 <input type="hidden" required="true" value="<?php echo $caixa_id; ?>" name="caixa_id" >
                                 <input type="hidden" name="saldo" value="<?php echo $saldo_final; ?>">
                                     <h3>
                                     <label class="btn btn-warning pull-right"><?php echo $operador; ?></label>
                                     <label class="btn btn-primary pull-right"><?php echo $nome_caixa; ?></label>
                                     <label class="btn btn-success pull-right"><?php echo 'saldo : '. app_format_money($saldo_final, 'R$'); ?></label>
                                     </h3>
                                 <hr class="hr-panel-heading" />
                                 <br><br>

                                 <label>SALDO EM CAIXA</label>
                                 <input type="text" readonly="true" value="<?php echo app_format_money($saldo_final, 'R$'); ?>"  class="form-control">
                                  <?php $hoje = date('Y-m-d'); ?>
                                 <br>
                                 <label>Data do Repasse</label>
                                 <input type="date" required="true" name="data_repasse" value="<?php echo $hoje; ?>" class="form-control">
                                 <br>
                                 <label for="medicos_pagamento_recebido"><?php echo 'Conta Financeira Destino'; ?></label>
                                 <?php

                                 ?>
                                 <select required="true" class="form-control" name="conta_id">
                                     <option value="">SELECIONE UMA CONTA PARA RECEBER O REPASSE</option>
                                   <?php
                                   foreach ($contas_financeiras as $medico) {
                                      $propria = $medico['nota_fiscal_propria'];
                                       ?>
                                     <option  value="<?php echo $medico['id']; ?>" <?php echo $selected; ?>><?php echo $medico['nome']; ?></option>
                                    <?php } ?>
                               </select>
                                 <br>
                                 <?php
                                $amount = '0.00';
                                 //  echo render_input('valor','valor_repasse',$amount,'text',array('max'=>$saldo_final)); ?> 
                                <label>Valor </label>
                                <input type="text" name="valor" id="valor" onKeyPress="return(moeda(this,'.',',',event))" maxlength="7" value="<?php echo $amount; ?>"  class="form-control">
                                 
                                 
                                
                                    <?php echo render_select('forma_id',$payment_modes,array('id','name'),'payment_mode'); ?>
                                 
                                <?php echo render_textarea( 'observacao', 'observacao', '',array( 'rows'=>5)); ?>
                                <button class="btn btn-info pull-right mbot15">
                                    <?php echo _l( 'submit'); ?>
                                </button>
                                <?php echo form_close(); ?>
                            </div>

                             <?php
                            }
                           } ?>
                           </div>
                        
                        <table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
                            <thead>

                                <tr>
                                    <th>
                                        <?php echo 'Data Repasse'; ?>
                                    </th>
                                    <th >
                                        <?php echo 'Conta Financeira'; ?>
                                    </th>
                                    <th >
                                        <?php echo 'Valor'; ?>
                                    </th>
                                    <th >
                                        <?php echo 'Forma Pagamento'; ?>
                                    </th>
                                    <th >
                                        <?php echo 'Caixa Origem'; ?>
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
                                $caixa_pgtos = $this->Repasses_model->get(); 
                                foreach($caixa_pgtos as $caixa){

                                $soma_total += $caixa['valor'];    
                               
                                $usuario = get_staff_full_name($caixa['usuario_log']);
                             
                                ?>
                            <tr>
                                <td>
                                  <?php echo _d($caixa['data_repasse']);   ?>
                                </td>
                                <td>
                                  <?php echo $caixa['conta'];   ?>
                                </td>
                                <td>
                                  <?php echo app_format_money($caixa['valor'], 'R$');  ?>
                                </td>
                                <td>
                                  <?php echo $caixa['forma'];   ?>
                                </td>
                                <td>
                                  <?php echo $caixa['caixa'];   ?>
                                </td>
                                <td>
                                  <?php echo $caixa['observacao'];   ?>
                                </td>
                                <td>
                                  <?php echo _d($caixa['data_log']).'<br>'.$usuario;   ?>
                                </td>


                            </tr>

                        <?php }

                        $saldo = $soma_total - $soma_total_saida;
                        ?>
                            <tr>
                                <td>
                                  Total
                                </td>
                                <td>

                                </td>
                                <td>
                                    <label class="btn btn-primary"><?php echo app_format_money($soma_total, 'R$');  ?></label>
                                </td>
                                <td>

                                </td>
                                
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>

                                </td>


                            </tr>
                
                    </tbody>
                            <thead>
                            <tr>
                                    <th>
                                        <?php echo 'Data Repasse'; ?>
                                    </th>
                                    <th >
                                        <?php echo 'Conta Financeira'; ?>
                                    </th>
                                    <th >
                                        <?php echo 'Valor'; ?>
                                    </th>
                                    <th >
                                        <?php echo 'Forma Pagamento'; ?>
                                    </th>
                                    <th >
                                        <?php echo 'Caixa Origem'; ?>
                                    </th>
                                    
                                    <th >
                                        <?php echo 'Observação'; ?>
                                    </th>
                                    <th >
                                        <?php echo 'Log'; ?>
                                    </th>
                                </tr>
                        </thead>                   
                        </table>
                     </div>
                  </div>
               </div>
               
            </div>
    </div>
</div>
<?php init_tail(); ?>