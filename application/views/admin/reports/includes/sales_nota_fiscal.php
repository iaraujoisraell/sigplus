<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
<div id="nota-report" class="hide">
    <div class="row">
        <div class="col-md-6">
        <h3>Notas Fiscais</h3>
        </div>
    </div>
    <div class="col-md-12">

         <div class="clearfix"></div>
    <div class="row">
         <hr class="hr-panel-heading" />
    </div>
   
    <div class="clearfix"></div>
    </div>
    
    <table class="table table-nota-report scroll-responsive" >
         <thead>
            <tr>
              <th><?php echo 'Nro'; ?></th>  
              <th><?php echo 'Fatura'; ?></th>
              <th><?php echo 'Cliente'; ?></th>
              <th><?php echo 'CNPJ'; ?></th>
              <th><?php echo 'Conta'; ?></th>
              <th><?php echo 'Valor'; ?></th>
              <th><?php echo 'Data Emissão'; ?></th>
              <th><?php echo 'Status'; ?></th>
            </tr>
         </thead>
         <tbody></tbody>
         <tfoot>
            <td></td>
            <td ></td>
            <td></td>
            <td ></td>
            <td></td>
            <td ></td>
            <td></td>
            <td ></td>
         </tfoot>
      </table>
    
   
   </div>
