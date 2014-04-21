/**
 * @file
 *
 * Drupal js version of app.js from foundation
 */
(function ($, Drupal) {

    Drupal.behaviors.foundation = {
        attach: function(context, settings) {
            $(document).foundation();
        }
    };

})(jQuery, Drupal);
