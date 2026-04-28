<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
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
<style>
 body {
 font-family:'Open Sans';
 background:#f1f1f1;
 }
 h3 {
 margin-top: 7px;
 font-size: 16px;
 }

 .install-row.install-steps {
 margin-bottom:15px;
 box-shadow: 0px 0px 1px #d6d6d6;
 }

 .control-label {
 font-size:13px;
 font-weight:600;
 }
 .padding-10 {
 padding:10px;
 }
 .mbot15 {
 margin-bottom:15px;
 }
 .bg-default {
 background: #03a9f4;
 border:1px solid #03a9f4;
 color:#fff;
 }
 .bg-success {
 border: 1px solid #dff0d8;
 }
 .bg-not-passed {
 border:1px solid #f1f1f1;
 border-radius:2px;
 }
 .bg-not-passed {
 border-right:0px;
 }
 .bg-not-passed.finish {
 border-right:1px solid #f1f1f1 !important;
 }
 .bg-not-passed h5 {
 font-weight:normal;
 color:#6b6b6b;
 }
 .form-control {
 box-shadow:none;
 }
 .bold {
 font-weight:600;
 }
 .col-xs-5ths,
 .col-sm-5ths,
 .col-md-5ths,
 .col-lg-5ths {
 position: relative;
 min-height: 1px;
 padding-right: 15px;
 padding-left: 15px;
 }
 .col-xs-5ths {
 width: 20%;
 float: left;
 }
 b {
 font-weight:600;
 }
 .bootstrap-select .btn-default {
 background: #fff !important;
 border: 1px solid #d6d6d6 !important;
 box-shadow: none;
 color: #494949 !important;
 padding: 6px 12px;
 }
</style>

    <div class="content">
            <div class="row">
                <section class="content-header">
                  <h1>
                        Pagamento parcela : 
                   <?php echo $parcelas->parcela.'/'.$titulo->total_parcela; ?> 
                  </h1>
                  <h2>
                   <?php echo $titulo->complemento; ?> 
                  </h2>
                    <h3>Nro Documento : <?php echo $titulo->numero_documento; ?></h3>
                    <h3>Data Vencimento : <?php echo _d($parcelas->data_vencimento); ?></h3>
                  <ol class="breadcrumb">
                    <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-dashboard"></i> Financeiro </a></li>
                    <li ><a href="<?php echo admin_url('financeiro'); ?>">Títulos a pagar </a></li>
                    <li class="active"><a href="#">Parcelas </a></li>
                  </ol>
                </section>
                <div class="panel_s">

                       

                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h2>Informações do pagamento</h2>
                                    <?php echo form_open('admin/financeiro/registra_add_pagamento_by_id/'.$parcelas->id,array('id'=>'parcela_form')); ?>
                                    
            
                                    <div class="row">
                                                <div class="col-md-6">
                                                   <div class="form-group">
                                                        <label>Conta/Banco</label>
                                                        <select name="banco_id"  id="banco_id" class="form-control">
                                                            <option checked="true" value="">Selecione</option>
                                                            <?php foreach ($bancos as $banco){ ?>
                                                             <option value="<?php echo $banco['id']; ?>"><?php echo $banco['banco']. ' ( AG: '.$banco['agencia'].' [Conta : '.$banco['numero_conta'].']) '; ?></option>   
                                                            <?php } ?>
                                                        </select>
                                                  </div>
                                                   <div class="form-group">
                                                       <label>Data do Pagamento</label>
                                                        <input type="date"  name="data_pagamento" id="data_pagamento"  value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                                    </div>
                                                   <div class="form-group">
                                                        <label>Formas Pagamento</label>
                                                        <select name="forma_pagamento"  id="forma_pagamento" class="form-control">
                                                            <option checked="true" value="">Selecione</option>
                                                            <?php foreach ($formas_pagamentos as $forma){ ?>
                                                             <option value="<?php echo $forma['id']; ?>"><?php echo $forma['name']; ?></option>   
                                                            <?php } ?>
                                                        </select>
                                                    </div>




                                                   <div class="form-group">
                                                       <label>Desconto</label>
                                                       <input type="text"  name="desconto" id="desconto" onKeyPress="return(moeda(this,'.',',',event))" maxlength="10" class="form-control">
                                                   </div>

                                                   <div class="form-group">
                                                       <label>Descricao / Detalhes</label>
                                                       <input type="text"  name="descricao" id="descricao"   class="form-control">
                                                   </div>
                                                    <br>

                                            </div>
                                    </div>
                                        
                                    <div class="modal-footer">
                                        <a href="<?php echo admin_url('financeiro/parcelas_conta_pagar/'.$titulo_id); ?>"  class="btn btn-default"><?php echo _l('close'); ?></a> 
                                        <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                                        <?php echo form_close(); ?>
                                    </div>
                              
                            </div>
                        </div>
                    
                </div>
            </div>
    </div>

<?php init_tail(); ?>


</body>
</html>
