
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plusgins_select/bootstrap-3.3.7/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plusgins_select/bootstrap-3.3.7/bootstrap-theme.min.css">
<script src="<?php echo base_url(); ?>assets/plusgins_select/bootstrap-3.3.7/bootstrap.min.js"></script>

<script src="<?php echo base_url(); ?>assets/plusgins_select/multselect/dist/jquery.tree-multiselect.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plusgins_select/multselect/dist/jquery.tree-multiselect.min.css">

<script>
$(document).ready(function () {
 
 $('.btn-excluir').click(function(){
 
 var codcipessoa = $(this).data('codcipessoa');
 
 if( confirm("Deseja realmente excluir esta pessoa?\n\nC�d. Pessoa: " + codcipessoa) ){

$.post("", {codcipessoa : codcipessoa, acao : "removerDestinatario"}, function(msg){

/*
var obj = jQuery.parseJSON(msg);

if(obj.status){
alert('Pessoa exclu�da com sucesso!');
}else{
alert('Erro ao excluir pessoa!');
}
*/

window.location = '';
})

 }
 
 })
 
 $('.checkAll').on('click', function () {
$(this).closest('table').find('tbody :checkbox')
 .prop('checked', this.checked)
 .closest('tr').toggleClass('selected', this.checked);
 });

 $('tbody :checkbox').on('click', function () {
$(this).closest('tr').toggleClass('selected', this.checked); //Classe de sele��o na row
 
$(this).closest('table').find('.checkAll').prop('checked', ($(this).closest('table').find('tbody :checkbox:checked').length == $(this).closest('table').find('tbody :checkbox').length)); //Tira / coloca a sele��o no .checkAll
 });
 
});  
</script>

<div class="content">
<div class="panel">

<?php //print_r($departments_staffs); exit;?>
<select name='for_staffs[]' id="matricula" multiple="multiple">
    
<?php

foreach($departments_staffs as $v){
    

//retiro os j� adicionados
//if( $pessoas[$v['matricula']] ) {
//continue;
//}

//$selected = ( array_key_exists($v['staffid'],$staffs) ? "selected" : "" );
    if($staffs){
    if((in_array($v['staffid'], $staffs) and $v['origem'] == $v['staffdepartmentid'])){
        $selected = 'selected';
    } else {
        $selected = '';
    }
    }

//?>
<option <?php echo $selected; ?> value="<?php echo ($v['staffid'].'-'. $v['staffdepartmentid']); ?>" data-section="<?php echo $v['name']; ?>" data-description="<?php echo $v['name']; ?>"><?php echo $v['firstname']; ?></option>
<?php
}
?>
</select>



 
</div>

</div>
   
<script type="text/javascript">
  var $select = $('#matricula');
 
  var time = new Date();
  console.profile('tree-multiselect');
  $select.treeMultiselect({
/*sectionDelimiter: '#',*/
allowBatchSelection: true,
enableSelectAll: false,
sortable: true,
searchable: true,
startCollapsed:true,
sortable:false,
selectAllText:'Todos',
unselectAllText:'Remover'
});
  console.profileEnd();
  //console.log("time elapsed - " + (new Date() - time) + "ms");
</script>