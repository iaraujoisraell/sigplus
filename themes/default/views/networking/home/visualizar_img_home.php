<?php

// $posts = $this->networking_model->getPostagemById($id);
 
 
$empresa =  $this->session->userdata('empresa');

$url_img_post =  "assets/uploads/$empresa/posts/$imagem";
?>

<div style="width: 800px;" class="modal-dialog">
        <div class="modal-content">
            <div class="col-md-12">
                <img width="100%" height="100%" class="img-responsive pad" src="<?php echo base_url().$url_img_post; ?>" alt="Photo">
            </div>
        </div>
    </div>
               
           
