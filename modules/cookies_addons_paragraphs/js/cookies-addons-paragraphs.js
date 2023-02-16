/**
 * @file
 * Defines Javascript behaviors for the Cookies Addons paragraphs module.
 */
(function (Drupal, $) {
  'use strict';

  /**
   * Define defaults.
   */
  Drupal.behaviors.cookiesAddonsParagraphs = {
    activate: function (service, context) {
      $('.cookies-addons-paragraph-placeholder', context).each( function () {
        if (!$(this).hasClass('request-sent')) {
          // Handle paragraphs.
          var $container = $(this).parents('.paragraph');
          if ($container.length) {
            $container.replaceWith($(this));
          }

          var cookies_service = $(this).data('cookies-service');
          if (cookies_service === service) {
            $(this).addClass('request-sent');

            var paragraph_id = $(this).data('paragraph-id');

            Drupal.ajax({
              url: '/cookies-addons-paragraphs/get-paragraph/' + paragraph_id + '/' + service,
              type: "POST",
            }).execute();
          }
        }
      });
    },

    fallback: function (service, context) {
      $('.cookies-addons-paragraph-placeholder[data-cookies-service="' + service + '"]', context).each( function () {
        var service_name = $(this).data('service-name');
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
