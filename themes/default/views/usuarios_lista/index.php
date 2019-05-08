<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

     <title>Controle de Projetos - TI UnimedManaus</title>

     <!-- GLOBAL STYLES - Include these on every page. -->
            <link href="<?= $assets ?>dashboard/css/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
            <link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic' rel="stylesheet" type="text/css">
           
            
       
            <link href="<?= $assets ?>styles/theme.css" rel="stylesheet"/>
            <link href="<?= $assets ?>styles/style.css" rel="stylesheet"/>

    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
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
<script>
  localStorage.setItem('sldateEntregaDemanda', '<?= $this->sma->hrld($data_hoje) ?>');
  
    $("#sldateEntregaDemanda").datetimepicker({
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
</head>

<body>
<?php 

           $usuario = $this->session->userdata('user_id');
           $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           $projetos_usuario->projeto;        
           
           
?>
    <div class="box">
        
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-list"></i><?=lang('Lista de Usuários para este projeto. Selecione os Usuários que você gostaria que aparecesse nas respesctivas listas.') ;?>
               
        </h2>
        
    </div>
    
   
     <div id="blanket"></div>
                    <div id="aguarde">Aguarde...Atualizando as Informações</div>
        

    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                      
                <div class="portlet portlet-default">
                    <div style="text-align: right" class="col-lg-12">
                    </div>
                    <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("Gestores/edit_usuario_lista_form" , $attrib); ?>
          <input type="hidden" value="<?php echo $projeto; ?>" name="projeto"/>
                    <div class="portlet-body">
                        <div class="table-responsive">
                            <?php
                            $cont_superintendente = 1;
                            foreach ($superintendencias as $area) {
                            
                                //$acoes_setor = $this->atas_model->getAllitemPlanosProjetoSetor($projetos_usuario,$setor_selecionado);
                            
                            ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h2> <?PHP echo $area->superintendencia; ?>  </h2>
                                </div>
                            </div>    
                           
                                    <?php
                                    $wu4[''] = '';
                                    $cont_setor = 1;

                                    
                                    $usuario = $this->session->userdata('user_id');
                                    $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                                    $cadastroUsuario = $this->site->getPerfilAtualByID($usuario);
                                    $projeto_atual = $cadastroUsuario->projeto_atual;
                                    
                                    $setores = $this->site->getAllSuperintendenciaSetor($area->superintendencia_id);  
                                    
                                     foreach ($setores as $setor) {
                                        $cont_superintendente++;
                                        $cont_setor++;
                                        
                                        $setor_selecionado = $setor->id_setor;
                                        ?>   
                                  
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div  class="col-lg-4">
                                                   <h3> <?PHP ECHO $setor->setor; ?></h3> 
                                                </div>
                                                
                                                <div class="col-lg-8">
                                                    <div class="row">
                                                        <div class="col-lg-5">
                                                            Usuários
                                                        </div>
                                                         <div class="col-lg-3">
                                                            Participação Ata
                                                        </div>
                                                        <div class="col-lg-2">
                                                            Usuário/Ata
                                                        </div>
                                                        <div class="col-lg-2">
                                                            Treinamentos
                                                        </div>
                                                        
                                                    </div>
                                                   
                                                      <script type="text/javascript">
                                                            function marcarTodos<?php echo $setor_selecionado; ?>(marcar){
                                                                var itens = document.getElementsByName('participacao<?php echo $setor_selecionado; ?>[]');
                                                               
                                                             

                                                                var i = 0;
                                                                for(i=0; i<itens.length;i++){
                                                                    itens[i].checked = marcar;
                                                                }
                                                             
                                                            }
                                                            
                                                            function marcarTodosUsuarios<?php echo $setor_selecionado; ?>(marcar){
                                                                var itens = document.getElementsByName('usuarios<?php echo $setor_selecionado; ?>[]');
                                                               
                                                               
                                                                var i = 0;
                                                                for(i=0; i<itens.length;i++){
                                                                    itens[i].checked = marcar;
                                                                }
                                                              

                                                            }
                                                            
                                                            function marcarTodosTreinamentos<?php echo $setor_selecionado; ?>(marcar){
                                                                var itens = document.getElementsByName('treinamentos<?php echo $setor_selecionado; ?>[]');
                                                               
                                                              

                                                                var i = 0;
                                                                for(i=0; i<itens.length;i++){
                                                                    itens[i].checked = marcar;
                                                                }
                                                              

                                                            }
                                                        </script>
                                                       
                                                    
                                                    <div class="row">
                                                        
                                                        <div style="font-weight: bold" class="col-lg-5">
                                                            
                                                        </div>
                                                         <div class="col-lg-3">
                                                             <input  type='checkbox'value="0" name="participacao<?php echo $setor_selecionado; ?>[]" onclick="marcarTodos<?php echo $setor_selecionado; ?>(this.checked);" /> <span style="font-weight: bold" id="acao_participacao">Todos </span> <br> 
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <input  type='checkbox' value="0" name="usuarios<?php echo $setor_selecionado; ?>[]"    onclick="marcarTodosUsuarios<?php echo $setor_selecionado; ?>(this.checked);" />  <span style="font-weight: bold" id="acao">Todos</span> <br>
                                                        </div>
                                                        <div class="col-lg-2">
                                                           <input  type='checkbox' value="0" name="treinamentos<?php echo $setor_selecionado; ?>[]" onclick="marcarTodosTreinamentos<?php echo $setor_selecionado; ?>(this.checked);" />  <span style="font-weight: bold" id="acao">Todos</span> <br>
                                                        </div>
                                                        
                                                    </div>
                                                            
                                                                 <div class="row">
                                                                   
                                                                    <?php
                                                                     $users = $this->site->getAllUserSetor($setor_selecionado);
                                                                     foreach ($users as $plano) {
                                                                         $usuario = $plano->id;
                                                                         $users_lista_participantes = $this->site->getAllUserListaParticipantes($usuario,$projeto_atual);
                                                                         $participacao_ata = $users_lista_participantes->participante_atas;
                                                                         $vinculo_usuario  = $users_lista_participantes->usuario_ata;
                                                                         $treinamento_usuario = $users_lista_participantes->treinamentos;
                                                                         
                                                                    ?>
                                                                        <div class="col-lg-5">
                                                                             <?php echo $plano->first_name . ' '; ?><?php echo $plano->last_name; ?> 
                                                                         </div>
                                                                         <div class="col-lg-3">
                                                                             <input name="participacao<?php echo $setor_selecionado; ?>[]" value="<?php echo $plano->id; ?>" <?php if($participacao_ata == 1){ ?> checked  <?php } ?> type="checkbox"> <?php echo $plano->first_name . ' '; ?>
                                                                         </div>
                                                                         <div class="col-lg-2">
                                                                             <input name="usuarios<?php echo $setor_selecionado; ?>[]" value="<?php echo $plano->id; ?>" <?php if($vinculo_usuario == 1){ ?> checked  <?php } ?> type="checkbox"> <?php echo $plano->first_name . ' '; ?>
                                                                         </div>
                                                                         <div class="col-lg-2">
                                                                             <input name="treinamentos<?php echo $setor_selecionado; ?>[]"  value="<?php echo $plano->id; ?>" <?php if($treinamento_usuario == 1){ ?> checked  <?php } ?> type="checkbox"> <?php echo $plano->first_name . ' '; ?>
                                                                         </div>
                                                                    <?php 
                                                                     }
                                                                    ?>
                                                                    
                                                                 </div>
                                                       
                                                    <br>
                                                    <hr>
                                                </div>
                                            </div>
                                        </div>
                                       <?php
                                       
                                        }
                                   
                            }
                            ?>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
          <center>
                    <div class="col-md-12">
                    <div
                        class="fprom-group">
                        <div class="fprom-group"> 
                            <button type="submit" value="Salvar" class="btn btn-primary" onclick="javascript:document.getElementById('blanket').style.display = 'block';document.getElementById('aguarde').style.display = 'block';">Salvar</button>
                                
                            <a  class="btn btn-danger" href="<?= site_url('Login_Projetos/menu'); ?>"><?= lang('Sair') ?></a>
                        </div> 
                         
                    </div>
                </div>
                </center>
                    <!-- /.portlet-body -->
                </div>
                <!-- /.portlet -->

            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= $assets ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.dtFilter.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/select2.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.calculator.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/core.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/perfect-scrollbar.min.js"></script>
 <script type="text/javascript" charset="UTF-8">var r_u_sure = "<?=lang('r_u_sure')?>";
    <?=$s2_file_date?>
    $.extend(true, $.fn.dataTable.defaults, {"oLanguage":<?=$dt_lang?>});
    $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
    $(window).load(function () {
        $('.mm_<?=$m?>').addClass('active');
        $('.mm_<?=$m?>').find("ul").first().slideToggle();
        $('#<?=$m?>_<?=$v?>').addClass('active');
        $('.mm_<?=$m?> a .chevron').removeClass("closed").addClass("opened");
    });
</script>
</body>

</html>