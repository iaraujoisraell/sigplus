<div class="row" id="trocar">
                                <?php foreach ($departmentos as $dep): ?>
                                    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                        <div class="card bg-light d-flex flex-fill">
                                            <div class="card-body pt-0">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="lead"><b><?php echo $dep['name']; ?></b></h2>
                                                    </div>
                                                    <div class="col-5 text-center">
                                                        <img src="../../dist/img/user1-128x128.jpg" alt="user-avatar" class="img-circle img-fluid">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="text-right">
                                                    <a href="#" class="btn btn-sm bg-teal">
                                                        <i class="fas fa-comments"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-user"></i> Ver Perfil
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>