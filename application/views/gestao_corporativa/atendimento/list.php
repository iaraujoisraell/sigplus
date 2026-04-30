<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<div class="content">
    <div class="row">

        <div class="col-md-12">

            <h5 style="font-weight: bold;">INTRANET - REGISTROS DE ATENDIMENTO</h5>
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li>
                    <li class="active"><a href="#"><i class="fa fa-user"></i> Registro de Atendimento </a></li>
                </ol>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php if (has_permission_intranet('registro_atendimento', '', 'create') || is_admin()) { ?>
                        <a href="<?php echo base_url('gestao_corporativa/Atendimento/add'); ?>" class="btn btn-info pull-rigth btn-with-tooltip">
                            <i class="fa fa-plus"></i> Novo Atendimento
                        </a>
                    <?php } ?>

                    <button type="button" class="btn btn-success pull-right" id="btn-exportar">
                        <i class="fa fa-download"></i> Exportar
                    </button>
                </div>
            </div>
            <br>

            <div class="panel_s mbot10">
                <div class="panel-heading" style="padding-top: 0px; padding-bottom: 0px;">
                    <div class="checkbox">
                        <?php echo form_hidden('meus_atendimentos', '1'); ?>
                        <input type="checkbox" id="my" checked>
                        <label for="user_created">Somente os meus atendimentos</label>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-11 col-sm-12">
                            <div class="row">
                                <div class="col-md-2 col-sm-6">
                                    <?php
                                    echo render_select('categoria_id', $categorias, array('id', 'titulo'), '', json_decode($filters['categoria_id']), array('data-none-selected-text' => 'CATEGORIA', 'required' => 'true', 'multiple' => 'true'), [], '', '', false);
                                    ?>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <?php
                                    echo render_select('canal_atendimento_id', $canais_atendimentos, array('id', 'canal'), '', json_decode($filters['canal_atendimento_id']), array('data-none-selected-text' => 'CANAL', 'required' => 'true', 'multiple' => 'true'), [], '', '', false);
                                    ?>
                                </div>

                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="client" value="<?php echo $filters['client']; ?>" placeholder="NOME CLIENTE">
                                    </div>
                                </div>

                                <div class="col-md-1 col-sm-6" id="id_div">
                                    <div class="form-group">
                                        <input type="number" class="form-control" value="<?php echo $filters['id']; ?>" name="id" placeholder="#WFID">
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="number" name="protocolo" value="<?php echo $filters['protocolo']; ?>" placeholder="PROTOCOLO">
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="number" name="carteirinha" value="<?php echo $filters['carteirinha']; ?>" placeholder="CARTEIRINHA">
                                    </div>
                                </div>

                                <div class="col-md-1 col-sm-6">
                                    <div class="select-placeholder form-group" app-field-wrapper="period">
                                        <select id="period" name="period" class="selectpicker" data-width="100%"
                                                data-none-selected-text="PERÍODO" data-live-search="true">
                                            <option value="d" <?php if ($filters['period'] == 'd' || $filters['period'] == '') echo 'selected'; ?>>HOJE</option>
                                            <option value="s" <?php if ($filters['period'] == 's') echo 'selected'; ?>>SEMANA</option>
                                            <option value="m" <?php if ($filters['period'] == 'm') echo 'selected'; ?>>MÊS</option>
                                            <option value="Y" <?php if ($filters['period'] == 'Y') echo 'selected'; ?>>ANO</option>
                                            <option value="-" <?php if ($filters['period'] == '-') echo 'selected'; ?>>TODOS</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-12">
                            <button type="button" class="btn btn-warning w-100" onclick="reload();">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <div class="horizontal-scrollable-tabs" style="width: 100%;">
                        <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                        <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                        <div class="horizontal-tabs">
                            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#interno" aria-controls="interno" role="tab" data-toggle="tab" data-tipo="interno">
                                        ATENDIMENTOS INTERNOS
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a href="#portal" aria-controls="portal" role="tab" data-toggle="tab" data-tipo="portal">
                                        ATENDIMENTOS VIA PORTAL
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="tab-content mtop15">

                        <!-- TAB INTERNO -->
                        <div role="interno" class="tab-pane active" id="interno">
                            <div class="table-responsive">
                                <table class="table dt-table table-registro-atendimentos">
                                    <thead>
                                        <tr>
                                            <th><strong>#</strong></th>
                                            <th><strong>PROTOCOLO</strong></th>
                                            <th><strong>REQUISIÇÕES</strong></th>
                                            <th><strong>CANAL</strong></th>
                                            <th><strong>CLIENTE</strong></th>
                                            <th><strong>CATEGORIA</strong></th>
                                            <th><strong>CONTATO</strong></th>
                                            <th><strong>INF. CHAVE</strong></th>
                                            <th><strong>CADASTRO</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-interno">
                                        <tr>
                                            <td colspan="9" class="text-center">
                                                <i class="fa fa-spinner fa-spin"></i> Carregando...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TAB PORTAL -->
                        <div role="portal" class="tab-pane" id="portal">
                            <div class="table-responsive">
                                <table class="table dt-table table-registro-atendimentos-portal">
                                    <thead>
                                        <tr>
                                            <th><strong>#</strong></th>
                                            <th><strong>PROTOCOLO</strong></th>
                                            <th><strong>REQUISIÇÕES</strong></th>
                                            <th><strong>CLIENTE</strong></th>
                                            <th><strong>CONTATO</strong></th>
                                            <th><strong>INF. CHAVE</strong></th>
                                            <th><strong>CADASTRO</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-portal">
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <i class="fa fa-spinner fa-spin"></i> Carregando...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
    $(function () {
        reload();
    });

    function reload() {
        carregarTabela('interno');
        carregarTabela('portal');
    }

    function carregarTabela(tipo) {
        var seletorTbody = (tipo === 'portal') ? '#tbody-portal' : '#tbody-interno';
        var seletorTabela = (tipo === 'portal') ? '.table-registro-atendimentos-portal' : '.table-registro-atendimentos';
        var colspan = (tipo === 'portal') ? 7 : 9;

        // Destrói DataTable antigo se existir
        if ($.fn.DataTable.isDataTable(seletorTabela)) {
            $(seletorTabela).DataTable().destroy();
        }

        $(seletorTbody).html(
            '<tr><td colspan="' + colspan + '" class="text-center">' +
            '<i class="fa fa-spinner fa-spin"></i> Carregando...</td></tr>'
        );

        var dados = {
            client: $('[name="client"]').val(),
            carteirinha: $('[name="carteirinha"]').val(),
            id: $('[name="id"]').val(),
            protocolo: $('[name="protocolo"]').val(),
            categoria_id: $('[name="categoria_id"]').val() || [],
            canal_atendimento_id: $('[name="canal_atendimento_id"]').val() || [],
            period: $('[name="period"]').val(),
            meus_atendimentos: document.getElementById('my').checked ? 1 : 0,
            portal: (tipo === 'portal') ? 1 : 0
        };

        $.ajax({
            url: '<?php echo base_url('gestao_corporativa/Atendimento/buscar'); ?>',
            type: 'POST',
            data: dados,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    if (response.total > 0) {
                        $(seletorTbody).html(response.html);
                    } else {
                        $(seletorTbody).html(
                            '<tr><td colspan="' + colspan + '" class="text-center text-muted">' +
                            'Nenhum registro encontrado.</td></tr>'
                        );
                    }

                    // Inicializa DataTable nativo (paginação, busca, exportação)
                    $(seletorTabela).DataTable({
                        "order": [[0, "desc"]],
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json"
                        },
                        "pageLength": 25,
                        "dom": 'lBfrtip',
                        "buttons": [
                            { extend: 'copy', text: '<i class="fa fa-copy"></i> Copiar', className: 'btn btn-default btn-sm' },
                            { extend: 'excel', text: '<i class="fa fa-file-excel-o"></i> Excel', className: 'btn btn-default btn-sm', title: 'Atendimentos_' + tipo },
                            { extend: 'csv', text: '<i class="fa fa-file-text-o"></i> CSV', className: 'btn btn-default btn-sm', title: 'Atendimentos_' + tipo },
                            { extend: 'pdf', text: '<i class="fa fa-file-pdf-o"></i> PDF', className: 'btn btn-default btn-sm', title: 'Atendimentos_' + tipo, orientation: 'landscape', pageSize: 'A4' },
                            { extend: 'print', text: '<i class="fa fa-print"></i> Imprimir', className: 'btn btn-default btn-sm' }
                        ]
                    });
                }
            },
            error: function () {
                $(seletorTbody).html(
                    '<tr><td colspan="' + colspan + '" class="text-center text-danger">' +
                    'Erro ao carregar registros.</td></tr>'
                );
            }
        });
    }

    // Botão exportar genérico — dispara o botão Excel da aba ativa
    $('#btn-exportar').on('click', function () {
        var abaAtiva = $('.tab-pane.active').attr('id');
        var seletorTabela = (abaAtiva === 'portal') ? '.table-registro-atendimentos-portal' : '.table-registro-atendimentos';

        if ($.fn.DataTable.isDataTable(seletorTabela)) {
            $(seletorTabela).DataTable().button('.buttons-excel').trigger();
        }
    });
</script>
</body>
</html>