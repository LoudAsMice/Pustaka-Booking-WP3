<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pustaka Booking | <?= $title; ?></title>
    <link rel="icon" type="image" href="<?= base_url('assets/img/logo'); ?>/logo-pb.jpg">
    <link rel="stylesheet" href="<?= base_url('assets/'); ?>/user/css/bootstrap.css">
    <link href="<?= base_url('assets'); ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets'); ?>/vendor/datatables/datatables.bootstrap4.css" rel="stylesheet" type="text/css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url(); ?>">Pustaka</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link active" href="<?= base_url(); ?>">Beranda <span class="sr-only">(current)</span></a>
                <?php
                if (!empty(session('email'))) { ?>
                <a class="nav-item nav-link" href="<?= base_url('booking');?>">Booking <?php $db = \Config\Database::connect(); echo $db->table('temp')->where(['email_user' => session('email')])->countAllResults();?> Buku</a>
                <a class="nav-item nav-link" href="<?= base_url('member/myprofile'); ?>">Profil Saya</a>
                <a class="nav-item nav-link" href="<?= base_url('member/logout'); ?>"><i class="fas fw fa-login"></i> Log out</a>
                <?php } else { ?>
                <a class="nav-item nav-link" data-toggle="modal" data-target="#daftarModal" href="#"><i class="fas fw fa-login"></i> Daftar</a>
                <a class="nav-item nav-link" data-toggle="modal" data-target="#loginModal" href="#"><i class="fas fw fa-login"></i> Log in</a>
                <?php } ?>
                <span class="nav-item nav-link nav-right" style="display:block; margin-left:20px;">Selamat Datang <b><?= $user; ?></b></span>
            </div>
        </div>
    </div>
</nav>
<div class="container mt-5">