<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package ForThePeople
 */

get_header(); ?>

<div class="container force-fullwidth" id="interior-page">
  <div class="row-fluid">
    <div class="span12" id="col0">
      <div class="container">
        <div class="row-fluid fof">
          <div class="span8">
            <h1 class="heading">Sorry, the page you're looking for does not exist.</h1>
            <p>We apologize for the inconvenience.</p>
            <p>You could try one of the following links or perform a search above:</p>
            <div class="row-fluid">
              <div class="span6">
                <ul class="circle pull-left">
                  <li><a href="/office-locations">Office Locations</a></li>
                  <li><a href="/free-case-evaluation">Contact Us</a></li>
                  <li><a href="/sitemap">Sitemap</a></li>
                </ul>
              </div>
              <div class="span6"></div>
            </div>
          </div>
          <div class="span4"><img style="width: 200px; margin-top: 40px;" src="/wp-content/themes/forthepeople/assets/images/structure/404.png" alt="404 Error"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>
