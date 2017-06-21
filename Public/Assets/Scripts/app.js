var Application = (function () {

    var pageHandlers = {}, currentHandler;

    var Application = function () {

        var $notFoundTemplate = $('#not-found'), $mainContent = $('body div#main');

        $(window).on('hashchange', function () {

            if (
                currentHandler && currentHandler !== window.location.hash &&
                pageHandlers[currentHandler] && typeof pageHandlers[currentHandler].leave === 'function'
            ) {

                pageHandlers[currentHandler].leave();
            }

            currentHandler = window.location.hash;

            requestAnimationFrame(function () {

                var $template = $(currentHandler);

                $mainContent.html($template.length ? $template.html() : $notFoundTemplate.html());

                if (pageHandlers[currentHandler] && typeof pageHandlers[currentHandler].enter === 'function') {

                    pageHandlers[currentHandler].enter();
                }
            });
        });
    };

    Application.prototype.page = function (identifier, enter, leave) {

        if (typeof pageHandlers[identifier] !== 'undefined') {

            throw new Error('Identifier ' + identifier + ' already registered.');
        }

        pageHandlers[identifier] = {
            enter: enter,
            leave: leave
        };
    };

    return new Application();
})();