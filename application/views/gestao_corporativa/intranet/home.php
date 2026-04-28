<!-- FIM LTE-->
<link  href="<?php echo base_url(); ?>assets/intranet/css/material-dashboard.css?v=3.0.2" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lte/plugins/fullcalendar/main.css">

<?php render_intranet_widgets('cards'); ?>

<!--<div class="content-header">

        <div class="row">
            <div class="col-sm-6">
                <h1 class="m-0">INTRANET</h1>
            </div>
        </div>
   
</div>-->


<div class="content">
    <div class="">
        <div class="row">
            <?php
            $firstname = $current_user->firstname;
            $lastname = $current_user->lastname;
            $name = $firstname . ' ' . $lastname;
            $cargo = $current_user->cargo;

            $url = base_url('assets/images/user-placeholder.jpg');
            ?>
            <section class="col-md-3 ">
                <!-- Widget: user widget style 1 -->
                <div class="card card-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <?php
                    if ($current_user->background_image):
                        $url_bg = base_url() . 'uploads/staff_profile_images/' . $current_user->staffid . '/bg_' . $current_user->background_image;
                    else:
                        $url_bg = base_url() . 'uploads/staff_profile_images/bg.jpg';
                    endif;
                    ?>
                    <div class="widget-user-header text-white"
                         style="background: url('<?php echo $url_bg; ?>') center center;">
                        <h3 class="widget-user-username text-right"><?php echo $name; ?></h3>
                        <h5 class="widget-user-desc text-right"><?php echo $cargo; ?></h5>
                    </div>
                    <div class="widget-user-image">
                        
                        <?php
                        $profile_image = $current_user->profile_image;
                        if($current_user->terceiro_id){
                        $this->load->model('Company_model');
                        $row_terceiro =  $this->Company_model->get_terceiros($current_user->terceiro_id);
                        $color = $row_terceiro->cor;
                        }
                        else{
                            $color = "#d3d3d3";
                        }

                        if ($profile_image) {
                            $usuario_id = get_staff_user_id();
                            ?>
                            <?php //echo staff_profile_image($current_user->staffid, array('img', 'img-circle', 'staff-profile-image-small', 'pull-left')); ?>
                            <img class="img-circle" style="min-width: 100px; min-height: 100px; max-height: 100px; max-width: 100px; border: 5px solid <?php echo $color; ?> ;"  src="<?php echo base_url(); ?>uploads/staff_profile_images/<?php echo $usuario_id . '/small_' . $profile_image; ?>" alt="User Avatar">
                        <?php } 
                        else { ?>
                            <img class="img-circle" style="min-width: 100px; min-height: 100px; max-height: 100px; max-width: 100px;"  src="<?php echo $url; ?>" alt="User Avatar">
                        <?php } ?>

                    </div>
                    <div class="card-footer">

                    </div>
                </div>
                <?php if (has_permission_intranet('home_view', '', 'view_noticias') || is_admin()) { ?>
                    <?php render_intranet_widgets('links'); ?>
                <?php } ?>
                <?php if (has_permission_intranet('home_view', '', 'view_noticias') || is_admin()) { ?>
                    <?php render_intranet_widgets('noticias'); ?>
                <?php } ?>
                <!-- /.widget-user -->
            </section>

            <section class="col-md-6 ">
                <?php if (has_permission_intranet('feed', '', 'create') || is_admin()) { ?>
                    <div class="card card-primary collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">O que deseja compartilhar hoje?</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool"   data-card-widget="collapse"><i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->



                        <div class="card-body">
                            <div class="card card-outline card-info">
                                <!-- /.card-header -->
                                <?php //echo form_open_multipart('gestao_corporativa/feed/add_post'); ?>

                                <?php echo form_open('gestao_corporativa/feed/add_post', array('id' => 'invoice_item_form')); ?>     
                                <textarea class="summernote" id="m_summernote_1" name="conteudo_textarea">

                                </textarea>


                                <select id="post-visibility"  name="setores[]" class="select2" multiple="multiple" data-placeholder="Para todos os Setores" style="width: 100%;">

                                    <?php foreach ($departments as $department) { ?>
                                        <option value="<?php echo $department['departmentid']; ?>"><?php echo $department['name']; ?></option>
                                    <?php } ?>
                                </select>

                                <div class="modal-footer">

                                    <button type="submit" class="btn btn-primary pull-right">Publicar</button>
                                </div> 


                                <?php echo form_close(); ?>


                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>


                    
                <?php } ?>

                <?php if (has_permission_intranet('home_view', '', 'view_feed') || is_admin()) { ?>
                    <?php render_intranet_widgets('posts'); ?>
                <?php } ?>
                <?php if (has_permission_intranet('home_view', '', 'view_calendario') || is_admin()) { ?>
                    <?php render_intranet_widgets('calendario'); ?>
                <?php } ?>
            </section>


            <section class="col-md-3">
                
                <?php if (has_permission_intranet('home_view', '', 'view_banners') || is_admin()) { ?>
                    <?php render_intranet_widgets('banner'); ?>
                <?php } ?>
                <?php if (has_permission_intranet('home_view', '', 'view_links_destaque') || is_admin()) { ?>
                    <?php render_intranet_widgets('destaques'); ?>
                <?php } ?>
                
                <?php if (has_permission_intranet('home_view', '', 'view_aniversariantes') || is_admin()) { ?>
                    <?php render_intranet_widgets('aniversariantes'); ?>
                <?php } ?>
            </section>
            <!-- BANNER -->

        </div>    

    </div>
</div>
<script src="<?php echo base_url(); ?>assets/lte/plugins/jquery/jquery.min.js"></script>

<script>
    $(function () {
        var SummernoteDemo = {init: function () {
                $(".summernote").summernote({height: 150})
            }};
        jQuery(document).ready(function () {
            SummernoteDemo.init()
        });
        // $('#summernote_value').summernote({focus: true, height: 250 });

        $("#tabs").tabs();

        //Initialize Select2 Elements
        $('.select2').select2();

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });

        // CodeMirror
        CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
            mode: "htmlmixed",
            theme: "monokai"
        });

    });


</script>
<script>
    $("body").on('keyup', '.comment-input input', function (event) {
        if (event.keyCode == 13) {
            alert('aqui');
            add_comment(this);
        }
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


<script>
    // comentários
    function add_comentario_post(postid) {
        var postid = postid;
        // Sua função aqui
        var comentario = $('#coment_post_' + postid).val();

        if (postid && comentario) {

            $.ajax({
                type: "POST",
                url: "<?php echo base_url("gestao_corporativa/feed/add_comment"); ?>",
                data: {
                    coment_post: comentario,
                    postid: postid
                },
                success: function (data) {
                    $('#conteudo').html(data);
                }
            });

        }

    }
    ;
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

