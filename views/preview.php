<?php foreach (pc_multiple_images_get_images() as $item): ?>
    <a class="pc-multiple-images-preview-single" href="#">
        <img src="<?= $item['sizes']['thumbnail']['url']; ?>" alt="">
    </a>
<?php endforeach; ?>
