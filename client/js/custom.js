/* custom scripts */
(function ($) {
    let wrapper = null;
    let startButton = null;
    let stopButton = null;
    let container = null;
    let pBar = null;
    let timeout = null;
    let inProgress = false;

    $.fn.autoLogOut = function (opts) {
        let options = $.extend({}, $.fn.autoLogOut.defaults, opts);
        if (!Number.isInteger(options.seconds) || options.seconds <= 0) {
            console.log('autoLogOut.options.seconds must be a positive integer. Switching to default value');
            options.seconds = $.fn.autoLogOut.defaults.seconds;
        }
        if (!$.isFunction(options.callback)) {
            console.log('autoLogOut.options.callback must be a function.  Switching to default function');
            options.callback = $.fn.autoLogOut.defaults.callback;
        }
        if(options.startButtonClass === null || options.startButtonClass.trim().length === 0) {
            options.startButtonClass = $.fn.autoLogOut.defaults.startButtonClass;
        }
        if(options.stopButtonClass === null || options.stopButtonClass.trim().length === 0) {
            options.stopButtonClass = $.fn.autoLogOut.defaults.stopButtonClass;
        }        
        wrapper = $(this);
        wrapper.on('stop', function() {
            stop();
        });
        wrapper.on('start', function() {
            start();
        })
        if(!wrapper.hasClass('auto-LogOut')) {
            wrapper.addClass('auto-LogOut');
        }
        if (options.showControls) {
            stopButton = $('<button />')
                            .attr({type: 'button', class: options.stopButtonClass})
                            .html(options.stopButtonInner)
                            .appendTo(wrapper);
            stopButton.on('click', function() {
                stop();
            });

            startButton = $('<button />')
                            .attr({type: 'button', class: options.startButtonClass})
                            .html(options.startButtonInner)
                            .appendTo(wrapper);
            startButton.on('click', function() {
                start();
            });
        }


        if(container === null) {
            container = $('<div />')
                            .attr({'class': 'auto-LogOut-container'})
                            .css({'height': options.progressBarHeight})
                            .css({'background-color': options.backgroundColor})
                            .appendTo(wrapper);
        }

        if(pBar === null) {
            pBar = $('<span />')
                        .attr({'class': 'auto-LogOut-progress-bar'})
                        .css({'background-color': options.foregroundColor})
                        .appendTo(container);
        }

        start();

        function start() {
            if(inProgress) {return;}
            inProgress = true;
            pBar.css({'transition': options.seconds + 's linear'});
            pBar.width('100%');
            timeout =  window.setTimeout(function () {
                pBar.css({'transition': ''});
                if ($.isFunction(options.callback)) {
                    options.callback();
                }
            }, options.seconds * 1000);
        }
        
        function stop() {
            if(!inProgress) {return;}
            inProgress = false;
            pBar.css({'transition': ''});
            pBar.width('0%');
            window.clearTimeout(timeout);
        }
    }

    $.fn.autoLogOut.defaults = {
        seconds: 300,
        callback: function () {
            location.reload();
        },
        showControls: true,
        progressBarHeight: '2px',
        stopButtonClass: 'auto-LogOut-button',
        stopButtonInner: 'Stop',
        startButtonClass: 'auto-LogOut-button',
        startButtonInner: 'Start',
        backgroundColor: '#6c757d',
        foregroundColor: '#880000'
    }

})(jQuery);

$('.auto-LogOut').autoLogOut({
	seconds: 1430,
    callback: function () {
		logOut();
    },
    progressBarHeight: '2px',
    showControls: false
});


$(".quit").click(function(e){
    e.preventDefault();
    window.location.href = "https://www.google.com/";
});

function logOut()
    {
        $.ajax({
            type: "GET",
            url: "logout.php",
            success: function () {
               $('#loggedout').modal({backdrop: 'static', keyboard: false});
            }  
        });
}
		 