<!-- FIM LTE-->
<link  href="<?php echo base_url(); ?>assets/intranet/css/material-dashboard.css?v=3.0.2" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lte/plugins/fullcalendar/main.css">

<div class="col-12">

    <div class="card" style="margin-top: 10px;">
        <div class="card-header d-flex p-0">
            <h3 class="card-title p-3"><?php echo $title; ?></h3>
            <ul class="nav nav-pills ml-auto p-2">
                <?php if($menu->menu_pai == 0){?>
                <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Principal</a></li>
                <?php }?>
                
                <?php foreach($submenus as $submenu):?>
                <li class="nav-item"><a class="nav-link <?php if($menu->id == $submenu['id']){ echo 'active'; }?>" href="#tab_<?php echo $submenu['id'];?>" data-toggle="tab"><?php echo $submenu['nome_menu'];?></a></li>
                <?php endforeach;?>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <?php if($menu->menu_pai == 0){?>
                <div class="tab-pane active" id="tab_1">
                    <?php echo $menu->conteudo; ?>
                </div>
                <?php }?>

                <?php foreach($submenus as $submenu):?>
                <div class="tab-pane <?php if($menu->id == $submenu['id']){ echo 'active'; }?>" id="tab_<?php echo $submenu['id'];?>">
                    <?php echo $submenu['conteudo'];?>
                </div>
                <?php endforeach;?>

            </div>

        </div>
        <div class="card-footer">
            <span class="description"><?php echo $menu->firstname.' '.$menu->lastname;?> - <?php echo date("d-m-Y", strtotime($menu->data_cadastro));?></span>
        </div>


    </div>

</div>




<!-- /.content -->

<script src="<?php echo base_url(); ?>assets/lte/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url(); ?>assets/lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/lte/dist/js/adminlte.min.js"></script>

<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<!-- AFETA O BANNER -->
<script src="<?php echo base_url(); ?>assets/intranet/js/core/bootstrap.min.js"></script>

<script>
    $(function () {
        $("#tabs").tabs();
    });
</script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>



<script src="<?php echo base_url(); ?>assets/intranet/js/plugins/fullcalendar.min.js"></script>

<script>
    var calendar = new FullCalendar.Calendar(document.getElementById("calendar_intranet"), {
        contentHeight: 'auto',
        initialView: "dayGridMonth",
        headerToolbar: {
            start: 'title', // will normally be on the left. if RTL, will be on the right
            center: '',
            end: 'today prev,next' // will normally be on the right. if RTL, will be on the left
        },
        selectable: true,
        editable: true,
        initialDate: '<?php echo date('Y-m-d'); ?>',
        events: [
<?php foreach ($date as $data): ?>
                {
                    title: '<?PHP echo $data->titulo ?>',
                    start: '<?PHP echo $data->inicio ?>',
                    end: '<?PHP echo $data->fim ?>',
                    color: '<?PHP echo $data->cor ?>'
                },
<?php endforeach; ?>
        ],
        views: {
            month: {
                titleFormat: {
                    month: "long",
                    year: "numeric"
                }
            },
            agendaWeek: {
                titleFormat: {
                    month: "long",
                    year: "numeric",
                    day: "numeric"
                }
            },
            agendaDay: {
                titleFormat: {
                    month: "short",
                    year: "numeric",
                    day: "numeric"
                }
            }
        },
    });

    calendar.render();

    var ctx1 = document.getElementById("chart-line-1").getContext("2d");

    new Chart(ctx1, {
        type: "line",
        data: {
            labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                    label: "Visitors",
                    tension: 0.5,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#fff",
                    borderWidth: 2,
                    backgroundColor: "transparent",
                    data: [50, 45, 60, 60, 80, 65, 90, 80, 100],
                    maxBarThickness: 6,
                    fill: true
                }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                    },
                    ticks: {
                        display: false
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                    },
                    ticks: {
                        display: false
                    }
                },
            },
        },
    });
</script>

