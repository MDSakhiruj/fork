jQuery(function ($) {
    $( 'div[data-dismissible] button.notice-dismiss, div[data-dismissible] .dismiss-this' ).on("click", function (event) {

            event.preventDefault();

            var $this = $( this );

            var attr_value, option_name, dismissible_length, data;

            attr_value = $this.closest("div[data-dismissible]").attr( 'data-dismissible' ).split( '-' );

            dismissible_length = attr_value.pop();

            option_name = attr_value.join( '-' );

            data = {
                'action': 'wpspeedo_dismiss_admin_notice',
                'option_name': option_name,
                'dismissible_length': dismissible_length,
                'nonce': wpspeedo_dismissible_notice.nonce
            };

            $.post( ajaxurl, data );
            
            $this.closest("div[data-dismissible]").stop().fadeTo( 100, 0, function() {
                $(this).slideUp( 300, function() {
                    $(this).remove();
                });
            });

        }
    );
});