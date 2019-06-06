

 
  <div class="col-lg-12">
    <div class="box">
     <section class="content-header">
                  <h1>
                    MINHAS RAT'S 
                    <small><?php echo 'Registro de Atividades'; ?> </small>
                  </h1>
                  <ol class="breadcrumb">
                    <li><a href="<?= site_url('welcome'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Rat's</li>
                  </ol>
                </section>

             <div class="box-header">
                    <span class="pull-right-container">
                       <div class=" clearfix no-border">
                       <!--    <a  title="Cadastrar Nova Tarefa" class="btn btn-primary pull-right" href="<?= site_url('project/novoPlano'); ?>" data-toggle="modal" data-target="#myModal">  
                           <i class="fa fa-plus"></i>   Novo Registro
                           </a> -->
                         
                        </div>
                    </span>
                </div>
    </div>
    </div>
<br>
<!-- FILTRO  -->
<div class="col-lg-12">
    <div class="box">
      <div class="row">
        <div class="col-lg-12">
            <section class="content-header">
                <small><?php echo 'Filtro'; ?> </small>
            </section>
          <?php
            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
            echo form_open_multipart("welcome/registro_atividade", $attrib);
            echo form_hidden('filtro', '1');
            ?>
        
            <div class="col-md-3">
              <div class="form-group">
                <label>Projetos</label>
                <?php

                $wu_projetos[''] = '';

                $projetos_users = $this->networking_model->getAllProjetosUserById_User();
                foreach ($projetos_users as $projeto_u) {
                    $wu_projetos[$projeto_u->id] = $projeto_u->projeto;
                }
                    echo form_dropdown('projeto_filtro', $wu_projetos, (isset($_POST['projeto_filtro']) ? $_POST['projeto_filtro'] : $projeto_filtro), 'id="reacao"  class="form-control selectpicker  select" data-placeholder="' . lang("Todos Projetos") . ' "  style="width:100%;" ');
                ?>

              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                  <label>Data Início</label>
                  <input type='date' name='data_de' value="<?php echo $data_inicio; ?>" class="form-control">
             </div>
            </div>


           <div class="col-md-3">
              <div class="form-group">
                  <label>Data Fim</label>
                  <input type='date' name='data_ate' value="<?php echo $data_fim; ?>" class="form-control">
             </div>
            </div>   

           


            <div class="col-md-12">
              <?php echo form_submit('add_marco', lang("Pesquisar"), 'id="add_item" class="btn btn-primary "  '); ?>
              <a class="btn btn-warning" href="<?= site_url('welcome/registro_atividade/0/92'); ?>"><i class="fa fa-eraser "></i> Limpar</a>
               
            <br>
            </div>
            <?php echo form_close(); ?>      
    <br>
    
        </div>
      </div>  
        <br>
   </div>     
