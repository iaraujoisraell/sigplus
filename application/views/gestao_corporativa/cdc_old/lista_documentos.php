
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Gestão de Documentos</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url('admin'); ?>">Home</a></li>
              <li class="breadcrumb-item active">Documentos</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

<section class="content">
    <div class="container-fluid">
    <div class="row">
        <div class="col-12">
        <div class="card">
              <div class="card-header">
                <h3 class="card-title">Lista de Documentos</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="lista_documentos" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Categoria</th>
                    <th>Sequencial</th>
                    <th>Código</th>
                    <th>Título</th>
                    <th>Versão</th>
                    <th>Documento</th>
                  </tr>
                  </thead>
                  <tbody>
                      
                  <?php foreach ($documentos as $documento) { ?>    
                  <tr>
                    <td><?php echo $documento['categoria'];?></td>
                    <td><?php echo $documento['sequencial'];?></td>
                    <td><?php echo $documento['codigo'];?></td>
                    <td><?php echo $documento['titulo'];?></td>
                    <td><?php echo $documento['numero_versao'];?></td>
                    <td><a class="btn btn-sm bg-info" href="<?php echo base_url();?>gestao_corporativa/intranet/visualizar_documento/<?php echo $documento['send_id'];?>">Abrir Documento</a></td>
                  </tr>
                  <?php } ?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Categoria</th>
                    <th>Sequencial</th>
                    <th>Código</th>
                    <th>Título</th>
                    <th>Versão</th>
                    <th>Documento</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
        </div>          
    </div>           
    </div>    
</section>


