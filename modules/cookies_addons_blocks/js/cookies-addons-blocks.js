/**
 * @file
 * Defines Javascript behaviors for the Cookies Addons blocks module.
 */
(function (Drupal, $) {
  'use strict';

  /**
   * Define defaults.
   */
  Drupal.behaviors.cookiesAddonsBlocks = {
    activate: function (service, context) {
      $('.cookies-addons-blocks-placeholder', context).each( function () {
        if (!$(this).hasClass('request-sent')) {
          // Handle blocks.
          var $container = $(this).parents('.block');
          if ($container.length) {
            $container.replaceWith($(this));
          }

          var cookies_service = $(this).data('cookies-service');
          if (cookies_service === service) {
            $(this).addClass('request-sent');

            var block_id = $(this).data('block-id');

            Drupal.ajax({
              url: '/cookies-addons-blocks/get-block/' + block_id + '/' + service,
              type: "POST",
            }).execute();
          }
        }
      });
    },

    fallback: function (service, context) {
      $('.cookies-addons-blocks-placeholder[data-cookies-service="' + service + '"]', context).each( function () {
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
