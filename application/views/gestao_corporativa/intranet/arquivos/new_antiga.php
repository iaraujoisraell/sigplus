
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../../../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../../../assets/img/favicon.png">
  <title>
    INTRANET - Arquivos
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="../../assets/intranet/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../../assets/intranet/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="../../assets/intranet/css/material-dashboard.css?v=3.0.2" rel="stylesheet" />
</head>

<body class="g-sidenav-show  bg-gray-200">
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    
    <div class="container-fluid py-1">
      <div class="row align-items-center">
              <div class="col-lg-4 col-sm-8">
                  <div class="nav-wrapper position-relative end-0">

                      <button class="btn bg-gradient-dark" id="a" href="javascript:void(0)" onClick="history.go(-1); return false;" >
                          <span class="material-icons">
                              arrow_back
                          </span>
                          Voltar
                      </button>

                  </div>
              </div>
          </div>
      <div class="row">
        <div class="col-lg-9 col-12 mx-auto position-relative">
            <?php echo form_open_multipart('gestao_corporativa/intra/Arquivos/salvar'); ?>
          <div class="card">
            <div class="card-header p-3 pt-2">
              <div class="icon icon-lg icon-shape bg-gradient-dark shadow text-center border-radius-xl mt-n4 me-3 float-start">
                <i class="material-icons opacity-10">folder_open</i>
              </div>
              <h6 class="mb-0">Documento</h6>
            </div>
            <div class="card-body pt-2">
              <div class="input-group input-group-dynamic">
                <label for="projectName" class="form-label">Título do Documento</label>
                <input type="text" class="form-control" id="projectName" name="titulo">
              </div>
                <label for="projectName" class="form-label mt-4">Descrição do Documento</label>
              <div class="input-group input-group-dynamic">
                
                <textarea type="text" class="form-control" id="projectName" name="descricao"></textarea>
              </div>
             
              <label class="form-label mt-4">Documento:</label> 
                <input class="form-control" name="arquivo" type="file"/>

                <label class="form-label mt-4">Enviar para:</label>   
                
                
              <div class="accordion" id="accordionRental">
                
                  <?php foreach ($departments as $departamento): $i++;?>
                   <input id="id" name="id" value="<?php echo $id; ?>" type="hidden" class="form-control">
                  <div class="accordion-item">
                              <h6 class="accordion-header" id="headingOne">
                                  <button class="accordion-button border-bottom font-weight-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $i;?>" aria-expanded="false" aria-controls="collapseOne">
                                      
                                      <div class="form-check">
                                              <input class="form-check-input  bg-gradient-dark text-white" type="checkbox" onclick='marcar("for_staffs<?php echo $departamento['departmentid']?>")'  >
                                              <label class="custom-control-label" for="customCheck1"><?php echo $departamento['name']; ?></label>
                                          </div>
                                      <i class="collapse-close fa fa-plus text-xs pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
                                      <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
                                  </button>
                              </h6>
                              <div id="collapse<?php echo $i;?>" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionRental" style="">
                                  <div class="accordion-body text-sm opacity-8">
                                      <ul class="list-group">
                                          <?php foreach($departamento['staffs'] as $staff):?>
                                          <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2 pt-0">
                                              <div class="form-check ms-4">
                                                          <input class="for_staffs<?php echo $departamento['departmentid']?> form-check-input " name="for_staffs[]" type="checkbox" value="<?php echo $staff->staffid?>" id="fcustomCheck1" >
                                                          <label class="custom-control-label"  for="customCheck1"><?php echo ($staff->firstname . $staff->lastname); ?></label>
                                                      </div>
                                          </li>
                                          <?php endforeach;?>
                                      </ul>
                                  </div>
                              </div>
                          </div>
                  <?php endforeach;?>
                     
                     
                      </div>  
              <div class="d-flex justify-content-end mt-4">
                <button type="submit" name="button" class="btn bg-gradient-dark m-0 ms-2">Salvar</button>
              </div>
            </div>
          </div>
            <?php echo form_close(); ?>
        </div>
      </div>
      <footer class="footer py-4  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class="fa fa-heart"></i> by
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                for a better web.
              </div>
            </div>
            <div class="col-lg-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                  <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>
  <!--   Core JS Files   -->
  <script language="JavaScript">
  function marcar(nome){
    var boxes = document.getElementsByClassName(nome);
    for(var i = 0; i < boxes.length; i++){
        if(boxes[i].checked === false){
            boxes[i].checked = true;
        } else{
            boxes[i].checked = false;
        }
    }
  }
   
  function desmarcar(){
    var boxes = document.getElementsByName("linguagem");
    for(var i = 0; i < boxes.length; i++)
      boxes[i].checked = false;
  }
</script>
  <script src="../../assets/intranet/js/core/popper.min.js"></script>
  <script src="../../assets/intranet/js/core/bootstrap.min.js"></script>
  <script src="../../assets/intranet/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../../assets/intranet/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../../assets/intranet/js/plugins/choices.min.js"></script>
  <script src="../../assets/intranet/js/plugins/quill.min.js"></script>
  <script src="../../assets/intranet/js/plugins/flatpickr.min.js"></script>
  <script src="../../assets/intranet/js/plugins/dropzone.min.js"></script>
  <script>
    if (document.getElementById('editor')) {
      var quill = new Quill('#editor', {
        theme: 'snow' // Specify theme in configuration
      });
    }

    if (document.getElementById('choices-multiple-remove-button')) {
      var element = document.getElementById('choices-multiple-remove-button');
      const example = new Choices(element, {
        removeItemButton: true
      });

      example.setChoices(
        [{
            value: 'One',
            label: 'Label One',
            disabled: true
          },
          {
            value: 'Two',
            label: 'Label Two',
            selected: true
          },
          {
            value: 'Three',
            label: 'Label Three'
          },
        ],
        'value',
        'label',
        false,
      );
    }

    if (document.querySelector('.datetimepicker')) {
      flatpickr('.datetimepicker', {
        allowInput: true
      }); // flatpickr
    }

    Dropzone.autoDiscover = false;
    var drop = document.getElementById('dropzone')
    var myDropzone = new Dropzone(drop, {
      url: "/file/post",
      addRemoveLinks: true

    });
  </script>
  <!-- Kanban scripts -->
  <script src="../../assets/intranet/js/plugins/dragula/dragula.min.js"></script>
  <script src="../../assets/intranet/js/plugins/jkanban/jkanban.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../../assets/intranet/js/material-dashboard.min.js?v=3.0.2"></script>
</body>

</html>