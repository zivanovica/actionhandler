(function () {
    var initialize = function () {

        $(document).on('submit', 'form#submit-idea', function (event) {

            event.preventDefault();

            console.log('submitting idea.', $(this).serialize());
        });
    };

    var destroy = function () {

        $(document).off('submit', 'form#submit-idea');
    };

    Application.page('#new-idea', initialize, destroy);
})();
