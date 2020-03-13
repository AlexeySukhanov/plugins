<?php

/**
 * Provide a public-facing view
 *
 * @link       https://yourhighesttruth.com
 * @since      1.0.0
 *
 * @package    Yht_Reviews
 * @subpackage Yht_Reviews/public/partials
 */
?>

<div class="yht-reviews-container">
    <div class="yht-reviews">

        <div id="container">

            <div class="overview">
                <div class="flex-item score">
                    <div class="score-value"><?php echo $overall_rating; ?></div>
                    <div class="score-note">Out of 5</div>
                    <?php if($yht_leave_review_url != '#'): ?>
                        <a class="link-create" href="<?php   echo $yht_leave_review_url; ?>" target="_blank">Click here to leave a review &rarr;</a>
                    <?php endif; ?>
                </div>
                <div class="flex-item score-distribution">
                    <table cellpadding="0" cellspacing="0">
                        <colgroup>
                            <col width="45px">
                            <col>
                            <col width="20px">
                        </colgroup>
                        <tr>
                            <td style="white-space: nowrap; text-align: right;"><span class="caption">5 star</span></td>
                            <td><span class="bar"><span style="width: <?php echo $five_stars_perc; ?>%"></span></span></td>
                            <td style="white-space: nowrap"><span class="counter">(<?php echo $five_stars; ?>)</span></td>
                        </tr>
                        <tr>
                            <td style="white-space: nowrap; text-align: right;"><span class="caption">4 star</span></td>
                            <td><span class="bar"><span style="width: <?php echo $four_stars_perc; ?>%"></span></span></td>
                            <td style="white-space: nowrap"><span class="counter">(<?php echo $four_stars; ?>)</span></td>
                        </tr>
                        <tr>
                            <td style="white-space: nowrap; text-align: right;"><span class="caption">3 star</span></td>
                            <td><span class="bar"><span style="width: <?php echo $three_stars_perc; ?>%"></span></span></td>
                            <td style="white-space: nowrap"><span class="counter">(<?php echo $three_stars; ?>)</span></td>
                        </tr>
                        <tr>
                            <td style="white-space: nowrap; text-align: right;"><span class="caption">2 star</span></td>
                            <td><span class="bar"><span style="width: <?php echo $two_stars_perc; ?>%"></span></span></td>
                            <td style="white-space: nowrap"><span class="counter">(<?php echo $two_stars; ?>)</span></td>
                        </tr>
                        <tr>
                            <td style="white-space: nowrap; text-align: right;"><span class="caption">1 star</span></td>
                            <td><span class="bar"><span style="width: <?php echo $one_star_perc; ?>%"></span></span></td>
                            <td style="white-space: nowrap"><span class="counter">(<?php echo $one_star; ?>)</span></td>
                        </tr>
                    </table>
                </div>
                <div class="flex-item overall-rating">
                    <table cellpadding="0" cellspacing="0">
                        <colgroup>
                            <col width="95px">
                        </colgroup>
                        <tr>
                            <td>Overall <br> Rating</td>
                            <td>
                                <?php
                                if ( $overall_rating_ceil ) {
                                    echo '<span class="stars">';
                                    for ( $i = 0; $i < $overall_rating_ceil; $i++ ) {
                                        echo '
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="25px" height="25px" viewBox="0 0 25 25">
                                                    <path fill-rule="evenodd" fill="#ffcc00"
                                                          d="M12.514,0.000 C12.514,0.000 9.091,9.091 9.091,9.091 C9.091,9.091 0.000,9.091 0.000,9.091 C0.000,9.091 7.732,15.463 7.732,15.463 C7.732,15.463 4.783,24.999 4.783,24.999 C4.783,24.999 12.485,19.103 12.485,19.103 C12.485,19.103 20.218,24.999 20.218,24.999 C20.218,24.999 17.268,15.463 17.268,15.463 C17.268,15.463 25.000,9.091 25.000,9.091 C25.000,9.091 15.909,9.091 15.909,9.091 C15.909,9.091 12.514,0.000 12.514,0.000 Z" />
                                                    </svg>                                            
                                                ';
                                    }
                                    echo '</span>';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><hr></td>
                        </tr>
                        <tr>
                            <td style="font-size: 36px;"><?php echo $five_four_rating; ?>%</td>
                            <td style="color: #6f767a;padding-left: 16px;">of customers who buy this product give it a 4 or 5 star rating.</td>
                        </tr>
                    </table>
                </div>
            </div>
            <hr>

            <div id="reviews">

                <?php

                $count = $count_query->post_count;
                if ( $reviews_query->have_posts() ) :
                    while ( $reviews_query->have_posts() ) :
                        $reviews_query->the_post();

                        // START reviews output cycle
                        ?>

                        <div class="yht-review-item">
                            <div class="review-header">
                                <div class="flex-item">

                                    <?php
                                    $yht_user_name = get_post_meta( get_the_ID(), 'yht_user_name', true );
                                    if ( $yht_user_name && $yht_display_name ) {
                                        echo '<span class="name">' . $yht_user_name . '</span> <br>';
                                    }
                                    ?>
                                    <?php
                                    $yht_date = get_post_meta( get_the_ID(), 'yht_date', true );
                                    if ( $yht_date && $yht_display_date ) {
                                        $formated_date = date( 'F d, Y', strtotime( $yht_date ) );
                                        echo '<span class="time">' . $formated_date . '</span>';
                                    }
                                    ?>

                                </div>
                                <div class="flex-item">

                                    <?php
                                    $yht_rating = get_post_meta( get_the_ID(), 'yht_rating', true );
                                    if ( $yht_rating ) {
                                        echo '<span class="stars">';
                                        for ( $i = 0; $i < $yht_rating; $i++ ) {
                                            echo '
                                                <svg xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" xmlns:osb="http://www.openswatchbook.org/uri/2009/osb" version="1.1" xmlns:cc="http://creativecommons.org/ns#" viewBox="0 0 28.3125 28.34375" xmlns:dc="http://purl.org/dc/elements/1.1/">
                                                    <path d="m14.17025,1.71875-3.423,9.091-9.091,0,7.732,6.372-2.949,9.536,7.702-5.896,7.733,5.896-2.95-9.536,7.732-6.372-9.091,0z" stroke="#336699" stroke-miterlimit="4" stroke-dasharray="none" stroke-width="1.8" fill="#336699"></path>
                                                </svg>                                                
                                            ';
                                        }
                                        echo '</span>';
                                    }
                                    ?>

                                    <span class="verified-purchase" >
                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAATCAYAAAByUDbMAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAbNJREFUeNqMlMsrhFEYxo3bjFlQJsXaJZdcJtmIkkkmC4niP7DQKIUasbCyIhYixVqNlWZhMZSymaImk4VLFjaiGRYWFtPwed56jk6nOTNz6tfXeznPOb3n/V6X4zglllULBkAzKAdfIAni4CfnDhEzaAMRkHFyrzewCqrMvS7jZiGwCdy0o+AJZEE16AL9jD2CKXCX62Zh7fQj0JDj1kIQnDPvE3SqmEoYAb9MmLGImBwz/wG4lVgZuGdgv0ghhbrhohIL0HFZxGa5QRTM0p7g3hfgEscOHesFhDzgjLlSEj/9Cfr8pXiDVr5FXHvVMOjRbC84BUEtntBeVVaLKN9QuZcnrdFO83SvVpv/+mjs0T8nN0tTuU7rrRTwgQsQAwHpIrAAtoy+9/H7Icq7VF7RTutgpztajUKWWl4zp0+MMRpXRtIQ+GZs2yI0zvirtJjqsySdB0ayNPOGPLvldVUtl/U/YBhkGZgusmEj2h/g0cWEJa1Gh6DeIjIKYsxLgSbb1Jjn1KikLb31DDKgBnRrU0OmyaRtaijawUmeefbOXiw4z8z+GQSNoIKT9jbfpP0TYACkw2VuWLUQDQAAAABJRU5ErkJggg==">
                                        <em>Verified Purchase</em>
                                    </span>
                                </div>
                            </div>

                            <div class="review-body">

                                <div class="feedback-content">
                                    <div class="feedback-audio-wrapper" style="display: none;">
                                        <audio id="audioplayer" class="video-js vjs-default-skin" controls="" width="310" height="60">
                                            <source src="" type="audio/mp3">
                                        </audio>
                                    </div>
                                    <strong class="title"><?php if( $yht_display_title ) the_title(); ?></strong>
                                    <?php
                                    $yht_testimonial = get_post_meta( get_the_ID(), 'yht_testimonial', true );
                                    if ( $yht_testimonial ) {
                                        echo '<br><span class="text">' . $yht_testimonial . '</span>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <hr>

                    <?php
                    endwhile;
                endif;

                ?>

                <div class="navigation">

                    <?php
                    $no_of_paginations = ceil($count / $per_page);

                    if ($cur_page >= 4) { 
                        $start_loop = $cur_page - 1; 

                        if ($no_of_paginations > $cur_page + 1) { 
                            $end_loop = $cur_page + 1; 
                        }
                        else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 3) {
                            $start_loop = $no_of_paginations - 3;
                            $end_loop = $no_of_paginations;
                        } else {
                            $end_loop = $no_of_paginations;
                        }

                    } else { 
                        $start_loop = 1; 
                        if ($no_of_paginations > 4)
                            $end_loop = 4; 
                        else
                            $end_loop = $no_of_paginations;
                    }


                    // Pagination Buttons logic
                    $pag_container = "
                        <div class='cvf-universal-pagination'>
                            <ul>";


                    if ($previous_btn && $cur_page > 1) {
                        $pre = $cur_page - 1;
                        $pag_container .= "<li p='$pre' class='active'>←</li>";
                    }

                    if ( $first_btn && $cur_page > 6 ) {
                        $pag_container .= "<li p='1' class='active'>1</li>";
                        $pag_container .= "<li p='2' class='active'>2</li>";
                        $pag_container .= "<li p='3' class='active'>3</li>";
                        $pag_container .= "<li class='dots'>...</li>";
                    } elseif ( $first_btn && $cur_page > 5 ) {
                        $pag_container .= "<li p='1' class='active'>1</li>";
                        $pag_container .= "<li p='2' class='active'>2</li>";
                        $pag_container .= "<li p='3' class='active'>3</li>";
                        $pag_container .= "<li p='4' class='active'>4</li>";
                    } elseif( $first_btn && $cur_page > 4 ) {
                        $pag_container .= "<li p='1' class='active'>1</li>";
                        $pag_container .= "<li p='2' class='active'>2</li>";
                        $pag_container .= "<li p='3' class='active'>3</li>";
                    } elseif( $first_btn && $cur_page > 3) {
                        $pag_container .= "<li p='1' class='active'>1</li>";
                        $pag_container .= "<li p='2' class='active'>2</li>";
                    }



                    for ($i = $start_loop; $i <= $end_loop; $i++) {

                        if ($cur_page == $i)
                            $pag_container .= "<li p='$i' class = 'selected' >{$i}</li>";
                        else
                            $pag_container .= "<li p='$i' class='active'>{$i}</li>";
                    }

                    if ($last_btn && $cur_page < $no_of_paginations - 5 ) {
                        $pag_container .= "<li class='dots'>...</li>";
                        $pag_container .= "<li p='" . ($no_of_paginations - 2) . "' class='active'>" . ($no_of_paginations - 2) . "</li>";
                        $pag_container .= "<li p='" . ($no_of_paginations - 1) . "' class='active'>" . ($no_of_paginations - 1) . "</li>";
                        $pag_container .= "<li p='" . $no_of_paginations . "' class='active'>" . $no_of_paginations . "</li>";
                    } else if ($last_btn && $cur_page < $no_of_paginations - 4 ) {
                        $pag_container .= "<li p='" . ($no_of_paginations - 3) . "' class='active'>" . ($no_of_paginations - 3) . "</li>";
                        $pag_container .= "<li p='" . ($no_of_paginations - 2) . "' class='active'>" . ($no_of_paginations - 2) . "</li>";
                        $pag_container .= "<li p='" . ($no_of_paginations - 1) . "' class='active'>" . ($no_of_paginations - 1) . "</li>";
                        $pag_container .= "<li p='" . $no_of_paginations . "' class='active'>" . $no_of_paginations . "</li>";
                    }  elseif ($last_btn && $cur_page < $no_of_paginations - 3 ) {
                        $pag_container .= "<li p='" . ($no_of_paginations - 2) . "' class='active'>" . ($no_of_paginations - 2) . "</li>";
                        $pag_container .= "<li p='" . ($no_of_paginations - 1) . "' class='active'>" . ($no_of_paginations - 1) . "</li>";
                        $pag_container .= "<li p='" . $no_of_paginations . "' class='active'>" . $no_of_paginations . "</li>";
                    } else if ($last_btn && $cur_page < $no_of_paginations - 2 ) {
                        $pag_container .= "<li p='" . ($no_of_paginations - 1) . "' class='active'>" . ($no_of_paginations - 1) . "</li>";
                        $pag_container .= "<li p='" . $no_of_paginations . "' class='active'>" . $no_of_paginations . "</li>";
                    } elseif ( $last_btn && $cur_page < $no_of_paginations - 1 ) {
                        $pag_container .= "<li p='" . $no_of_paginations . "' class='active'>" . $no_of_paginations . "</li>";
                    }


                    if ($next_btn && $cur_page < $no_of_paginations) {
                        $nex = $cur_page + 1;
                        $pag_container .= "<li p='$nex' class='active'>→</li>";
                    }

                    $pag_container = $pag_container . "
                            </ul>
                        </div>";

                    echo '<div class = "yht-pages pages">' . $pag_container . '</div>';

                    ?>


                    <a class="vendor" href="https://www.trustspot.io/" rel="nofollow" target="_blank">
                    <span class="logo" >
						<img src="https://yourhighesttruth.com/yoga-ayahuasca-retreats-peru/wp-content/uploads/2020/03/trustspot.jpg" >
                    </span>
                        <div class="powered-by-text">
                            Powered by <br> <span><img src="https://yourhighesttruth.com/yoga-ayahuasca-retreats-peru/wp-content/uploads/2020/03/trustspot_logo_230.png"></span>
                        </div>
                    </a>
                </div>

                <div style="clear: both"></div>
            </div>

        </div>
    </div>