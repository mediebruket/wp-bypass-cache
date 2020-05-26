<div class="wrap">
    <h2><?php

    echo $this->plugin->displayName; ?> &raquo; <?php esc_html_e('Settings', 'mb-bypass-cache'); ?></h2>

    <?php
    if (isset($this->message)) {
        ?>
        <div class="updated fade"><p><?php echo $this->message; ?></p></div>
        <?php
    }
    if (isset($this->errorMessage)) {
        ?>
        <div class="error fade"><p><?php echo $this->errorMessage; ?></p></div>
        <?php
    }
    ?>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <!-- Content -->
            <div id="post-body-content">
                <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <h3 class="hndle"><?php esc_html_e('Settings', 'mb-bypass-cache'); ?></h3>

                        <div class="inside">
                            <form action="options-general.php?page=<?php echo $this->plugin->name; ?>" method="post">
                                <p>
                                    <label
                                            for="mbbc_bypass_urls">
                                        <strong>
                                            <?php esc_html_e('URLs that shall not be cached', 'mb-bypass-cache'); ?>
                                        </strong>
                                    </label>
                                    <textarea
                                            name="mbbc_bypass_urls"
                                            id="mbbc_bypass_urls"
                                            class="widefat"
                                            rows="8"
                                            placeholder="<?php echo htmlspecialchars("/my-url/\n/another-url/"); ?>"
                                            <?php // phpcs:disable ?>
                                            style="font-family: 'Courier New', monospace;"><?php echo $this->settings[$this->plugin->settingName]; ?></textarea>
                                    <?php
                                    esc_html_e(
                                        'Cache will be disabled for these URLs. One URL per line, with leading and trailing slashes.',
                                        'mb-bypass-cache'
                                    );
                                    ?>
                                </p>
                                <?php wp_nonce_field($this->plugin->name, $this->plugin->name . '_nonce'); ?>
                                <p>
                                    <input
                                            name="submit"
                                            type="submit"
                                            class="button button-primary"
                                            value="<?php esc_attr_e('Save', 'mb-bypass-cache'); ?>"
                                    />
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
