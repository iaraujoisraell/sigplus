<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('Editar Pesquisa de Satisfação'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <center>  <p class="introtext"> <font style="font-size: 20px;"> <?php echo $pesquisa->titulo; ?> </font></p></center>
                
                <div class="col-md-12">
                    <p  style="text-align: justify">Com o objetivo de avaliar a qualidade dos treinamentos oferecidos pela Unimed Manaus, solicitamos que você preencha o formulário abaixo, atribuindo a nota correspondente a cada um dos itens abaixo relacionados.A sua contribuição é importante para a continuidade e qualidade dos cursos.</p>
                </div>
                <br><br>
            </div>

        </div>
    </div>
</div>
              <div class="row ">
                    <div class="col-md-12">
                       
                            

                            <?php
                            //$wu3[''] = '';
                            foreach ($grupo_perguntas as $grupo) {
                                $wu4[$grupo->id] = $grupo->nome;
                            
                            ?>
                            <div class="row " style="width: 100%" >
                                    <div  class="col-lg-12">
                                        <table style="width: 100%" class="table table-striped table-bordered table-hover table-green">
                                            <thead>
                                                <tr style="width: 100%" >
                                                    <th style="width: 60%" > <?php echo $grupo->nome; ?></th>
                                                    <th style="width: 40%" > Respostas</th>
                                              </tr>
                                            </thead>   
                                        </table>
                                        
                                        <table style="width: 100%" >

                                                <tbody>
                                                    <?php
                                                    $wu4[''] = '';
                                                    $cont = 1;
                                                    $perguntas = $this->atas_model->getAllPerguntasByGrupo($grupo->id);
                                                    foreach ($perguntas as $pergunta) {
                                                        ?>   
                                                        <tr class="odd gradeX">
                                                            <td style="width: 1%"><?php echo $cont++; ?></td>
                                                            <td style="width: 59%"><?php echo $pergunta->pergunta; ?></td>
                                                            <td style="width: 40%">
                                                                <table style="width: 100%" >
                                                                    <tbody>
                                                                        <tr class="odd gradeX">
                                                                        <?php
                                                                        //$cont = 1;
                                                                        $respostas = $this->atas_model->getAllRespostaByPergunta($pergunta->id);
                                                                        foreach ($respostas as $resposta) {
                                                                            ?>  
                                                                            
                                                                            <td ><input type="radio"><?php echo $resposta->resposta; ?></td>
                                                                            <?php
                                                                                }
                                                                                ?>
                                                                            </tr>
                                                                    </tbody>
                                                                </table>
                                                                
                                                                
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        
                    </div>
                </div>

 <br>
 <div class="row ">
<div class="col-md-12">
                        <div class="form-group">
                            <?= lang("OBSERVAÇÕES/SUGESTÕES", "slpergunta"); ?>
                                     <?php echo form_textarea('pergunta', (isset($_POST['pergunta']) ? $_POST['pergunta'] : ""), 'class="form-control" id="slpergunta" required="required" style="margin-top: 10px; height: 150px;"'); ?>
                          </div>
                    </div>
     </div>
 <div class="row ">
 <br><br><br>
 <br><br><br>
</div>