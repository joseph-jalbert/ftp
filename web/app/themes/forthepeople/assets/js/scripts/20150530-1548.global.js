window.mobilecheck = function() { var check = false;(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera); return check;}
yepnope({test: Modernizr.cssgradients,nope: '/wp-content/themes/forthepeople/assets/js/libs/pie/pie.js'});

;(function(e){e.fn.visible=function(t,n,r){var i=e(this).eq(0),s=i.get(0),o=e(window),u=o.scrollTop(),a=u+o.height(),f=o.scrollLeft(),l=f+o.width(),c=i.offset().top,h=c+i.height(),p=i.offset().left,d=p+i.width(),v=t===true?h:c,m=t===true?c:h,g=t===true?d:p,y=t===true?p:d,b=n===true?s.offsetWidth*s.offsetHeight:true,r=r?r:"both";if(r==="both")return!!b&&m<=a&&v>=u&&y<=l&&g>=f;else if(r==="vertical")return!!b&&m<=a&&v>=u;else if(r==="horizontal")return!!b&&y<=l&&g>=f}})(jQuery);

$(function(){
var canvasNav,
  isCanvasNavLoaded = false,
  fixedNavOffset = $('#site-nav').offset().top,
  $siteNavContainer = $('.siteNav-container'),
  $canvasNavToggle = $('.canvas-nav-toggle'),
  $stickyNav = $('.sticky-canvas-nav'),
  $topNav = $('#topNav'),
  $liDropdown = $('li.dropdown'),
  windowWidth = window.innerWidth,
  windowHeight = window.innerHeight - 50;


if( $('html').hasClass('touch') ){

  $liDropdown.on('click', function(e) {

    if($(this).hasClass('open') ){
       return true;
    }else{
      $liDropdown.removeClass('open');
      $(this).addClass('open');
    }

   e.preventDefault();
  });

}else{

  $liDropdown.hover(function(){
    $(this).addClass('open');
  },function(){
    $(this).removeClass('open');
  });

};

  loadCanvasNav = function(){
    canvasNav = $siteNavContainer.scotchPanel({
        containerSelector: '#page-wrap',
        direction: 'left',
        duration: 300,
        transition: 'ease',
        distanceX: '70%',
        enableEscapeKey: true,
        beforePanelOpen: function() {
          $canvasNavToggle.html('<i class="icon-remove"></i> Close');
          $siteNavContainer.css('display','');

       if (window.scrollY < fixedNavOffset){
            window.scrollTo(0, fixedNavOffset)
          }

          $siteNavContainer.css({paddingTop:$(window).scrollTop() + 50});
          $topNav.addClass('nav-overflow').css('height', windowHeight);
        },
        beforePanelClose: function() {
          $siteNavContainer.css('display','none');
          $canvasNavToggle.html('<i class="icon-reorder"></i> Menu');
          $topNav.removeClass('nav-overflow').css('height','');
          $liDropdown.removeClass('open');
        }
    });

    isCanvasNavLoaded = true;
    return isCanvasNavLoaded;
  };

  removeCanvasNav = function(){
    $siteNavContainer.removeAttr('style');
    $topNav.removeClass('nav-overflow').css('height','');
    return isCanvasNavLoaded = false;
  };

  initCanvasCheck = function(){
    if ( window.innerWidth <= 1023 ){
      loadCanvasNav();
    }else{
      removeCanvasNav();
    };
  }
  initCanvasCheck();

checkStickyNav = function(){
  fixedNavOffset = $('#site-nav').offset().top;
  if( $(window).scrollTop() >= fixedNavOffset ){
      $stickyNav.addClass('sticky-canvas-nav-showing');
    }else{
      $stickyNav.removeClass('sticky-canvas-nav-showing');
    }
};

  $(window).scroll(function () {

    checkStickyNav();

    if(isCanvasNavLoaded){
      clearTimeout($.data(this, 'scrollTimer'));
      $.data(this, 'scrollTimer', setTimeout(function() {
        $siteNavContainer.css({paddingTop:$(window).scrollTop() + 50});
        $topNav.css('height', windowHeight);
      }, 250));
    };
  });



  $( window ).resize(function() {

     if (window.innerWidth != windowWidth ) {

        windowWidth = window.innerWidth;
        windowHeight = window.innerHeight - 50;

        if(isCanvasNavLoaded){
          canvasNav.close();
        }else{
          initCanvasCheck();
        }
        if ( window.innerWidth >= 1024 && isCanvasNavLoaded){
          removeCanvasNav();
        }

        //fixedNavOffset = $('#site-nav').offset().top;
      };

    if( window.innerWidth - 50 != windowHeight){
      windowHeight = window.innerHeight - 50;
    };

  });

  $('.btn.canvas-nav-toggle').click(function() {
    canvasNav.toggle();
    return false;
  });
  $('.nav-overlay').click(function() {
    canvasNav.close();
  });
// --
  if(mobilecheck() === false){
    $("a[href^=tel]:not(.nav-clicktocall)").contents().unwrap();
  };
  $('.tt').tooltip();

  // Definition Pop-Overs
  $('.popover-def').popover({
      trigger: 'hover'
  });
  if( $('div.modal') ){ $(document.body).append( $('div.modal').detach() ); }
}); //end doc rdy

function contactPush(){window.location ='/free-case-evaluation';}
function getRandomInt (min, max) {return Math.floor(Math.random() * (max - min + 1)) + min;};
function trackEventGA(Category, Action, Label, Value) {
    "use strict";
    if (typeof (_gaq) !== "undefined") {
        _gaq.push(['_trackEvent', Category, Action, Label, Value]);
    } else if (typeof (ga) !== "undefined") {
        ga('send', 'event', Category, Action, Label, Value);
    }
}