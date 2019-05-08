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


    //BAR CHART - SAÍDA DE EMPRÉSTIMOS HUPL
    var bar = new Morris.Bar({
      element: 'emprestimos_barras_hupl',
      resize: true,
      data: [
          <?php
          $query_periodo = "select distinct(s.dt_mesano_referencia) from saldo_estoque s 
    where s.cd_estabelecimento = 1
    and S.DT_MESANO_REFERENCIA >= '01/06/2018'";
    $periodo_saldo = oci_parse($ora_conexao,$query_periodo);
    oci_execute($periodo_saldo, OCI_NO_AUTO_COMMIT);


while (($row_periodo_se = oci_fetch_array($periodo_saldo, OCI_BOTH)) != false){
    $periodo = $row_periodo_se[0];    
    
    $primeira_parte = substr($periodo, 0, 6);   
    $segunda_parte = substr($periodo, 6);
    $periodo_ano_completo = $primeira_parte.'20'.$segunda_parte;
    
    $mes_periodo = substr($periodo, 3, 2);   
    $ano_periodo = substr($periodo, 6);
    $periodo_mes_ano = $mes_periodo.'/'.$ano_periodo;
    
    if($mes_periodo == 06){
     $dias = 30;   
    }else if($mes_periodo == 09){
     $dias = 30;   
    }else if($mes_periodo == 11){
     $dias = 30;   
    }else if($mes_periodo == 02){
     $dias = 28;   
    }else if($mes_periodo == 04){
     $dias = 30;   
    }else{
     $dias = 31;   
    }
    
    $query_emprestimo_por_periodo = "
        select  SUM(a.qt_material * to_char(SD.VL_CUSTO_MEDIO, 'FM999G999G990D90')) AS CUSTO_TOTAL
        from     emprestimo_material a
        inner join emprestimo e on e.nr_emprestimo = a.nr_emprestimo
        INNER JOIN SALDO_ESTOQUE SD ON SD.CD_MATERIAL = A.CD_MATERIAL
        where  e.cd_local_estoque = 19
        and e.dt_emprestimo between '$periodo_ano_completo' and '$dias/$mes_periodo/20$ano_periodo'
        AND SD.DT_MESANO_REFERENCIA = (SELECT MAX(DT_MESANO_REFERENCIA) FROM SALDO_ESTOQUE S WHERE S.CD_LOCAL_ESTOQUE = E.CD_LOCAL_ESTOQUE AND S.CD_MATERIAL = A.CD_MATERIAL) 
        AND SD.CD_LOCAL_ESTOQUE = 19
        and e.ie_tipo = 'E'";
    
        $saldo_por_periodo2 = oci_parse($ora_conexao,$query_emprestimo_por_periodo);
        oci_execute($saldo_por_periodo2, OCI_NO_AUTO_COMMIT);
        $row_periodo_saldo2 = oci_fetch_array($saldo_por_periodo2, OCI_BOTH);
        $entrada_hmu_mes = $row_periodo_saldo2[0];  
        $entrada_hmu_mes = str_replace(',', '.', $entrada_hmu_mes);
          
        /*
         * SAÍDA MÊS EMPRÉTIMO
         */
        
         $query_emprestimo_saida_por_periodo = "
        select  SUM(a.qt_material * to_char(SD.VL_CUSTO_MEDIO, 'FM999G999G990D90')) AS CUSTO_TOTAL
        from     emprestimo_material a
        inner join emprestimo e on e.nr_emprestimo = a.nr_emprestimo
        INNER JOIN SALDO_ESTOQUE SD ON SD.CD_MATERIAL = A.CD_MATERIAL
        where  e.cd_local_estoque = 19
        and e.dt_emprestimo between '$periodo_ano_completo' and '$dias/$mes_periodo/20$ano_periodo'
        AND SD.DT_MESANO_REFERENCIA = (SELECT MAX(DT_MESANO_REFERENCIA) FROM SALDO_ESTOQUE S WHERE S.CD_LOCAL_ESTOQUE = E.CD_LOCAL_ESTOQUE AND S.CD_MATERIAL = A.CD_MATERIAL) 
        AND SD.CD_LOCAL_ESTOQUE = 19
        and e.ie_tipo = 'S'";
    
        $saida_hmu_por_periodo2 = oci_parse($ora_conexao,$query_emprestimo_saida_por_periodo);
        oci_execute($saida_hmu_por_periodo2, OCI_NO_AUTO_COMMIT);
        $row_saida_periodo_saldo2 = oci_fetch_array($saida_hmu_por_periodo2, OCI_BOTH);
        $saida_hmu_mes = $row_saida_periodo_saldo2[0];  
        $saida_hmu_mes = str_replace(',', '.', $saida_hmu_mes);
        
       // $saldo_almoxarifado2 = 14488.27;
        ?>
          
         {y: '<?php echo $periodo_mes_ano; ?>', a: <?php echo $entrada_hmu_mes; ?>, b: <?php echo $saida_hmu_mes; ?>},

<?php
}

?>    
      ],
      barColors: ['#00a65a', '#f56954'],
      xkey: 'y',
      ykeys: ['a', 'b'],
      labels: ['ENTRADA', 'SAÍDA'],
      hideHover: 'auto'
    });
    
    
    
    
    
    
     //BAR CHART - SAÍDA DE EMPRÉSTIMOS HMU
    var bar = new Morris.Bar({
      element: 'emprestimos_barras_hmu',
      resize: true,
      data: [
         <?php
          $query_periodo = "select distinct(s.dt_mesano_referencia) from saldo_estoque s 
    where s.cd_estabelecimento = 1
    and S.DT_MESANO_REFERENCIA >= '01/06/2018'";
    $periodo_saldo = oci_parse($ora_conexao,$query_periodo);
    oci_execute($periodo_saldo, OCI_NO_AUTO_COMMIT);


while (($row_periodo_se = oci_fetch_array($periodo_saldo, OCI_BOTH)) != false){
    $periodo = $row_periodo_se[0];    
    
    $primeira_parte = substr($periodo, 0, 6);   
    $segunda_parte = substr($periodo, 6);
    $periodo_ano_completo = $primeira_parte.'20'.$segunda_parte;
    
    $mes_periodo = substr($periodo, 3, 2);   
    $ano_periodo = substr($periodo, 6);
    $periodo_mes_ano = $mes_periodo.'/'.$ano_periodo;
    
    if($mes_periodo == 06){
     $dias = 30;   
    }else if($mes_periodo == 09){
     $dias = 30;   
    }else if($mes_periodo == 11){
     $dias = 30;   
    }else if($mes_periodo == 02){
     $dias = 28;   
    }else if($mes_periodo == 04){
     $dias = 30;   
    }else{
     $dias = 31;   
    }
    
    $query_emprestimo_por_periodo = "
        select  SUM(a.qt_material * to_char(SD.VL_CUSTO_MEDIO, 'FM999G999G990D90')) AS CUSTO_TOTAL
        from     emprestimo_material a
        inner join emprestimo e on e.nr_emprestimo = a.nr_emprestimo
        INNER JOIN SALDO_ESTOQUE SD ON SD.CD_MATERIAL = A.CD_MATERIAL
        where  e.cd_local_estoque = 65
        and e.dt_emprestimo between '$periodo_ano_completo' and '$dias/$mes_periodo/20$ano_periodo'
        AND SD.DT_MESANO_REFERENCIA = (SELECT MAX(DT_MESANO_REFERENCIA) FROM SALDO_ESTOQUE S WHERE S.CD_LOCAL_ESTOQUE = E.CD_LOCAL_ESTOQUE AND S.CD_MATERIAL = A.CD_MATERIAL) 
        AND SD.CD_LOCAL_ESTOQUE = 65
        and e.ie_tipo = 'E'";
    
        $saldo_por_periodo2 = oci_parse($ora_conexao,$query_emprestimo_por_periodo);
        oci_execute($saldo_por_periodo2, OCI_NO_AUTO_COMMIT);
        $row_periodo_saldo2 = oci_fetch_array($saldo_por_periodo2, OCI_BOTH);
        $entrada_hmu_mes = $row_periodo_saldo2[0];  
        $entrada_hmu_mes = str_replace(',', '.', $entrada_hmu_mes);
          
        /*
         * SAÍDA MÊS EMPRÉTIMO
         */
        
         $query_emprestimo_saida_por_periodo = "
        select  SUM(a.qt_material * to_char(SD.VL_CUSTO_MEDIO, 'FM999G999G990D90')) AS CUSTO_TOTAL
        from     emprestimo_material a
        inner join emprestimo e on e.nr_emprestimo = a.nr_emprestimo
        INNER JOIN SALDO_ESTOQUE SD ON SD.CD_MATERIAL = A.CD_MATERIAL
        where  e.cd_local_estoque = 65
        and e.dt_emprestimo between '$periodo_ano_completo' and '$dias/$mes_periodo/20$ano_periodo'
        AND SD.DT_MESANO_REFERENCIA = (SELECT MAX(DT_MESANO_REFERENCIA) FROM SALDO_ESTOQUE S WHERE S.CD_LOCAL_ESTOQUE = E.CD_LOCAL_ESTOQUE AND S.CD_MATERIAL = A.CD_MATERIAL) 
        AND SD.CD_LOCAL_ESTOQUE = 65
        and e.ie_tipo = 'S'";
    
        $saida_hmu_por_periodo2 = oci_parse($ora_conexao,$query_emprestimo_saida_por_periodo);
        oci_execute($saida_hmu_por_periodo2, OCI_NO_AUTO_COMMIT);
        $row_saida_periodo_saldo2 = oci_fetch_array($saida_hmu_por_periodo2, OCI_BOTH);
        $saida_hmu_mes = $row_saida_periodo_saldo2[0];  
        $saida_hmu_mes = str_replace(',', '.', $saida_hmu_mes);
        
       // $saldo_almoxarifado2 = 14488.27;
        ?>
          
         {y: '<?php echo $periodo_mes_ano; ?>', a: <?php echo $entrada_hmu_mes; ?>, b: <?php echo $saida_hmu_mes; ?>},

<?php
}

?>      
       
      ],
      barColors: ['#00a65a', '#f56954'],
      xkey: 'y',
      ykeys: ['a', 'b'],
      labels: ['ENTRADA', 'SAÍDA'],
      hideHover: 'auto'
    });
    
  });
</script>


<script>
  $(function () {
    $('#example1').DataTable()
    $('#example3').DataTable()
    $('#example4').DataTable()
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

