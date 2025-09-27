<!-- 
    <div class="banner-container">

      <div class="banner">
        <img src="../img/Banner/10.png" class="slide active" />
        <img src="../img/Banner/1.png" class="slide" />
        <img src="../img/Banner/2.png" class="slide" />
      </div>

      <button class="prev" onclick="changeSlide(-1)">❮</button>
      <button class="next" onclick="changeSlide(1)">❯</button>

    </div>

     -->
<?php
// Lấy danh sách banner từ database
$sql = "SELECT name, image_link, link FROM banner ORDER BY id";
$result = executeResult($sql);
?>

<div class="banner-container">
    <div class="banner">
        <?php foreach ($result as $banner): ?>
            <!-- <a href="<?= $banner['link']; ?>"> -->
                <img src="<?= $banner['image_link']; ?>" class="slide" alt="<?= $banner['name']; ?>" />
            <!-- </a> -->
        <?php endforeach; ?>
    </div>
    <button class="prev" onclick="changeSlide(-1)">❮</button>
    <button class="next" onclick="changeSlide(1)">❯</button>
</div>
<script src="script.js"></script>