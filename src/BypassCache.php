<?php

namespace Mediebruket\BypassCache;

use stdClass;

class BypassCache
{

    /** @var stdClass  */
    public $plugin;

    /** @var string */
    public $message;

    /** @var string */
    public $errorMessage;

    /** @var array */
    public $settings;

    public function __construct()
    {
        $this->plugin = new StdClass();
        $this->plugin->name = 'mb-bypass-cache';
        $this->plugin->displayName = 'Bypass Cache';
        $this->plugin->settingName = 'mbbc_bypass_urls';
        $this->plugin->version = '1.0.0';
        $this->plugin->folder = plugin_dir_path(__DIR__);

        add_action('admin_menu', [&$this, 'addAdminMetaBoxAndMenu']);
        add_action('init', function () {
            $this->setCookies();
        });
    }

    public function setCookies(): void
    {
        $setting = get_option($this->plugin->settingName);
        $parsedSetting = self::parseSetting($setting);

        if (! $parsedSetting) {
            return;
        }

        if (is_admin() || is_feed() || is_robots() || is_trackback()) {
            return;
        }

        foreach ($parsedSetting as $item) {
            setcookie('wordpress_no_cache', '1', 0, $item);
        }
    }

    /**
     * Initialize options page.
     */
    public function addAdminMetaBoxAndMenu(): void
    {
        add_submenu_page(
            'options-general.php',
            $this->plugin->displayName,
            $this->plugin->displayName,
            'manage_options',
            $this->plugin->name,
            [&$this, 'adminPanel']
        );
    }

    /**
     * Render and process form.
     */
    public function adminPanel(): void
    {
        if (!current_user_can('administrator')) {
            echo '<p>' . __('Sorry, you are not allowed to access this page.', 'mb-bypass-cache') . '</p>';
            return;
        }

        // Save Settings
        if (isset($_REQUEST['submit'])) {
            // Check nonce
            $nonce = $_REQUEST[$this->plugin->name . '_nonce'];
            // Missing nonce
            if (! isset($nonce)) {
                $this->errorMessage = __('nonce field is missing. Settings NOT saved.', 'mb-bypass-cache');
            } elseif (! wp_verify_nonce($nonce, $this->plugin->name)) {
                // Invalid nonce
                $this->errorMessage = __('Invalid nonce specified. Settings NOT saved.', 'mb-bypass-cache');
            } else {
                // Save
                // $_REQUEST has already been slashed by wp_magic_quotes in wp-settings
                // so do nothing before saving
                update_option($this->plugin->settingName, $_REQUEST[$this->plugin->settingName]);
                $this->message = __('Settings Saved.', 'mb-bypass-cache');
            }
        }

        // Get latest settings
        $this->settings = [
            $this->plugin->settingName => esc_html(wp_unslash(get_option($this->plugin->settingName))),
        ];

        include_once($this->plugin->folder . 'views/settings.php');
    }

    /**
     * Convert settings string to array and remove empty strings in array.
     * @param $setting
     * @return array
     */
    private static function parseSetting($setting): array
    {
        return array_filter(explode("\n", $setting), function ($s) {
            return (bool) $s;
        });
    }
}
