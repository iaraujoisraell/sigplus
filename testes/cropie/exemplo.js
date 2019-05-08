 $(document).ready(function(){
  function CropUpload() {
    var $uploadCrop;
    var blob;
    function readFile(input) {
      if (input.files && input.files[0]) {
              var reader = new FileReader();
              reader.onload = function (e) {
          $('.upload-demo').addClass('ready');
                $uploadCrop.croppie('bind', {
                  url: e.target.result
                }).then(function(){
                  console.log('Imagem lida com sucesso');
                });
              }
              reader.readAsDataURL(input.files[0]);
          }
          else {
            swal("Seu navegador não suporta o FileReader API");
        }
    }
    $uploadCrop = $('#upload-demo').croppie({
      viewport: {
        width: 450,
        height: 400,
        type: 'square'
      },
      boundary: {
          width: 550,
          height: 400
      },
      update: function(resp){
          $uploadCrop.croppie('result', {
            type: 'canvas'
          }).then(function (resp) {
            $('#img-preview').attr('src', resp);
          });
          $uploadCrop.croppie('result', {
            type: 'blob'
          }).then(function (resp) {
            blob = resp;
          });
      },
                        enableExif: true
    });
    $('#img').on('change', function () {
      readFile(this);
    });
  
    $('form#img-upload').submit(function (ev) {
      ev.preventDefault();
      var data = new FormData(this);
      
      data.append('img-h', blob);
        $.ajax({
          type: "POST",
          enctype: 'multipart/form-data',
          url: "upload.php",
          data: data,
          processData: false,
          contentType: false,
          success: function (data) {
            $("#response").html(data);
            console.log(data);
          },
          error: function (ev) {
            $("#response").text(ev.responseText);
            console.log(ev);
          }
        });
    });
  }
  CropUpload();
});