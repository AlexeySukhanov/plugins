<?php

/**
 * Provide layout for YHT Reviews settings page.
 *
 *
 * @link       https://yourhighesttruth.com
 * @since      1.0.0
 *
 * @package    Yht_Reviews
 * @subpackage Yht_Reviews/admin/partials
 */

?>

<div class="wrap">
    <h2>YHT Reviews Settings</h2>
    <form method="post" enctype="multipart/form-data" action="options.php">

        <?php
        settings_fields('yht_reviews_options');
        do_settings_sections( $this->yht_settings_page_addr );
        ?>

        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>
    </form>
</div>
