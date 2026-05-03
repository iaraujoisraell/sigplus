<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php /** Header parcial: usa $title; chama no início de cada view */ ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? $title . ' · ' : ''; ?>Painel SaaS — sigplus</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/lte/plugins/fontawesome-free/css/all.min.css">
    <style>
        *{box-sizing:border-box;}
        body{margin:0;font-family:'Inter','Segoe UI',sans-serif;background:#f1f5f9;color:#1f2937;font-size:14px;}
        a{color:#0a66c2;text-decoration:none;}
        a:hover{text-decoration:underline;}
        .saas-topbar{background:linear-gradient(135deg,#0f172a,#1e293b);color:#fff;padding:12px 24px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 3px rgba(0,0,0,.2);position:sticky;top:0;z-index:100;}
        .saas-brand{display:flex;align-items:center;gap:10px;font-weight:700;font-size:16px;}
        .saas-brand i{color:#38bdf8;}
        .saas-user{display:flex;align-items:center;gap:14px;font-size:13px;}
        .saas-user a{color:#cbd5e1;}
        .saas-user a:hover{color:#fff;}
        .saas-page{max-width:1300px;margin:24px auto;padding:0 24px;}
        .saas-h{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:10px;}
        .saas-h h1{margin:0;font-size:22px;color:#0f172a;}
        .saas-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
        .saas-card-h{padding:14px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#0f172a;font-size:14px;display:flex;justify-content:space-between;align-items:center;}
        .saas-card-b{padding:18px;}

        .saas-btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;border:1px solid transparent;text-decoration:none;line-height:1.4;}
        .saas-btn:hover{text-decoration:none;}
        .saas-btn-primary{background:#0a66c2;color:#fff;}
        .saas-btn-primary:hover{background:#084b8e;color:#fff;}
        .saas-btn-default{background:#fff;color:#475569;border-color:#cbd5e1;}
        .saas-btn-default:hover{background:#f8fafc;color:#0f172a;}
        .saas-btn-success{background:#16a34a;color:#fff;}
        .saas-btn-warn{background:#f59e0b;color:#fff;}
        .saas-btn-danger{background:#dc2626;color:#fff;}
        .saas-btn-sm{font-size:11px;padding:4px 10px;}

        .saas-table{width:100%;border-collapse:collapse;font-size:13px;}
        .saas-table th{background:#f8fafc;padding:10px 14px;text-align:left;font-size:11px;text-transform:uppercase;color:#475569;font-weight:700;letter-spacing:.04em;border-bottom:1px solid #e5e7eb;}
        .saas-table td{padding:10px 14px;border-bottom:1px solid #f1f5f9;}
        .saas-table tr:hover{background:#f8fafc;}

        .saas-form .row{display:grid;gap:14px;margin-bottom:14px;}
        .saas-form .row.cols-2{grid-template-columns:1fr 1fr;}
        .saas-form .row.cols-3{grid-template-columns:1fr 1fr 1fr;}
        .saas-form .row.cols-4{grid-template-columns:1fr 1fr 1fr 1fr;}
        @media(max-width:768px){.saas-form .row{grid-template-columns:1fr !important;}}
        .saas-form label{font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:4px;}
        .saas-form input,.saas-form select,.saas-form textarea{width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:8px 10px;font-size:13px;font-family:inherit;}
        .saas-form input:focus,.saas-form select:focus,.saas-form textarea:focus{outline:none;border-color:#0a66c2;box-shadow:0 0 0 2px rgba(10,102,194,.15);}
        .saas-form .footer{display:flex;justify-content:flex-end;gap:8px;margin-top:18px;padding-top:14px;border-top:1px solid #f1f5f9;}

        .pill{display:inline-block;padding:2px 8px;border-radius:6px;font-size:10px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;}
        .pill.ativo{background:#dcfce7;color:#166534;}
        .pill.inativo{background:#fee2e2;color:#991b1b;}
        .pill.admin{background:#dbeafe;color:#1e40af;}

        .alert{padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:13px;border-left:3px solid;}
        .alert.ok{background:#f0fdf4;color:#14532d;border-color:#16a34a;}
        .alert.warn{background:#fffbeb;color:#78350f;border-color:#f59e0b;}
        .alert.error{background:#fef2f2;color:#7f1d1d;border-color:#dc2626;}

        .empty{text-align:center;padding:50px;color:#94a3b8;}
        .empty i{font-size:36px;color:#cbd5e1;display:block;margin-bottom:8px;}
    </style>
</head>
<body>
<?php $impersonating = $this->session->userdata('saas_impersonating'); ?>
<div class="saas-topbar">
    <a href="<?php echo base_url('painel'); ?>" class="saas-brand" style="color:#fff;">
        <i class="fa fa-cubes"></i> Painel SaaS — sigplus
    </a>
    <div class="saas-user">
        <i class="fa fa-user-shield"></i>
        <span><?php echo html_escape($this->session->userdata('saas_admin_nome') ?? '—'); ?></span>
        <a href="<?php echo base_url('painel/logout'); ?>" title="Sair"><i class="fa fa-sign-out-alt"></i> Sair</a>
    </div>
</div>
