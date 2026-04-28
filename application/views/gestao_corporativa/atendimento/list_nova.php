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
                                        <select name="origem_carteirinha" class="form-control">
                                            <option value="">ORIGEM</option>
                                            <option value="UNIMED MANAUS">UNIMED MANAUS</option>
                                            <option value="UNIMED INTERCÂMBIO">UNIMED INTERCÂMBIO</option>
                                            <option value="AVULSO">AVULSO</option>
                                        </select>
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
            origem_carteirinha: $('[name="origem_carteirinha"]').val(),
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

    $(function() {
        carregarTudoRA();

        $('#btnBuscar').on('click', function() {
            carregarTudoRA();
        });

        $('#btnLimpar').on('click', function() {
            $('[name="client"]').val('');
            $('[name="carteirinha"]').val('');
            $('[name="id"]').val('');
            $('[name="protocolo"]').val('');
            $('[name="data_inicio"]').val('<?php echo date('Y-m-d'); ?>');
            $('[name="data_fim"]').val('<?php echo date('Y-m-d'); ?>');
            $('#my').prop('checked', true);
            $('[name="categoria_id"]').val([]).selectpicker('refresh');
            $('[name="canal_atendimento_id"]').val([]).selectpicker('refresh');
            carregarTudoRA();
        });

        $(document).on('keyup', '[name="client"], [name="carteirinha"], [name="id"], [name="protocolo"]', debounceBuscaRA);
        $(document).on('change', '[name="categoria_id"], [name="canal_atendimento_id"], [name="data_inicio"], [name="data_fim"], #my', function() {
            carregarTudoRA();
        });

        $('a[href="#portal"], a[href="#interno"]').on('shown.bs.tab', function() {
            // mantém atualizado ao alternar aba
            carregarTudoRA();
        });
    });
</script>
</body>
</html>