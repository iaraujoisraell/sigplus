<?php 
    $usuario = $this->session->userdata('user_id');
    $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
    $dados_periodo = $this->atas_model->getPeriodoHEByID($id_periodo);
    $id_usuario = $dados_periodo->user_id;
    $dados_user = $this->atas_model->getUserTIByUser($id_usuario);
    $cpf = $dados_user->cpf;
    
    $user_inf = $this->site->getUser($id_usuario);
   // echo $cpf;
    
   
?>


    <div class="content-wrapper">
        
    <section class="content-header">
      <h1>
        Requisição de Horas
        <small>Lançamento de horas</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?= site_url('welcome/home'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
        <h4><?php echo $user_inf->first_name; ?></h4>
    </section>
        
    <section class="content">
      
        
        <div class="row">


                <?php
                       function float_min($num) {
      $num = number_format($num,2);
      $num_temp = explode('.', $num);
      $num_temp[1] = $num-(number_format($num_temp[0],2));
      $saida = number_format(((($num_temp[1]) * 60 / 100)+$num_temp[0]),2);
      $saida = strtr($saida,'.',':');
      return $saida;
      // By Alexandre Quintal - alexandrequintal@yahoo.com.br
    }    
    
                
                $meses = array(
                    '1'=>'Janeiro',
                    '2'=>'Fevereiro',
                    '3'=>'Março',
                    '4'=>'Abril',
                    '5'=>'Maio',
                    '6'=>'Junho',
                    '7'=>'Julho',
                    '8'=>'Agosto',
                    '9'=>'Setembro',
                    '10'=>'Outubro',
                    '11'=>'Novembro',
                    '12'=>'Dezembro'
                );
                //competencia
                
                
                 $status =  $competencia->status_verificacao;
                                                   
                    if($status == 1){
                        $status_periodo = 'FECHADO';
                    }else{
                        $status_periodo = 'ABERTO';
                       
                    }
                    
                    
                    
                    /*
                     * RETORNA O SALDO DO PERÍODO ANTERIOR
                     */
                     include 'conexao_rh3.php';
                     
                     if($competencia->mes == '1'){
                        $mes_anterior = 12;
                     }else{
                        $mes_anterior = $competencia->mes - 1;
                     }
                     if($mes_anterior == 12){
                         $ano_saldo = $competencia->ano - 1;
                     }else{
                         $ano_saldo = $competencia->ano;
                     }
                  //  echo 'mes :'.$mes_anterior.'<br>';
                  //  echo 'ano :'.$ano_saldo.'<br>';
                    
                    $query_saldo_hora = "select round(sum(m.qtdhorasreais),2) from pt_bh_movimentacoes m
                                            where m.idpessoa in (select p.id
                                                                     from pessoas p
                                                                    where p.datarescisao is null
                                                                      and p.cpf in '$cpf'
                                                                      and p.tipopessoa = 'F')
                                            and m.databanco < '11/$mes_anterior/$ano_saldo'
                                            and m.datapagamento is null
                                            and m.idbancohoras in 
                                            (select b.id from pt_bancoshoras b 
                                                         where b.datainicio >=  '01/05/2018')";
                   // echo $query_saldo_hora; 
                    $saldo_mes_anterior = oci_parse($ora_conexao,$query_saldo_hora);
                    oci_execute($saldo_mes_anterior, OCI_NO_AUTO_COMMIT);
                    $cont = 0;
                    while (($row_sd_hmu = oci_fetch_array($saldo_mes_anterior, OCI_BOTH)) != false)
                    {
                    if($row_sd_hmu[0] < 0){
                        $color = 'red';
                    }else{
                        $color = 'blue';
                    }
                    $saldo_competencia_anterios = str_replace(",", ".", $row_sd_hmu[0]);    //DATA
                  //   $saldo_competencia_anterios = substr_replace(',', '.', $saldo_competencia_anterios2);
                    $horas_saldo = float_min($saldo_competencia_anterios);
                    
                    }
                    
                    
                ?>
        
        <div class="col-md-12">
            <h3>Competência: <?php echo $meses[$competencia->mes].'/'.$competencia->ano; ?> <small><?php echo ' ( '.$status_periodo.' ) '; ?></small></h3>
         <?php


                $conta_hora_extra = 0;
                $conta_banco_hora = 0;
                $conta_debito_hora = 0;
                $cont=0;
                foreach ($lacamentos as $periodo) {
                    $tipo = $periodo->tipo_registro;
                    $hora_extra = $periodo->hora_extra;
                    $debito = $periodo->debito;
                    $saldo = $periodo->saldo;
                 //   $saldo = calculaTempo($periodo->hora_inicio, $periodo->hora_fim_confirmado);

                    if($debito){
                        $tempos_Debito[$cont++] = $debito;

                    }

                    if ($saldo){
                        if($hora_extra == 'SIM'){
                           $tempos_HoraExtra[$cont++] = $saldo;
                        }else{
                            $tempos_BancoHora[$cont++] = $saldo;
                        }
                    }

                }            
                $segundos = 0;
                /*
                 * CALCULA O TEMPO DE DÉBITO DE HORA
                 */
                foreach ( $tempos_Debito as $tempo ){ 
                    list( $h, $m, $s ) = explode( ':', $tempo ); 
                    $segundos += $h * 3600;
                    $segundos += $m * 60;
                    $segundos += $s;
                }

                $horas = floor( $segundos / 3600 ); //converte os segundos em horas e arredonda caso nescessario
                $segundos %= 3600; // pega o restante dos segundos subtraidos das horas
                $minutos = floor( $segundos / 60 );//converte os segundos em minutos e arredonda caso nescessario
                $segundos %= 60;// pega o restante dos segundos subtraidos dos minutos

                /******************************************************
                 * CALCULA O TEMPO DE HORA EXTRA
                 *******************************************************/

                 foreach ( $tempos_HoraExtra as $tempo_he ){ //percorre o array $tempo
                    list( $h, $m, $s ) = explode( ':', $tempo_he ); //explode a variavel tempo e coloca as horas em $h, minutos em $m, e os segundos em $s

                    $segundoshe += $h * 3600;
                    $segundoshe += $m * 60;
                    $segundoshe += $s;
                }
                $horashe = floor( $segundoshe / 3600 ); //converte os segundos em horas e arredonda caso nescessario
                $segundoshe %= 3600; // pega o restante dos segundos subtraidos das horas
                $minutoshe = floor( $segundoshe / 60 );//converte os segundos em minutos e arredonda caso nescessario
                $segundoshe %= 60;// pega o restante dos segundos subtraidos dos minutos

                /******************************************************
                 * CALCULA O TEMPO DE BANCO DE HORAS
                 *******************************************************/

                foreach ( $tempos_BancoHora as $tempo_bh ){ //percorre o array $tempo
                    list( $h, $m, $s ) = explode( ':', $tempo_bh ); //explode a variavel tempo e coloca as horas em $h, minutos em $m, e os segundos em $s

                    $segundosbh += $h * 3600;
                    $segundosbh += $m * 60;
                    $segundosbh += $s;
                }
                $horasbh = floor( $segundosbh / 3600 ); //converte os segundos em horas e arredonda caso nescessario
                $segundosbh %= 3600; // pega o restante dos segundos subtraidos das horas
                $minutosbh = floor( $segundosbh / 60 );//converte os segundos em minutos e arredonda caso nescessario
                $segundosbh %= 60;// pega o restante dos segundos subtraidos dos minutos
                //print_r($tempos_Debito);

               ?>
            <ol class="breadcrumb">
                    <li> Saldo Anterior : <font style="color: blue;"> <?php echo str_replace(",", ".", $horas_saldo); ?></font></li>
                    <li> Horas Extra : <font style="color: blue;"> <?php echo "{$horashe}:{$minutoshe}"; ?></font></li>
                    <li class="active">Crédito : <font style="color: green;"> <?php echo "{$horasbh}:{$minutosbh}"; ?></font></li>
                    <li class="active">Débito : <font style="color: red;"> <?php echo "- {$horas}:{$minutos}"; ?> </font></li>
                </ol>
        <div class="col-lg-12">

                   <div style="text-align: right" class="col-lg-12">
                  
              </div>
                        <?php
                        $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                        echo form_open_multipart("Projetos/conclui_envio_hora_extra", $attrib);
                        echo form_hidden('mes', $competencia->mes);
                        echo form_hidden('ano', $competencia->ano);
                        echo form_hidden('id_periodo', $id_periodo);
                        echo form_hidden('cpf', $cpf);
                        ?>
                        <div class="portlet-body">
                            <div class="table-responsive">
                                <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                    <thead>
                                     <tr style="background-color: orange; width: 100%;">

                                        <th style="width: 15%;">DATA</th>
                                        <th style="width: 35%;">Descrição Justificativa</th>
                                        <th style="width: 5%;"> Entrada</th>
                                        <th style="width: 5%;"> Entrada almoço</th>
                                        <th style="width: 5%;"> Saída almoço</th>
                                        <th style="width: 5%;"> Saída </th>
                                        <th style="width: 5%;">Crédito</th>
                                        <th style="width: 5%;">Débito</th>
                                        <th style="width: 5%;">Ação</th>
                                        <th style="width: 5%;">H.Extra</th>
                                        <th style="width: 5%;">Autorizar</th>
                                        <th style="width: 5%;">Horas Aut.</th>
                                        <th style="width: 10%;">Justificativa.</th>
                                       
                                    </tr>

                                    </thead>
                                    <tbody>
                                         <?php
                                            $wu4[''] = '';
                                            $cont = 1;
                                            foreach ($lacamentos as $periodo) {
                                                $id_cript = encrypt($periodo->id,'UNIMED');
                                                $meses = array(
                                                    '1'=>'Janeiro',
                                                    '2'=>'Fevereiro',
                                                    '3'=>'Março',
                                                    '4'=>'Abril',
                                                    '5'=>'Maio',
                                                    '6'=>'Junho',
                                                    '7'=>'Julho',
                                                    '8'=>'Agosto',
                                                    '9'=>'Setembro',
                                                    '10'=>'Outubro',
                                                    '11'=>'Novembro',
                                                    '12'=>'Dezembro'
                                                );

                                                $data_registro = $competencia->ano.'-'.$periodo->mes.'-'.$periodo->dia;
                                                $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');
                                                $diasemana_numero = date('w', strtotime($data_registro));
                                                //$dia_semana = $diasemana[$diasemana_numero];
                                                if($periodo->mes == 12){
                                            $dia_semana = $diasemana[$diasemana_numero - 1 ];
                                            }else{
                                                $dia_semana = $diasemana[$diasemana_numero ];
                                            }

                                                $qtde_registros =  $this->user_model->get_total_registro_data($periodo->id_periodo, $periodo->mes, $periodo->dia );
                                                $qtde_reg = $qtde_registros->quantidade;
                                                $qtde_colspan = $qtde_reg + 1;
                                                
                                                
                                                
                                               ?>   
                                                    <?php 
                                                    $tipo = $periodo->tipo_registro;
                                                    $saldo = calculaTempo($periodo->hora_inicio, $periodo->hora_fim_confirmado);

                                                    if( $tipo == 'Crédito'){
                                                        $desc_saldo = $saldo;
                                                        $cor = "blue";
                                                    }else if( $tipo == 'Débito'){
                                                        $desc_saldo = "-".$saldo;
                                                        $cor = "red";
                                                    }
                                                    
                                                    $saldo_tratado = explode(':', $periodo->saldo);
                                                    $hora_t = $saldo_tratado[0];
                                                    $min_t = $saldo_tratado[1];
                                                    
                                                    if(strlen($hora_t) == 1){
                                                        $nova_hora = '0'.$hora_t;
                                                         $saldo_novo = $nova_hora.':'.$min_t;
                                                    }else{
                                                        $saldo_novo = $hora_t.':'.$min_t;
                                                    } 
                                                    
                                                    $extra = $periodo->extra;
                                                   
                                                    echo form_hidden('justificativa'.$periodo->id, $periodo->descricao);
                                                    echo form_hidden('credito'.$periodo->id, $periodo->saldo);
                                                    
                                                   
                                                    ?>               

                                                <tr  >
                                                   
                                                    <td style="width: 15%; <?php if($extra == 1){ ?> background-color: yellow; <?php } ?>"><?php echo $periodo->dia.'/'.substr($meses[$periodo->mes], 0, 3); ?> <small class="label pull-right <?php if(($dia_semana == "Sabado")||($dia_semana == "Domingo")){ ?> bg-orange <?php }else{ ?> bg-gray <?php } ?> "><?php echo '('.$dia_semana.')'; ?></small></td> 
                                                    <td style="width: 35%;<?php if($extra == 1){ ?> background-color: yellow; <?php } ?>"><?php echo $periodo->descricao; ?> </td>
                                                    <td style="width: 5%;<?php if($extra == 1){ ?> background-color: yellow; <?php } ?>"><?php echo $periodo->hora_inicio; ?></td>
                                                    <td style="width: 5%;<?php if($extra == 1){ ?> background-color: yellow; <?php } ?>"><?php echo $periodo->entrada_intervalo; ?></td> 
                                                    <td style="width: 5%;<?php if($extra == 1){ ?> background-color: yellow; <?php } ?>"><?php echo $periodo->saida_intervalo; ?></td> 
                                                    <td style="width: 5%;<?php if($extra == 1){ ?> background-color: yellow; <?php } ?>"><?php echo $periodo->hora_fim_confirmado; ?> </td>
                                                    <td style="width: 5%; <?php if($extra == 1){ ?> background-color: yellow; <?php } ?> color: blue; "><?php echo $periodo->saldo; ?></td>
                                                    <td style="width: 5%; <?php if($extra == 1){ ?> background-color: yellow; <?php } ?> color: red;  "><?php echo $periodo->debito; ?></td>
                                                    <td style="width: 5%; ">
                                                <?php 
                                                $acoes_registros =  $this->user_model->getAcoesPeriodoHEById($periodo->id);
                                                 foreach ($acoes_registros as $periodo2) {
                                                     $acao = $periodo2->id_acao;
                                                     if($periodo2->id_acao == 0){
                                                         $acao = 'N/A';
                                                     }
                                                 ?>
                                                <p><a href="<?= site_url('welcome/verAcaoRequisicaoHoras/'.$periodo2->id_acao); ?>" data-toggle="modal" data-target="#myModal"><?php echo $acao; ?></a></p>
                                                <?php
                                                 }
                                                ?> 
                                            </td>
                                                    <td style="width: 5%;<?php if($extra == 1){ ?> background-color: yellow; <?php } ?>"><?php echo $periodo->hora_extra; ?> </td>
                                                    <td style="width: 5%;<?php if($extra == 1){ ?> background-color: yellow; <?php } ?>" class="center">
                                                        <?php if($status == 1){ 
                                                            echo $periodo->confirmacao;
                                                            ?>
                                                        
                                                        <?php }else{ ?>
                                                            <?php if($periodo->hora_extra == 'SIM'){
                                                               
                                                                ?>
                                                            <select style="height: 32px;"   name="opcao<?php echo $periodo->id; ?>">
                                                                <option value="SIM">SIM</option>
                                                                <?php if($extra != 1){ ?>
                                                                <option value="NÃO">NÃO</option>
                                                                <?php } ?>
                                                            </select>
                                                                <?php // }
                                                                    } ?>
                                                        <?php } ?>
                                                    </td>
                                                    <td style="width: 5%; <?php if($extra == 1){ ?> background-color: yellow; <?php } ?>">
                                                        <?php if($status == 1){ 
                                                            echo $periodo->hora_aprovada;
                                                            ?>
                                                        
                                                        <?php }else{ ?>
                                                          <?php if($periodo->hora_extra == 'SIM'){ 
                                                               if($extra == 1){
                                                              ?>
                                                        <input style="height: 32px;" type="time" disabled="true" name="hora_liberado<?php echo $periodo->id; ?>" readonly="true" value="<?php echo $saldo_novo; ?>">
                                                        <?php
                                                             }else{
                                                              ?>
                                                        
                                                        <input style="height: 32px;" type="time" name="hora_liberado<?php echo $periodo->id; ?>" value="<?php echo $saldo_novo; ?>">
                                                             <?php }
                                                             } ?>
                                                        <?php } ?>
                                                    </td>
                                                    
                                                    <td style="width: 10%;">
                                                        <?php if($periodo->hora_extra == 'SIM'){ ?>
                                                        <input  type="text" value="<?php echo $periodo->justificativa; ?>" maxlength="50" name="justificativa<?php echo $periodo->id; ?>" >
                                                        <?php } ?>
                                                    </td>    
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
             <?php if($status != 1){ ?>
                        <center>
                            <div class="col-md-12">
                            <?php echo form_submit('add_item', lang("Concluir"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0; width: 200px; height: 50px; " onclick="javascript:document.getElementById('."blanket".').style.display = "block"; document.getElementById('."aguarde".').style.display = "block";" '); ?>
                                        
                             </div>
                       
                        </center> 
             <?php } ?>

                </div>


    </div>
        </div>
    </section>      
        
        
         <section class="content-header">
              <h4>
                Resumo Eventos/Horas
              </h4>
                <br>
           <?php
            $soma_tempo_acoes = 0;
            $projetos = $this->user_model->getProjetoResumoHoras($id_periodo); 
            foreach ($projetos as $projeto) {
                $id_projeto = $projeto->id_projeto;
           ?>
                <ul>
                    <li>
                        <h4> <?php echo $projeto->projeto; ?> </h4>
                    </li>    
                </ul>
                <?php
                $cont_evento = 1;
                $eventos = $this->user_model->getEventosProjetoResumoHoras($id_periodo, $id_projeto); 
                foreach ($eventos as $evento) {
                    $id_evento = $evento->id_evento
                ?>
                <div style="margin-left: 50px;">
                    <ul>
                        <?php echo $cont_evento++. ' - '.$evento->tipo.' / '.$evento->nome_evento; ?>
                    </ul>    
                </div>
                <?php
                $eventos_itens = $this->user_model->getItemEventosProjetoResumoHoras($id_periodo, $id_projeto, $id_evento); 
                foreach ($eventos_itens as $evento_item) {
                    $id_item_evento = $evento_item->id_item_evento;
                ?>
                <div style="margin-left: 100px;">
                       <?php echo $evento_item->item_evento; ?>
                </div>
                
                <?php
                $eventos_itens_acoes = $this->user_model->getItemEventosAcoesProjetoResumoHoras($id_periodo,  $id_item_evento); 
                foreach ($eventos_itens_acoes as $evento_item_acao) {
                $id_acao = $evento_item_acao->id_acao;
                
               // $id_item = $evento_item_acao->id_item;
                $id_periodo_registro = $evento_item_acao->id_periodo_registro;        
                    
                
                    $soma_saldo_acao = 0;
                    $conts = 0;
                    $acoes_eventos_itens = $this->user_model->getItemEventosAcoesProjetoResumoHorasSaldo($id_periodo, $id_acao); 
                    foreach ($acoes_eventos_itens as $acao_evento_item) {
                        $id_registro = $acao_evento_item->id_registro;
                        $saldo_hora = $acao_evento_item->saldo;
                    
                         // VERIFICAR QTAS AÇÕES TEM NO MESMO DIA?   
                        $cont_acao = 0;
                        $acoes_eventos_itens_qtde_acoes = $this->user_model->getQuantidadeAcoesProjetoResumoHorasSaldo($id_registro); 
                        $quantidade_acoes = $acoes_eventos_itens_qtde_acoes->quantidade; 
                    
                        $tempo_qtde_acoes = $this->user_model->getTempoAcoesByRegistros($saldo_hora, $quantidade_acoes); 
                        $tempo_acoes = $tempo_qtde_acoes->tempo; 
                        
                        $soma_tempo_acoes+=$tempo_acoes;
                        $tempos_Saldo_HoraExtra2[$soma_tempo_acoes++] = $tempo_acoes;
                        $tempos_Saldo_HoraExtra[$conts++] = $tempo_acoes;
                    }

                     foreach ( $tempos_Saldo_HoraExtra as $tempo_he ){ //percorre o array $tempo
                        list( $h, $m, $s ) = explode( ':', $tempo_he ); //explode a variavel tempo e coloca as horas em $h, minutos em $m, e os segundos em $s

                        $segundoshe += $h * 3600;
                        $segundoshe += $m * 60;
                        $segundoshe += $s;
                    }
                    $horashe = floor( $segundoshe / 3600 ); //converte os segundos em horas e arredonda caso nescessario
                    $segundoshe %= 3600; // pega o restante dos segundos subtraidos das horas
                    $minutoshe = floor( $segundoshe / 60 );//converte os segundos em minutos e arredonda caso nescessario
                    $segundoshe %= 60;// pega o restante dos segundos subtraidos dos minutos
                            
                ?>
                <div style="margin-left: 150px;">
                    <ul>
                        <li>
                           <?php echo $id_acao.' - '.$evento_item_acao->acao; ?> <font style="color: blue; font-size: 14px;">  <?php echo "{$horashe}:{$minutoshe} h"; ?> </font>
                        </li>    
                    </ul>    
                </div>
               
               <?php } ?>
               
               <?php } ?>
             <?php } ?>
            <?php } ?>
                
          
               
        </section>    
            
          <br>
        <br>
    </div>
    

