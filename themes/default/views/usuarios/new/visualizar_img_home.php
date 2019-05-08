<?php

 $postagens = $this->projetos_model->getPostagemById($id);

?>

    <div class="modal-dialog">
        <div class="modal-content">
          
              
                         
                        <div class="col-md-12">
                            <img width="100%" height="100%" class="img-responsive pad" src="<?php echo base_url().'assets/uploads/projetos/'.$postagens->anexo; ?>" alt="Photo">
                            
                           
                        </div>
                

           

        </div>
    </div>
               
           
