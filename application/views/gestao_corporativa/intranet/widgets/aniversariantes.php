<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="widget relative" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('quick_stats'); ?>">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Aniversariantes da Semana</h3>

            <div class="card-tools">
                <span class="badge badge-warning"><?php echo count($bdays); ?></span>

            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
            <ul class="row users-list clearfix">
                <?php
                setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                date_default_timezone_set('America/Sao_Paulo');
                foreach ($bdays as $bday):
                    ?>
                    <li class="col-md-3">
                        <img style="width: 60px; height: 60px; " src="<?php echo staff_profile_image_caminho($bday->staffid); ?>" alt="<?php echo $bday->firstname . ' ' . $bday->lastname; ?>" >
                        <span class="users-list-date">
                            <?php
                            $nome = trim($bday->firstname . ' ' . $bday->lastname);
                            $nome_parts = explode(' ', $nome);
                            $primeiro_nome = $nome_parts[0];
                            $ultimo_nome = end($nome_parts);

                            echo $primeiro_nome . ' ' . $ultimo_nome;
                            ?>
                            <p>(<?php echo date('d/m', strtotime($bday->data_nascimento)); //echo strftime('%d de %B', strtotime($bday->data_nascimento));  ?>)</p>

                        </span>
                    </li>
<?php endforeach; ?>

            </ul>
            <!-- /.users-list -->
        </div>
        <!-- /.card-body -->

        <!-- /.card-footer -->
    </div>


</div>


