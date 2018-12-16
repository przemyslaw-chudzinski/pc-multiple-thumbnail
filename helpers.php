<?php

if (!function_exists('pc_multiple_images_get_images')) {
    function pc_multiple_images_get_images()
    {
        global $post, $pcMultipleImagesPlugin;
        $postMeta = get_post_meta($post->ID, $pcMultipleImagesPlugin->getAttachmentName());
        if (count($postMeta) > 0) {
            foreach ($postMeta as $key => $single) {
                $postMeta[$key] = json_decode($single, true);
            }
        }
        return $postMeta;
    }
}