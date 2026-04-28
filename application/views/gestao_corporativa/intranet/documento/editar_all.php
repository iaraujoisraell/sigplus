<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_admin(); ?>
<
<style>
    body {
        font-family:'Open Sans';
        background:#f1f1f1;
    }
    h3 {
        margin-top: 7px;
        font-size: 18px;
    }

    .install-row.install-steps {
        margin-bottom:15px;
        box-shadow: 0px 0px 1px #d6d6d6;
    }

    .control-label {
        font-size:13px;
        font-weight:600;
    }
    .padding-10 {
        padding:10px;
    }
    .mbot15 {
        margin-bottom:15px;
    }
    .bg-default {
        background: #03a9f4;
        border:1px solid #03a9f4;
        color:#fff;
    }
    .bg-success {
        border: 1px solid #dff0d8;
    }
    .bg-not-passed {
        border:1px solid #f1f1f1;
        border-radius:2px;
    }
    .bg-not-passed {
        border-right:0px;
    }
    .bg-not-passed.finish {
        border-right:1px solid #f1f1f1 !important;
    }
    .bg-not-passed h5 {
        font-weight:normal;
        color:#6b6b6b;
    }
    .form-control {
        box-shadow:none;
    }
    .bold {
        font-weight:600;
    }
    .col-xs-5ths,
    .col-sm-5ths,
    .col-md-5ths,
    .col-lg-5ths {
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }
    .col-xs-5ths {
        width: 20%;
        float: left;
    }
    b {
        font-weight:600;
    }
    .bootstrap-select .btn-default {
        background: #fff !important;
        border: 1px solid #d6d6d6 !important;
        box-shadow: none;
        color: #494949 !important;
        padding: 6px 12px;
    }
</style>

<div class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="card card-outline card-info">

                <div class="card-header">

                    <h3 class="card-title">

                        <strong>CONTEÚDO DO DOCUMENTO:</strong>

                    </h3>

                </div>

                <div class="card-body">



                    <input type="hidden" class="form-control p-2" id="id" name="id" value="<?php echo $documento->id; ?>">

                    <input type="hidden" class="form-control p-2" id="id" name="id_principal" value="<?php echo $documento->id_principal; ?>">



                    <input type="hidden" class="form-control p-2" id="projectName" name="id_editado" value="<?php echo $processo->id; ?>">

                    <textarea class="summernote" id="summernote">
<?php echo $doc->conteudo; ?>
                    </textarea>
                    <br>

                    <div id="result">Texto atualizado automaticamente.</div>
                </div>

                <div class="card-footer">

                    <button class="btn mb-0 js-btn-prev bg-blue" onclick="editar_texto();" title="Atualizar">Atualizar</button>


                </div>
            </div>

        </div>

    </div>

</div>

<?php init_tail(); ?>


<script>
    //$(function () {
        //timer = setInterval(function () {
            //alert("2 seconds are up"); 
           // var texto = document.getElementById("summernote").value;
           // $.ajax({
               // type: "POST",
                //url: "<?php echo base_url('gestao_corporativa/intra/Documentos/editar_texto'); ?>",
            //    data: {
                  //  id: '<?php echo $doc->id; ?>',
                  //  conteudo: texto
              //  }
           // });
       // }, 2000);
   // });
    function editar_texto() {
        //alert('chegou');
        //exit;
        var texto = document.getElementById("summernote").value;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/intra/Documentos/editar_texto'); ?>",
            data: {
                id: '<?php echo $doc->id; ?>',
                conteudo: texto
            }
        });
    }
</script>
<script src="<?php echo base_url() ?>assets/intranet/lte/plugins/summernote/summernote-bs4.min.js"></script>
<script>
    $(function () {
        $('.summernote').summernote({
            callbacks: {
                onKeyup: function (e) {
                    setTimeout(function () {
                        editar_texto();
                    }, 200);
                },
                onKeydown: function (e) {
                    setTimeout(function () {
                        editar_texto();
                    }, 200);
                }
            }
        });
    });
</script>

</body>

</html>
