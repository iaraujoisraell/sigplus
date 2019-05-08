<style>
    div.panel,p.flip
{
    width: 70px;
    padding:5px;
    text-align:center;
    background:#e5eecc;
    border:solid 1px #c3c3c3;
    position:fixed;
    right:10px;
    bottom:-15px;
    z-index:1;
}
div.panel
{
    position:fixed;
    bottom: 29px;
    width: 200px;
    height: auto;
    display:none;
    text-align:left;
    z-index:fixed;
}
</style>    

<script>
$(".flip").mouseenter(function () {
    $(".flip").animate({ width: 200 }, "fast");
    $(".panel").delay(200).slideDown("fast");
});
$(".flip").mouseleave(function () {
    $(".panel").slideUp("fast");
    $(".flip").delay(200).animate({ width: 70 }, "fast");
});
</script>

<div class="panel">
    <p><b>Teste</b></p>
    <p>>teste1</p>
    <p>>teste2</p>
</div>
 
<p class="flip">Mensagens</p>
