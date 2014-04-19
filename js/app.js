/**
 * @file
 *
 * This app.js from foundation adapted to Drupal.
 */
(function ($, Drupal) {

    Drupal.behaviors.foundation = {
        attach: function(context, settings) {
            $(document).foundation();
        }
    };

})(jQuery, Drupal);
