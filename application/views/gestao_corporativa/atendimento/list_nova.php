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

                    <!-- BOTÃO EXPORTAR -->
                    <div class="btn-group pull-right">
                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-download"></i> Exportar <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="#" onclick="exportarRA('excel'); return false;"><i class="fa fa-file-excel-o"></i> Excel</a></li>
                            <li><a href="#" onclick="exportarRA('csv'); return false;"><i class="fa fa-file-text-o"></i> CSV</a></li>
                            <li><a href="#" onclick="exportarRA('pdf'); return false;"><i class="fa fa-file-pdf-o"></i> PDF</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#" onclick="exportarRA('print'); return false;"><i class="fa fa-print"></i> Imprimir</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <br>

            <div class="panel_s mbot10">
                <div class="panel-heading" style="padding-top: 0; padding-bottom: 0;">
                    <div class="checkbox">
                        <?php echo form_hidden('meus_atendimentos', '1'); ?>
                        <input type="checkbox" id="my" checked>
                        <label for="my">Somente os meus atendimentos</label>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-11 col-sm-12">
                            <div class="row">
                                <div class="col-md-2 col-sm-6">
                                    <?php
                                    echo render_select(
                                        'categoria_id',
                                        $categorias,
                                        ['id', 'titulo'],
                                        '',
                                        !empty($filters['categoria_id']) ? json_decode($filters['categoria_id']) : [],
                                        ['data-none-selected-text' => 'CATEGORIA', 'multiple' => 'true'],
                                        [],
                                        '',
                                        '',
                                        false
                                    );
                                    ?>
                                </div>

                                <div class="col-md-2 col-sm-6">
                                    <?php
                                    echo render_select(
                                        'canal_atendimento_id',
                                        $canais_atendimentos,
                                        ['id', 'canal'],
                                        '',
                                        !empty($filters['canal_atendimento_id']) ? json_decode($filters['canal_atendimento_id']) : [],
                                        ['data-none-selected-text' => 'CANAL', 'multiple' => 'true'],
                                        [],
                                        '',
                                        '',
                                        false
                                    );
                                    ?>
                                </div>

                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="client" value="<?php echo isset($filters['client']) ? $filters['client'] : ''; ?>" placeholder="NOME CLIENTE">
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <input type="number" class="form-control" value="<?php echo isset($filters['id']) ? $filters['id'] : ''; ?>" name="id" placeholder="#WFID">
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="number" name="protocolo" value="<?php echo isset($filters['protocolo']) ? $filters['protocolo'] : ''; ?>" placeholder="PROTOCOLO">
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="number" name="carteirinha" value="<?php echo isset($filters['carteirinha']) ? $filters['carteirinha'] : ''; ?>" placeholder="CARTEIRINHA">
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="colaborador" value="<?php echo isset($filters['colaborador']) ? $filters['colaborador'] : ''; ?>" placeholder="COLABORADOR">
                                    </div>
                                </div>


                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <input type="date" class="form-control" name="data_inicio" value="<?php echo isset($filters['data_inicio']) ? $filters['data_inicio'] : date('Y-m-d'); ?>">
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <input type="date" class="form-control" name="data_fim" value="<?php echo isset($filters['data_fim']) ? $filters['data_fim'] : date('Y-m-d'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                       
                    </div>

                     <div class="col-md-12 col-sm-12">
                            <button type="button" class="btn btn-primary " id="btnBuscar">
                                <i class="fa fa-search" aria-hidden="true"></i> FILTRAR
                            </button>
                            <button type="button" class="btn btn-default  mtop5" id="btnLimpar">
                                Limpar
                            </button>
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
                                    <a href="#interno" aria-controls="interno" role="tab" data-toggle="tab">ATENDIMENTOS INTERNOS</a>
                                </li>
                                <li role="presentation">
                                    <a href="#portal" aria-controls="portal" role="tab" data-toggle="tab">ATENDIMENTOS VIA PORTAL</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="tab-content mtop15">
                        <div role="tabpanel" class="tab-pane active" id="interno">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="tabela-ra-interno">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>PROTOCOLO</th>
                                            <th>REQUISIÇÕES</th>
                                            <th>CANAL</th>
                                            <th>CLIENTE</th>
                                            <th>ORIGEM</th>
                                            <th>CATEGORIA</th>
                                            <th>CONTATO</th>
                                            <th>INF. CHAVE</th>
                                            <th>CADASTRO</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-ra-interno">
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">Carregando...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane" id="portal">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="tabela-ra-portal">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>PROTOCOLO</th>
                                            <th>REQUISIÇÕES</th>
                                            <th>CLIENTE</th>
                                            <th>ORIGEM</th>
                                            <th>CONTATO</th>
                                            <th>INF. CHAVE</th>
                                            <th>CADASTRO</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-ra-portal">
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">Carregando...</td>
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
    var raTimer = null;

    function getFiltrosRA() {
        return {
            client: $('[name="client"]').val(),
            carteirinha: $('[name="carteirinha"]').val(),
            colaborador: $('[name="colaborador"]').val(),
            id: $('[name="id"]').val(),
            protocolo: $('[name="protocolo"]').val(),
            categoria_id: $('[name="categoria_id"]').val(),
            canal_atendimento_id: $('[name="canal_atendimento_id"]').val(),
            meus_atendimentos: $('#my').is(':checked') ? 1 : 0,
            data_inicio: $('[name="data_inicio"]').val(),
            data_fim: $('[name="data_fim"]').val()
        };
    }

    function carregarTabelaRA(portal) {
        var filtros = getFiltrosRA();
        filtros.portal = portal ? 1 : 0;

        var tbody = portal ? '#tbody-ra-portal' : '#tbody-ra-interno';
        var colspan = portal ? 8 : 10;

        $(tbody).html('<tr><td colspan="' + colspan + '" class="text-center text-muted">Carregando...</td></tr>');

        $.ajax({
            url: '<?php echo base_url('gestao_corporativa/Atendimento/buscar'); ?>',
            type: 'POST',
            dataType: 'json',
            data: filtros,
            success: function(res) {
                if (res.success) {
                    $(tbody).html(res.html);
                } else {
                    $(tbody).html('<tr><td colspan="' + colspan + '" class="text-center text-danger">Erro ao carregar.</td></tr>');
                }
            },
            error: function() {
                $(tbody).html('<tr><td colspan="' + colspan + '" class="text-center text-danger">Erro ao carregar.</td></tr>');
            }
        });
    }

    function carregarTudoRA() {
        carregarTabelaRA(0);
        carregarTabelaRA(1);
    }

    function debounceBuscaRA() {
        clearTimeout(raTimer);
        raTimer = setTimeout(function() {
            carregarTudoRA();
        }, 400);
    }

    /**
     * EXPORTAR — pega a tabela da aba ativa e exporta no formato escolhido.
     * Funciona direto na tabela renderizada (sem reinicializar DataTable),
     * gerando o arquivo no client-side a partir do HTML já carregado.
     */
    function exportarRA(formato) {
        var abaAtiva = $('.tab-pane.active').attr('id'); // 'interno' ou 'portal'
        var idTabela = (abaAtiva === 'portal') ? 'tabela-ra-portal' : 'tabela-ra-interno';
        var tabela = document.getElementById(idTabela);

        if (!tabela) {
            alert('Tabela não encontrada.');
            return;
        }

        // Verifica se há dados (não é a linha "Carregando..." nem "Nenhum registro")
        var linhas = $('#' + idTabela + ' tbody tr').length;
        var primeiraLinha = $('#' + idTabela + ' tbody tr:first td').text().trim();
        if (linhas === 0 || primeiraLinha === 'Carregando...' || primeiraLinha.indexOf('Nenhum') !== -1) {
            alert('Não há dados para exportar.');
            return;
        }

        var nomeArquivo = 'atendimentos_' + abaAtiva + '_' + dataAtual();

        switch (formato) {
            case 'excel':
                exportarExcel(tabela, nomeArquivo);
                break;
            case 'csv':
                exportarCSV(tabela, nomeArquivo);
                break;
            case 'pdf':
                exportarPDF(idTabela, nomeArquivo, abaAtiva);
                break;
            case 'print':
                imprimirTabela(idTabela, abaAtiva);
                break;
        }
    }

    function dataAtual() {
        var d = new Date();
        return d.getFullYear() + '-' +
            ('0' + (d.getMonth() + 1)).slice(-2) + '-' +
            ('0' + d.getDate()).slice(-2) + '_' +
            ('0' + d.getHours()).slice(-2) +
            ('0' + d.getMinutes()).slice(-2);
    }

    /**
     * Excel — gera .xls usando o próprio HTML da tabela (Excel abre nativamente).
     * Limpa botões/dropdowns antes de exportar.
     */
    function exportarExcel(tabela, nomeArquivo) {
        var clone = tabela.cloneNode(true);

        // Remove elementos que não devem ir para o Excel
        $(clone).find('.dropdown, .table-export-exclude, button, .btn').remove();

        var html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" ' +
            'xmlns:x="urn:schemas-microsoft-com:office:excel" ' +
            'xmlns="http://www.w3.org/TR/REC-html40">' +
            '<head><meta charset="UTF-8"></head>' +
            '<body>' + clone.outerHTML + '</body></html>';

        var blob = new Blob(['\ufeff' + html], { type: 'application/vnd.ms-excel;charset=utf-8' });
        baixarArquivo(blob, nomeArquivo + '.xls');
    }

    /**
     * CSV — converte cada linha da tabela em uma linha CSV com separador ";".
     */
    function exportarCSV(tabela, nomeArquivo) {
        var csv = [];
        var linhas = tabela.querySelectorAll('tr');

        linhas.forEach(function(linha) {
            var colunas = linha.querySelectorAll('td, th');
            var dadosLinha = [];

            colunas.forEach(function(col) {
                var clone = col.cloneNode(true);
                // remove dropdowns/botões
                $(clone).find('.dropdown, button, .btn').remove();
                // troca <br> por espaço para não quebrar a linha do CSV
                $(clone).find('br').replaceWith(' | ');
                var texto = (clone.innerText || clone.textContent || '').trim();
                texto = texto.replace(/"/g, '""'); // escapa aspas
                dadosLinha.push('"' + texto + '"');
            });

            csv.push(dadosLinha.join(';'));
        });

        var blob = new Blob(['\ufeff' + csv.join('\n')], { type: 'text/csv;charset=utf-8' });
        baixarArquivo(blob, nomeArquivo + '.csv');
    }

    /**
     * PDF — abre uma nova janela com a tabela formatada para impressão em PDF.
     * Usuário usa "Salvar como PDF" do navegador (funciona em todos os navegadores).
     */
    function exportarPDF(idTabela, nomeArquivo, abaAtiva) {
        imprimirTabela(idTabela, abaAtiva, true);
    }

    /**
     * Impressão — abre janela com layout limpo.
     * Se modoPDF=true, sugere o nome do arquivo via título da página.
     */
    function imprimirTabela(idTabela, abaAtiva, modoPDF) {
        var conteudo = document.getElementById(idTabela).outerHTML;
        var titulo = (abaAtiva === 'portal') ? 'Atendimentos Via Portal' : 'Atendimentos Internos';
        var dataHora = new Date().toLocaleString('pt-BR');

        var html = '<!DOCTYPE html><html><head><meta charset="UTF-8">' +
            '<title>' + titulo + '</title>' +
            '<style>' +
            'body{font-family: Arial, sans-serif; padding:20px; color:#333;}' +
            'h2{margin:0 0 5px 0;}' +
            '.subtitle{color:#666; margin-bottom:20px; font-size:12px;}' +
            'table{width:100%; border-collapse:collapse; font-size:11px;}' +
            'th,td{border:1px solid #ccc; padding:6px; text-align:left; vertical-align:top;}' +
            'th{background:#f4f4f4; font-weight:bold;}' +
            'a{color:#000; text-decoration:none;}' +
            '.dropdown, button, .btn, .table-export-exclude{display:none !important;}' +
            '@media print{body{padding:10px;}}' +
            '</style></head><body>' +
            '<h2>' + titulo + '</h2>' +
            '<div class="subtitle">Gerado em: ' + dataHora + '</div>' +
            conteudo +
            '<scr' + 'ipt>window.onload=function(){window.print();}</scr' + 'ipt>' +
            '</body></html>';

        var janela = window.open('', '_blank');
        janela.document.write(html);
        janela.document.close();
    }

    function baixarArquivo(blob, nome) {
        if (window.navigator.msSaveOrOpenBlob) {
            // IE/Edge antigo
            window.navigator.msSaveOrOpenBlob(blob, nome);
        } else {
            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = nome;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

    $(function() {
        carregarTudoRA();

        $('#btnBuscar').on('click', function() {
            carregarTudoRA();
        });

        $('#btnLimpar').on('click', function() {
            $('[name="client"]').val('');
            $('[name="carteirinha"]').val('');
            $('[name="colaborador"]').val('');
            $('[name="id"]').val('');
            $('[name="protocolo"]').val('');
            $('[name="data_inicio"]').val('<?php echo date('Y-m-d'); ?>');
            $('[name="data_fim"]').val('<?php echo date('Y-m-d'); ?>');
            $('#my').prop('checked', true);
            $('[name="categoria_id"]').val([]).selectpicker('refresh');
            $('[name="canal_atendimento_id"]').val([]).selectpicker('refresh');
            carregarTudoRA();
        });

        $(document).on('keyup', '[name="client"], [name="carteirinha"], [name="colaborador"], [name="id"], [name="protocolo"]', debounceBuscaRA);
        $(document).on('change', '[name="categoria_id"], [name="canal_atendimento_id"], [name="data_inicio"], [name="data_fim"], #my', function() {
            carregarTudoRA();
        });

        // Removido o reload ao trocar aba — evita requisição duplicada.
        // Os dados das duas abas já são carregados de uma vez no início.
    });
</script>
</body>
</html>