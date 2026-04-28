<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Confirmação de Dados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow-lg p-4" style="max-width: 550px; width: 100%;">

            <h3 class="text-center mb-3">Confirmação de Cadastro</h3>
            <p class="text-center text-muted mb-4">
                Antes de acessar o sistema, confirme seu e-mail e telefone.
            </p>

            <!-- ALERTAS DIN�MICOS -->
            <?php if (!$email_confirmado): ?>
                <div class="alert alert-warning">
                    <strong>E-mail não confirmado!</strong> Enviamos um link de confirmação para:
                    <br>
                    <b><?= $email ?></b>
                </div>

                <div class="text-center mb-4">
                    <button id="btnReenviarEmail" class="btn btn-primary w-100">
                        Reenviar e-mail de confirmação
                    </button>
                </div>
            <?php endif; ?>

            <?php if (!$telefone_confirmado): ?>
                <div class="alert alert-warning">
                    <strong>Telefone não confirmado!</strong> Um código SMS será enviado para:
                    <br>
                    <b><?= $telefone ?></b>
                </div>

                <div class="input-group mb-3">

                    <form action="<?= base_url('portal/auth/validar_sms') ?>" method="POST">

                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
                            value="<?= $this->security->get_csrf_hash(); ?>" />
                        <div class="input-group mb-3">
                            <input type="text" name="codigo" class="form-control" placeholder="Digite o código" required>
                            <button type="submit" class="btn btn-success">Validar</button>
                        </div>
                    </form>


                    <!--
                    <input type="text" id="codigo_sms" class="form-control" placeholder="Digite o código recebido">
                    <button id="btnValidarSMS" class="btn btn-success">
                        Validar
                    </button>-->
                </div>

                <div class="text-center">
                    <button id="btnReenviarSMS" class="btn btn-outline-secondary w-100">
                        Reenviar SMS
                    </button>
                </div>
            <?php endif; ?>

            <?php if ($email_confirmado && $telefone_confirmado): ?>
                <div class="alert alert-success text-center">
                    Todos os dados foram confirmados! <br>
                    <a href="<?= base_url('dashboard') ?>" class="btn btn-success mt-3">Prosseguir</a>
                </div>
            <?php endif; ?>

        </div>
    </div>


    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById("btnReenviarEmail")?.addEventListener("click", function() {
            fetch("<?= base_url('auth/reenviar_email') ?>")
                .then(r => r.json())
                .then(res => alert(res.msg));
        });

        document.getElementById("btnReenviarSMS")?.addEventListener("click", function() {
            fetch("<?= base_url('auth/reenviar_sms') ?>")
                .then(r => r.json())
                .then(res => alert(res.msg));
        });

        document.getElementById("btnValidarSMS")?.addEventListener("click", function() {

            let codigo = document.getElementById("codigo_sms").value;

            if (codigo.trim() === "") {
                alert("Digite o código SMS!");
                return;
            }

            fetch("<?= base_url('portal/auth/validar_sms') ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "codigo=" + codigo
                })
                .then(r => r.json())
                .then(res => {
                    alert(res.msg);
                    if (res.success) {
                        location.reload();
                    }
                });
        });
    </script>

</body>

</html>