<script src="<?= $assets ?>bi/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= $assets ?>bi/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= $assets ?>bi/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="<?= $assets ?>bi/bower_components/raphael/raphael.min.js"></script>
<script src="<?= $assets ?>bi/bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="<?= $assets ?>bi/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="<?= $assets ?>bi/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?= $assets ?>bi/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?= $assets ?>bi/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?= $assets ?>bi/bower_components/moment/min/moment.min.js"></script>
<script src="<?= $assets ?>bi/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="<?= $assets ?>bi/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?= $assets ?>bi/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="<?= $assets ?>bi/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?= $assets ?>bi/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?= $assets ?>bi/dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?= $assets ?>bi/dist/js/pages/dashboard.js"></script>



<!-- DataTables -->
<script src="<?= $assets ?>bi/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= $assets ?>bi/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->

<?php include 'conexao_oracle.php'; ?>
 

<script>
  $(function () {
    "use strict";
    
     //BAR CHART - SAÍDA DE EMPRÉSTIMOS HMU
    var bar = new Morris.Bar({
      element: 'saida_hmu',
      resize: true,
      data: [
      <?php
      $mes_atual = date('m');
      $ano_atual = date('Y');
      $ano_anterior = $ano_atual - 1;
      
      $periodo_de = "01/$mes_atual/$ano_anterior";
      $periodo_ate = "01/$mes_atual/$ano_atual";
      
    $query_periodo = "select distinct(s.dt_mesano_referencia) from saldo_estoque s 
    where s.cd_estabelecimento = 1
    and S.DT_MESANO_REFERENCIA BETWEEN '$periodo_de' AND '$periodo_ate'";
    $periodo_saldo = oci_parse($ora_conexao,$query_periodo);
    oci_execute($periodo_saldo, OCI_NO_AUTO_COMMIT);


while (($row_periodo_se = oci_fetch_array($periodo_saldo, OCI_BOTH)) != false){
    $periodo = $row_periodo_se[0];    
    $primeira_parte = substr($periodo, 0, 6);   
    $segunda_parte = substr($periodo, 6);
    $periodo = $primeira_parte.'20'.$segunda_parte;
    
    $query_almoxarifado_saldo_periodo = "select 0 ie_valor, 0 cd_base, nvl(substr(a.DS_LOCAL_ESTOQUE,1,255), 'Não Informado')  ds_base, 
        to_char(a.CD_LOCAL_ESTOQUE) nm_drill, 
         sum( VL_ESTOQUE) VL_ESTOQUE,
         sum( VL_ESTOQUE_MEDIO) VL_ESTOQUE_MEDIO,
         sum( VL_CONSUMO) VL_CONSUMO,
        TASY.DIVIDIR(SUM(VL_CONSUMO),SUM(VL_ESTOQUE_MEDIO)) VL_3,
         sum( VL_EXCEDENTE) VL_EXCEDENTE,
        ((TASY.dividir(sum(VL_ESTOQUE), sum(VL_CONSUMO))) * 30) VL_5,
        TASY.dividir(((SUM(VL_ESTOQUE) - SUM(VL_CONSUMO)) * 100), SUM(VL_ESTOQUE)) VL_6,
        to_char( TRUNC(TASY.DIVIDIR(30,TASY.DIVIDIR(SUM(VL_CONSUMO),SUM(VL_ESTOQUE_MEDIO)))), '0' ) VL_7,
         1 from TASY.eis_saldo_estoque_v a
         where 1 = 1 
        AND CD_ESTABELECIMENTO = a.CD_ESTABELECIMENTO
        AND CD_ESTABELECIMENTO = 1
        and a.CD_LOCAL_ESTOQUE = 93
         and DS_LOCAL_ESTOQUE is not null 
         and DT_REFERENCIA between TO_DATE('$periodo','dd/mm/yyyy') and TO_DATE('$periodo 23:59:59','dd/mm/yyyy hh24:mi:ss')
         group by 1, a.DS_LOCAL_ESTOQUE
        ,to_char(a.CD_LOCAL_ESTOQUE)";
    
    $query_nutricao_saldo_periodo = "select 0 ie_valor, 0 cd_base, nvl(substr(a.DS_LOCAL_ESTOQUE,1,255), 'Não Informado')  ds_base, 
        to_char(a.CD_LOCAL_ESTOQUE) nm_drill, 
         sum( VL_ESTOQUE) VL_ESTOQUE,
         sum( VL_ESTOQUE_MEDIO) VL_ESTOQUE_MEDIO,
         sum( VL_CONSUMO) VL_CONSUMO,
        TASY.DIVIDIR(SUM(VL_CONSUMO),SUM(VL_ESTOQUE_MEDIO)) VL_3,
         sum( VL_EXCEDENTE) VL_EXCEDENTE,
        ((TASY.dividir(sum(VL_ESTOQUE), sum(VL_CONSUMO))) * 30) VL_5,
        TASY.dividir(((SUM(VL_ESTOQUE) - SUM(VL_CONSUMO)) * 100), SUM(VL_ESTOQUE)) VL_6,
        to_char( TRUNC(TASY.DIVIDIR(30,TASY.DIVIDIR(SUM(VL_CONSUMO),SUM(VL_ESTOQUE_MEDIO)))), '0' ) VL_7,
         1 from TASY.eis_saldo_estoque_v a
         where 1 = 1 
        AND CD_ESTABELECIMENTO = a.CD_ESTABELECIMENTO
        and a.CD_LOCAL_ESTOQUE = 69
         and DS_LOCAL_ESTOQUE is not null 
         AND CD_ESTABELECIMENTO = 1
         and DT_REFERENCIA between TO_DATE('$periodo','dd/mm/yyyy') and TO_DATE('$periodo 23:59:59','dd/mm/yyyy hh24:mi:ss')
         group by 1, a.DS_LOCAL_ESTOQUE
        ,to_char(a.CD_LOCAL_ESTOQUE)";

    
    $query_farmacia_saldo_periodo = "select 1 ie_valor, 0 cd_base, 'Total' ds_base, 
        ' ' nm_drill, 
         sum( VL_ESTOQUE) VL_ESTOQUE,
         sum( VL_ESTOQUE_MEDIO) VL_ESTOQUE_MEDIO,
         sum( VL_CONSUMO) VL_CONSUMO,
        TASY.DIVIDIR(SUM(VL_CONSUMO),SUM(VL_ESTOQUE_MEDIO)) VL_3,
        sum( VL_EXCEDENTE) VL_EXCEDENTE,
        ((TASY.dividir(sum(VL_ESTOQUE), sum(VL_CONSUMO))) * 30) VL_5,
        TASY.dividir(((SUM(VL_ESTOQUE) - SUM(VL_CONSUMO)) * 100), SUM(VL_ESTOQUE)) VL_6,
        to_char( TRUNC(TASY.DIVIDIR(30,TASY.DIVIDIR(SUM(VL_CONSUMO),SUM(VL_ESTOQUE_MEDIO)))), '0' ) VL_7,
         1 from TASY.eis_saldo_estoque_v a
         where 1 = 1 
        AND CD_ESTABELECIMENTO = a.CD_ESTABELECIMENTO
      and a.CD_LOCAL_ESTOQUE not in (1,69,93)
      AND CD_ESTABELECIMENTO = 1
         and DS_LOCAL_ESTOQUE is not null 
         and DT_REFERENCIA between TO_DATE('$periodo','dd/mm/yyyy') and TO_DATE('$periodo 23:59:59','dd/mm/yyyy hh24:mi:ss')
         group by 1 
         order by 1, 5  Desc";
  
    /*
     * ALMOXARIFADO
     */
        $saldo_por_periodo = oci_parse($ora_conexao,$query_almoxarifado_saldo_periodo);
        oci_execute($saldo_por_periodo, OCI_NO_AUTO_COMMIT);
        $row_periodo_saldo = oci_fetch_array($saldo_por_periodo, OCI_BOTH);
        $saldo_almoxarifado2 = $row_periodo_saldo[4];  
        $saldo_almoxarifado2 = str_replace(',', '.', $saldo_almoxarifado2);
       
    /*
     * NUTRIÇÃO
     */
        $saldo_por_periodo_nutricao = oci_parse($ora_conexao,$query_nutricao_saldo_periodo);
        oci_execute($saldo_por_periodo_nutricao, OCI_NO_AUTO_COMMIT);
        $row_periodo_saldo_nutri = oci_fetch_array($saldo_por_periodo_nutricao, OCI_BOTH);
        $saldo_nutricao = $row_periodo_saldo_nutri[4];  
        $saldo_nutricao = str_replace(',', '.', $saldo_nutricao);   
     
   
        
    /*
     * FARMÁCIA
     */
        $saldo_farmacia = 0;
        $saldo_por_periodo_farmacia = oci_parse($ora_conexao,$query_farmacia_saldo_periodo);
        oci_execute($saldo_por_periodo_farmacia, OCI_NO_AUTO_COMMIT);
        $row_periodo_saldo_farmacia = oci_fetch_array($saldo_por_periodo_farmacia, OCI_BOTH);
        $saldo_farmacia = $row_periodo_saldo_farmacia[4];  
        $saldo_farmacia = str_replace(',', '.', $saldo_farmacia);       
        
    
  ?>     
    {y: '<?php echo $periodo; ?>', a: <?php echo $saldo_almoxarifado2; ?>, b: <?php echo $saldo_nutricao; ?>, c:<?php echo $saldo_farmacia; ?>},
 <?php   
}
?>
      ],
      barColors: ['#00BFFF', 'orange',  'green'],
      xkey: 'y',
      ykeys: ['a', 'b', 'c'],
      labels: ['Almoxarifado', 'Nutrição',  'Farmácia'],
      hideHover: 'auto'
    });
    
  });
</script>

<?php 

?>
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example3').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>