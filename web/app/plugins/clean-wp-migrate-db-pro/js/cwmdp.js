jQuery(document).ready(function ($) {


    var $statusWrapInner = $('.cwmdp-log'),
        optionCounter = 0,

        log = function (msg, error, bold) {

            var $note = $('<p>');

            if (error) {
                $note.css('color', 'red');
            }
            if (bold) {
                $note.css('font-weight', 'bold');
            }
            $note.append(msg);

            $statusWrapInner.append($note);

        },

        complete = function () {
            optionCounter = 0;
            log('Complete!', false, true);

        },
        deleteOption = function (option, callback) {

            var data = {
                'action': 'cwmdp_clear_option',
                'option': option,
                'security': cwmdp.security
            };

            $.post(ajaxurl, data, function (response) {

                if (!response.success) {
                    log("There's been an error... shutting down process", true, true);
                    return false;
                } else {
                    log('Deleted ' + option);
                    callback();
                }


            });

        },
        deleteOptions = function (arr) {
            deleteOption(arr[optionCounter], function () {
                optionCounter++;
                if (optionCounter < arr.length) {
                    deleteOptions(arr);
                } else {
                    complete();
                    return true;
                }
            });
        };


    $('#clean-wp-migrate-db-pro').on('click', function () {

        var data = {
            'action': 'cwmdp_get_options',
            'security': cwmdp.security
        };
        $.post(ajaxurl, data, function (response) {

            var singular = 'option',
                plural = 'options',
                options = plural,
                numberElements = response.data.options.length;

            if (numberElements === 1) {
                options = singular;
            }

            log('Grabbed ' + response.data.options.length + ' ' + options);

            if (numberElements === 0) {
                log("There are no options to clear, we're done here", false, true);
                complete();
                return true;
            }
            optionCounter = 0;

            deleteOptions(response.data.options);


        });


    });

});