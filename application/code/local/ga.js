var _gaq = _gaq || [];

(function()){
  var trackErrorThroughEvent = function(exception, f) {
    _gaq.push(['_trackEvent',
      'JS ERROR: '+ (f || 'native') + ' (nav:'+ navigator.userAgent +') Exception: '+ (exception.name || 'Error'),
      exception.message || exception,
      ' url: '+document.location.href+' referer: '+ document.referrer
      ]);
    };

  var outLink = function(link){
    var _gA = link.href.split('/');
    var _gS = link.href.split('#');

    var _gT = '/out-link/' + _gA[2] + '/?d=' + _gS[0];
        _gaq.push(['_trackPageview',_gT]);
        _gaq.push(function() {
            try {
                setTimeout('window.open(_gat._getTrackerByName()._getLinkerUrl(\'' + lien.href + '\',false),\'_blank\')', 100);
            } catch(err) {
                window.open(lien.href, '_blank');   /* préserve le fonctionnement du lien href en cas de pb */
                trackErrorThroughEvent(err,'lienSortant : '+lien.href );
            }
        });
    }
})();