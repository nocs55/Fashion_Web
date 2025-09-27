<?php
session_start();
require_once('../db/config.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="style_admin.css">
      <!-- font-awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <!-- ck editor -->
    <!-- <script src="ckeditor/ckeditor.js"></script> -->
    <script src="//cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <!-- <script src="https://cdn.ckeditor.com/4.16.0/full/ckeditor.js"></script> -->
    <!-- ck finder -->
    <script src="ckfinder/ckfinder.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <title>Quản trị viên</title>
  </head>
  <body>
    <div class="admin-header">
      <h1>QUẢN LÝ BÁN PHỤ KIỆN THỜI TRANG T&N </h1>
    </div>