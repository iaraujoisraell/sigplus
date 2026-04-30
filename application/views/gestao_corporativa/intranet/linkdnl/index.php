<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$me              = $this->staff_model->get(get_staff_user_id());
$me_avatar       = staff_profile_image_caminho(get_staff_user_id());
$me_nome         = trim(($me->firstname ?? '') . ' ' . ($me->lastname ?? ''));
$me_cargo        = !empty($me->cargo) ? $me->cargo : 'Colaborador';
$me_setor        = !empty($me->departmentid) ? get_departamento_nome($me->departmentid) : '';
$me_empresa      = get_option('companyname');
?>
<?php init_head_intranet(); ?>

<?php $this->load->view("gestao_corporativa/intranet/linkdnl/_styles"); ?>

<?php $this->load->view('gestao_corporativa/intranet/includes/navbar'); ?>

<div id="wrapper">
    <div class="content">
        <div class="page-intranet">

            <div class="dashboard-grid">

                <!-- ESQUERDA -->
                <div>
                    <div class="ui-card profile-card">
                        <div class="profile-cover"></div>

                        <div class="profile-avatar-wrap">
                            <img class="profile-avatar" src="<?php echo $me_avatar; ?>" alt="<?php echo html_escape($me_nome); ?>">
                        </div>

                        <div class="profile-body">
                            <div class="profile-name"><?php echo html_escape($me_nome); ?></div>
                            <div class="profile-role"><?php echo html_escape($me_cargo); ?></div>
                            <?php if (!empty($me_setor)): ?>
                                <div class="profile-location"><?php echo html_escape($me_setor); ?></div>
                            <?php endif; ?>

                            <?php if (!empty($me_empresa)): ?>
                                <div class="profile-company">
                                    <i class="fa fa-building-o"></i>
                                    <?php echo html_escape($me_empresa); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="ui-card mini-card quick-access-card">
                    <!-- <div class="mini-card-title">Acesse rapidamente os links</div> -->

                        <div class="quick-access-grid" style="margin-top:14px;">
                            <?php
                            $this->load->model('Link_model');
                            $links = $this->Link_model->get_link_department_for_user_id();
                            foreach ($links as $link) {
                            ?>
                                <a
                                    class="quick-access-item"
                                    target="_blank"
                                    href="<?php echo $link['url']; ?>"
                                    style="background: <?php echo $link['color']; ?>;"
                                >
                                    <?php if (!empty($link['titulo'])) { ?>
                                        <span class="quick-access-badge"><?php echo $link['titulo']; ?></span>
                                    <?php } ?>

                                    <?php if (!empty($link['icon'])) { ?>
                                        <i class="<?php echo $link['icon']; ?>"></i>
                                    <?php } ?>

                                    <span class="quick-access-name"><?php echo $link['nome']; ?></span>
                                </a>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="ui-card mini-card profile-analytics profile-links-card">
                       
                        <?php if (!isset($without_permission) || !$without_permission) { ?>
                            <?php if (has_permission_intranet('menu_top_view', '', 'view_menus') || is_admin()) { ?>
                                <?php
                                $this->load->model('Menu_model');
                                $menus_principal = $this->Menu_model->get_menus();
                                ?>

                                <?php if (!empty($menus_principal)) { ?>
                                    <div class="profile-links-list">
                                        <?php foreach ($menus_principal as $menu) { ?>
                                            <?php $submenus = $this->Menu_model->get_submenus($menu['id']); ?>

                                            <div class="profile-link-group">
                                                <div class="profile-link-group-title">
                                                    <?php echo html_escape($menu['nome_menu']); ?>
                                                </div>

                                                <?php if (!empty($submenus)) { ?>
                                                    <?php foreach ($submenus as $submenu) { ?>
                                                        <a href="<?php echo $submenu['urk']; ?>" class="profile-sub-link">
                                                            <span class="profile-sub-link-left">
                                                                <?php if (!empty($submenu['icon'])) { ?>
                                                                    <i class="<?php echo $submenu['icon']; ?>"></i>
                                                                <?php } else { ?>
                                                                    <i class="fa fa-angle-right"></i>
                                                                <?php } ?>
                                                                <span><?php echo html_escape($submenu['nome_menu']); ?></span>
                                                            </span>
                                                        </a>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <a href="<?php echo !empty($menu['urk']) ? $menu['urk'] : '#'; ?>" class="profile-sub-link">
                                                        <span class="profile-sub-link-left">
                                                            <i class="fa fa-angle-right"></i>
                                                            <span>Acessar</span>
                                                        </span>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </div>

                    <?php $this->load->view('gestao_corporativa/intranet/linkdnl/_atalhos_pessoais'); ?>

                    <?php $this->load->view('gestao_corporativa/intranet/linkdnl/_widgets_atas_planos'); ?>

                    <div class="ui-card mini-card news-widget-card">
                        <div class="news-widget-header">
                            <a href="<?php echo base_url('gestao_corporativa/intra/Pubs/ver_todas'); ?>" class="news-widget-title-link">
                                <h4>Notícias</h4>
                            </a>

                            <span class="news-widget-badge"><?php echo !empty($noticia) ? count($noticia) : 0; ?></span>
                        </div>

                        <div class="news-widget-divider"></div>

                        <div class="news-widget-body">
                            <?php
                            setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                            date_default_timezone_set('America/Sao_Paulo');
                            ?>

                            <?php if (!empty($noticia)) { ?>
                                <?php foreach (array_slice($noticia, 0, 4) as $n) { ?>
                                    <?php
                                    $titulo = $n['titulo'];
                                    $foto   = !empty($n['foto']) ? base_url('assets/intranet/img/avisos/' . $n['foto']) : '';
                                    $link   = base_url('gestao_corporativa/intra/Pubs/ver_aviso/?id=' . $n['id']);
                                    ?>
                                    
                                    <a href="<?php echo $link; ?>" target="_blank" class="news-widget-item">
                                        <?php if (!empty($foto)) { ?>
                                            <div class="news-widget-thumb-wrap">
                                                <img class="news-widget-thumb" src="<?php echo $foto; ?>" alt="<?php echo html_escape($titulo); ?>">
                                            </div>
                                        <?php } ?>

                                        <div class="news-widget-content">
                                            <div class="news-widget-item-title"><?php echo html_escape($titulo); ?></div>
                                            <div class="news-widget-item-date">
                                                <?php echo strftime('%d/%m/%Y', strtotime($n['data_cadastro'])); ?>
                                            </div>
                                        </div>
                                    </a>
                                <?php } ?>
                            <?php } else { ?>
                                <div class="news-widget-empty">Nenhuma notícia no momento</div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- CENTRO -->
                <div>
                    <div class="ui-card feed-compose">
                        <div class="feed-compose-top">
                            <img class="feed-compose-avatar" src="<?php echo $me_avatar; ?>" alt="<?php echo html_escape($me_nome); ?>">
                            <div class="feed-compose-input">Começar publicação</div>
                        </div>

                        <div class="feed-compose-actions">
                            <div class="feed-compose-action">
                                <i class="fa fa-picture-o" style="color:#378fe9;"></i> Foto
                            </div>
                            <div class="feed-compose-action">
                                <i class="fa fa-file-text-o" style="color:#e16745;"></i> Comunicado
                            </div>
                            <div class="feed-compose-action">
                                <i class="fa fa-calendar-check-o" style="color:#1a8754;"></i> Evento
                            </div>
                        </div>
                    </div>

                    <div class="feed-sort">
                        <div class="line"></div>
                        <div>Classificar por: <strong>principais</strong> <i class="fa fa-angle-down"></i></div>
                    </div>

                    <?php foreach ($posts as $post): ?>
                        <?php $this->load->view('gestao_corporativa/intranet/feed/post_item', ['post' => $post]); ?>
                    <?php endforeach; ?>
                </div>

                <!-- DIREITA -->
                <div>
                    <?php $this->load->view('gestao_corporativa/intranet/linkdnl/_widgets_direita'); ?>

                    <?php
                    function adjustBrightness($hex, $steps) {
                        $steps = max(-255, min(255, $steps));

                        $hex = str_replace('#', '', $hex);

                        if(strlen($hex) == 3){
                            $hex = str_repeat(substr($hex,0,1),2).
                                str_repeat(substr($hex,1,1),2).
                                str_repeat(substr($hex,2,1),2);
                        }

                        $color_parts = str_split($hex, 2);
                        $return = '#';

                        foreach($color_parts as $color){
                            $color = hexdec($color);
                            $color = max(0,min(255,$color + $steps));
                            $return .= str_pad(dechex($color),2,'0',STR_PAD_LEFT);
                        }

                        return $return;
                    }
                    $links_destaque = $this->Link_destaque_model->get_link_department_for_user_id();

                    foreach($links_destaque as $link){
                    ?>

                    <div class="right-link-card-dynamic" 
                        style="background: linear-gradient(135deg, <?php echo $link['color']; ?>, <?php echo adjustBrightness($link['color'], -20); ?>);">

                        <div class="right-link-content">
                            <h4><?php echo $link['nome']; ?></h4>
                            <p><?php echo $link['titulo']; ?></p>
                        </div>

                        <?php if(!empty($link['icon'])){ ?>
                            <div class="right-link-icon">
                                <i class="<?php echo $link['icon']; ?>"></i>
                            </div>
                        <?php } ?>

                        <a href="<?php echo $link['url']; ?>" target="_blank" class="right-link-overlay"></a>
                    </div>

                    <?php } ?>

                    <div class="ui-card birthdays-card" style="margin-bottom:14px;">
                        <div class="birthdays-header">
                            <h4>Aniversariantes da Semana</h4>
                            <span class="birthdays-badge"><?php echo count($bdays); ?></span>
                        </div>

                        <div class="birthdays-body">
                            <?php
                            setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                            date_default_timezone_set('America/Sao_Paulo');
                            ?>

                            <?php if (!empty($bdays)) { ?>
                                <div class="birthdays-grid">
                                    <?php foreach ($bdays as $bday) { ?>
                                        <?php
                                        $nome = trim($bday->firstname . ' ' . $bday->lastname);
                                        $nome_parts = explode(' ', preg_replace('/\s+/', ' ', $nome));
                                        $primeiro_nome = $nome_parts[0];
                                        $ultimo_nome   = end($nome_parts);
                                        $nome_exibicao = $primeiro_nome;

                                        if (count($nome_parts) > 1) {
                                            $nome_exibicao .= ' ' . $ultimo_nome;
                                        }
                                        ?>
                                        <div class="birthday-item">
                                            <img
                                                class="birthday-avatar"
                                                src="<?php echo staff_profile_image_caminho($bday->staffid); ?>"
                                                alt="<?php echo html_escape($nome); ?>"
                                            >

                                            <div class="birthday-name">
                                                <?php echo html_escape($nome_exibicao); ?>
                                            </div>

                                            <div class="birthday-date">
                                                (<?php echo date('d/m', strtotime($bday->data_nascimento)); ?>)
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } else { ?>
                                <div class="birthdays-empty">Nenhum aniversariante nesta semana.</div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="ui-card events-card">
                        <h4>Próximos eventos</h4>

                        <?php
                        $meses_abr = ['', 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
                        $hoje      = date('Y-m-d');
                        $futuros   = [];
                        if (!empty($date)) {
                            foreach ($date as $ev) {
                                $start = isset($ev->start) ? $ev->start : (isset($ev->data) ? $ev->data : null);
                                if ($start && substr($start, 0, 10) >= $hoje) {
                                    $futuros[] = $ev;
                                }
                            }
                            usort($futuros, function ($a, $b) {
                                $da = isset($a->start) ? $a->start : (isset($a->data) ? $a->data : '');
                                $db = isset($b->start) ? $b->start : (isset($b->data) ? $b->data : '');
                                return strcmp($da, $db);
                            });
                        }
                        $futuros = array_slice($futuros, 0, 4);
                        ?>

                        <?php if (!empty($futuros)): ?>
                            <?php foreach ($futuros as $ev):
                                $when  = isset($ev->start) ? $ev->start : (isset($ev->data) ? $ev->data : '');
                                $ts    = strtotime($when);
                                $dia   = $ts ? date('d', $ts) : '--';
                                $mes   = $ts ? $meses_abr[(int) date('n', $ts)] : '';
                                $hora  = $ts && date('H:i', $ts) !== '00:00' ? date('H:i', $ts) : '';
                                $titulo = isset($ev->title) ? $ev->title : (isset($ev->titulo) ? $ev->titulo : 'Evento');
                                $local  = isset($ev->where) ? $ev->where : (isset($ev->local) ? $ev->local : '');
                            ?>
                                <div class="event-item">
                                    <div class="event-date">
                                        <span class="day"><?php echo $dia; ?></span>
                                        <span class="month"><?php echo $mes; ?></span>
                                    </div>

                                    <div class="event-content">
                                        <strong><?php echo html_escape($titulo); ?></strong>
                                        <?php if ($hora || $local): ?>
                                            <span><?php echo $hora ? $hora : ''; ?><?php echo ($hora && $local) ? ' • ' : ''; ?><?php echo html_escape($local); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-muted small" style="padding: 6px 2px;">Nenhum evento agendado.</div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
