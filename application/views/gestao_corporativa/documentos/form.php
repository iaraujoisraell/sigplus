<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/summernote/summernote-bs4.min.css">

<style>
    .doc-form-page{max-width:1100px;margin:24px auto;padding:0 18px;}
    .form-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .form-card-header{padding:12px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;}
    .form-card-body{padding:18px;}
    .form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;}
    .form-grid-4{display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:14px;}
    @media(max-width:900px){.form-grid-3,.form-grid-4{grid-template-columns:1fr 1fr;}}
    @media(max-width:600px){.form-grid-3,.form-grid-4{grid-template-columns:1fr;}}

    .file-card{display:flex;align-items:center;gap:10px;padding:10px 14px;background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;}
    .file-card i{color:#0a66c2;font-size:18px;}
    .file-card .nome{font-size:13px;color:#1f2937;font-weight:500;flex:1;}
</style>

<div class="content">
    <div class="doc-form-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Documento'); ?>">Documentos</a></li>
            <li class="active">Editar</li>
        </ol>

        <?php echo form_open_multipart('gestao_corporativa/Documento/save', ['id' => 'form-doc']); ?>
        <input type="hidden" name="id" value="<?php echo (int) $doc['id']; ?>">

        <div class="form-card">
            <div class="form-card-header">Dados do documento</div>
            <div class="form-card-body">
                <div class="form-group">
                    <label class="control-label">Título <span class="text-danger">*</span></label>
                    <input type="text" name="titulo" class="form-control" required maxlength="250" value="<?php echo html_escape($doc['titulo']); ?>">
                </div>

                <div class="form-grid-4">
                    <div class="form-group">
                        <label class="control-label">Código</label>
                        <input type="text" name="codigo" class="form-control" value="<?php echo html_escape($doc['codigo']); ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Categoria</label>
                        <select name="categoria_id" class="form-control select2">
                            <option value="">— sem categoria —</option>
                            <?php foreach ($categorias as $c): ?>
                                <option value="<?php echo (int) $c['id']; ?>" <?php echo (int) $doc['categoria_id'] === (int) $c['id'] ? 'selected' : ''; ?>><?php echo html_escape($c['titulo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Setor</label>
                        <select name="setor_id" class="form-control select2">
                            <option value="">— sem setor —</option>
                            <?php foreach ($departments as $d): ?>
                                <option value="<?php echo (int) $d['departmentid']; ?>" <?php echo (int) $doc['setor_id'] === (int) $d['departmentid'] ? 'selected' : ''; ?>><?php echo html_escape($d['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Responsável</label>
                        <select name="responsavel" class="form-control select2">
                            <option value="">— selecionar —</option>
                            <?php foreach ($staffs as $s): ?>
                                <option value="<?php echo (int) $s['staffid']; ?>" <?php echo (int) $doc['responsavel'] === (int) $s['staffid'] ? 'selected' : ''; ?>><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">Descrição curta</label>
                    <textarea name="descricao" class="form-control" rows="2"><?php echo html_escape($doc['descricao']); ?></textarea>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">Conteúdo</div>
            <div class="form-card-body">
                <div class="form-group">
                    <label class="control-label">Texto do documento (opcional — escrever direto aqui)</label>
                    <textarea id="conteudo" name="conteudo"><?php echo $doc['conteudo']; ?></textarea>
                </div>

                <div class="form-group">
                    <label class="control-label">Arquivo anexo</label>
                    <?php if (!empty($doc['file'])): ?>
                        <div class="file-card" style="margin-bottom:8px;">
                            <i class="fa fa-file"></i>
                            <span class="nome"><?php echo html_escape($doc['file']); ?></span>
                            <a href="<?php echo base_url('gestao_corporativa/Documento/download/' . (int) $doc['id']); ?>" class="btn btn-success btn-xs"><i class="fa fa-download"></i> Baixar</a>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
                    <small class="text-muted">PDF, Office, imagens. Máx. 50 MB.</small>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">Vínculos</div>
            <div class="form-card-body">
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="control-label">Projeto</label>
                        <select name="project_id" id="project_id" class="form-control select2">
                            <option value="">— sem vínculo —</option>
                            <?php foreach ($projects as $p): ?>
                                <option value="<?php echo (int) $p['id']; ?>" <?php echo (int) $doc['project_id'] === (int) $p['id'] ? 'selected' : ''; ?>><?php echo html_escape($p['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Fase</label>
                        <select name="fase_id" id="fase_id" class="form-control select2" data-current="<?php echo (int) $doc['fase_id']; ?>">
                            <option value="">— sem fase —</option>
                            <?php
                            $fases_ini = !empty($doc['project_id']) ? $this->Projeto_fase_model->list_options($doc['project_id']) : [];
                            foreach ($fases_ini as $fa): ?>
                                <option value="<?php echo (int) $fa['id']; ?>" <?php echo (int) $doc['fase_id'] === (int) $fa['id'] ? 'selected' : ''; ?>><?php echo html_escape($fa['codigo_sequencial'] . ' ' . $fa['titulo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Visibilidade</label>
                        <label style="display:flex;align-items:center;gap:6px;font-weight:400;margin-top:8px;">
                            <input type="checkbox" name="publico" value="1" <?php echo !empty($doc['publico']) ? 'checked' : ''; ?>> Público (toda a empresa pode ver)
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">Fluxo de aprovação <small class="text-muted">— ordem importa</small></div>
            <div class="form-card-body">
                <select name="aprovadores[]" id="aprovadores" class="form-control select2-multi" multiple>
                    <?php
                    $sel_apv = array_column($aprovadores, 'staff_id');
                    foreach ($staffs as $s):
                        $sid = (int) $s['staffid'];
                    ?>
                        <option value="<?php echo $sid; ?>" <?php echo in_array($sid, $sel_apv) ? 'selected' : ''; ?>><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="text-muted">Cada pessoa aprova na ordem listada. Última aprovação publica o documento.</small>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">Destinatários <small class="text-muted">— quem precisa estar ciente</small></div>
            <div class="form-card-body">
                <select name="destinatarios[]" id="destinatarios" class="form-control select2-multi" multiple>
                    <?php
                    $sel_dst = array_column($destinatarios, 'staff_id');
                    foreach ($staffs as $s):
                        $sid = (int) $s['staffid'];
                    ?>
                        <option value="<?php echo $sid; ?>" <?php echo in_array($sid, $sel_dst) ? 'selected' : ''; ?>><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-body" style="text-align:right;">
                <a href="<?php echo base_url('gestao_corporativa/Documento'); ?>" class="btn btn-default">Cancelar</a>
                <a href="<?php echo base_url('gestao_corporativa/Documento/view/' . (int) $doc['id']); ?>" class="btn btn-default"><i class="fa fa-eye"></i> Ver</a>
                <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Salvar</button>
            </div>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>
<script src="<?php echo base_url() ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>
<script src="<?php echo base_url() ?>assets/lte/plugins/summernote/summernote-bs4.min.js"></script>
<script src="<?php echo base_url() ?>assets/lte/plugins/summernote/lang/summernote-pt-BR.min.js"></script>
<script>
$(function () {
    $('.select2').select2({width:'100%'});
    $('.select2-multi').select2({width:'100%', placeholder: 'Selecionar pessoas…'});

    $('#conteudo').summernote({
        lang:'pt-BR', height:280,
        toolbar:[['style',['style']],['font',['bold','italic','underline','strikethrough']],['color',['color']],['para',['ul','ol','paragraph']],['table',['table']],['insert',['link','picture']],['view',['codeview','fullscreen']]]
    });

    $('#project_id').on('change', function () {
        var pid = $(this).val();
        var $f = $('#fase_id');
        $f.empty().append('<option value="">— sem fase —</option>');
        if (pid) {
            $.getJSON('<?php echo base_url('gestao_corporativa/Projeto_fase/list_options'); ?>/' + pid, function (data) {
                (data || []).forEach(function (f) {
                    $f.append($('<option>').val(f.id).text(f.codigo_sequencial + ' ' + f.titulo));
                });
                $f.trigger('change.select2');
            });
        } else {
            $f.trigger('change.select2');
        }
    });
});
</script>
</body>
</html>
