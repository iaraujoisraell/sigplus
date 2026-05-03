<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
    body {
        background: #f3f2ef;
        font-family: 'Segoe UI', Arial, sans-serif;
        color: #1d2226;
    }

    #wrapper {
        background: #f3f2ef;
    }

    .page-intranet {
        max-width: 1280px;
        margin: 0 auto;
        padding: 18px 18px 30px;
    }

    

    /* =========================
       GRID CENTRALIZADO
    ========================== */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 260px minmax(0, 1fr) 300px;
        gap: 22px;
        align-items: start;
    }

    /* =========================
       CARDS BASE
    ========================== */
    .ui-card {
        background: #fff;
        border: 1px solid #e0dfdc;
        border-radius: 12px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }

    /* =========================
       LATERAL ESQUERDA
    ========================== */
    .profile-card {
        overflow: hidden;
        text-align: center;
        margin-bottom: 14px;
    }

    .profile-cover {
        height: 72px;
        background: linear-gradient(135deg, #0a66c2 0%, #378fe9 60%, #70b5f9 100%);
        position: relative;
    }

    .profile-avatar-wrap {
        margin-top: -38px;
        position: relative;
        z-index: 2;
    }

    .profile-avatar {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        border: 3px solid #fff;
        object-fit: cover;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .profile-body {
        padding: 0 18px 18px;
    }

    .profile-name {
        font-size: 22px;
        font-weight: 700;
        color: #1d2226;
        margin-top: 12px;
        line-height: 1.2;
    }

    .profile-role {
        font-size: 14px;
        color: #5e5e5e;
        margin-top: 8px;
        line-height: 1.4;
    }

    .profile-location {
        font-size: 13px;
        color: #777;
        margin-top: 6px;
    }

    .profile-company {
        margin-top: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 14px;
        color: #1d2226;
        font-weight: 600;
    }

    .mini-card {
        padding: 16px 18px;
        margin-bottom: 14px;
    }

    .mini-card-title {
        font-size: 14px;
        font-weight: 600;
        color: #666;
        line-height: 1.45;
    }

    .mini-card-strong {
        margin-top: 8px;
        font-size: 16px;
        font-weight: 700;
        color: #1d2226;
    }

    .profile-analytics .line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        font-size: 14px;
    }

    .profile-analytics .line strong {
        color: #0a66c2;
        font-size: 18px;
    }

    .profile-menu a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
        text-decoration: none !important;
        color: #1d2226;
        font-size: 15px;
        font-weight: 600;
        border-bottom: 1px solid #f0f0f0;
        transition: .2s;
    }

    .profile-menu a:last-child {
        border-bottom: 0;
    }

    .profile-menu a:hover {
        color: #0a66c2;
        transform: translateX(2px);
    }

    .profile-menu i {
        width: 18px;
        text-align: center;
        color: #444;
    }

    /* =========================
       FEED
    ========================== */
    .feed-compose {
        padding: 16px;
        margin-bottom: 16px;
    }

    .feed-compose-top {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .feed-compose-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        object-fit: cover;
    }

    .feed-compose-input {
        flex: 1;
        height: 50px;
        border: 1px solid #cfd6dc;
        border-radius: 999px;
        background: #fff;
        display: flex;
        align-items: center;
        padding: 0 18px;
        color: #666;
        font-size: 17px;
        cursor: pointer;
        transition: .2s;
    }

    .feed-compose-input:hover {
        background: #f9f9f9;
    }

    .feed-compose-actions {
        display: flex;
        justify-content: space-around;
        gap: 8px;
        margin-top: 14px;
    }

    .feed-compose-action {
        flex: 1;
        height: 44px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: #5e5e5e;
        font-size: 15px;
        font-weight: 600;
        transition: .2s;
        cursor: pointer;
    }

    .feed-compose-action:hover {
        background: #f3f3f3;
    }

    .feed-compose-action i {
        font-size: 18px;
    }

    .feed-sort {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 8px 0 14px;
        color: #666;
        font-size: 13px;
    }

    .feed-sort .line {
        flex: 1;
        height: 1px;
        background: #d8d8d8;
    }

    .feed-post {
        margin-bottom: 16px;
        overflow: hidden;
    }

    .feed-post-header {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 18px 10px;
    }

    .feed-post-user {
        display: flex;
        gap: 12px;
        min-width: 0;
    }

    .feed-post-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }

    .feed-post-meta {
        min-width: 0;
    }

    .feed-post-name {
        font-size: 18px;
        font-weight: 700;
        color: #1d2226;
        line-height: 1.15;
    }

    .feed-post-role {
        font-size: 13px;
        color: #666;
        margin-top: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .feed-post-date {
        font-size: 13px;
        color: #666;
        margin-top: 3px;
    }

    .feed-post-header-right {
        display: flex;
        align-items: center;
        gap: 18px;
        color: #666;
        font-size: 22px;
    }

    .feed-post-content {
        padding: 0 18px 14px;
        font-size: 17px;
        line-height: 1.55;
        color: #1d2226;
    }

    .feed-post-content a {
        color: #0a66c2;
        font-weight: 600;
        text-decoration: none;
    }

    .feed-post-image {
        width: 100%;
        display: block;
        border-top: 1px solid #f0f0f0;
        border-bottom: 1px solid #f0f0f0;
    }

    .feed-post-stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 18px;
        font-size: 14px;
        color: #666;
        border-bottom: 1px solid #ededed;
    }

    .feed-post-actions {
        display: flex;
        align-items: center;
        justify-content: space-around;
        padding: 6px 10px;
    }

    .feed-post-action {
        flex: 1;
        height: 46px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: #5e5e5e;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s;
    }

    .feed-post-action:hover {
        background: #f3f3f3;
    }

    /* =========================
       DIREITA
    ========================== */
    .right-link-card {
        padding: 18px;
        border-radius: 12px;
        color: #fff;
        margin-bottom: 14px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .right-link-card h4 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
    }

    .right-link-card p {
        margin: 8px 0 0;
        font-size: 14px;
        opacity: .95;
    }

    .right-link-green { background: linear-gradient(135deg,#1a8754,#1f9d64); }
    .right-link-blue  { background: linear-gradient(135deg,#0a66c2,#2375d8); }

    .news-card {
        padding: 18px;
    }

    .news-card h4 {
        margin: 0 0 14px;
        font-size: 17px;
        font-weight: 700;
    }

    .news-item {
        margin-bottom: 16px;
    }

    .news-item:last-child {
        margin-bottom: 0;
    }

    .news-item strong {
        display: block;
        font-size: 14px;
        color: #1d2226;
        line-height: 1.35;
    }

    .news-item span {
        display: block;
        margin-top: 4px;
        color: #777;
        font-size: 12px;
    }

    /* =========================
       RESPONSIVO
    ========================== */
    @media (max-width: 1200px) {
        .dashboard-grid {
            grid-template-columns: 240px minmax(0, 1fr) 280px;
        }

        .intra-search {
            width: 250px;
        }
    }

    @media (max-width: 992px) {
        .topbar-wrap {
            flex-wrap: wrap;
            height: auto !important;
            padding-top: 10px !important;
            padding-bottom: 10px !important;
        }

        .topbar-left,
        .topbar-center,
        .topbar-right {
            width: 100%;
        }

        .topbar-center {
            flex-direction: column;
            align-items: stretch;
        }

        .intra-search {
            width: 100%;
        }

        .intra-nav-menu {
            justify-content: space-between;
            overflow-x: auto;
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .page-intranet {
            padding: 10px;
        }

        .intra-user-info,
        .intra-brand-subtitle {
            display: none;
        }

        .intra-nav-item {
            min-width: 74px;
        }

        .feed-post-name {
            font-size: 16px;
        }

        .feed-post-content {
            font-size: 15px;
        }
    }

    .intra-topbar {
    position: sticky;
    top: 0;
    z-index: 1050;
    background: #ffffff;
    border-bottom: 1px solid #e0e0e0;
    box-shadow: 0 1px 6px rgba(0,0,0,0.06);
    }

    .intra-topbar .topbar-wrap {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 18px;
        height: 74px;
        display: grid;
        grid-template-columns: 220px 1fr 260px;
        align-items: center;
        gap: 10px;
    }

    .topbar-left {
        display: flex;
        align-items: center;
    }

    .topbar-center {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .topbar-center-full {
        width: 100%;
    }

    .topbar-right {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
    }

    .intra-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none !important;
    }

    .intra-brand-logo {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #0a66c2;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 20px;
        font-weight: 700;
        box-shadow: 0 4px 14px rgba(10,102,194,0.22);
    }

    .intra-brand-text {
        line-height: 1.05;
    }

    .intra-brand-title {
        font-size: 15px;
        font-weight: 700;
        color: #1d2226;
    }

    .intra-brand-subtitle {
        font-size: 11px;
        color: #666;
        margin-top: 3px;
    }

    .intra-nav-menu-modules {
        display: flex;
        align-items: stretch;
        justify-content: center;
        gap: 0;
        width: 100%;
    }

    .intra-nav-item {
        flex: 1;
        max-width: 120px;
        min-width: 92px;
        height: 74px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-decoration: none !important;
        color: #5f6368;
        position: relative;
        border-bottom: 3px solid transparent;
        transition: all .2s ease;
    }

    .intra-nav-item:hover {
        color: #0a66c2;
        background: rgba(10,102,194,0.04);
    }

    .intra-nav-item.active {
        color: #0a66c2;
        border-bottom-color: #0a66c2;
    }

    .intra-nav-item i {
        font-size: 23px;
        margin-bottom: 7px;
        line-height: 1;
    }

    .intra-nav-item span {
        font-size: 13px;
        font-weight: 700;
        line-height: 1;
        text-align: center;
    }

    .intra-badge {
        position: absolute;
        top: 10px;
        right: 22px;
        min-width: 19px;
        height: 19px;
        padding: 0 5px;
        border-radius: 999px;
        background: #cc1016;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(204,16,22,0.18);
    }

    .intra-icon-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 1px solid #e3e3e3;
        background: #fff;
        color: #526a7a;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none !important;
        transition: .2s;
    }

    .intra-icon-btn:hover {
        color: #0a66c2;
        border-color: #c7d7ea;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }

    .intra-user {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none !important;
        padding-left: 12px;
        border-left: 1px solid #ececec;
    }

    .intra-user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .intra-user-info {
        line-height: 1.05;
    }

    .intra-user-name {
        font-size: 13px;
        font-weight: 700;
        color: #1d2226;
    }

    .intra-user-role {
        font-size: 11px;
        color: #666;
        margin-top: 3px;
    }

    .intra-user-chevron {
        color: #666;
        font-size: 12px;
    }

    @media (max-width: 1100px) {
        .intra-topbar .topbar-wrap {
            grid-template-columns: 190px 1fr 220px;
        }

        .intra-nav-item {
            min-width: 80px;
            max-width: 100px;
        }

        .intra-nav-item i {
            font-size: 21px;
        }

        .intra-nav-item span {
            font-size: 12px;
        }
    }

    @media (max-width: 992px) {
        .intra-topbar .topbar-wrap {
            display: flex;
            flex-wrap: wrap;
            height: auto;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .topbar-left,
        .topbar-center,
        .topbar-right {
            width: 100%;
        }

        .topbar-center {
            order: 2;
        }

        .topbar-right {
            order: 3;
            justify-content: space-between;
            margin-top: 8px;
        }

        .intra-nav-menu-modules {
            overflow-x: auto;
            justify-content: flex-start;
        }

        .intra-nav-item {
            flex: 0 0 auto;
            min-width: 95px;
        }
    }

    @media (max-width: 576px) {
        .intra-brand-subtitle,
        .intra-user-info {
            display: none;
        }

        .intra-user {
            border-left: 0;
            padding-left: 0;
        }
    }

    .intra-nav-item {
        flex: 1;
        max-width: 120px;
        min-width: 92px;
        height: 74px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-decoration: none !important;
        color: #5f6368;
        position: relative;
        border-bottom: 3px solid transparent;
        transition: all .2s ease;
    }

    .intra-nav-item:hover {
        color: #0a66c2;
        background: rgba(10,102,194,0.04);
    }

    .intra-nav-item.active {
        color: #0a66c2;
        border-bottom-color: #0a66c2;
    }

    .intra-nav-item span:last-child {
        font-size: 13px;
        font-weight: 700;
        line-height: 1.1;
        text-align: center;
    }

    .fake-icon {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 7px;
        color: inherit;
    }

    .fake-icon svg {
        width: 28px;
        height: 28px;
        display: block;
    }

    .intra-badge {
        position: absolute;
        top: 10px;
        right: 22px;
        min-width: 19px;
        height: 19px;
        padding: 0 5px;
        border-radius: 999px;
        background: #cc1016;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(204,16,22,0.18);
    }

    .profile-links-card{
        padding: 14px 0 8px;
    }

    .card-mini-title{
        font-size: 14px;
        font-weight: 700;
        color: #44546a;
        padding: 0 16px 10px;
        border-bottom: 1px solid #eceff3;
        margin-bottom: 4px;
    }

    .profile-links-list{
        display: flex;
        flex-direction: column;
    }

    .profile-link-group{
        padding: 8px 0;
        border-bottom: 1px solid #f1f3f6;
    }

    .profile-link-group:last-child{
        border-bottom: none;
    }

    .profile-link-group-title{
        padding: 0 16px 6px;
        font-size: 13px;
        font-weight: 700;
        color: #2f3b4a;
    }

    .profile-sub-link{
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 8px 16px;
        text-decoration: none !important;
        color: #5f6b7a !important;
        transition: .18s ease;
    }

    .profile-sub-link:hover{
        background: #f6f8fb;
        color: #334155 !important;
    }

    .profile-sub-link-left{
        display: inline-flex;
        align-items: center;
        gap: 9px;
        min-width: 0;
    }

    .profile-sub-link i{
        width: 14px;
        text-align: center;
        color: #6b7280;
        flex-shrink: 0;
    }

    .birthdays-card{
        padding: 0;
        overflow: hidden;
    }

    .birthdays-header{
        display:flex;
        align-items:center;
        justify-content:space-between;
        padding:14px 18px;
        border-bottom:1px solid #e9edf2;
    }

    .birthdays-header h4{
        margin:0;
        font-size:15px;
        font-weight:700;
        color:#1f2937;
    }

    .birthdays-badge{
        min-width:22px;
        height:22px;
        border-radius:6px;
        background:#f4b400;
        color:#111827;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        font-size:12px;
        font-weight:700;
        padding:0 6px;
        line-height:1;
    }

    .birthdays-body{
        padding:14px 12px 16px;
    }

    .birthdays-grid{
        display:grid;
        grid-template-columns:repeat(4, minmax(0, 1fr));
        gap:12px;
    }

    .birthday-item{
        display:flex;
        flex-direction:column;
        align-items:center;
        text-align:center;
        padding:6px 4px;
    }

    .birthday-avatar{
        width:64px;
        height:64px;
        border-radius:50%;
        object-fit:cover;
        margin-bottom:8px;
        border:2px solid #eef2f7;
        background:#f8fafc;
    }

    .birthday-name{
        font-size:12px;
        font-weight:500;
        color:#667085;
        line-height:1.35;
        text-transform:uppercase;
        min-height:32px;
    }

    .birthday-date{
        margin-top:3px;
        font-size:12px;
        color:#64748b;
        line-height:1.2;
    }

    .birthdays-empty{
        font-size:13px;
        color:#64748b;
        padding:4px 2px;
    }

    @media (max-width: 991px){
        .birthdays-grid{
            grid-template-columns:repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 480px){
        .birthdays-grid{
            grid-template-columns:1fr;
        }
    }


    .right-link-card-dynamic{
        position: relative;
        border-radius: 16px;
        padding: 18px 18px;
        color: #fff;
        margin-bottom: 14px;
        overflow: hidden;
        cursor: pointer;
        transition: .25s ease;
    }

    .right-link-card-dynamic:hover{
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,.12);
    }

    .right-link-content h4{
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }

    .right-link-content p{
        margin-top: 6px;
        font-size: 13px;
        opacity: .95;
    }

    .right-link-icon{
        position: absolute;
        right: 14px;
        bottom: 10px;
        font-size: 32px;
        opacity: .15;
    }

    .right-link-overlay{
        position:absolute;
        inset:0;
        z-index:2;
    }

    .quick-access-card .mini-card-title{
        margin-bottom: 0;
    }

    .quick-access-grid{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:12px;
    }

    .quick-access-grid{
        display:flex !important;
        flex-direction:column;
        grid-template-columns:none !important;
        gap:0 !important;
    }

    .quick-access-item{
        position:relative;
        text-decoration:none !important;
        color:#64748b !important;
        background:transparent;
        border:0;
        display:flex;
        flex-direction:row !important;
        align-items:center;
        justify-content:flex-start;
        gap:8px;
        padding:5px 4px;
        transition:.12s ease;
        border-bottom:0;
        min-height:auto;
        border-radius:4px;
    }

    .quick-access-item:hover{
        color:var(--qa-color, #0a66c2) !important;
        background:transparent;
        transform:none;
        box-shadow:none;
    }
    .quick-access-item:hover .quick-access-name{color:var(--qa-color, #0a66c2);}

    .quick-access-item i{
        font-size:11px;
        line-height:1;
        margin-bottom:0;
        width:14px;
        text-align:center;
        color:var(--qa-color, #cbd5e1);
        flex-shrink:0;
    }

    .quick-access-name{
        font-size:12px;
        font-weight:400;
        line-height:1.2;
        text-align:left;
        color:#64748b;
        text-transform:none;
    }

    .quick-access-badge{
        position:static;
        margin-left:auto;
        background:#e5e7eb;
        color:#475569;
        border-radius:4px;
        padding:1px 6px;
        font-size:9px;
        font-weight:600;
        line-height:1.4;
        z-index:2;
    }

    @media (max-width: 480px){
        .quick-access-grid{
            grid-template-columns:1fr 1fr;
        }

        .quick-access-item{
            min-height:60px;
            padding:10px 8px;
        }

        .quick-access-item i{
            font-size:18px;
            margin-bottom:6px;
        }

        .quick-access-name{
            font-size:11px;
        }
    }

    /* =========================
    RESPONSIVO MOBILE SIGPLUS
    ========================= */

    @media (max-width: 768px){

        body{
            overflow-x: hidden;
        }

        .page-intranet,
        .container,
        .container-fluid,
        #wrapper{
            max-width: 100%;
            overflow-x: hidden;
        }

        /* TOPO */
        .sig-topbar-inner{
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
            padding: 10px 12px 12px;
            min-height: auto;
        }

        .sig-brand{
            width: 100%;
            justify-content: flex-start;
        }

        .sig-brand-logo{
            width: 46px;
            height: 46px;
            border-radius: 12px;
        }

        .sig-brand-title{
            font-size: 18px;
            line-height: 1.1;
        }

        .sig-brand-subtitle{
            font-size: 12px;
        }

        /* MÓDULOS NO TOPO */
        .sig-modulebar-inline{
            width: 100%;
            display: grid !important;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
            justify-content: initial;
            align-items: stretch;
            flex: unset;
        }

        .sig-module-inline{
            min-width: 0;
            width: 100%;
            padding: 8px 4px;
            border-radius: 10px;
            gap: 5px;
        }

        .sig-module-inline i{
            font-size: 16px;
        }

        .sig-module-inline span{
            font-size: 11px;
            line-height: 1.15;
            text-align: center;
        }

        /* ÍCONES + USUÁRIO */
        .sig-top-actions{
            width: 100%;
            justify-content: flex-start;
            flex-wrap: nowrap;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 2px;
        }

        .sig-top-actions::-webkit-scrollbar{
            height: 4px;
        }

        .sig-icon-btn{
            width: 40px;
            height: 40px;
            flex: 0 0 auto;
        }

        .sig-user-box{
            margin-left: 2px;
            padding-left: 10px;
            flex: 0 0 auto;
        }

        .sig-user-box img{
            width: 34px;
            height: 34px;
        }

        .sig-user-text{
            display: none !important;
        }

        .sig-user-menu{
            right: 0;
            left: auto;
            min-width: 220px;
            max-width: calc(100vw - 24px);
        }

        /* SE EXISTIR A BARRA DE MÓDULOS ANTIGA */
        .sig-modulebar{
            padding: 0;
        }

        .sig-modulebar-inner{
            padding: 10px 12px;
            justify-content: flex-start;
            flex-wrap: nowrap;
            overflow-x: auto;
            gap: 10px;
        }

        .sig-module{
            width: 92px;
            height: 62px;
            flex: 0 0 auto;
            border-radius: 12px;
        }

        .sig-module-title{
            font-size: 10px;
        }

        /* COLUNAS / CARDS */
        .row{
            margin-left: -6px;
            margin-right: -6px;
        }

        .row > [class*="col-"]{
            padding-left: 6px;
            padding-right: 6px;
        }

        .ui-card,
        .news-card,
        .mini-card,
        .birthdays-card{
            border-radius: 12px;
            margin-bottom: 12px;
        }

        /* CARD PERFIL */
        .profile-card,
        .profile-hero-card,
        .profile-summary-card{
            overflow: hidden;
        }

        .profile-cover,
        .profile-banner,
        .hero-banner{
            height: 66px !important;
        }

        .profile-avatar,
        .hero-avatar,
        .profile-picture-large{
            width: 72px !important;
            height: 72px !important;
            margin-top: -36px !important;
            border-width: 3px !important;
        }

        .profile-main,
        .profile-body,
        .profile-content{
            padding: 12px 14px 14px !important;
            text-align: center;
        }

        .profile-main h2,
        .profile-main h3,
        .profile-name{
            font-size: 17px !important;
            line-height: 1.2;
            margin-bottom: 6px !important;
        }

        .profile-role,
        .profile-description,
        .profile-location,
        .profile-company{
            font-size: 13px !important;
            line-height: 1.35;
        }

        /* LINKS DE DESTAQUE DA DIREITA */
        .right-link-card-dynamic,
        .right-link-card{
            border-radius: 14px;
            padding: 16px 16px;
            margin-bottom: 12px;
        }

        .right-link-content h4,
        .right-link-card h4{
            font-size: 15px;
        }

        .right-link-content p,
        .right-link-card p{
            font-size: 12px;
        }

        .right-link-icon{
            font-size: 26px;
            right: 12px;
            bottom: 8px;
        }

        /* GRID DE ATALHOS INTERNOS */
        .quick-access-grid{
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 10px;
        }

        .quick-access-item{
            min-height: 72px;
            padding: 12px 8px 10px;
            border-radius: 0;
        }

        .quick-access-item i{
            font-size: 18px;
            margin-bottom: 7px;
        }

        .quick-access-name{
            font-size: 10px;
            line-height: 1.15;
        }

        .quick-access-badge{
            font-size: 9px;
            padding: 2px 6px;
            top: -3px;
            right: -3px;
        }

        /* CARD INSTITUCIONAL / MENU LATERAL */
        .profile-links-card{
            padding-top: 12px;
        }

        .card-mini-title,
        .mini-card-title{
            font-size: 14px;
            padding-left: 14px;
            padding-right: 14px;
        }

        .profile-link-group-title{
            font-size: 13px;
            padding-left: 14px;
            padding-right: 14px;
        }

        .profile-sub-link{
            padding: 9px 14px;
            font-size: 13px;
        }

        /* ANIVERSARIANTES */
        .birthdays-header{
            padding: 12px 14px;
        }

        .birthdays-header h4{
            font-size: 14px;
        }

        .birthdays-body{
            padding: 12px 10px 14px;
        }

        .birthdays-grid{
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .birthday-avatar{
            width: 58px;
            height: 58px;
        }

        .birthday-name{
            font-size: 11px;
            min-height: 28px;
        }

        .birthday-date{
            font-size: 11px;
        }
    }

    /* CELULARES MENORES */
    @media (max-width: 480px){

        .sig-topbar-inner{
            padding: 10px 10px 12px;
        }

        .sig-modulebar-inline{
            gap: 8px;
        }

        .sig-module-inline{
            padding: 8px 2px;
        }

        .sig-module-inline span{
            font-size: 10px;
        }

        .quick-access-grid{
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 8px;
        }

        .quick-access-item{
            min-height: 68px;
            padding: 10px 6px;
        }

        .quick-access-name{
            font-size: 9px;
        }

        .birthdays-grid{
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .right-link-card-dynamic{
            padding: 14px 14px;
        }
    }

    @media (max-width: 768px){
        .intranet-layout,
        .page-layout,
        .content-grid{
            display: block !important;
        }

        .left-column,
        .center-column,
        .right-column,
        .sidebar-left,
        .sidebar-right,
        .feed-column{
            width: 100% !important;
            max-width: 100% !important;
            flex: 0 0 100% !important;
            margin-bottom: 12px;
        }
    }

    @media (max-width: 768px){
        .sig-topbar{
            padding-bottom: 6px;
        }

        .sig-modulebar-inline{
            margin-top: 2px;
        }

        .sig-module-inline{
            background: transparent;
        }

        .sig-module-inline:hover,
        .sig-module-inline.active{
            background: rgba(255,255,255,.10);
        }
    }

    .news-widget-card{
        padding: 0;
        overflow: hidden;
    }

    .news-widget-header{
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 16px 10px;
    }

    .news-widget-title-link{
        text-decoration: none !important;
    }

    .news-widget-title-link h4{
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
    }

    .news-widget-badge{
        min-width: 22px;
        height: 22px;
        border-radius: 6px;
        background: #f4b400;
        color: #111827;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        padding: 0 6px;
        line-height: 1;
    }

    .news-widget-divider{
        height: 1px;
        background: #ececec;
        margin: 0 16px;
    }

    .news-widget-body{
        padding: 12px 16px 16px;
    }

    .news-widget-item{
        display: flex;
        gap: 10px;
        text-decoration: none !important;
        color: inherit !important;
        padding: 10px 0;
        border-bottom: 1px solid #f1f3f5;
        transition: .18s ease;
    }

    .news-widget-item:last-child{
        border-bottom: none;
        padding-bottom: 0;
    }

    .news-widget-item:hover{
        opacity: .92;
    }

    .news-widget-thumb-wrap{
        width: 72px;
        height: 54px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
        background: #eef2f6;
    }

    .news-widget-thumb{
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .news-widget-content{
        min-width: 0;
        flex: 1;
    }

    .news-widget-item-title{
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.35;
        margin-bottom: 4px;
    }

    .news-widget-item-date{
        font-size: 12px;
        color: #6b7280;
    }

    .news-widget-empty{
        font-size: 15px;
        color: #777;
    }

    @media (max-width: 768px){
        .news-widget-header{
            padding: 14px 14px 10px;
        }

        .news-widget-divider{
            margin: 0 14px;
        }

        .news-widget-body{
            padding: 10px 14px 14px;
        }

        .news-widget-thumb-wrap{
            width: 64px;
            height: 48px;
        }

        .news-widget-item-title{
            font-size: 13px;
        }
    }
  
  .events-card {
        padding: 16px;
    }

    .events-card h4 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 12px;
        color: #0f172a;
    }

    .event-item {
        display: flex;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #f1f3f5;
    }

    .event-item:last-child {
        border-bottom: none;
    }

    .event-date {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        background: #eef2ff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .event-date .day {
        font-size: 16px;
        font-weight: 700;
        color: #1d4ed8;
        line-height: 1;
    }

    .event-date .month {
        font-size: 11px;
        color: #475569;
    }

    .event-content {
        flex: 1;
        min-width: 0;
    }

    .event-content strong {
        display: block;
        font-size: 14px;
        color: #1f2937;
        line-height: 1.4;
    }

    .event-content span {
        font-size: 12px;
        color: #6b7280;
    }
</style>
