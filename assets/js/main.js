$(document).ready(function () {
    $('#place-of-birth').autocomplete({
        source: place_of_birth,
        minLength: 2
    });

    $("table thead th:eq(0)").data("sorter", false);


    $("#creatures-list-wrapper").tablesorter({
        headers: {
            0: { sorter: false },
            2: { sorter: false },
            3: { sorter: false },
            5: { sorter: false },
            6: { sorter: false },
            7: { sorter: false },
            9: { sorter: false }
        }
    });

    $('#creature-form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
        } else {
            e.preventDefault();

            $.ajax({
                url: "process.php",
                type: "post",
                data: { module: 'creatures', action: 'save', data: $('#creature-form').serialize() },
                dataType: 'json',
                success: function (response) {
                    $('#creatures-modal').modal('hide');

                    if (response.success) {
                        $('.alert-success').removeClass('hidden');
                        $('.alert-success').html(response.message);

                        $.ajax({
                            url: "process.php",
                            type: "get",
                            data: { module: 'creatures', action: 'reload' },
                            dataType: "json",
                            success: function (data) {
                                $('#creature-form')[0].reset();
                                if (data.success) {
                                    $('#creatures-list-container').html(data.html);
                                }
                            }
                        });

                        setTimeout(function () { $('.alert-success').addClass('hidden'); }, 3000);
                    } else {
                        $('.alert-danger').removeClass('hidden');
                        $('.alert-danger').html(response.message);

                        setTimeout(function () { $('.alert-danger').addClass('hidden'); }, 3000);
                    }
                }
            });
        }
    })

    $('#password-form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
        } else {
            e.preventDefault();

            $.ajax({
                url: "process.php",
                type: "post",
                data: { module: 'password', action: 'validate', data: $('#password-form').serialize() },
                dataType: 'json',
                success: function (response) {
                    $('#password-modal').modal('hide');
                    $('#password-form')[0].reset();

                    if (response.success) {
                        if ($('#page').val() == 'crimes') {
                            $('#crimes-modal').modal('show');
                        } else if ($('#page').val() == 'notes') {
                            $('#notes-modal').modal('show');
                        } else {
                            $('#creatures-modal').modal('show');
                        }
                    } else {
                        $('.alert-danger').removeClass('hidden');
                        $('.alert-danger').html(response.message);

                        setTimeout(function () { $('.alert-danger').addClass('hidden'); }, 3000);
                    }
                }
            });
        }
    })

    $(document).on('click', '#toggle-new-creature', function () {
        $('#creature-form').submit();
    });

    $(document).on('click', '.toggle-crimes', function () {
        var _creature_id = $(this).data('id');
        $('#creature').val(_creature_id);
        $('#page').val('crimes');
        $('#password-modal').modal('show');
        // $('#crimes-modal').modal('show');
    });

    $(document).on('click', '.toggle-notes', function () {
        var _creature_id = $(this).data('id');
        $('#creature').val(_creature_id);
        $('#page').val('notes');
        $('#password-modal').modal('show');
        // $('#notes-modal').modal('show');
    });

    $(document).on('click', '#toggle-save-crime', function () {
        var _creature_id = $('#creature').val();

        if (_creature_id) {
            $.ajax({
                url: "process.php",
                type: "post",
                data: { module: 'crimes', action: 'save', data: { id: _creature_id, form: $('#crimes-form').serialize() } },
                dataType: 'json',
                success: function (response) {
                    $('#crimes-modal').modal('hide');
                    $('#crimes-form')[0].reset();

                    if (response.success) {
                        $('.alert-success').removeClass('hidden');
                        $('.alert-success').html(response.message);

                        $.ajax({
                            url: "process.php",
                            type: "get",
                            data: { module: 'creatures', action: 'reload' },
                            dataType: "json",
                            success: function (data) {
                                $('#creature-form')[0].reset();
                                if (data.success) {
                                    $('#creatures-list-container').html(data.html);
                                }
                            }
                        });

                        setTimeout(function () { $('.alert-success').addClass('hidden'); }, 3000);
                    } else {
                        $('.alert-danger').removeClass('hidden');
                        $('.alert-danger').html(response.message);

                        setTimeout(function () { $('.alert-danger').addClass('hidden'); }, 3000);
                    }
                }
            });
        }
    });

    $(document).on('click', '#toggle-save-note', function () {
        var _creature_id = $('#creature').val();

        if (_creature_id) {
            $.ajax({
                url: "process.php",
                type: "post",
                data: { module: 'notes', action: 'save', data: { id: _creature_id, form: $('#notes-form').serialize() } },
                dataType: 'json',
                success: function (response) {
                    $('#notes-modal').modal('hide');
                    $('#notes-form')[0].reset();

                    if (response.success) {
                        $('.alert-success').removeClass('hidden');
                        $('.alert-success').html(response.message);

                        $.ajax({
                            url: "process.php",
                            type: "get",
                            data: { module: 'creatures', action: 'reload' },
                            dataType: "json",
                            success: function (data) {
                                $('#creature-form')[0].reset();
                                if (data.success) {
                                    $('#creatures-list-container').html(data.html);
                                }
                            }
                        });

                        setTimeout(function () { $('.alert-success').addClass('hidden'); }, 3000);
                    } else {
                        $('.alert-danger').removeClass('hidden');
                        $('.alert-danger').html(response.message);

                        setTimeout(function () { $('.alert-danger').addClass('hidden'); }, 3000);
                    }
                }
            });
        }
    });

    $(document).on('click', '.action-navigation input:button', function (e) {
        e.stopImmediatePropagation();
    });

    $(document).on('click', '.collapse-details', function (e) {
        var _id = $(this).data('id');

        $('.collapse-details-row').addClass('hidden');
        $('#collapse-details-' + _id).removeClass('hidden');
        $(this).addClass('close-details');
    });

    $(document).on('click', '.close-details', function () {
        var _id = $(this).data('id');
        $('#collapse-details-' + _id).addClass('hidden');
        $(this).removeClass('close-details');
    });

    $(document).on('change', '#is-punished', function () {
        $.ajax({
            url: "process.php",
            type: "post",
            data: { module: 'creatures', action: 'filter-is-punished', flag: $(this).is(':checked'), no_of_crimes: $('#no-of-crimes').val() },
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    $('#creatures-list-container').html(data.html);
                }
            }
        });
    });

    $(document).on('change', '#filter-race', function () {
        $.ajax({
            url: "process.php",
            type: "post",
            data: { module: 'creatures', action: 'filter-race', race: $(this).val() },
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    $('#creatures-list-container').html(data.html);
                }
            }
        });
    });

    $(document).on('click', '.toggle-deceased', function () {
        if (confirm('Mark the selected creature as deceased?')) {
            $.ajax({
                url: "process.php",
                type: "post",
                data: { module: 'creatures', action: 'mark-deceased', id: $(this).data('id') },
                dataType: 'json',
                success: function (data) {
                    if (data.success) {
                        $('#creatures-list-container').html(data.html);
                    }
                }
            });
        }
    });

    $(document).on('click', '#toggle-add-creature', function () {
        $('#password-modal').modal('show');
    });

    $(document).on('click', '#toggle-validate', function () {
        $('#password-form').submit();
    });

    $(document).on('click', '#toggle-logout', function () {
        $.ajax({
            url: "process.php",
            type: "post",
            data: { module: 'user', action: 'logout' },
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    location.href = 'index.php';
                }
            }
        });
    });

    $(document).on('click', '#toggle-login', function () {
        $.ajax({
            url: "process.php",
            type: "post",
            data: { module: 'user', action: 'login', data: $('#password-form').serialize() },
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    location.href = 'index.php';
                } else {
                    alert(data.message);
                }
            }
        });
    });

    // $(document).on('change', '#no-of-crimes', function () {
    //     $.ajax({
    //         url: "process.php",
    //         type: "post",
    //         data: { module: 'creatures', action: 'filter-no-of-crimes', no_of_crimes: $('#no-of-crimes').val() },
    //         dataType: 'json',
    //         success: function (data) {
    //             if (data.success) {
    //                 $('#creatures-list-container').html(data.html);
    //             }
    //         }
    //     });
    // });

});
