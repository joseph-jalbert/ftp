jQuery(document).ready(function($) {
    'use strict';
    disableSendButton();
    $('#mm_shorturl_any_clear_button').on('click', function(event) {
        event.preventDefault();
        clearURLs();
    });
    $('#mm_shorturl_any_submit_button').on('click', function(event) {
        executeSubmit(event);
    });
    $('#mm_shorturl_any_input_url').on('change keypress', function(event) {
        if (!!$.trim($("#mm_shorturl_any_input_url").val())) {
            if (event.which == 13) {
                executeSubmit(event);
            } else {
                enableSendButton();
            }
        } else {
            disableSendButton();
        }
    });

    function executeSubmit(event) {
        event.preventDefault();
        disableSendButton();
        displayShortURL('Fetching...');
        submitAjaxRequest();
    }

    function submitAjaxRequest() {
        var requestData = {
            action:    'shorten_any_url',
            targeturl: $('#mm_shorturl_any_input_url').val(),
            nonce:     $('#url_shortener_nonce').val()
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
            } else {
                displayShortURL('Error received: ' + messageBodyCode);
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
        $('#mm_shorturl_any_output_url').val(shortURL);
    }

    function clearURLs() {
        clearShortURL();
        clearInputURL();
    }

    function clearShortURL() {
        $('#mm_shorturl_any_output_url').val('');
    }

    function clearInputURL() {
        $('#mm_shorturl_any_input_url').val('');
    }

    function replaceNonce(newNonce) {
        $('#url_shortener_nonce').val(newNonce);
    }

    function enableSendButton() {
        var sendButton = $('#mm_shorturl_any_submit_button');
        if (sendButton.hasClass('disabled')) {
            sendButton.removeClass('disabled');
        }
    }

    function disableSendButton() {
        var sendButton = $('#mm_shorturl_any_submit_button');
        if (!sendButton.hasClass('disabled')) {
            sendButton.addClass('disabled');
        }
    }
});