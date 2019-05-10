<script>
  localStorage.setItem('date', '<?= $this->sma->hrld($data_hoje) ?>');
  
    $("#date").datetimepicker({
                format: site.dateFormats.js_ldate,
                fontAwesome: true,
                language: 'sma',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0
            }).datetimepicker('update', new Date());
            
            $(document).ready(function() {
    $('.btn-theme').click(function(){
        $('#aguarde, #blanket').css('display','block');
    });
});
</script>
<style>
    #blanket,#aguarde {
    position: fixed;
    display: none;
}

#blanket {
    left: 0;
    top: 0;
    background-color: #f0f0f0;
    filter: alpha(opacity =         65);
    height: 100%;
    width: 100%;
    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=65)";
    opacity: 0.65;
    z-index: 9998;
}

#aguarde {
    width: auto;
    height: 30px;
    top: 40%;
    left: 45%;
    background: url('http://i.imgur.com/SpJvla7.gif') no-repeat 0 50%; 
    line-height: 30px;
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    z-index: 9999;
    padding-left: 27px;
}
</style> 
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('Enviar e-mail para usuários com ações atrasadas '); ?><?php echo $id; ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

               
               
                <div class="row">
                    <div id="blanket"></div>
                    <div id="aguarde">Aguarde...</div>
                   
                    
                   
                  
                     <div  class="col-lg-12">
                     <center>
                            <div class="col-md-12">
                                <a  class=" btn btn-warning btn-theme " href="<?= site_url('Reports/enviaEmailAcoesAtrasadas'); ?>" onclick="javascript:document.getElementById('blanket').style.display = 'block';document.getElementById('aguarde').style.display = 'block';"><i class="fa fa-mail-forward"></i><?= lang('Enviar') ?></a>
                 
                                 
                              </div>
                         </center>
                        </div>
                            
                   

                    <div class="col-lg-12">

                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                     <h3>USUÁRIOS COM AÇÕES ATRASADAS</h3>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                            <tr>
                                                <th>N</th>
                                                <th>Responsável</th>
                                                <th>Email</th>
                                                <th>Dt Último Aviso</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                                $wu4[''] = '';
                                                $cont = 1;
                                                foreach ($usuarios as $usuario) {
                                                       
                                                    //$acoes = $this->Atas_Model->getAllAcoes($plano->idplanos);
                                                ?>   
                                                <tr class="odd gradeX">
                                                     <td><?php echo $cont++; ?></td>
                                                <td><?php echo $usuario->username; ?></td>
                                                <td><?php echo $usuario->email; ?></td>
                                                <td><?php echo $this->sma->hrld($usuario->ultimo_aviso_email); ?></td>
                                                
                                             
                                            </tr>
                                                <?php
                                                }
                                                ?>
                                            
                                            
                                           
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.portlet-body -->
                        </div>
                        <!-- /.portlet -->

                    </div>
                    <!-- /.col-lg-12 -->

              
                             
            </div>

        </div>
    </div>
</div>



