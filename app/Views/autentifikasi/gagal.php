<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Gagal Aktifasi Akun </title>

    <link href="<?= base_url('assets'); ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?= base_url('assets'); ?>/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <!-- page wrapper -->
    <div id="wrapper">
        <!-- content wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- main content -->
            <div id="content">
                <!-- begin page content -->
                <div class="container-fluid mt-5">
                    <!-- 404 error text -->
                    <div class="text-center"> <?= session()->getFlashdata('pesan'); ?> 
                    <a href="<?= base_url('autentifikasi'); ?>" class="btn btn-secondar y ">&larr; Close </a> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- scroll to top button -->
    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <!-- Bootstrap core JavaScript --> 
    <script src="<?= base_url('assets'); ?>/vendor/jquery/jquery.min .js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/bootstrap/js/bootstrap.bundle.min.j s"></script>

    <!-- Core plugin JavaScript --> 
    <script src="<?= base_url('assets'); ?>/vendor/jquery-easing/jquery.easing.min.j s"></script>

    <!-- Custom scripts for all pages--> 
    <script src="<?= base_url('assets'); ?>/js/sb-admin-2.min.js"></script> 
    <script> $('.alert-message').alert().delay(3500).slideUp('slow'); </script>

</body>
</html>