;(function(p,l,o,w,i,n,g){if(!p[i]){p.GlobalSnowplowNamespace=p.GlobalSnowplowNamespace||[];
p.GlobalSnowplowNamespace.push(i);p[i]=function(){(p[i].q=p[i].q||[]).push(arguments)
};p[i].q=p[i].q||[];n=l.createElement(o);g=l.getElementsByTagName(o)[0];n.async=1;
n.src=w;g.parentNode.insertBefore(n,g)}}(window,document,"script","//dapncd6vgox95.cloudfront.net/sp.js","sptrkr"));

  window.sptrkr('newTracker', 'cf', 'd4w9sagg50fn1.cloudfront.net', {appId: 'usotTracker',cookieDomain: '.usovertimelawyers.dev'});

  window.sptrkr('trackPageView');

  window.sptrkr(function(){

    //console.log(this.cf.getDomainUserInfo());
    customTracking(this.cf.getTrackingPayload());

  });

  customTracking = function(trackingPayloadObj) {
    //var theURL = $.param(obj);

    var theURLParams = createURLParams(trackingPayloadObj);
    //console.log(theURLParams);

    //create image to send to tracker cfm
    var image = new Image(1, 1);
    var trackerURL = "http://processing.usovertimelawyers.dev/sptracker.cfm?";
    var source = trackerURL + theURLParams;
    //console.log(source)
    image.src = source;

  }

  createURLParams = function(trackingPayloadObj) {
    var theURLParams;
    //if no user? OR session?, send all?
    if(!jsCookies.check("SP_USER")){
      theURLParams = Object.keys(trackingPayloadObj).map(function(k) {
          return encodeURIComponent(k) + '=' + encodeURIComponent(trackingPayloadObj[k])
      }).join('&');
    } else {
      //page? (pageid?, url?)
      theURLParams = "duid="+encodeURIComponent(trackingPayloadObj.duid)+"&url="+encodeURIComponent(trackingPayloadObj.url)+"&refr="+encodeURIComponent(trackingPayloadObj.refr)+"&eid="+encodeURIComponent(trackingPayloadObj.eid);
    }

    return theURLParams;
  }