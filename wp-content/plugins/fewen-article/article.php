<?php ?>

<div class="fw-wrap" style="overflow: scroll">
    <div class="fw-main-container">
        <div class="fw-title">
            博客文章
        </div>
        <div class="fw-content-container">
            <div class="fw-articles-statistic-container">

            </div>
            <div class="fw-articles-content-container">
                <?php
                wp_edit_posts_query();

                if( have_posts() ) :
                    while ( have_posts() ) : the_post();
                        view('single-article');

                    endwhile;

                else:


                endif;


                ?>
            </div>


        </div>

    </div>

</div>