jQuery(document).ready(function($) {
    'use strict';
    disableSendButton();
    $('#url_shortener_start').on('click', function(event) {
        event.preventDefault();
        showPluginForm();
        hideStartButton();
    });
    $('#mm_shorturl_clear_button').on('click', function(event) {
        event.preventDefault();
        clearPluginForm();
    });
    $('#mm_shorturl_submit_button').on('click', function(event) {
        event.preventDefault();
        disableSendButton();
        displayShortURL('Fetching...');
        clearFullURL();
        submitAjaxRequest();
    });
    $('.url_shortener_input').on('change keypress', function(event) {
        clearURLs();
    });
    $('.url_shortener_required_input').on('change keypress', function(event) {
        if (areRequiredFieldsFilled()) {
            enableSendButton();
        } else {
            disableSendButton();
        }
    });


    function areRequiredFieldsFilled() {
        return (!!$.trim($("#url_shortener_name").val()) &&
                !!$("#url_shortener_source option:selected").val() &&
                !!$("#url_shortener_medium option:selected").val());
    }

    function submitAjaxRequest() {
        var requestData = {
            action:    'shorten_url',
            permalink: $('#url_shortener_permalink').val(),
            nonce:     $('#url_shortener_nonce').val(),
            source:    $('#url_shortener_source').val(),
            medium:    $('#url_shortener_medium').val(),
            term:      $('#url_shortener_term').val(),
            content:   $('#url_shortener_content').val(),
            name:      $('#url_shortener_name').val()
        };
        $.post(ajaxurl, requestData, function(response) {
            replaceNonce(response.data.new_nonce);
            processResponse(response.data.message, response.data.full_url);
        }).fail(function(jqXHR, textStatus) {
            console.log(jqXHR.responseText);
            displayShortURL("There was an error processing your request: " + textStatus);
        });
    }

    function processResponse(messageJSON, full_url) {
        var messageCode,
            messageBodyCode;
        if (typeof messageJSON === "string") {
            messageCode = messageJSON;
        } else {
            messageCode = messageJSON.response.code;
        }

        if (messageCode == 422) {
            messageBodyCode = JSON.parse(messageJSON.body).fields[0].code;
            if (messageBodyCode == 'NONEXIST_DOMAIN') {
                displayShortURL('Check the Short URL Domain in Settings. Invalid Domain.');
            } else if (messageBodyCode == 'INVALID_TARGET_URL') {
                displayShortURL('There is a problem with the text used in the fields or the Permalink. Invalid URL.');
            }
        } else if (messageCode == 401) {
            displayShortURL('Check the Short URL API Key in Settings. Invalid API Key.');
        } else if (messageCode == 200) {
            displayShortURL(messageJSON.body);
            displayFullURL(full_url);
        } else {
            console.log(messageCode);
            displayShortURL('Check your connection and that the configured Service URL is valid. Unable to reach the service.');
        }
        enableSendButton();
    }

    function displayShortURL(shortURL) {
        $('#mm_shorturl_output_url').val(shortURL);
    }

    function displayFullURL(fullURL) {
        $('#mm_shorturl_output_full_url').val(fullURL)
    }

    function clearURLs() {
        clearShortURL();
        clearFullURL();
    }

    function clearShortURL() {
        $('#mm_shorturl_output_url').val('');
    }

    function clearFullURL() {
        $('#mm_shorturl_output_full_url').val('');
    }

    function clearPluginForm() {
        $('#url_shortener_source option').each(function(index, option) {
            $(option).attr('selected',false);
        });
        $('#url_shortener_source_default').attr('selected',true);
        $('#url_shortener_medium option').each(function(index, option) {
            $(option).attr('selected',false);
        });
        $('#url_shortener_medium_default').attr('selected',true);
        $('#url_shortener_term').val('');
        $('#url_shortener_content').val('');
        $('#url_shortener_name').val('');
        $('#mm_shorturl_output_url').html('');
        disableSendButton();
    }

    function replaceNonce(newNonce) {
        $('#url_shortener_nonce').val(newNonce);
    }

    function enableSendButton() {
        var sendButton = $('#mm_shorturl_submit_button');
        if (sendButton.hasClass('disabled')) {
            sendButton.removeClass('disabled');
        }
    }

    function disableSendButton() {
        var sendButton = $('#mm_shorturl_submit_button');
        if (!sendButton.hasClass('disabled')) {
            sendButton.addClass('disabled');
        }
    }

    function showPluginForm() {
        $('#mm_shorturl_form').removeClass('hidden');
    }

    function hidePluginForm() {
        $('#mm_shorturl_form').addClass('hidden');
    }

    function showStartButton() {
        $('#url_shortener_start').removeClass('hidden');
    }

    function hideStartButton() {
        $('#url_shortener_start').addClass('hidden');
    }

});