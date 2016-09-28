<?php

/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package ForThePeople
 */

?>
</div>
<!-- #content -->
<?php if(is_page_template('all-class-actions.php') || is_singular('classactionlawyers') || is_page_template('securities-contact-page.php') || is_page_template('empty-page.php')) { ?>
<div class="serving-headline">Handling Cases Nationwide</div>
<?php } else if(is_page_template('empty-page-title.php')) { ?>
<?php } else { ?>
<div class="serving-headline">Serving All of Florida, Georgia, Mississippi, Tennessee, Kentucky, New York, Pennsylvania, Alabama &amp; Arkansas</div>
<?php } ?>
<footer id="footer">
  <div class="footer-layer1">
    <div class="container">
      <div class="row-fluid">
        <div class="span4">
          <div class="logo pull-left"></div>
          <ul class="unstyled pull-left hidden-phone">
            <li><a href="/free-case-evaluation/">Free Case Evaluation</a></li>
            <li><a href="http://employment.forthepeople.com">Employment</a></li>
            <li><a href="/disclaimer-and-terms-of-use/">Terms & Conditions</a></li>
            <li><a href="/site-accessibility/">Site Accessibility</a></li>
          </ul>
        </div>
        <div class="span4">
          <div class="phone"><a href="tel:8776674265" onclick="trackEventGA('Click to Call', 'Call', 'Footer', 550);">877.667.4265</a></div>
          <div class="offices hidden-phone"> <span><a href="/office-locations/">Offices Throughout</a></span>
            <div class="row-fluid">
              <div class="span4"> Florida

                Mississippi

	              Pennsylvania
              </div>
              <div class="span4"> Georgia

                Tennessee 

                Alabama </div>
              <div class="span4"> New York

                Kentucky
                  Arkansas
              </div>
            </div>
          </div>
        </div>
        <div class="span4 hidden-phone">
          <div class="socialmedia"> <a href="https://www.facebook.com/MMForthePeople" class="sm-facebook" target="_blank"><span>Facebook</span></a> <a href="https://twitter.com/forthepeople" class="sm-twitter" target="_blank"><span>Twitter</span></a> <a href="https://www.youtube.com/user/mmforthepeople" class="sm-youtube" target="_blank"><span>YouTube</span></a> </div>
          <span>Consumer Alerts Newsletter</span>
          <div class="row-fluid">
              <div class="consumer-alerts-form" id="consumer-alerts-form">

                  <script>
                      hbspt.forms.create({
                          portalId: '1841598',
                          formId: '5d03efdc-1d30-4ed2-a0b6-1139a765daac',
                          onFormSubmit: <?php echo forthepeople_render_hubspot_text_filter_callback(); ?>
                      });
                  </script>
              </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <!--- /footer-layer1 --->
  <div class="footer-layer2">© 1998 - <?php echo date("Y"); ?>. All Rights Reserved. Morgan &amp; Morgan, PA</div>
</footer>
<!--- /footer --->
</div>
<!-- #page -->
<?php wp_footer(); ?>
<script>
/* Removed Google Search. Bill V.P.
(function(){
	var cx = '011106375241371260146:4-r2y4eom2i';
	var gcse = document.createElement('script'); gcse.type = 'text/javascript'; gcse.async = true;
	gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
		'//www.google.com/cse/cse.js?cx=' + cx;
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(gcse, s);
})();
*/

jQuery(function($) {
  $('a[href*="#"]:not([href="#"],[data-toggle=tab],[data-toggle=collapse])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top - 100
        }, 1000);
        return false;
      }
    }
  });
});
</script>
<?php if(is_page_template('office-location.php') || is_front_page() || is_page_template('all-class-actions.php') || is_page_template('featured-news.php') || is_page_template('tampa-alternate.php')) { ?>
<script>
jQuery(document).ready(function($) {
$("#fullSlider div.rsTabs").wrapInner("<div class='rsNavContainer'></div>");
});
jQuery(function($){$('#testimonial-slider').slides({hoverPause: true});});
</script>
<?php } ?>
<?php if(is_page('open-lawsuits')) { ?>
<script>
jQuery(function($){
$('#pageFilter').sortAllTheThings({
filterInput: '#pageFilter',
sortList:'ul.practice-areas',
placeholder:'Filter Class Action Lawsuits...'
});
});
</script>
<?php } else if (is_page_template('our-attorneys.php') || is_page_template('our-attorneys-inner.php') || is_page_template('our-attorneys-alternate.php')) { ?>
<script>
jQuery(function($){
$('#pageFilter').sortAllTheThings({
filterInput: '#pageFilter',
sortList:'.attoreyList-all ul',
placeholder:'filter attorney...'
});
});
</script>
<?php } ?>
<?php if (is_page_template('business-litigation.php') || is_page_template('securities-litigation.php')) { ?>
<script type="text/javascript">
        (function() {
            if( window.innerWidth > 320 ) {
                window.scrollReveal = new scrollReveal();
            }
        })();
        jQuery(document).ready(function($){
            $.stellar({horizontalScrolling: false});
        });
</script>
<?php } ?>

<?php if (in_category('featured-news')) { ?>
<script>
jQuery(document).ready(function($) {
    $(".breadBasket a:contains(Featured News)").attr("href", "/featured-news/");
});
</script>
<?php } ?>
<?php if (is_page_template('mesothelioma.php')) { ?>
<script>
jQuery(document).ready(function($){
        var $tabs = $('.tabs-nav li');
        $('#prevtab').on('click', function() {
            $tabs.filter('.active').prev('li').find('a[data-toggle="tab"]').tab('show');
        });

        $('#nexttab').on('click', function() {
            $tabs.filter('.active').next('li').find('a[data-toggle="tab"]').tab('show');
        });
        $(".tabs-with-content ul li a").click(function() {
            $('html, body').animate({
                scrollTop: $(".tabs-with-content .tab-content").offset().top
            }, 500);
        });
});
</script>
<?php } ?>

<?php if(is_page() && !is_page_template() || is_page_template('practice-area.php') || is_page_template('practice-area-inner.php') || is_page_template('office-location.php')) { ?>
<script>
jQuery(document).ready(function($) {
	if ($(window).width() > 1024) {
		$(window).on("scroll", function() {
			var fromTop = $(window).scrollTop();
			$("body").toggleClass("down", (fromTop > 225));
		});
	}
});
</script>
<?php } ?>

<script type="text/javascript">var switchTo5x=true;</script>
<?php if ( is_singular( array( 'post', 'classactionlawyers' ) ) ) { ?>
    <script type="text/javascript">stLight.options({publisher: "b8866a21-e797-44ce-a731-27ba7a59e669", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
<?php } ?>
<script type="text/javascript">jQuery('.tt').tooltip();</script>
<!--[if lte IE 8]> </div> <![endif]-->

<div id="fb-root"></div>
<script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.7";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

</body></html>
