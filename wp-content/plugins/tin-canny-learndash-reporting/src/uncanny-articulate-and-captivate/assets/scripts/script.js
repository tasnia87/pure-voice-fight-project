jQuery(document).ready(function($){
  $( '.AnC-iFrame' ).each( function() {
    var src = $( this ).attr( 'data-src' );
    $( this ).attr( 'src', src );
  });

  $("a.nivo_iframe").each( function() {
    var options={};
    options.theme="default";
    if ( $(this).attr("data-transition") ) {
      options.effect=$(this).attr("data-transition");
    }

    var width = $(this).attr("data-width");
    var height = $(this).attr("data-height");

    function nivo_resize() {
      var wrap_height = $( '.nivo-lightbox-wrap' ).height();
      var content_height = $( '.nivo-lightbox-content' ).height();

      $( '.nivo-lightbox-wrap' ).stop();

      if ( wrap_height > content_height ) {
        $( '.nivo-lightbox-wrap' ).animate({
          'padding-top' : ( wrap_height - content_height ) / 2 + 'px'
        });
      } else {
        $( '.nivo-lightbox-wrap' ).animate({
          'padding-top' : '0px'
        });
      }
    }

    options.afterShowLightbox = function() {
      if( width ) {
        $( '.nivo-lightbox-content' ).css({
          'max-width' : width
        });
      }

      if( height ) {
        $( '.nivo-lightbox-content' ).css({
          'max-height' : height
        });

        nivo_resize();
        $( window ).resize(function() {
          nivo_resize();
        });
      }
    };

    $(this).nivoLightbox( options );
  });
});