</div>
<br>

    <section  class="content">
        <div class="col-lg-12">
            <div class="row">
                
                
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
        
                
                
                    <div class="box">
                      <script type="text/javascript">
                        function optionCheckPDF(){
                            if(document.getElementById("carrega_form_pdf").style.display == "block"){
                             //   document.getElementById("hiddenDiv").style.visibility ="visible";
                                document.getElementById("carrega_form_pdf").style.display = "none";
                            }else{
                                document.getElementById("carrega_form_pdf").style.display = "block";
                            }


                        }
                    </script>
                    <div class="col-md-12">
                        <a onclick="optionCheckPDF()"  > <i class="fa fa-file-pdf-o "></i> Exportar para pdf  </a>
                        <br>
                    </div>
                
                            
                    <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                    echo form_open_multipart("welcome/registro_atividade_pdf", $attrib);
                    echo form_hidden('gerar_pdf', '1');
                    ?>
        
                    <div id="carrega_form_pdf" style="display: none">
                        <br><br>
                          <div class="col-md-12">
                              <div class="form-group company">
                                 <?= lang("Exibir valores no PDF", "detalhes"); ?>
                                  <br>
                                <?php // print_r($sql_rat); ?>
                                  <input type="radio" value="1" checked="true" name="valores_pdf" class="form-control input">SIM 
                                  <input type="radio" value="0"  name="valores_pdf" class="form-control input">NÃO
                            </div>
                            <div class="form-group company">
                                 <?= lang("Observações para o PDF", "detalhes"); ?>
                                <?php // print_r($sql_rat); ?>
                                <textarea class="form-control input" name="detalhes">
                                </textarea>
                            </div>
                        </div>
                        <center>
                        <input class="btn btn-danger " type="submit" value="Gerar PDF" >
                        </center>
                      </div>  
                    
              
                    
                
                      <br>
                    <div class="table-responsive">
                        <div class="box-body">
                            <table style="width: 100%" id="minhas_rats" class="table table-bordered table-striped">
                            <thead>
                                
                            <th style="color: #ffffff; width: 5%;"><input type="checkbox" checked="true"></th>
                                <th style="color: #ffffff; width: 15%;">PROJETO</th>
                                <th style="color: #ffffff; width: 20%;">REGISTROS</th>
                                <th style="color: #ffffff; width: 30%;">DESCRIÇÃO RAT</th>
                                <th style="color: #ffffff; width: 20%;">AÇÃO</th>
                                <th style="color: #ffffff; width: 10%;">OPÇÕES</th>
                                
                            </thead>
                            <tbody>
                                 <?php
                                 
                                    $wu4[''] = '';
                                    $cont = 1;
                                    $total_valor = 0;
                                    $soma_hora = '0';
                                    $soma_minutos = '0';
                                       foreach ($rats as $rat) {
                                            
                                            $projeto = $rat->projeto;
                                            $id_rat = $rat->id_rat;
                                            $data_rat = $rat->data_rat;
                                            $dataHoje = date('Y-m-d');
                                            $inicio = $rat->hora_inicio;
                                            $termino = $rat->hora_fim;
                                            $data_prazo = $rat->data_termino;
                                            $tempo = $rat->tempo;
                                            
                                            $partes_tempo = explode(":", $tempo);
                                            $hora_tempo = $partes_tempo[0];
                                            $minuto_tempo = $partes_tempo[1];
                                            
                                            $horas_minutos = $hora_tempo * 60;
                                            
                                            $subtotal = $horas_minutos + $minuto_tempo;
                                            
                                           // echo 'Hora : '.$horas_minutos.'<br>';
                                           // echo 'Minuto : '.$minuto_tempo.'<br>';
                                           // echo 'sub_total : '.$subtotal.'<br>';
                                           // echo '---------- <br>';  13:30
                                            
                                            
                                            $soma_minutos += $minuto_tempo;
                                            
                                            $soma_hora += $subtotal; 
 
                                            $valor = $rat->valor;
                                            $total_valor += $valor;
                                            $rat_descricao = $rat->descricao_rat;
                                            
                                            $idacao = $rat->idplanos;
                                            $sequencial = $rat->sequencial;
                                            $acao_desc = $rat->descricao_acao;
                                            
                                            $status = $rat->status_acao;
                                            
                                             if ($status == 'PENDENTE') {

                                                
                                                /*
                                                 * SE A DATA ATUAL FOR < A DATA DO PRAZO
                                                 * PENDENTE
                                                 */
                                                if ($data_prazo >= $dataHoje ) {
                                                    $novo_status = 'PENDENTE';
                                                    $desc_tipo = "orange-active";
                                                }else

                                                /*
                                                 * SE A DATA ATUAL FOR > A DATA DO PRAZO
                                                 * ATRASADO (X DIAS)
                                                 * +5 DIAS
                                                 * +10 DIAS
                                                 * 
                                                 */
                                                if ( $data_prazo < $dataHoje ) {
                                                    
                                                    $novo_status = 'ATRASADO';
                                                    $desc_tipo = "red-active";

                                                 
                                                }
                                                
                                                
                                            } else if ($status == 'AGUARDANDO VALIDAÇÃO') {
                                                $novo_status = 'AGUARD. VALIDAÇÃO';
                                                $desc_tipo = "blue-gradient";


                                            } else if ($status == 'CONCLUÍDO') {
                                                $novo_status = 'CONCLUÍDO';
                                                $desc_tipo = "green-active";

                                            }else if ($status == 'CANCELADO') {
                                                $novo_status = 'CANCELADO';
                                                $desc_tipo = "black-active";
                                            }
                                           
                                            ?>   


                                            <tr >
                                                <td style="width: 5%;"><input type="checkbox" name="registros[]" value="<?php echo $id_rat; ?>" checked="true"></td>
                                                <td style="width: 15%;"><?php echo $projeto; ?></td> 
                                                
                                                <td style="width: 20%;">
                                                    <small class="label bg-gray"><?php echo 'Dt da Atividade. : '. date('d/m/Y', strtotime($data_rat)); ?></small> <br>
                                                    <small class="label bg-gray"><?php echo 'Hr Início : '. $inicio; ?></small> 
                                                    <small class="label bg-gray"><?php echo 'Hr Fim : '.$termino; ?></small>
                                                    <h4><small class="label bg-blue-active"><?php echo 'Tempo ( '.$tempo.' )'; ?></small></h4>
                                                    <?php if($valor > 0){ ?>
                                                    <h4><small class="label bg-green-active"> <?php echo 'Custo R$'. str_replace('.', ',', $valor); ?></small></h4>
                                                    <?php } ?>
                                                </td>
                                                
                                                <td style="width: 30%;"><p style="text-align: justify"><?php echo $rat_descricao;  ?></p></td>
                                                
                                                
                                                <td style="width: 20%;">
                                                    <a title="Visualizar Ação" href="<?= site_url('welcome/dados_cadastrais_acao/'.$idacao); ?>" target="_blank" class="label bg-black">
                                                    <?php echo $sequencial; ?>
                                                     </a>
                                                    <a title="Visualizar Ação" href="<?= site_url('welcome/dados_cadastrais_acao/'.$idacao); ?>" target="_blank" class="label bg-<?php echo $desc_tipo; ?>">    
                                                    <?php echo $novo_status; ?>
                                                   </a>
                                                    <?php echo $acao_desc; ?>
                                                </td>
                                                <td style="width: 10%;" class="center">
                                                    <a title="Editar RAT" href="<?= site_url('welcome/editar_rat/'.$id_rat); ?>" data-toggle="modal" data-target="#myModal"  class="btn btn-bitbucket">
                                                        <i class="fa fa-pencil "></i>
                                                    </a>
                                                    <a title="Deletar RAT" href="<?= site_url('welcome/deletar_rat/'.$id_rat); ?>" data-toggle="modal" data-target="#myModal"  class="btn btn-danger">
                                                        <i class="fa fa-trash "></i>
                                                    </a>
                                                </td>
                                               
                                                
                                               
                                              
                                                </tr>
                                    <?php
                                    }
                                    
                                    ?>
                                              
                                                
                            </tbody>
                        </table>
                            <br><br>
                            <?php
                            
                            $horas = floor($soma_hora / 60);
                            $minutos = $soma_hora % 60;// floor(($total - ($horas * 60)) / 60);
                           // $segundos = floor($total % 60);
                           // echo $horas . ":" . $minutos . ":" . $segundos;
                            
                            ?>
                             <h2> <font class="label bg-orange-active"> <?php echo 'Total de horas ' ; ?>  <?php echo  $horas . ":" . $minutos.' h'; ?> </font></h2>
                            <?php if( $total_valor > 0 ){ ?>
                            
                            <h2> <font class="label bg-green"> <?php echo 'Valor Total : R$ '; ?>  <?php echo number_format($total_valor, 2, ',', '.');  ?> </font></h2>
                            
                            <?php } ?>
                            <br><br>
                        </div>    
                    </div>
                   
                      <?php echo form_close(); ?>   
                      
                </div>
                
      <!-- /.row (main row) -->
            </div>
        </div>
    </section>
    <!-- /.content -->
 

 <script>
  $(function () {
  $('#minhas_rats').DataTable({
     // "order": [[ 0, "desc" ]]
    })
  })
</script>