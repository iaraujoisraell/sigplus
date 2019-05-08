 <body class="hold-transition skin-green sidebar-collapse  sidebar-mini">


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
   
    <?php 
   // $processos_analises = $this->AudCon_model->getProcessosAnalisesById($id);
    
   // $analises = $this->AudCon_model->getAnaliseById($processos_analises->analise); ?>
 <!-- Left side column. contains the logo and sidebar -->


  <!-- Content Wrapper. Contains page content -->
 
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Minhas Ações Concluídas
        <small>Painel de Controle</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

       
                   
          <section class="col-lg-12 connectedSortable">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Processamentos </h3>
            </div>
                           <div class="box-body">
                                <div class="table-responsive">
                                    <table id="example1" class="table table-striped table-bordered table-hover table-green ">
                                        <thead>
                                            <tr style="background-color: green; color: #ffffff">
                                                <th>ID</th>
                                                <th>PROJETO</th>
                                                <th>ATA</th>
                                                <th>EVENTO</th>
                                                <th>DESCRIÇÃO</th>
                                                <th>DATA QUE EU RECEBI</th>
                                                <th>DATA DO PRAZO</th>
                                                <th>DATA DE CONCLUSÃO</th>
                                                <th>ANEXO</th>
                                                <th>OPÇÕES</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php
                                                $wu4[''] = '';
                                                //$cont = 1; href="<?= site_url('Login_Projetos/projeto_ata/'.$projeto->id); >"
                                                foreach ($planos as $plano) {
                                                    $evento =  $this->atas_model->getAllitemEventoByID($plano->eventos);   
                                                    $ata_user = $this->atas_model->getAtaUserByAtaUser($plano->idatas, $usuario);
                                                    $result = $ata_user->id;
                                                    
                                                    //
                                                    
                                                      $projetos_usuario = $this->atas_model->getAtaProjetoByID_ATA($plano->idatas);
                                                ?>   
                                           
                                                <tr class="odd gradeX">
                                                <td><?php echo $plano->idplanos; ?></td>
                                                <td><?php echo $projetos_usuario->projetos; ?></td> 
                                                  <td><?php echo $plano->idatas; ?> <?php if($result){ ?> <a title="Download ATA" href="<?= site_url('welcome/pdf/'.$plano->idatas); ?> "><i class="fa fa-file-pdf-o"></i></a> <?php }else{ ?>  <?php } ?></td>     
                                                      
                                                <th><?php echo $evento->evento.'/'.$evento->item; ?></th>       
                                                <td><?php echo $plano->descricao; ?>
                                                <p><font  style="font-size: 10px; "><?php echo $plano->observacao; ?></font></p>
                                                </td>
                                                <td><?php if($this->sma->hrld($plano->data_elaboracao) != '0000-00-00 00:00:00'){ echo $this->sma->hrld($plano->data_elaboracao); }else{ echo $this->sma->hrld($projetos_usuario->data_ata); } ?></td>
                                                <td class="center"><?php echo $this->sma->hrld($plano->data_termino); ?></td>
                                                <td class="center"><?php if($plano->data_retorno_usuario != '0000-00-00 00:00:00'){echo $this->sma->hrld($plano->data_retorno_usuario);}else{ echo $this->sma->hrld($plano->data_termino);} ?></td>
                                                 <td>
                                                                <?php if ($plano->anexo) { ?>
                                                                    <a target="_blank" href="<?= site_url('../assets/uploads/atas/' . $plano->anexo) ?>" class="tip btn btn-file" title="<?= lang('Fazer Download do Anexo') ?>">
                                                                        <span class="glyphicon glyphicon-paperclip"></span>
                                                                        <span class="glyphicon-class">Ver Anexo</span>
                                                                    </a>
                                                                 <?php } ?>
                                                            </td>      
                                                 <td class="center"><a style="background-color: chocolate; color: #ffffff;" class="btn fa fa-folder-open-o"  href="<?= site_url('welcome/manutencao_acao_disable/'.$plano->idplanos); ?>"><?= lang('Abrir') ?></a></td>
                                             
                                                </tr>
                                                <?php
                                                
                                                }
                                                ?>
                                            
                                            
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
          </div>
                       </section>
           
 
 </div>
      
    
     
</body>
