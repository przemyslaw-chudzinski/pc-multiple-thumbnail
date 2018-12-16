<div>
    <div class="<?= $this->preview_container_class; ?>" id="pc-multiple-images-preview">
        <?php require_once 'preview.php'; ?>
    </div>
    <a id="pc-multiple-images-select-media" href="#" style="display: <?= count(pc_multiple_images_get_images()) > 0 ? 'none' : 'block'; ?>;"><?= $this->open_button_label; ?></a>
    <a href="#" id="pc-multiple-images-clear-media" style="display: <?= count(pc_multiple_images_get_images()) > 0 ? 'block' : 'none'; ?>;"><?= $this->remove_button_label; ?></a>
    <input type="hidden" name="<?= $this->input_name; ?>" id="pc-multiple-images-input" value="<?= json_encode(pc_multiple_images_get_images()); ?>">
</div>