/**
 * @file
 * Defines Javascript behaviors for the Cookies Addons views module.
 */
(function (Drupal, $) {
  'use strict';

  /**
   * Define defaults.
   */
  Drupal.behaviors.cookiesAddonsViews = {
    activate: function (service, context) {
      $('.cookies-addons-views-placeholder', context).each( function () {
        if (!$(this).hasClass('request-sent')) {
          // Handle views added by the viewsreference module.
          var $container = $(this).parents('.views-element-container');
          if ($container.length) {
            $container.replaceWith($(this));
          }

          var cookies_service = $(this).attr('cookies-service');
          if (cookies_service === service) {
            $(this).addClass('request-sent');

            var view_id = $(this).attr('view-id');
            var display_id = $(this).attr('display-id');
            var args = $(this).data('args');

            Drupal.ajax({
              url: '/cookies-addons-views/get-view/' + view_id + '/' + display_id + '/' + service + '/' + args,
            }).execute();
          }
        }
      });
    },

    fallback: function (service, context) {
      $('.cookies-addons-views-placeholder[cookies-service="' + service + '"]', context).each( function () {
        var service_name = $(this).attr('service-name');
        $(this).cookiesOverlay(service, service_name);
      });
    },

    attach: function (context) {
      var self = this;

      document.addEventListener('cookiesjsrUserConsent', function(event) {
        var services = (typeof event.detail.services === 'object') ? event.detail.services : {};
        $.each(services, function (service, accepted) {
          if (accepted) {
            self.activate(service, context);
          }
          else {
            self.fallback(service, context);
          }
        });
      });
    }
  };

})(Drupal, jQuery);
