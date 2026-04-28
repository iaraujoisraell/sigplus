
<?php $this->load->model('Link_destaque_model');
$links_destaque = $this->Link_destaque_model->get_link_department_for_user_id();
foreach($links_destaque as $link){
?>
<div class="small-box " style="background-color: <?php echo $link['color'];?>; !important;  color: white;">
    <div class="inner">
        <h5><?php echo $link['nome'];?></h5>
        <h10><?php echo $link['titulo'];?></h10>
    </div>
    <div class="icon">
        <i class="<?php echo $link['icon'];?>"></i>
    </div>
    <a href="<?php echo $link['url'];?>" target="_blank"  class="small-box-footer">Clique para acessar<i class="fas fa-arrow-circle-right" style="margin-left: 10px;"></i></a>
</div>
<?php }?>