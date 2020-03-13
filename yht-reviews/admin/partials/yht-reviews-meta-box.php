<?php

/**
 * Provide meta-box for YHT Reviews CPT.
 *
 *
 * @link       https://yourhighesttruth.com
 * @since      1.0.0
 *
 * @package    Yht_Reviews
 * @subpackage Yht_Reviews/admin/partials
 */

?>

<div class=yht-wrapper-top>;

    <div class="yht-name-date">
        <label><strong>Name: </strong>
            <input type="text" name="yht_user_name" value="<?= $yht_user_name ?>" />
        </label>
        <br />
        <label><strong>Date: </strong>
            <input type="date" name="yht_date" value="<?= $yht_date ?>" />
        </label>
    </div>

    <div class="yht-rating">
        <label><strong>Rating Value: </strong>
            <table>
                <tr>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                </tr>
                <tr>
                    <td><input type="radio" name="yht_rating" value="1" <?= $yht_rating === "1" ? 'checked="checked"' : '' ?>/></td>
                    <td><input type="radio" name="yht_rating" value="2" <?= $yht_rating === "2" ? 'checked="checked"' : '' ?>/></td>
                    <td><input type="radio" name="yht_rating" value="3" <?= $yht_rating === "3" ? 'checked="checked"' : '' ?>/></td>
                    <td><input type="radio" name="yht_rating" value="4" <?= $yht_rating === "4" ? 'checked="checked"' : '' ?>/></td>
                    <td><input type="radio" name="yht_rating" value="5" <?= $yht_rating === "5" ? 'checked="checked"' : '' ?>/></td>
                </tr>
            </table>
        </label>
    </div>

</div>

<!-- Simple textarea editor for testimonials input: -->
<!--<div class="yht-testimonial">-->
<!--    <label><strong>Testimonial:</strong><br/>-->
<!--       <textarea name="yht_testimonial" cols="30" rows="14">--><?//= $yht_testimonial ?><!--"</textarea>-->
<!--   </label>-->
<!--</div>-->

<!-- TeenyMCE editor for testimonials input: -->
<div class="yht-testimonial">
    <label><strong>Testimonial:</strong><br/>
    <?php
    wp_editor( $yht_testimonial, 'yht_testimonial', array(
        'wpautop'       => 0,
        'teeny' => true,
        'media_buttons' => false,
        'textarea_rows' => 32
    ) );
    ?>
    </label>
</div>