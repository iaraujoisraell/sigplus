<?php $this->load->model('Link_model');
$links = $this->Link_model->get_link_department_for_user_id();
foreach($links as $link){?>
<a class="btn btn-app" style="background-color: <?php echo $link['color'];?>; !important; color: white;" target="_blank" href="<?php echo $link['url'];?>">
<span class="badge bg-secondary"><?php echo $link['titulo'];?></span>
<i class="<?php echo $link['icon'];?>"></i> <?php echo $link['nome'];?>
</a>
<?php }
?>