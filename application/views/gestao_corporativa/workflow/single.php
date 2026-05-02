<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>

<?php $this->load->view('gestao_corporativa/css_background'); ?>
<?php
$andamento = $this->Workflow_model->get_fluxos_andamento($workflow->id);
//$correcao = $this->Workflow_model->verifica_duplicacao($workflow->id);
//print_r($workflow); exit;
?>

<?php
if (is_array($info_client)) {
    $client_email = $info_client['EMAIL'];
    $client_sms = $info_client['TELEFONE'];
}
?>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li>
                    <li><a href="<?= base_url('gestao_corporativa/Workflow'); ?>"><i class="fa fa-backward"></i> Workflow </a></li>
                    <li class="">WORKFLOW #<?php echo $workflow->id; ?> </li>
                </ol>
            </div>
        </div>
        <?php
        $staffid = get_staff_user_id();
        if ($in_department == true || $user_created == true || is_admin()) { ?>
            <?php
            $classe = 'info';
            $info = 'EM DIA';
            if (strtotime(date('Y-m-d')) > strtotime($fluxo_atual->data_prazo)) {
                $classe = 'danger';
                $info = 'ATRASADO';
            }
            if ($atual == true) {
            ?>
                <div class="col-md-12">
                    <div class="alert alert-<?php echo $classe; ?> alert-dismissible">
                        <h5><i class="icon fa fa-info-circle"></i> Você está responsável pelo processo atual <span class=" label label-<?php echo $classe; ?> inline-block">
                                <?php echo date("d/m/Y", strtotime($fluxo_atual->data_prazo)); ?> (<?php echo $info; ?>)
                            </span></h5>
                    </div>
                </div>
            <?php }

            if ($correcao == true) {
            ?>
                <div class="col-md-12">
                    <div class="alert alert-warning alert-dismissible">
                        <h5><i class="icon fa fa-info-circle"></i> Duplicação Corrigida.</h5>
                    </div>
                </div>
            <?php } ?>


            <div class="col-md-<?php echo $in_department ? '8' : '12'; ?>">
                <div class="panel_s">
                    <div class="panel-heading">
                        WORKFLOW #<?php echo $workflow->id; ?>
                        <?php $status = get_ro_status($workflow->status); ?>
                        <div class="label mtop5 single-ticket-status-label" style="background: <?php echo $status['color']; ?>"><?php echo $status['label']; ?></div>

                        <?php echo '<a target="_blank" href="' . base_url() . 'gestao_corporativa/workflow/pdf/' . $workflow->id . '" class="' . (is_mobile() ? ' ' : ' mleft15 ') . ' pull-right single-ticket-status-label"><i class="icon fa fa-print"></i> </a>'; ?>
                        <?php

                        if (is_admin() || get_staff_user_id() == 932) {
                            echo '<button class="' . (is_mobile() ? ' ' : ' mleft15 ') . ' pull-right single-ticket-status-label" data-toggle="modal" data-target="#modalTransferir"><i class="icon fa fa-exchange"></i> </button>';
                        }
                        ?>


                    </div>

                    <!--MODAL DE TRANSFERENCIA -->

                    <div class="modal fade" id="modalTransferir">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header bg-primary">
                                    <h4 class="modal-title">Transferir Workflow</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <form id="formTransferirWorkflow">

                                    <div class="modal-body">

                                        <input type="hidden" name="workflow_id" value="<?= $workflow->id ?>">

                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
                                            value="<?= $this->security->get_csrf_hash(); ?>" />


                                        <div class="form-group">
                                            <label for="">Setor destino</label>
                                            <select name="setor_destino" id="setor_destino" class="form-control" required>
                                                <option value="">Selecione...</option>
                                                <?php foreach ($departments as $s): ?>
                                                    <option value="<?= $s['departmentid'] ?>"><?= $s['name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- CAMPO DINÂMICO (hidden por padrão) -->
                                        <div class="form-group" id="grupo_subsetor" style="display:none;">
                                            <label>Selecione a categoria</label>
                                            <select name="subsetor" id="subsetor" class="form-control">
                                                <option value="">Carregando...</option>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Transferir</button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>


                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 mbot20 before-ticket-message">
                                <?php if ($workflow->user_end_client) { ?>
                                    <div class="alert alert-warning alert-dismissible">
                                        <h5><i class="icon fa fa-info-circle"></i> Processo já finalizado com o cliente! </h5>
                                        <strong class="text-muted">- <?php echo get_staff_full_name($workflow->user_end_client); ?> (<?php echo date('d/m/Y H:i:s', strtotime($workflow->date_end_client)); ?>)</strong>
                                    </div>

                                <?php } ?>
                                <?php
                                if (($workflow->status == 3 || $workflow->status == 4) and $department_responsable == true) {
                                    echo '<a data-toggle="tooltip" data-title="O workflow retornará aberto para o último departamento com o forumlário do mesmo a ser repreenchido." '
                                        . 'class="btn btn-warning" style="margin-bottom: 10px;" href="' . base_url('gestao_corporativa/Workflow/open/' . $workflow->id) . '">'
                                        . '<i class="fa fa-external-link" aria-hidden="true"></i> REABRIR WORKFLOW'
                                        . '</a>';
                                }
                                ?>
                                <div class="row">


                                    <div class="col-md-12">
                                        <span class="ticket-label label label-default inline-block">
                                            Categoria: <?php echo $categoria->titulo; ?>
                                        </span>
                                        <span class="ticket-label label label-default inline-block">
                                            Data Inicio: <?php echo date("d/m/Y", strtotime($workflow->date_start)); ?>
                                        </span>
                                        <span class="ticket-label label label-default inline-block">
                                            Usuario Inicio: <?php echo get_staff_full_name($workflow->user_start); ?>
                                        </span>
                                        <span class="ticket-label label label-default inline-block">
                                            Data Prazo: <?php echo date("d/m/Y", strtotime($workflow->date_prazo)); ?>
                                        </span>
                                        <span class="ticket-label label label-default inline-block">
                                            Data Finalização: <?php
                                                                if ($workflow->date_end) {
                                                                    echo date("d/m/Y", strtotime($workflow->date_end));
                                                                } else {
                                                                    echo 'Não Finalizado.';
                                                                }
                                                                ?>
                                        </span><!-- comment -->
                                        <span class="ticket-label label label-default inline-block">
                                            Usuário Finalização: <?php
                                                                    if ($workflow->user_end) {
                                                                        echo get_staff_full_name($workflow->user_start);
                                                                    } else {
                                                                        echo 'Não Finalizado.';
                                                                    }
                                                                    ?>
                                        </span>
                                    </div>
                                    <?php if (is_array($info_client)) { ?>

                                        <div class="col-md-12 before-ticket-message" style="">
                                            <br>


                                            <p class="">
                                                <?php //print_r($info_client); NOME_ABREV
                                                ?>

                                                <span class="bold">CLIENTE/CARTEIRINHA: </span><?php echo $info_client['NOME_COMPLETO'] . ' - ' . $info_client['NUMERO_CARTEIRINHA']; ?> <br>
                                                <span class="bold">EMAIL/TELEFONE </span><?php echo $info_client['EMAIL'] . ' - ' . $info_client['TELEFONE']; ?> <br>
                                                <span class="bold">CONTRATANTE:</span> <?php echo $info_client['CONTRATANTE']; ?> <br>
                                                <span class="bold">CPF/CNPJ CONTRATANTE:</span><?php echo $info_client['CPF_CONTRATANTE']; ?> ( <?php echo $info_client['TIPOCONTRATANTE']; ?> ) <br>
                                                <span class="bold">CONTRATO:</span> <?php echo $info_client['CONTRATO']; ?> - <?php echo $info_client['CONTRATACAO']; ?><br>
                                                <span class="bold">CPF:</span> <?php echo $info_client['CPF']; ?><br>
                                                <span class="bold">DATA DE NASCIMENTO:</span> <?php echo $info_client['DATADENASCIMENTO']; ?><br>
                                                <span class="bold">DATA DE ADESÃO:</span> <?php echo $info_client['DATAADESAO']; ?><br>
                                                <span class="bold">DATA DE VALIDADE:</span> <?php echo $info_client['VALIDADE']; ?><br>
                                                <span class="bold">PRODUTO:</span> <?php echo $info_client['PRODUTO']; ?><br>
                                                <span class="bold">ABRANGENCIA:</span> <?php echo $info_client['ABRANGENCIA']; ?><br>
                                                <span class="bold">ACOMODAÇÃO:</span> <?php echo $info_client['ACOMODACAO']; ?><br>
                                                <span class="bold">TITULAR:</span> <?php echo $info_client['TITULAR']; ?><br>
                                                <span class="bold">REDE:</span> <?php echo $info_client['REDE']; ?><br>
                                                <span class="bold">CNS:</span> <?php echo $info_client['CNS']; ?><br>
                                                <span class="bold">SITUAÇÃO:</span> <?php echo $info_client['SITUACAO']; ?><br>


                                            </p>



                                        </div>

                                    <?php } ?>
                                    <?php if ($workflow->cancel_id == 0 || $workflow->reopen == 1) { ?>

                                        <div class="col-md-12">
                                            <hr>
                                            <?php
                                            $diferenca = strtotime(date('Y-m-d')) - strtotime(date("Y-m-d", strtotime($workflow->date_created)));
                                            $dias = floor($diferenca / (60 * 60 * 24));
                                            $total = $categoria->prazo;
                                            $pctm = $dias;
                                            $porcentagem = $pctm * 100 / $total;
                                            ?>
                                            <div class="project-overview-open-tasks">
                                                <div class="col-md-9">
                                                    <p class="text-uppercase bold text-dark font-medium">
                                                        <span><?php echo $porcentagem; ?>% </span> (<?php echo date("d/m/Y", strtotime($workflow->date_created)); ?> - <?php echo date("d/m/Y", strtotime($workflow->date_prazo)); ?>)
                                                    </p>
                                                    <p class="text-muted bold"><?php echo $dias ?> / <?php echo $categoria->prazo; ?> DIAS PARA O FIM DO PRAZO</p>
                                                </div>
                                                <div class="col-md-12 mtop5">
                                                    <div class="progress no-margin progress-bar-mini">
                                                        <div class="progress-bar progress-bar-success no-percent-text not-dynamic" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $porcentagem; ?>%" data-percent="<?php echo $porcentagem; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            <hr>

                                            <?php
                                            if ($in_department != true && $user_created == true || is_admin()) {
                                                $campos = [];
                                                $values_info['campos'] = $this->Categorias_campos_model->get_values($workflow->id, 'workflow', '0');
                                                $this->load->view('gestao_corporativa/categorias_campos/values_info3', $values_info);
                                                echo '<hr>';
                                            }
                                            ?>

                                            <div class="activity-feed">
                                                <?php
                                                $andamento = $this->Workflow_model->get_fluxos_andamento($workflow->id); //print_r($andamento);
                                                foreach ($andamento as $item) {
                                                    if ($item['atribuido_a']) {
                                                        if ($atual == true) {
                                                            if ($item['fluxo_sequencia'] == ($fluxo_atual->fluxo_sequencia - 1)) {
                                                                $fluxo_anterior = $item;
                                                            }
                                                        } ?>


                                                        <div class="feed-item row col-md-12" data-sale-activity-id="">
                                                            <div class="col-md-<?php
                                                                                if ($in_department != true && $user_created == true || is_admin()) {
                                                                                    echo '5';
                                                                                } else {
                                                                                    echo '12';
                                                                                }
                                                                                ?>">
                                                                <div class="date">
                                                                    <span class="text-has-action" data-toggle="tooltip" data-title="">
                                                                        <?php //echo staff_profile_image($item['atribuido_a'], array('staff-profile-xs-image pull-left mright5')); 
                                                                        ?>
                                                                        <?php //echo $item['fluxo_sequencia']; 
                                                                        ?>° <?php //echo get_departamento_nome($item['department_id']); 
                                                                            ?> -
                                                                        <?php //echo get_staff_full_name($item['atribuido_a']); 
                                                                        ?>
                                                                    </span>
                                                                </div>

                                                                <!--<div class="text">
                                                                    Recebido : <?php echo date("d/m/Y H:i:s", strtotime($item['date_created'])); ?>
                                                                </div>
                                                                <div class="text">
                                                                    Assumido : <?php echo date("d/m/Y  H:i:s", strtotime($item['data_assumido'])); ?>
                                                                </div>
                                                                <div class="text">
                                                                    Previsão : <?php echo date("d/m/Y", strtotime($item['data_prazo'])); ?>
                                                                </div>
                                                                <div class="text">
                                                                    Concluído : <?php
                                                                                if ($item['data_concluido']) {
                                                                                    echo date("d/m/Y  H:i:s", strtotime($item['data_concluido']));
                                                                                } else {
                                                                                    echo 'Não Concluído';
                                                                                }
                                                                                ?>
                                                                </div>-->
                                                            </div>

                                                            <?php if ($in_department != true && $user_created == true || is_admin()) {  ?>
                                                                <div class="col-md-7">
                                                                    <?php
                                                                    $campos = [];
                                                                    $values_info['campos'] = $this->Categorias_campos_model->get_values($workflow->id, 'workflow', $item['fluxo_id'], false, true);
                                                                    //print_r($values_info);
                                                                    $this->load->view('gestao_corporativa/categorias_campos/values_info2', $values_info);
                                                                    ?>
                                                                </div>
                                                            <?php }
                                                            ?>

                                                        </div>
                                                    <?php } ?>

                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php if ($workflow->cancel_id == 0 && $workflow->obs && $workflow->reopen == 0) { ?>
                            <div class="row">
                                <div class="col-md-12 before-ticket-message">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-warning alert-dismissible">
                                                <h5><i class="icon fa fa-info-circle"></i>
                                                    <?php if ($workflow->cancel_id != 0) { ?>
                                                        ESSE PROCESSO FOI CANCELADO!
                                                    <?php } else { ?>
                                                        ESSE PROCESSO FOI FINALIZADO ANTECIPADAMENTE!
                                                    <?php } ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-md-12">

                                            <p class=""><?php echo $workflow->obs; ?></p>
                                            <p class="bold"><?php echo get_staff_full_name($workflow->user_end); ?> - <?php echo date("d/m/Y H:i:s", strtotime($workflow->date_end)); ?></p>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        <?php } ?>

                    </div>
                    <div class="panel-footer">
                        WORKFLOW #<?php echo $workflow->id; ?>
                        <?php $status = get_ro_status($workflow->status); ?>
                        <div class="label mtop5 single-ticket-status-label" style="background: <?php echo $status['color']; ?>"><?php echo $status['label']; ?></div>

                    </div>

                </div>


                <div class="panel_s">
                        <div class="panel-heading">
                            Tags
                        </div>

                        <div class="panel-body">
                            <form id="form-workflow-tags" onsubmit="return false;">
                                <input type="hidden" name="workflow_id" id="workflow_id" value="<?php echo isset($workflow) ? (int)$workflow->id : 0; ?>">

                                <div class="form-group mtop15">
                                    <label for="tags" class="control-label">
                                        <i class="fa fa-tag" aria-hidden="true"></i> <?php echo _l('tags'); ?>
                                    </label>

                                    <input
                                        type="text"
                                        class="tagsinput"
                                        id="tags"
                                        name="tags"
                                        value="<?php echo (isset($workflow) ? prep_tags_input(get_tags_in($workflow->id, 'workflow')) : ''); ?>"
                                        data-role="tagsinput">
                                </div>

                                <div class="text-right">
                                    <button type="button" class="btn btn-info" id="btn-salvar-tags-workflow">
                                        <i class="fa fa-save"></i> Salvar Tags
                                    </button>
                                </div>

                                <div id="workflow-tags-msg" class="mtop10"></div>
                            </form>
                        </div>
                    </div> 

                <div class="panel_s">
                    <div class="panel-heading">
                        Notas
                    </div>

                    <div class="panel-body">
                        <?php $this->load->view('gestao_corporativa/notes/note', ["rel_type" => 'workflow', 'rel_id' => $workflow->id]); ?>

                    </div>
                </div>
                <?php
                $tab_pareceres_show = count($internal_requests) > 0 || $atual == true;
                $tab_info_show      = (count($external_requests) > 0 || $atual == true) && $workflow->registro_atendimento_id;
                $tab_contatos_show  = count($client_contacts) > 0;
                $tab_pdfs_show      = count($pdf_views) > 0;
                $any_tab_show       = $tab_pareceres_show || $tab_info_show || $tab_contatos_show || $tab_pdfs_show;
                ?>
                <?php if ($any_tab_show): ?>
                    <div class="panel_s">
                        <div class="panel-heading">Interações</div>
                        <div class="panel-body">
                            <ul class="nav nav-tabs" role="tablist" id="wf-interacoes-tabs">
                                <?php $first = true; ?>
                                <?php if ($tab_pareceres_show): ?>
                                    <li role="presentation" class="<?php echo $first ? 'active' : ''; ?>"><a href="#wf-pareceres" data-toggle="tab"><i class="fa fa-comments-o"></i> Pareceres internos <span class="label label-default mleft5"><?php echo count($internal_requests); ?></span></a></li>
                                    <?php $first = false; ?>
                                <?php endif; ?>
                                <?php if ($tab_info_show): ?>
                                    <li role="presentation" class="<?php echo $first ? 'active' : ''; ?>"><a href="#wf-info-comp" data-toggle="tab"><i class="fa fa-info-circle"></i> Info. complementares <span class="label label-default mleft5"><?php echo count($external_requests); ?></span></a></li>
                                    <?php $first = false; ?>
                                <?php endif; ?>
                                <?php if ($tab_contatos_show): ?>
                                    <li role="presentation" class="<?php echo $first ? 'active' : ''; ?>"><a href="#wf-contatos" data-toggle="tab"><i class="fa fa-phone"></i> Contatos com cliente <span class="label label-default mleft5"><?php echo count($client_contacts); ?></span></a></li>
                                    <?php $first = false; ?>
                                <?php endif; ?>
                                <?php if ($tab_pdfs_show): ?>
                                    <li role="presentation" class="<?php echo $first ? 'active' : ''; ?>"><a href="#wf-pdfs" data-toggle="tab"><i class="fa fa-file-pdf-o"></i> Visualizações PDF <span class="label label-default mleft5"><?php echo count($pdf_views); ?></span></a></li>
                                    <?php $first = false; ?>
                                <?php endif; ?>
                            </ul>
                            <div class="tab-content mtop15" style="text-align:left;">
                                <?php $first = true; ?>

                                <?php if ($tab_pareceres_show): ?>
                                    <div role="tabpanel" class="tab-pane <?php echo $first ? 'active' : ''; ?>" id="wf-pareceres">
                                        <?php if ($atual == true): ?>
                                            <a onclick="Internal_request('<?php echo $workflow->id; ?>');" style="margin-bottom: 5px;" class="btn btn-warning"><i class="fa fa-plus"></i> Solicitar parecer interno</a>
                                        <?php endif; ?>
                                        <?php foreach ($internal_requests as $request): ?>
                                            <div class="panel_s total-column" style="margin-bottom: 3px;">
                                                <div class="panel-body">
                                                    <span class="staff_logged_time_text text-success">Solicitação de parecer #<?php echo $request['id']; ?></span><br>
                                                    <span class="text-dark">De: <?php echo get_staff_full_name($request['user_created']); ?> · <?php echo date('d/m/Y H:i', strtotime($request['date_created'])); ?></span><br>
                                                    <span class="text-dark">Para: <?php echo get_staff_full_name($request['staffid']); ?><?php if ($request['status'] == 1) echo ' · ' . date('d/m/Y H:i', strtotime($request['date'])); ?></span><br>
                                                    <span class="text-dark">Descrição:</span> <?php echo $request['description']; ?><br>
                                                    <span class="text-dark">Situação:</span>
                                                    <?php if ($request['status'] == 0): ?>
                                                        <span class="label label-success">AGUARDANDO</span>
                                                    <?php else: ?>
                                                        <span class="label label-warning">RESPONDIDO</span>
                                                    <?php endif; ?>
                                                    <?php if ($request['status'] == 1): ?>
                                                        <hr style="margin:5px 0;">
                                                        <span class="text-dark bold">Resposta:</span><br>
                                                        <?php
                                                        $values_info['rel_type'] = 'workflow';
                                                        $values_info['campos']   = $this->Categorias_campos_model->get_values($request['id'], 'internal_request_workflow');
                                                        $this->load->view('gestao_corporativa/categorias_campos/values_info5', $values_info);
                                                        ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php $first = false; ?>
                                <?php endif; ?>

                                <?php if ($tab_info_show): ?>
                                    <div role="tabpanel" class="tab-pane <?php echo $first ? 'active' : ''; ?>" id="wf-info-comp">
                                        <?php if ($atual == true): ?>
                                            <a onclick="External_request('<?php echo $workflow->id; ?>');" class="btn btn-warning" style="margin-bottom: 5px;"><i class="fa fa-plus"></i> Solicitar informações complementares</a>
                                        <?php endif; ?>
                                        <?php foreach ($external_requests as $request): ?>
                                            <div class="panel_s total-column" style="margin-bottom: 3px;">
                                                <div class="panel-body">
                                                    <span class="text-success">Solicitação de Informações #<?php echo $request['id']; ?></span><br>
                                                    <span class="text-dark">De: <?php echo get_staff_full_name($request['user_created']); ?> · <?php echo date('d/m/Y H:i', strtotime($request['date_created'])); ?></span><br>
                                                    <span class="text-dark">Descrição:</span> <?php echo $request['description']; ?><br>
                                                    <span class="text-dark">Situação:</span>
                                                    <?php if ($request['status'] == 0): ?>
                                                        <span class="label label-success">AGUARDANDO</span>
                                                    <?php else: ?>
                                                        <span class="label label-warning">RESPONDIDO</span>
                                                    <?php endif; ?>
                                                    <?php if ($request['status'] == 1): ?>
                                                        <hr style="margin:5px 0;">
                                                        <span class="text-dark bold">Resposta:</span><br>
                                                        <?php
                                                        $values_info['rel_type'] = 'workflow';
                                                        $values_info['campos']   = $this->Categorias_campos_model->get_values($request['id'], 'external_request_workflow');
                                                        $this->load->view('gestao_corporativa/categorias_campos/values_info5', $values_info);
                                                        ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php $first = false; ?>
                                <?php endif; ?>

                                <?php if ($tab_contatos_show): ?>
                                    <div role="tabpanel" class="tab-pane <?php echo $first ? 'active' : ''; ?>" id="wf-contatos">
                                        <?php foreach ($client_contacts as $contact): ?>
                                            <div style="padding:6px 0;border-bottom:1px solid #eef1f4;">
                                                <strong class="text-muted" style="text-transform:uppercase;">- <?php echo get_staff_full_name($contact['user_created']); ?> (<?php echo date('d/m/Y H:i', strtotime($contact['date_created'], $baseTimestamp ?? null)); ?>)</strong>
                                            </div>
                                        <?php endforeach; ?>
                                        <?php if ($workflow->user_end_client): ?>
                                            <div style="padding:8px 0;color:#92400e;">
                                                <strong>FINALIZADO: <?php echo get_staff_full_name($workflow->user_end_client); ?> (<?php echo date('d/m/Y H:i', strtotime($workflow->date_end_client)); ?>)</strong>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php $first = false; ?>
                                <?php endif; ?>

                                <?php if ($tab_pdfs_show): ?>
                                    <div role="tabpanel" class="tab-pane <?php echo $first ? 'active' : ''; ?>" id="wf-pdfs">
                                        <?php foreach ($pdf_views as $contact): ?>
                                            <div style="padding:6px 0;border-bottom:1px solid #eef1f4;">
                                                <strong class="text-muted" style="text-transform:uppercase;">- <?php echo get_staff_full_name($contact['user_created']); ?> (<?php echo date('d/m/Y H:i', strtotime($contact['date_created'], $baseTimestamp ?? null)); ?>)</strong>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php if ($in_department == true) { ?>
                <div class="col-md-4"> <?php //echo "aqui";
                                        ?>
                    <div class="panel_s">
                        <div class="panel-heading">
                            INFORMAÇÕES DO WORKFLOW
                            <?php if ($workflow->estornado == 1) { ?>
                                <a href="#" class="pull-right btn-sm btn-warning" title="Informações Fluxos Estornados" onclick="estornados(<?= $workflow->id ?>)"> Informações Fluxos Estornados <i class="fa fa-exclamation-triangle"></i></a>
                            <?php } ?>
                        </div>

                        <div class="panel-body">
                            <div class="horizontal-scrollable-tabs">
                                <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                                <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                                <div class="horizontal-tabs">
                                    <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">
                                        <li role="presentation" class="active">
                                            <a href="#contact_info1" aria-controls="contact_info1" role="tab" data-toggle="tab">
                                                Solicitação
                                            </a>
                                        </li>

                                        <?php
                                        if ($atual == true) {
                                            $classe = 'info';
                                            if (strtotime(date('Y-m-d')) > strtotime($fluxo_atual->data_prazo)) {
                                                $classe = 'danger';
                                            }
                                        ?>
                                            <li role="presentation" class="" style="">
                                                <a href="#contact_info2" aria-controls="contact_info2" role="tab" data-toggle="tab">
                                                    Responsável - <?php echo get_staff_full_name(get_staff_user_id()); ?>
                                                </a>
                                            </li>

                                        <?php } ?>
                                        <li role="presentation" class="">
                                            <a href="#sms" aria-controls="sms" role="tab" data-toggle="tab">
                                                SMS
                                            </a>
                                        </li><!-- comment -->
                                        <li role="presentation" class="">
                                            <a href="#email" aria-controls="email" role="tab" data-toggle="tab">
                                                E-mails
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-content mtop15">
                                <div role="tabpanel" class="tab-pane active" id="contact_info1">


                                    <div class="alert alert-warning alert-dismissible">
                                        <h5><i class="icon fa fa-info-circle"></i> POR: <?php echo strtoupper(get_staff_full_name($workflow->user_created)); ?> - <?php echo date("d/m/Y", strtotime($workflow->date_created)); ?></h5>
                                    </div>
                                    <?php
                                    $campos = [];
                                    $values_info['rel_type'] = 'workflow';
                                    $values_info['campos'] = $this->Categorias_campos_model->get_values($workflow->id, 'workflow', '0');
                                    $this->load->view('gestao_corporativa/categorias_campos/values_info', $values_info);
                                    ?>





                                </div>
                                <?php if ($atual == true) { ?>
                                    <div role="tabpane2" class="tab-pane" id="contact_info2">

                                        <?php echo form_open_multipart("gestao_corporativa/Workflow/concluir_parte/" . $workflow->id, array('onsubmit' => "document.getElementById('disabled').disabled = true;")); ?>
                                        <input name="categoria_id" type="hidden" value="<?php echo $workflow->categoria_id; ?>">
                                        <input name="fluxo_id" type="hidden" value="<?php echo $fluxo_atual->fluxo_id; ?>">
                                        <input name="fluxo_andamento_id" type="hidden" value="<?php echo $fluxo_atual->id; ?>">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="alert alert-<?php echo $classe; ?> alert-dismissible">
                                                    <h5><i class="icon fa fa-info-circle"></i> <?php echo $fluxo_atual->codigo_sequencial; ?> - <?php
                                                                                                                                                if ($fluxo_atual->objetivo) {
                                                                                                                                                    echo $fluxo_atual->objetivo;
                                                                                                                                                } else {
                                                                                                                                                    echo 'Objetivo não cadastrado.';
                                                                                                                                                }
                                                                                                                                                ?></h5>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <?php
                                                $campos = $this->Categorias_campos_model->get_categoria_campos($fluxo_atual->categoria_id, $fluxo_atual->fluxo_id);

                                                $data['campos'] = $campos;
                                                $data['just_campos'] = true;
                                                $this->load->view('gestao_corporativa/categorias_campos/retorno_categoria', $data);
                                                ?>
                                                <?php
                                                if ($fluxo_atual->contato_cliente == 1) {
                                                    $disable = 'disabled = "true" data-title="Você precisa fazer contato com o cliente para prosseguir!"';
                                                ?>
                                                    <div class='checkbox checkbox-primary'>
                                                        <input type='checkbox' id='client_contact' name="client_contact">
                                                        <label for='client_contact'>Contato Cliente Realizado!</label>
                                                    </div>
                                                <?php } ?>



                                                <div class="btn-group w-100">
                                                    <?php
                                                    if ($fluxo_atual->finaliza_cliente == 1) {
                                                        $more = 'E FINALIZAR SOLICITAÇAO COM O CLIENTE';
                                                    ?>
                                                        <input type="hidden" id="finaliza" value="1" name="finaliza">

                                                        <?php
                                                    }
                                                    if (count($alternativas) > 0) {
                                                        if (count($alternativas) == 1) {
                                                        ?>
                                                            <input style="display: none;" type="text" id="alternativa" value="<?php echo $alternativas[0]['id']; ?>" name="alternativa">
                                                            <button class="btn btn-primary w-100" type="submit" id="disabled" data-toggle="tooltip" <?php echo $disable; ?>>
                                                                <i class="icon fa fa-check"></i> Concluir <?php echo $more; ?><i class="icon fa fa-check"></i>
                                                                <p class="font-medium-xs no-mbot text-muted">(<?php echo strtoupper($alternativas[0]['setor_name']); ?>)</p>
                                                            </button>
                                                        <?php } else { ?>
                                                            <div class="panel_s">
                                                                <div class="panel-heading">
                                                                    <?php echo $fluxo_atual->question; ?>
                                                                </div>

                                                                <div class="panel-body">

                                                                    <div class="col-md-12" style="">

                                                                        <br>
                                                                        <div class="form-group">

                                                                            <?php foreach ($alternativas as $alternativa) { ?>
                                                                                <div class="checkbox-inline">
                                                                                    <input style="padding: 020px 020px 020px 020px; margin: 12px 12px 12px 0px; border-radius: 5px 5px 5px 5px; background-color: #f5f8fb; font-size: 16px;"
                                                                                        type="radio" required="true" id="alternativa" value="<?php echo $alternativa['id']; ?>" name="alternativa">
                                                                                    <label for="alternativa" style=""><?php echo strtoupper($alternativa['alternativa']); ?><p class="font-medium-xs no-mbot text-muted">(<?php echo strtoupper($alternativa['setor_name']); ?>)</p></label>
                                                                                </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button class="btn btn-primary w-100" type="submit" id="disabled" data-toggle="tooltip" <?php echo $disable; ?>>
                                                                <i class="icon fa fa-check"></i> Concluir <?php echo $more; ?><i class="icon fa fa-check"></i>
                                                            </button>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <button class="btn btn-success w-100" type="submit" id="disabled" data-toggle="tooltip">
                                                            <i class="icon fa fa-check"></i> FINALIZAR WORKFLOW <?php echo $more; ?><i class="icon fa fa-check"></i>
                                                        </button>
                                                    <?php } ?>

                                                </div>

                                            </div>
                                        </div>


                                        <?php echo form_close(); ?>
                                    </div>

                                <?php } ?>

                                <div role="tabpane56" class="tab-pane" id="sms">
                                    <div class="row">
                                        <?php
                                        $data['rel_type'] = 'workflow';
                                        $data['rel_id'] = $workflow->id;
                                        $data['url_retorno'] = 'gestao_corporativa/Workflow/workflow/' . $workflow->id;
                                        $data['list_email'] = [];
                                        $data['client_number'] = $client_sms;
                                        $this->load->view('gestao_corporativa/Sms/index', $data);
                                        ?>
                                    </div>
                                </div>
                                <div role="tabpane3" class="tab-pane" id="email">
                                    <div class="row">
                                        <?php
                                        $data['rel_type'] = 'workflow';
                                        $data['rel_id'] = $workflow->id;
                                        $data['url_retorno'] = 'gestao_corporativa/Workflow/workflow/' . $workflow->id;
                                        $emails_ra = [];
                                        if ($client_email) {
                                            array_push($emails_ra, $client_email);
                                        }
                                        $data['list_email'] = $emails_ra;
                                        $data['email'] = $client_email;
                                        $this->load->view('gestao_corporativa/Email/index', $data);
                                        ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        
                        <?php if ($workflow->registro_atendimento_id) { ?>
                            <div class="panel-footer">
                                REGISTRO DE ATENDIMENTO VINCULADO

                                <br>
                                <div class="row">
                                    <div class="col-md-3">
                                        <span class="bold">Protocolo: </span><a href="<?php echo base_url('gestao_corporativa/Atendimento/view/' . $workflow->registro_atendimento_id); ?>"> <?php echo $atendimento->protocolo; ?></a>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="bold">Abertura: </span><span class="text-muted"><?php echo _d($atendimento->date_created); ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="bold">Contato: </span><span class="text-muted"><?php echo $atendimento->contato; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="bold">Email: </span><span class="text-muted"><?php echo $atendimento->email; ?></span>
                                    </div>

                                </div>

                                <?php
                                $campos = [];
                                $values_info['campos'] = $this->Categorias_campos_model->get_values($workflow->registro_atendimento_id, 'atendimento');
                                $this->load->view('gestao_corporativa/categorias_campos/values_info2', $values_info);
                                ?>
                            </div>

                        <?php } ?>
                        <?php if (($atual == true and count($andamento) == 1) || $department_responsable == true and in_array($workflow->status, [1, 2])) { ?>
                            <div class="panel-footer">

                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        AÇÕES
                                        <a class="btn btn-xs btn-success pull-right" onclick="Cancel('<?php echo $workflow->id; ?>', '<?php echo $fluxo_atual->id; ?>', 'finish');"><i class="icon fa fa-check"></i> FINALIZAR ANTECIPADAMENTE</a>
                                        <a class="btn btn-xs btn-danger mright5 pull-right " onclick="Cancel('<?php echo $workflow->id; ?>', '<?php echo $fluxo_atual->id; ?>', 'cancel');"><i class="icon fa fa-times"></i> CANCELAR</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>


                    <?php if ($workflow->cancel_id == 0 || $workflow->reopen == 1) { ?>
                        <?php
                        $i = 0;
                        for ($i = 0; $i < count($andamento); $i++) {
                            $classe = 'success';
                            $classe_label = 'success';
                            $info = 'CONCLUÍDO NO PRAZO';
                            if (strtotime($andamento[$i]['data_concluido']) > strtotime($andamento[$i]['data_prazo'])) {
                                $info = 'CONCLUÍDO FORA DO PRAZO';
                                $classe = 'danger';
                                $classe_label = 'danger';
                            }
                            if (!$andamento[$i]['data_concluido']) {
                                $info = 'EM ANDAMENTO';
                                $classe = 'info';
                                $classe_label = 'info';
                                if (strtotime(date('Y-m-d')) > strtotime($andamento[$i]['data_prazo'])) {
                                    $info = 'ATRASADO';
                                    $classe = 'danger';
                                    $classe_label = 'danger';
                                }
                            }

                        ?>
                            <div class="panel_s">
                                <div class="panel-heading">
                                    <?php echo $andamento[$i]['fluxo_sequencia']; ?>° - <?php echo get_departamento_nome($andamento[$i]['department_id']); ?> (<?php echo get_staff_full_name($andamento[$i]['atribuido_a']); ?>)

                                    <?php
                                    if ($fluxo_atual->id == $andamento[$i]['id']) {
                                        echo '<span class="label label-default ">ATUAL</span>';
                                    }
                                    ?>

                                    <?php
                                    if ($fluxo_atual->id == $andamento[$i]['id'] && $atual == true && count($andamento) > 1) {
                                        echo '<a target="_blank" data-toggle="tooltip" data-title="Estornar para fluxo anterior" class="' . (is_mobile() ? ' ' : ' mleft15 ') . ' pull-right single-ticket-status-label" onclick="Back(' . "'" . $workflow->id . "', " . "'" . $andamento[$i]['id'] . "', " . "'" . $andamento[$i - 1]['id'] . "'" . ')">'
                                            . '<i class="fa fa-arrow-circle-up" aria-hidden="true"></i>'
                                            . '</a>';
                                    }
                                    ?>
                                    <?php
                                    if ($andamento[$i]['atribuido_a'] == 0 && $i < count($andamento) - 1) {
                                        /*  echo '<a href="' . base_url('gestao_corporativa/workflow/delete_fluxo/' . $andamento[$i]['id']) . '?workflow_id=' . $andamento[$i]['workflow_id'] . '" data-toggle="tooltip" data-title="Cancelar Duplicado" class="' . (is_mobile() ? ' ' : ' mleft15 ') . ' pull-right single-ticket-status-label delete _delete">'
                                            . '<i class="fa fa-trash" aria-hidden="true"></i>'
                                            . '</a>';*/
                                    }
                                    ?>
                                </div>

                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12 mbot20 before-ticket-message">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="alert alert-<?php echo $classe; ?> alert-dismissible">
                                                        <h5><i class="icon fa fa-info-circle"></i> <?php
                                                                                                    if ($andamento[$i]['objetivo']) {
                                                                                                        echo $andamento[$i]['objetivo'];
                                                                                                    } else {
                                                                                                        echo 'Objetivo não cadastrado.';
                                                                                                    }
                                                                                                    ?> <span class="ticket-label label label-<?php echo $classe_label; ?> inline-block">
                                                                <?php echo $info; ?>
                                                            </span></h5>
                                                    </div>
                                                    <!--<a href="#" class="btn btn-default btn-xs" onclick="convert_ticket_to_task(14,'ticket'); return false;">Converter para ação</a>-->
                                                    <?php
                                                        $backs = $this->Workflow_model->get_workflow_back($andamento[$i]['id'], "");

                                                        if (!is_array($backs)) {
                                                            $backs = [];
                                                        }

                                                        if (!empty($backs)) {
                                                            echo '<hr />';
                                                            echo '<p style="color: red;">ESSE PROCESSO FOI ESTORNADO!!</p>';
                                                            $param = "'" . $andamento[$i]['id'] . "'";
                                                            echo '<button onclick="View_backs(' . $param . ');" style="margin-bottom: 5px;" class="btn btn-default">Visualizar Processos Estornados <i class="fa fa-eye"></i></button>';
                                                        }
                                                    ?>


                                                </div>
                                                <div class="col-md-12">
                                                    <?php
                                                    $campos = [];
                                                    $values_info['rel_type'] = 'workflow';
                                                    $values_info['campos'] = $this->Categorias_campos_model->get_values($workflow->id, 'workflow', $andamento[$i]['fluxo_id']);
                                                    $this->load->view('gestao_corporativa/categorias_campos/values_info', $values_info);
                                                    ?>
                                                </div>
                                                <div class="col-md-12">
                                                    <hr>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="col-md-3">
                                                        <p class="text-uppercase text-muted">Recebido data</p>
                                                        <p class="bold font-medium"><?php echo date("d/m/Y  H:i:s", strtotime($andamento[$i]['date_created'])); ?></p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p class="text-uppercase text-info">Previsto data</p>
                                                        <p class="bold font-medium"><?php echo date("d/m/Y", strtotime($andamento[$i]['data_prazo'])); ?></p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p class="text-uppercase text-success">Assumido data</p>
                                                        <p class="bold font-medium"><?php echo date("d/m/Y  H:i:s", strtotime($andamento[$i]['data_assumido'])); ?></p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p class="text-uppercase text-danger">Concluido data</p>
                                                        <p class="bold font-medium"><?php
                                                                                    if ($andamento[$i]['data_concluido'] && $andamento[$i]['data_concluido'] != '0000-00-00 00:00:00') {
                                                                                        echo date("d/m/Y  H:i:s", strtotime($andamento[$i]['data_concluido']));
                                                                                    } else {
                                                                                        echo 'Não Concluído';
                                                                                    }
                                                                                    ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="col-md-12">
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fa fa-info-circle"></i> Você não tem permissão para esse fluxo.</h5>
                </div>
            </div>
        <?php } ?>
    </div>

</div>
<?php init_tail(); ?>
<div id="modal_wrapper"></div>
<script>
    $(document).ready(function() {
        init_selectpicker();
    });

    $("#client_contact").change(function() {
        if ($(this).prop("checked") == true) {
            document.getElementById("disabled").disabled = false;
        } else {
            document.getElementById("disabled").disabled = true;

        }
    });

    function Cancel(el, fluxo_andamento_id, option) {
        // alert(el + fluxo_andamento_id + fluxo_andamento_id); exit;
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Workflow/modal", {
            slug: option,
            id: el,
            fluxo_andamento_id: fluxo_andamento_id
        }, function() {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#cancel').is(':hidden')) {
                $('#cancel').modal({
                    show: true
                });
            }
        });


    }

    function View_backs(fluxo_andamento_id) {
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Workflow/modal", {
            slug: 'view_backs',
            fluxo_andamento_id: fluxo_andamento_id
        }, function() {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#view_backs').is(':hidden')) {
                $('#view_backs').modal({
                    show: true
                });
            }
        });


    }

    function Back(el, fluxo_andamento_id, fluxo_andamento_id_old) {
        //alert(el + fluxo_andamento_id + fluxo_andamento_id_old); exit;
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Workflow/modal", {
            slug: 'back',
            id: el,
            fluxo_andamento_id: fluxo_andamento_id,
            fluxo_andamento_id_old: fluxo_andamento_id_old
        }, function() {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#back').is(':hidden')) {
                $('#back').modal({
                    show: true
                });
            }
        });
    }

    function Internal_request(el) {
        //alert(el + fluxo_andamento_id + fluxo_andamento_id_old); exit;
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Workflow/modal", {
            slug: 'internal_request',
            id: el
        }, function() {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#internal_request').is(':hidden')) {
                $('#internal_request').modal({
                    show: true,
                    backdrop: 'static'
                });
            }
        });
    }

    function External_request(el) {
        //alert(el + fluxo_andamento_id + fluxo_andamento_id_old); exit;
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Workflow/modal", {
            slug: 'external_request',
            id: el
        }, function() {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#external_request').is(':hidden')) {
                $('#external_request').modal({
                    show: true,
                    backdrop: 'static'
                });
            }
        });
    }

    function delete_internal_request(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Workflow/delete_internal_request'); ?>",
            data: {
                id: id
            },
            success: function(data) {
                alert_float('success', 'CANCELADO COM  SUCESSO!');
            }
        });
    }

    function delete_external_request(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Workflow/delete_external_request'); ?>",
            data: {
                id: id
            },
            success: function(data) {
                alert_float('success', 'CANCELADO COM  SUCESSO!');
            }
        });
    }

    function estornados(id) {
        // alert(id);
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Workflow/modal", {
            slug: 'estornados',
            id: id
        }, function() {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#estornados').is(':hidden')) {
                $('#estornados').modal({
                    show: true,
                    backdrop: 'static'
                });
            }
        });
    }

    $("#formTransferirWorkflow").submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?php echo base_url(); ?>gestao_corporativa/Workflow/salvar_transferencia",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(res) {
                if (res.status === "ok") {
                   alert_float('success', 'TRANSFERIDO COM  SUCESSO!');
                    window.location.reload();
                } else {
                    toastr.error(res.msg);
                }
            }
        });
    });


    $('#setor_destino').on('change', function() {

        let setor_id = $(this).val(); //alert(setor_id); exit;

        // Exibir apenas quando o setor for FINANCEIRO (ex: id = 3)

        $("#grupo_subsetor").show();

        $.ajax({
            url: "<?php echo base_url(); ?>gestao_corporativa/Workflow/get_subcategorias_financeiro",
            type: "POST",
            data: {
                setor_id: setor_id
            },
            dataType: "json",
            success: function(res) {

                $("#subsetor").empty();

                if (res.length > 0) {
                    $("#subsetor").append('<option value="">Selecione...</option>');
                    $.each(res, function(i, item) {
                        $("#subsetor").append('<option value="' + item.id + '">' + item.objetivo + ' - Prazo:' + item.prazo + ' dias - Sequencial:' + item.codigo_sequencial + ' - Vindo de:' + item.vindo_de + '</option>');
                    });
                } else {
                    $("#subsetor").append('<option value="">Nenhuma opção encontrada</option>');
                }
            }
        });

        /* } else {
             $("#grupo_subsetor").hide();
             $("#subsetor").empty();
         }*/

    });
</script>

<script>
    jQuery(function ($) {
        var timeoutTags;
        var workflowId = <?php echo isset($workflow) ? (int)$workflow->id : 0; ?>;

        $('#tags').on('itemAdded itemRemoved change', function () {
            clearTimeout(timeoutTags);

            timeoutTags = setTimeout(function () {
                var msg  = $('#workflow-tags-msg');
                var tags = $('#tags').val();

                if (!workflowId || workflowId <= 0) {
                    msg.html('<div class="alert alert-danger">ID do workflow não encontrado.</div>');
                    return;
                }

                msg.html('<div class="alert alert-info">Salvando...</div>');

                $.ajax({
                    url: '<?php echo site_url('gestao_corporativa/Workflow/salvar_tags'); ?>',
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    data: {
                        workflow_id: workflowId,
                        tags: tags
                    },
                    success: function (response) {
                        if (response.success) {
                            msg.html('<div class="alert alert-success">' + response.message + '</div>');
                        } else {
                            msg.html('<div class="alert alert-danger">' + response.message + '</div>');
                        }
                    },
                    error: function () {
                        msg.html('<div class="alert alert-danger">Erro ao salvar as tags.</div>');
                    }
                });
            }, 600);
        });
    });
</script>


</body>

</html>