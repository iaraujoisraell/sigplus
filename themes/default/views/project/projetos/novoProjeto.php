  

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
$projetos = $this->projetos_model->getProjetoAtualByID_completo();

?>
<div id="blanket"></div>
<div id="aguarde">Aguarde...</div>

    <div class="col-lg-12">
    <div class="box">
    <section class="content-header">
          <h1>
            <?php echo 'Novo Projeto'; ?>
             
                  
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= site_url('project'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Projetos</li>
          </ol>

        </section>
        <br>
    </div>   
    </div>
    
    <div class="row">  
    <div class="col-lg-12">
            <?php if ($Settings->mmode) { ?>
                        <div class="alert alert-warning">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <?= lang('site_is_offline') ?>
                        </div>
                    <?php }
                    if ($error) { ?>
                        <div class="alert alert-danger">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $error; ?></ul>
                        </div>
                    <?php }
                    if ($message) { ?>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $message; ?></ul>
                        </div>
                    <?php } ?>
                    </div>
    </div>
    
    <section  class="content">
    
    <div class="row">    
    
    
    <div class="col-lg-12">
        <div class="box">
            
                <div  class="nav-tabs-custom">
                    <ul style="background-color: #d2d6de; " class="nav nav-tabs">
                        <li class="active"><a href="#cadastro"  data-toggle="tab"><b>Dados Cadastrais<i class="fa fa-file-text-o"></i></b></a></li>  
                        <li><a title="Esta aba será liberada após o cadastro do projeto." ><b>Partes Interessadas <i class="fa fa-group"></i></b></a></li>                         
                        <li><a title="Esta aba será liberada após o cadastro do projeto." ><b>Equipe <i class="fa fa-user-circle-o"></i></b></a></li>
                        <li><a title="Esta aba será liberada após o cadastro do projeto." ><b>Marcos <i class="fa fa-calendar-check-o"></i></b></a></li>
                        <li><a title="Esta aba será liberada após o cadastro do projeto." ><b>Arquivos <i class="fa fa-folder-open"></i></b></a></li>
                        <li><a title="Esta aba será liberada após o cadastro do projeto." ><b>Acesso <i class="fa fa-user-secret"></i></b></a></li>
                        <li><a title="Esta aba será liberada após o cadastro do projeto."><b>Histórico <i class="fa fa-comments-o"></i></b></a></li>
                        <li><a title="Esta aba será liberada após o cadastro do projeto." ><b>Log <i class="fa fa-search"></i></b></a></li>
                    </ul>
                    
                    <div class="tab-content">
                        <div class="active tab-pane" id="cadastro">
                            <div class="row">
                                <div class="col-md-12">
                    <div class="form-group">
                        <center>
                            <h2> NOVO CADASTRO DE PROJETO   </h2>
                        </center>
                    </div>
                </div>
                  <div id="blanket"></div>
                    <div id="aguarde">Aguarde...Enviando Email</div>
                <div class="clearfix"></div>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("project/novoProjeto/", $attrib);
                ?>
                <div class="col-md-12">
                <!-- ITEM EVENTO -->
                <div class="col-md-12">
                  <div class="form-group">
                        <?= lang("Nome do Projeto", "projeto"); ?>
                          <?php echo form_input('projeto', (isset($_POST['projeto']) ? $_POST['projeto'] : $local), 'maxlength="250" required class="form-control input-tip"  id="projeto"'); ?>
                  </div>
                </div>
                
                <div class="col-md-6">
                   
                    <div class="form-group">
                        
                        <?= lang("Cliente", "cliente"); ?> <i title="O cliente pode ser a própria empresa ou um setor interno da empresa, representa quem será o 'dono' projeto." class="fa fa-info-circle"> </i> <small></small>
                         <a  title="Novo Cliente" class="btn btn-primary pull-right" href="<?= site_url('project/novoCadastroBasico/' . 91 . "/" . 28); ?>" data-toggle="modal" data-target="#myModal">  
                            <i class="fa fa-plus"></i> 
                        </a>
                            <?php
                        $wu_cliente[''] = '';
                        foreach ($clientes as $cliente) {
                            $wu_cliente[$cliente->id] = $cliente->name;
                        }
                        //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                        echo form_dropdown('cliente', $wu_cliente, (isset($_POST['cliente']) ? $_POST['cliente'] : ""), 'id="cliente" required="true"  class="form-control  select" data-placeholder="' . lang("Selecione o Cliente") . ' "   style="width:100%;" ');
                        ?>
                        
                    </div>
                    
                </div>
                
                <div class="col-md-6">
                   
                    <div class="form-group">
                        
                        <?= lang("Categoria", "categoria"); ?> <i title="Categoria do projeto." class="fa fa-info-circle"> </i> <small></small>
                         <a  title="Nova Categoria" class="btn btn-primary pull-right" href="<?= site_url('project/novoCadastroBasico/' . 96 . "/" . 28); ?>" data-toggle="modal" data-target="#myModal">  
                            <i class="fa fa-plus"></i> 
                        </a>
                            <?php
                        $wu_categoria[''] = '';
                        foreach ($categorias as $categoria) {
                            $wu_categoria[$categoria->id] = $categoria->descricao;
                        }
                        //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                        echo form_dropdown('categoria', $wu_categoria, (isset($_POST['categoria']) ? $_POST['categoria'] : ""), 'id="categoria" required="true"  class="form-control  select" data-placeholder="' . lang("Selecione a Categoria") . ' "   style="width:100%;" ');
                        ?>
                        
                    </div>
                    
                </div>
                

                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("Data Início *", "data_inicio"); ?> 
                        <input name="data_inicio" required="true" class="form-control" type="date" >
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("Data Término *", "data_termino"); ?>
                        <input name="data_termino" required="true" class="form-control" type="date" >
                    </div>
                </div>
                <!-- GERENTE -->
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("Gerente do Projeto", "gerente"); ?> <i title="É o responsável por conduzir o projeto e alcançar seus objetivos." class="fa fa-info-circle"> </i> <small> </small>
                    <!--      <a  title="Novo Usuário" class="btn btn-primary pull-right" href="<?= site_url('admin/novo_usuario_modal/'); ?>" data-toggle="modal" data-target="#myModal">  
                            <i class="fa fa-plus"></i> 
                        </a> -->
                        <?php
                        $wu4[''] = '';
                        foreach ($users as $user) {
                            $wu4[$user->id] = $user->nome . ' ' . $user->last . ' - ' . $user->setor;
                        }
                        //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                        echo form_dropdown('gerente', $wu4, (isset($_POST['gerente']) ? $_POST['gerente'] : $participantes_usuarios), 'id="gerente" required="true"  class="form-control  select" data-placeholder="' . lang("Selecione o Gerente") . ' "   style="width:100%;" ');
                        ?>
                    </div>
                </div>
                <!-- COORDENADOR -->
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("Coordenador do Projeto", "coordenador"); ?> <i title="No SigPlus, o coodenador do projeto gerencia as ações, recebe notificações, avalia e retorna as ações. O Gerente também pode fazer o papel do coordenador do projeto." class="fa fa-info-circle"> </i> <small> </small>
                        <?php
                        $wu42[''] = '';
                        foreach ($users as $user) {
                            $wu42[$user->id] = $user->nome . ' ' . $user->last . ' - ' . $user->setor;
                        }
                        //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                        echo form_dropdown('coordenador', $wu42, (isset($_POST['coordenador']) ? $_POST['coordenador'] : $participantes_usuarios), 'id="coordenador"   class="form-control  select" data-placeholder="' . lang("Selecione o Coordenador") . ' "   style="width:100%;"   ');
                        ?>
                    </div>
                </div>

                </div>
                <div class="col-md-12">

                    <div class="col-md-6">
                      <!-- JUSTIFICATIVA -->  

                      <div  class="form-group">
                            <?= lang("Justificativa do Projeto ", "historico"); ?><small> </small>
                            <?php echo form_textarea('justificativa', (isset($_POST['justificativa']) ? $_POST['justificativa'] : $acao->justificativa), 'class="form-control  input-tip "    id="justificativa"  '); ?>
                      </div>

                        <!-- OBJETIVO -->  
                      <div  class="form-group">
                            <?= lang("Objetivos do Projeto ", "objetivo"); ?><small> </small>
                            <?php echo form_textarea('objetivo', (isset($_POST['objetivo']) ? $_POST['objetivo'] : $acao->objetivo), 'class="form-control"   id="objetivo"  '); ?>
                      </div>
                        <!-- DESCRIÇÃO -->  
                        <div class="form-group">
                            <?= lang("Descrição do Projeto", "descricao"); ?><small> </small>
                            <?php echo form_textarea('descricao', (isset($_POST['descricao']) ? $_POST['descricao'] : $acao->descricao), 'class="form-control"   id="descricao"  '); ?>
                        </div>

                    </div>

                    <div class="col-md-6">
                        <!-- PREMISSAS -->
                        <div class="form-group">
                            <?= lang("Premissas", "Premissas"); ?><small> </small>
                            <?php echo form_textarea('Premissas', (isset($_POST['Premissas']) ? $_POST['Premissas'] : $acao->Premissas), 'class="form-control"   id="Premissas"  '); ?>
                        </div>
                        <!-- PORQUE -->  
                        <div class="form-group">
                            <?= lang("Restrições", "restricoes"); ?><small> </small>
                            <?php echo form_textarea('restricoes', (isset($_POST['restricoes']) ? $_POST['restricoes'] : $acao->restricoes), 'class="form-control"   id="restricoes"  '); ?>
                        </div>
                        <!-- COMO -->  
                        <div class="form-group">
                            <?= lang("Benefícios", "beneficios"); ?><small></small>
                            <?php echo form_textarea('beneficios', (isset($_POST['beneficios']) ? $_POST['beneficios'] : $acao->beneficios), 'class="form-control"  id="beneficios"  '); ?>
                        </div>

                    </div>

                </div>    

                <center>
                    <div class="col-md-12">
                          <?php echo form_submit('add_item', lang("Salvar"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;" onclick="alertas();" '); ?>
                            <a  class="btn btn-danger" onclick="history.go(-1)"  ><?= lang('Sair') ?></a>
                    </div>
                </center>
                 <?php echo form_close(); ?>
                            </div>
                         </div>   
                     </div>       
                    
                </div>
              
            
            
            
            <div class="row">
                <br>
                <BR>
            </div>
            
        </div>        
    </div>
        
   
    
    
        
    </div>
        
    </section>    


<!-- InputMask -->

<!-- Page script -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    //$('#reservation').daterangepicker();
    
     $(function() { $("#reservation").daterangepicker({
            locale: { format: 'DD/MM/YYYY' } ,  language: 'pt-BR',
            minDate: '<?php echo exibirData($projetos->dt_inicio) ?>',
            maxDate: '<?php echo exibirData($projetos->dt_final) ?>'
        
        }); });
     

    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'DD/MM/YYYY' })
    
    
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',                
    language: 'pt-BR'
    })
    

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    })
  })
</script>