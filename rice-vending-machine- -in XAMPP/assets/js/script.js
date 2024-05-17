"use strict";

/**ajax loader **/

const ajaxindicatorstart = (text) => {
    if($('body').find('#resultLoading').attr('id') !== 'resultLoading'){
        $('body').append(`<div id="resultLoading" style="display:none"><div><div>${text}</div></div><div class="bg"></div></div>`);
    }

    $('#resultLoading').css({
        'width':'100%',
        'height':'100%',
        'position':'fixed',
        'z-index':'10000000',
        'top':'0',
        'left':'0',
        'right':'0',
        'bottom':'0',
        'margin':'auto'
    });

    $('#resultLoading .bg').css({
        'background':'#000000',
        'opacity':'0.7',
        'width':'100%',
        'height':'100%',
        'position':'absolute',
        'top':'0'
    });

    $('#resultLoading>div:first').css({
        'width': '250px',
        'height':'75px',
        'text-align': 'center',
        'position': 'fixed',
        'top':'0',
        'left':'0',
        'right':'0',
        'bottom':'0',
        'margin':'auto',
        'font-size':'16px',
        'z-index':'10',
        'color':'#ffffff'
    });

    $('#resultLoading .bg').height('100%');
    $('#resultLoading').fadeIn(300);
    $('body').css('cursor', 'wait');
}

const ajaxindicatorstop = () => {
    $('#resultLoading .bg').height('100%');
    $('#resultLoading').fadeOut(300);
    $('body').css('cursor', 'default');
}

/**ajax loader **/

(function ($) {
    $("input").prop("autocomplete", "off");

    const snackbar = (text) => {
        const options = {
            text: text, duration: 5000, pos: 'bottom-center', actionTextColor: '#EB1616',
            textColor: '#000000', backgroundColor: '#FFFFFF'
        };

        Snackbar.show(options);
    }

    const ajax_error_message_rsp = function (jqXHR){
        switch (jqXHR.status) {
            case 0:
                return 'Not connect.\n Verify Network.';
            case 404:
                return 'Requested page not found. [404]';
            case 500:
                return 'Internal Server Error [500].';
            case 'parsererror':
                return 'Requested JSON parse failed.';
            case 'timeout':
                return 'Time out error.';
            case 'abort':
                return 'Ajax request aborted.';

            default:
                return 'Uncaught Error. Try reload the page.';
        }
    }

    const submitForm = (form) => {
        const deferred = $.Deferred();
        const data = new FormData(form);

        $.ajax({
            url: $(form).attr('action'),
            type: "POST",
            data: data,
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function() {
                ajaxindicatorstart("Please wait...");
                $('.error-message').html('');
            },
            error: function(jqXHR) {
                snackbar(ajax_error_message_rsp(jqXHR));
            },
            success: function(result) {
                deferred.resolve(result);
                if(result.validate === true) {
                    $(form).siblings('.error-message').html(`<div class="alert alert-danger">${result.message}</div>`);
                } else snackbar(result.message);

                if(result.redirect) {
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1500);
                }
            },
            complete: function() {
                $(form).find('input[type=text]').first().focus();
                ajaxindicatorstop();
            }
        });

        return deferred.promise();
    };

    $(document).on("submit", ".ajax-form", function(e) {
        e.preventDefault();
        submitForm(this);
    });

    const base_url = $('input[name=base_url]').val();
    const device_login = $('input[name=device_login]').val();

    let reconnectDelay = 2000;
    let isFillingContainer = false;
    let urlLast = window.location.href.split("/");
    urlLast = urlLast[urlLast.length - 1];

    const sendMessageToPlc = (payload, type) => {
        const deferred = $.Deferred();
        let request;

        const cancelRequest = () => {
            if (request && request.readyState !== 4) {
                request.abort();
                deferred.reject('Request canceled');
            }
        };

        const cancelTimeout = setTimeout(cancelRequest, reconnectDelay);

        request = $.ajax({
            url: `${base_url}plc`,
            type: type,
            data: payload,
            dataType: 'json',
            cache: false,
            error: function(jqXHR) {
                snackbar(ajax_error_message_rsp(jqXHR));
                deferred.reject(jqXHR);
            },
            success: function(data) {
                deferred.resolve(data);

                if (data.error === false) {
                    if (urlLast === 'ws') {
                        window.location = `${base_url}`;
                    }

                    if(data.data.length === 11) {
                        const [emergency, maintenance, startStop, itemType, weight, realtimeWeight, error, hoppe8, hoppe9, hoppe10, hoppe11] = data.data;

                        if(emergency && urlLast !== 'emergency') {
                            window.location = `${base_url}page-errors/emergency`;
                        }

                        if(!emergency && urlLast === 'emergency') {
                            window.location = `${base_url}`;
                        }

                        if(maintenance && urlLast !== 'maintenance') {
                            window.location = `${base_url}maintenance`;
                        }

                        if(!maintenance && urlLast === 'maintenance') {
                            window.location = `${base_url}`;
                        }

                        if (!startStop && window.location.href.split("/").includes('filling')) {
                            window.location = `${base_url}`;
                        }
                    } else {
                        snackbar(data.message);
                    }
                } else {
                    if (urlLast !== 'ws') {
                        window.location = `${base_url}page-errors/ws`;
                    } else {
                        snackbar(data.message);
                    }
                }
            },
            complete: function () {
                clearTimeout(cancelTimeout);
            }
        });

        return deferred.promise();
    };

    // Send a message to the plc server
    const sendMessage = (action, payload = '') => {
        sendMessageToPlc(payload, (action === 'getData' ? 'get' : 'post'));
        setTimeout(reconnect, reconnectDelay);
    }

    const reconnect = () => {
        if (window.location.href.split("/").includes('filling') && isFillingContainer === false) {
            const container = parseInt($('input[name=container]').val());
            const weight = parseInt($('input[name=weight]').val());
            const csrf_token = $('input[name=csrf_token]').val();
            const values = [1, container, weight];
            const updatePlcData = { startRegister : 4198, values, csrf_token: csrf_token };
            isFillingContainer = true;

            sendMessage('updateData', updatePlcData);
        } else {
            sendMessage('getData');
        }
    }

    // Start checking the connection status
    if(device_login) {
        // reconnect();
    
        $.ajax(`${base_url}sync-report`);
    }
})(jQuery);