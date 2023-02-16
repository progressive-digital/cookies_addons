/**
 * @file
 * Defines Javascript behaviors for the cookies addons embed iframe module.
 */
(function (Drupal, $) {
  'use strict';

  /**
   * Define defaults.
   */
  Drupal.behaviors.cookiesAddonsEmbedIframe = {
    consentGiven: function (context) {
      $('iframe.cookies-addons-embed-iframe', context).each(function (i, element) {
        var $element = $(element);
        if ($element.attr('src') !== $element.data('src')) {
          $element.attr('src', $element.data('src'));
        }
      });
    },

    consentDenied: function (context) {
      $('iframe.cookies-addons-embed-iframe, div.iframe-embed-lazy', context).cookiesOverlay('iframe');
    },

    attach: function (context) {
      var self = this;
      document.addEventListener('cookiesjsrUserConsent', function(event) {
        var service = (typeof event.detail.services === 'object') ? event.detail.services : {};
        if (typeof service.iframe !== 'undefined' && service.iframe) {
          self.consentGiven(context);
        } else {
          self.consentDenied(context);
        }
      });
    }
  };

})(Drupal, jQuery);
