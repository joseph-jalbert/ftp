<?php
/**
 *
 * @package ForThePeople
 */
?>

​<div class="socialmediawidget horizontal widgetWrap aside clearfix">
    <span class='st_plusone_vcount' displayText='Google +1'></span>
    <span class='st_facebook_vcount' displayText='Facebook'></span>
    <span class='st_twitter_vcount' displayText='Tweet'></span>
    <span class='st_email_vcount' displayText='Email'></span>
</div>
<div class="widgetWrap aside">
    <div class="title"><span>Blog Categories</span></div>
    <div class="body">
    	<ul class="childrenPageList blog-categories">
    	   <?php wp_list_categories('orderby=count&order=DESC&show_count=1&child_of=456&title_li='); ?>
    	</ul>
    </div>
    <div class="foot"></div>
</div>
                
<div class="widgetWrap aside">
    <div class="title"><span>Blog Archives</span></div>
    <div class="body padding-sml" id="accordion">
        <div class="accordion-group">
                
        <?php
        $years = $wpdb->get_col("SELECT DISTINCT YEAR(post_date)
        FROM $wpdb->posts WHERE post_status = 'publish'
        AND post_type = 'post' ORDER BY post_date DESC");
        foreach($years as $year) :
        ?>
            <div class="accordion-heading">
                <a data-target="#year<?php echo $year; ?>" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle" rel="nofollow" href="javascript:void(0);"><span class="label label-warning"><?php echo $year; ?></span></a>
            </div>

            <div class="accordion-body collapse" id="year<?php echo $year; ?>" style="height: 0px;">
                <div class="accordion-inner">
                    <ul class="unstyled">
                        <?php $months = $wpdb->get_col("SELECT DISTINCT MONTH(post_date)
                            FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post'
                            AND YEAR(post_date) = '".$year."' ORDER BY post_date DESC");
                            foreach($months as $postmonth) :
                        ?>
        
                            <li><a class="btn btn-link" href="<?php echo get_month_link($year, $postmonth); ?>"><?php echo date( 'F', mktime(0, 0, 0, $postmonth, 1) );?> <?php echo $year; ?></a></li>

                        <?php endforeach;?>

                    </ul>
                </div>
            </div>

        <?php endforeach; ?>                
                
        </div>
    </div>
    <div class="foot"></div>
</div>