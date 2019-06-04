  <?php
    function resume( $var, $limite ){	// Se o texto for maior que o limite, ele corta o texto e adiciona 3 pontinhos.	
              if (strlen($var) > $limite)	{
                    $var = substr($var, 0, $limite);		
                    $var = trim($var) . "...";	

              }return $var;

              }
          ?>
<script>
     if (localStorage.getItem('sldate')) {
                localStorage.removeItem('sldate');
            }
            
        if (!localStorage.getItem('sldate')) {
            $("#sldate").datetimepicker({
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
        }
            
            $(document).on('change', '#sldate', function (e) {
            localStorage.setItem('sldate', $(this).val());
        });
        if (sldate = localStorage.getItem('sldate')) {
            $('#sldate').val(sldate);
        }
         
         
         
                   $(document).ready(function() {
    $('.btn-theme').click(function(){
        $('#aguarde, #blanket').css('display','block');
    });
});


onload : optionCheck();
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
<script type="text/javascript">
    function optionCheck(){
        var option = document.getElementById("options").value;
        if(option == "REUNIÃO CONTÍNUA"){
         //   document.getElementById("hiddenDiv").style.visibility ="visible";
            document.getElementById("hiddenDiv").style.display = "block";
        }else{
            document.getElementById("hiddenDiv").style.display = "none";
        }


    }
</script>
<?php
        
        $usuario = $this->session->userdata('user_id');
//$users = $this->site->geUserByID($acao->responsavel);                              
        ?>    
<?php
$projetos = $this->projetos_model->getProjetoAtualByID_completo();
$id_projeto = $projetos->id;
$nome_projeto = $projetos->nome_projeto;
?>


    
    <div class="col-lg-12">
    <div class="box">
    <section class="content-header">
          <h1>
              <font style="color: red;"> <?php echo 'Cancelar Ação '.$acao->sequencial; ?> </font>
              <small><?php echo $nome_projeto; ?></small>
                  
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= site_url('project'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Cancelar Ação</li>
          </ol>

        </section>
        <br>
    </div>    
    </div>
    
     <style>
        #div1
        {
        border: 1px solid;
        border-color: #000000;
        width:100%;
        height:100px;
        background-color: #f4f4f4;


        }

        </style>
    <section  class="content">
    
    <div class="row">    
    
    
    <div class="col-lg-12">
        <div class="box">
            <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <center>
                                        <?php if($origem == 1){ ?>
                                        <h2>  PLANO DE AÇÃO  <?PHP ECHO $acao->idplano; ?>  </h2>
                                        <?php }else if($origem == 2){ ?>
                                        <h2>  ATA  <?PHP ECHO $acao->idplano; ?>  </h2>
                                        <?php } ?>
                                    </center>
                                </div>
                            </div>
                              <div id="blanket"></div>
                                <div id="aguarde">Aguarde...Enviando Email</div>
                            <div class="clearfix"></div>
                            <?php
                            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                           echo form_open_multipart("project/cancelarAcao", $attrib); 
                            echo form_hidden('id', $acao->idplanos);
                            echo form_hidden('idatas', $acao->idplano);
                            echo form_hidden('plano_acao', $plano_acao);
                            echo form_hidden('origem', $origem);
                            ?>
                            
                            <div class="col-md-12">
                                
                                <div class="col-md-6">
                                  <!-- DESCRIÇÃO DA AÇÃO -->  
                                  
                                  <div  class="form-group">
                                        <?= lang("Descrição ", "sldescricao"); ?><small>(O que ?)</small>
                                        <div id="div1">
                                                  <?php echo $acao->descricao; ?>
                                               </div>
                                  </div>
                              
                                    <!-- ONDE -->  
                                  <div  class="form-group">
                                        <?= lang("Local ", "onde"); ?><small>(Onde ?)</small>
                                        <div id="div1">
                                                  <?php echo $acao->onde; ?>
                                               </div>
                                  </div>
                                    <!-- PRAZO de -->
                                  <div class="form-group">
                                            <?= lang("Data Início", "sldate"); ?><small>(Quando ?)</small>
                                            
                                            <input class="form-control input-tip " value="<?php echo $acao->data_entrega_demanda; ?>" required="true" disabled="true" name="dateEntrega" type="date">
                                        </div>
                                    <!-- PRAZO ATE -->
                                  <div class="form-group">
                                            <?= lang("Data Término ", "sldateEntregaDemanda"); ?>
                                            <input class="form-control input-tip " value="<?php echo $acao->data_termino; ?>" required="true" disabled="true" name="data_termino" type="date">
                                        </div>
                                    <!-- HORAS -->
                                  <div class="form-group">
                                        <?= lang("Horas Previstas", "horas"); ?>
                                       <input class="form-control input-tip" placeholder="Horas Previstas" value="<?php echo $acao->horas_previstas; ?>" disabled="true"  name="horas_previstas" type="number">
                                       </div>
                                   <!-- QUEM -->
                                  <div class="form-group">
                                        <?= lang("Responsável ", "slResponsavel"); ?><small>(Quem ?)</small>
                                        <?php
                                        //$wu4[''] = '';
                                        foreach ($users as $user) {
                                            $wu4[$user->id] = $user->nome.' '.$user->last.' - '.$user->setor;
                                        }
                                        $id_usu_setor =  $this->atas_model->getUserSetorByUsuarioAndSetor($acao->responsavel, $acao->setor);
                                      //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                        echo form_dropdown('responsavel', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : $id_usu_setor->id), 'id="slResponsavel" disabled="true" required="true"  class="form-control  select" data-placeholder="' . lang("Selecione o(s) Responsavel(eis)") . ' "   style="width:100%;"   ');
                              
                                        ?>
                                    </div>
                                
                            </div>
                            
                                <div class="col-md-6">
                                    <!-- PORQUE -->  
                                    <div class="form-group">
                                        <?= lang("Motivo, Justificativa", "porque"); ?><small>(Por Quê? )</small>
                                        <div id="div1">
                                                  <?php echo $acao->porque; ?>
                                               </div>
                                    </div>
                                    <!-- COMO -->  
                                    <div class="form-group">
                                        <?= lang("Detalhes", "como"); ?><small>(Como? )</small>
                                        <div id="div1">
                                                  <?php echo $acao->como; ?>
                                               </div>
                                    </div>
                                    <!-- VALOR -->  
                                    <div class="form-group">
                                        <?= lang("Custo", "custo"); ?><small> (Descrição do Custo? )</small>
                                        <div id="div1">
                                                  <?php echo $acao->custo; ?>
                                               </div>
                                    </div>
                                    <?= lang("Valor", "custo"); ?><small> (Valor do Custo? )</small>
                                    <input class="form-control" placeholder="Valor do Custo para esta ação" onkeypress="mascara(this, mvalor);" disabled="true" value="<?php echo str_replace('.', ',', $acao->valor_custo); ?>"  name="valor_custo" type="text">
                                    
                                    <!-- DOCUMENTO -->  
                                    
                                </div>
                           
                            </div>    
                            
                            
                            
                            
                            
                            

                            <center>

                                <div class="col-md-12">
                                      <?php echo form_submit('add_item', lang("Cancelar Ação"), 'id="add_item" class="btn btn-danger" style="padding: 6px 15px; margin:15px 0;" onclick="alertas();" '); ?>
                                     <?php if($origem == 1){ ?>
                                        <a  class="btn btn-primary"  href="<?= site_url('project/plano_acao_detalhes/'.$plano_acao); ?>"><?= lang('Voltar') ?></a>
                                     <?php }else if($origem == 2){ ?>
                                        <a  class="btn btn-primary"  href="<?= site_url('Atas/plano_acao/'.$plano_acao.'/2'); ?>"><?= lang('Voltar') ?></a>
                                     <?php } ?>
                                    
                             
                                </div>
                                 </center>
                             <?php echo form_close(); ?>
                       
       
                <br><br><br>
                </div>
            
            
            <div class="row">
                <br>
                <BR>
            </div>
            
        </div>        
    </div>
        
   
    
    
        
    </div>
   
    
        
     
    
    
    <!-- /.col-lg-12 -->
        
    </section>    

