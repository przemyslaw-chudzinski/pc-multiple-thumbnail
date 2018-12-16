<?php

class PC_Multiple_Images {

    private static $plugin_id = 'pc-multiple-images';

    private $plugin_version = '1.0.0';

    private $attachmentName = 'pc_multiple_images_attachment';

    private $post_type = 'post';

    private $post_format = 'gallery';

    private $input_name = 'additional_post_images';

    private $open_button_label = 'Wybierz zdjęcia';

    private $remove_button_label = 'Usuń wybrane zdjęcia';

    private $preview_container_class = 'pc-multiple-images-preview';

    public function __construct()
    {
        register_activation_hook(__FILE__, [$this, 'onActivate']);
        $this->actions();
        $this->filters();
    }

    public function registerPluginScripts()
    {
        $handleScripts = static::$plugin_id.'-scripts';
        $handleStyles = static::$plugin_id.'-styles';
        wp_register_script($handleScripts, plugins_url('/assets/js/scripts.js', __FILE__), ['jquery'], false, true);
        wp_register_style($handleStyles, plugins_url('/assets/css/styles.css', __FILE__));
        wp_enqueue_style($handleStyles);
        wp_enqueue_script('jquery');
        wp_enqueue_script($handleScripts);
    }

    private function onActivate()
    {
        $ver_opt = static::$plugin_id.'-version';
        $installedVersion = get_option($ver_opt, NULL);

        if ($installedVersion === NULL) {
            $this->install();
        } else {
            $this->compareVersion($installedVersion);
        }
    }

    private function install($ver_opt)
    {
        update_option($ver_opt, $this->plugin_version);
    }

    private function compareVersion($installedVersion)
    {
        switch (version_compare($installedVersion, $this->plugin_version)) {

            case 0:
                // zainstalowana wrsja jest identyczna
                break;

            case 1:
                // zainstalowana wersja jest nowsza niż obecna
                break;

            case -1:
                // zainstalowana wersja jest starsza niz obecna
                break;
        }
    }

    private function actions()
    {
        add_action('post_submitbox_misc_actions', [$this, 'registerMetaBox']);
        add_action('save_post', [$this, 'saveAdditionalImages']);
        add_action('admin_enqueue_scripts', [$this, 'registerPluginScripts']);
    }

    private function filters()
    {
        add_filter('wp_headers', [$this, 'setHeaders']);
    }

    public function saveAdditionalImages($post_id)
    {
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        if( !current_user_can( 'edit_post' ) ) return;

        if ($this->post_type === $this->getCurrentPostType() && get_post_format($post_id) === $this->post_format && $this->getCurrentAction() === 'editpost') {
            if (!empty($this->getInputValue())) {
                $additional_post_images = $this->getInputValue();
                if (count($additional_post_images) > 0) {
                    delete_post_meta($post_id, $this->attachmentName);
                    foreach ($additional_post_images as $key => $img) {
                        add_post_meta($post_id, $this->attachmentName, json_encode($img));
                    }
                    return;
                }
            }
        }
        if ($this->post_type === $this->getCurrentPostType() && get_post_format($post_id) === $this->post_format) {
            delete_post_meta($post_id, $this->attachmentName);
        }
    }

    private function getInputValue($assoc = true)
    {
        return json_decode(stripslashes($_POST[$this->input_name]), $assoc);
    }

    public function registerMetaBox()
    {
        $metabox_id = static::$plugin_id.'-metabox';
        $metabox_title = 'Dodatkowe zdjęcia';
        $callback = [$this, 'createMetaBoxHtml'];
        $screen = 'post';
        $contex = 'side';
        $priority = 'default';
        $callback_args = [];
        add_meta_box($metabox_id, $metabox_title, $callback, $screen, $contex, $priority, $callback_args);
    }

    public function createMetaBoxHtml()
    {
        require_once 'views/metabox.php';
    }

    public function setHeaders($headers)
    {
        return $headers;
    }

    public function getAttachmentName()
    {
        return $this->attachmentName;
    }

    public function setAttachmentName($attachmentName)
    {
        $this->attachmentName = $attachmentName;
    }

    public function getInputName()
    {
        return $this->input_name;
    }

    public function setInputName($input_name)
    {
        $this->input_name = $input_name;
    }

    public function getOpenButtonLabel()
    {
        return $this->open_button_label;
    }

    public function setOpenButtonLabel($open_button_label)
    {
        $this->open_button_label = $open_button_label;
    }

    public static function getPluginId()
    {
        return self::$plugin_id;
    }

    public static function setPluginId($plugin_id)
    {
        self::$plugin_id = $plugin_id;
    }

    public function getPluginVersion()
    {
        return $this->plugin_version;
    }

    public function setPluginVersion($plugin_version)
    {
        $this->plugin_version = $plugin_version;
    }

    public function getPostFormat()
    {
        return $this->post_format;
    }

    public function setPostFormat($post_format)
    {
        $this->post_format = $post_format;
    }

    public function getPostType()
    {
        return $this->post_type;
    }

    public function setPostType($post_type)
    {
        $this->post_type = $post_type;
    }

    public function getPreviewContainerClass()
    {
        return $this->preview_container_class;
    }

    public function setPreviewContainerClass($preview_container_class)
    {
        $this->preview_container_class = $preview_container_class;
    }

    public function getRemoveButtonLabel()
    {
        return $this->remove_button_label;
    }

    public function setRemoveButtonLabel($remove_button_label)
    {
        $this->remove_button_label = $remove_button_label;
    }

    private function getCurrentAction()
    {
        return $_POST['action'];
    }

    private function getCurrentPostType()
    {
        return $_POST['post_type'];
    }
}