<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plusgins_select/multselect/dist/jquery.tree-multiselect.min.css">

<style>
    .ci-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .ci-card-header{padding:12px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;}
    .ci-card-body{padding:16px 18px;}
    .ci-meta-row{display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:14px;}
    @media (max-width:768px){.ci-meta-row{grid-template-columns:1fr 1fr;}}
    .ci-attach-list{margin-top:8px;}
    .ci-attach-list .file-row{display:flex;align-items:center;gap:8px;padding:6px 10px;background:#f9fafb;border-radius:6px;margin-bottom:4px;font-size:13px;}
    .ci-attach-list .file-row button{margin-left:auto;background:none;border:0;color:#dc2626;cursor:pointer;font-size:14px;}
</style>

<div class="content">
    <div class="row">
        <?php echo form_open_multipart('gestao_corporativa/intra/Comunicado/add_comunicado', ['id' => 'form-novo-ci']); ?>

        <div class="col-md-12">
            <ol class="breadcrumb" style="background-color: white;">
                <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="<?= base_url('gestao_corporativa/intra/comunicado'); ?>">Comunicados Internos</a></li>
                <li class="active">Novo</li>
            </ol>
        </div>

        <div class="col-md-12">

            <div class="ci-card">
                <div class="ci-card-header">Informações</div>
                <div class="ci-card-body">
                    <div class="form-group">
                        <label class="control-label">Título <span class="text-danger">*</span></label>
                        <input type="text" name="titulo" class="form-control" required maxlength="250">
                    </div>

                    <div class="ci-meta-row">
                        <div class="form-group">
                            <label class="control-label">Setor <span class="text-danger">*</span></label>
                            <?php
                            $this->load->model('Departments_model');
                            $departments = $this->Departments_model->get_staff_departments();
                            ?>
                            <select name="setor_id" class="form-control" required>
                                <option value="">— selecionar —</option>
                                <?php foreach ($departments as $d): ?>
                                    <option value="<?php echo (int) $d['departmentid']; ?>"><?php echo html_escape($d['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Categoria</label>
                            <select name="categoria" class="form-control">
                                <option value="">— nenhuma —</option>
                                <option value="informativo">Informativo</option>
                                <option value="norma">Norma / Procedimento</option>
                                <option value="urgente">Urgente</option>
                                <option value="treinamento">Treinamento</option>
                                <option value="outro">Outro</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Prioridade</label>
                            <select name="prioridade" class="form-control">
                                <option value="normal" selected>Normal</option>
                                <option value="alta">Alta</option>
                                <option value="baixa">Baixa</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Expira em</label>
                            <input type="date" name="dt_expiracao" class="form-control">
                        </div>
                    </div>

                    <div class="form-group" style="margin-top:6px;">
                        <label class="control-label">Descrição</label>
                        <textarea id="descricao" name="descricao"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Anexos</label>
                        <input type="file" name="attachments[]" id="ci-files" multiple class="form-control" accept="<?php echo get_ticket_form_accepted_mimes(); ?>">
                        <div class="ci-attach-list" id="ci-attach-list"></div>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="retorno" value="1"> Exigir retorno por escrito ao dar ciência
                        </label>
                    </div>
                </div>
            </div>

            <div class="ci-card">
                <div class="ci-card-header">Destinatários <span class="text-danger">*</span></div>
                <div class="ci-card-body">
                    <?php
                    $departments_staffs = $this->Intranet_model->get_departamentos_staffs_selecionados();
                    $staffs_for_view = [];
                    ?>
                    <select name="for_staffs[]" id="select-destinatarios" multiple style="width:100%;" required>
                        <?php foreach ($departments_staffs as $v): ?>
                            <option value="<?php echo $v['staffid'] . '-' . $v['staffdepartmentid']; ?>"
                                    data-section="<?php echo html_escape($v['name']); ?>">
                                <?php echo html_escape($v['firstname'] . ' ' . $v['lastname']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div style="margin-top:14px;">
                        <label class="control-label">Cópia (CC)</label>
                        <?php
                        $this->load->model('Staff_model');
                        $staffs = $this->Staff_model->get();
                        ?>
                        <select class="select2" multiple data-placeholder="Selecione staffs em CC" style="width:100%;" name="cc[]">
                            <?php foreach ($staffs as $staff): ?>
                                <option value="<?php echo (int) $staff['staffid']; ?>">
                                    <?php echo html_escape($staff['firstname'] . ' ' . $staff['lastname']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="ci-card">
                <div class="ci-card-body" style="text-align:right;">
                    <button type="submit" name="action" value="draft" class="btn btn-default">
                        <i class="fa fa-save"></i> Salvar rascunho
                    </button>
                    <button type="submit" name="action" value="publish" class="btn btn-info">
                        <i class="fa fa-paper-plane"></i> Publicar e enviar
                    </button>
                </div>
            </div>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>

<script src="<?php echo base_url() ?>assets/intranet/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url() ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plusgins_select/multselect/dist/jquery.tree-multiselect.js"></script>

<script>
    CKEDITOR.replace('descricao');

    $(function () {
        $('.select2').select2();

        $('#select-destinatarios').treeMultiselect({
            allowBatchSelection: true,
            enableSelectAll: false,
            sortable: false,
            searchable: true,
            startCollapsed: true,
            selectAllText: 'Todos',
            unselectAllText: 'Remover'
        });

        var $list = $('#ci-attach-list');
        $('#ci-files').on('change', function () {
            $list.empty();
            Array.from(this.files).forEach(function (f) {
                $list.append('<div class="file-row"><i class="fa fa-paperclip"></i> ' + f.name + ' <span class="text-muted small">(' + Math.round(f.size/1024) + ' KB)</span></div>');
            });
        });

        $('#form-novo-ci').on('submit', function (e) {
            var dest = $('select[name="for_staffs[]"]').val();
            var action = $(document.activeElement).val() || 'publish';
            if (action === 'publish' && (!dest || dest.length === 0)) {
                e.preventDefault();
                alert('Selecione ao menos um destinatário antes de publicar.');
            }
        });
    });
</script>
