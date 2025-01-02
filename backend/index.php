<?php
session_start();
date_default_timezone_set("Asia/Phnom_Penh");
include_once '../frontend/database/config_db.php';

if (!isset($_SESSION['user_login'])) {
    header("location:login.php");
}


if (isset($_COOKIE['user_login'])) {
    $_SESSION['user_login'] = $_COOKIE['user_login'];

}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../backend/pages/layout/head.php'; ?>

</head>

<body>
    <div class="main-wrapper">
        <div class="header">
            <?php include '../backend/pages/layout/header.php'; ?>
        </div>
        <div class="sidebar" id="sidebar">

            <?php
            if (isset($_GET['setting'])) {
                include("../backend/pages/layout/left_side_bar_setting.php");
            } else {
                include '../backend/pages/layout/sidebar.php';
            }

            ?>
        </div>
        <div class="page-wrapper">

            <div class="content">


                <?php

                if (isset($_GET['product'])) {
                    include("pages/product/" . $_GET['product'] . '.php');
                } elseif (isset($_GET['setting'])) {
                    include("pages/setting/" . $_GET['setting'] . '.php');
                } else {
                    require_once("pages/dashboard/dashboard.php");
                }

                ?>

            </div>
            <div class="notification-box">

                <?php include '../backend/pages/alert/message.php'; ?>

            </div>
        </div>
    </div>
    <div class="sidebar-overlay" data-reff=""></div>
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.slimscroll.js"></script>
    <script src="assets/js/Chart.bundle.js"></script>
    <script src="assets/js/chart.js"></script>

    <script src="assets/js/select2.min.js"></script>
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>

    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap4.min.js"></script>

    <script src="assets/js/app.js"></script>

    <script>

        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function (event) {
                if (!confirm('Are you sure to delete it?')) {
                    event.preventDefault();
                }
            });
        });
    </script>

</body>

</html>