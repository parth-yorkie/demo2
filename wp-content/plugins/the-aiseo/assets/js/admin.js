jQuery(document).ready(function ($) {

    // Individual Generate click
    $('.aiseo-generate').on('click', function () {
        var $btn = $(this);
        var postId = $btn.data('id');
        var $row = $('#aiseo-item-' + postId);
        var $spinner = $row.find('.spinner');
        var $suggestionRow = $('#aiseo-suggestion-' + postId);

        $btn.prop('disabled', true);
        $spinner.addClass('is-active');

        $.ajax({
            url: the_aiseo_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'the_aiseo_generate',
                nonce: the_aiseo_ajax.nonce,
                post_id: postId
            },
            success: function (response) {
                if (response.success) {
                    $suggestionRow.find('.aiseo-suggested-title').val(response.data.title);
                    $suggestionRow.find('.aiseo-suggested-desc').val(response.data.description);
                    $suggestionRow.show();
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function () {
                alert('Ajax error');
            },
            complete: function () {
                $btn.prop('disabled', false);
                $spinner.removeClass('is-active');
            }
        });
    });

    // Individual Regenerate click (both bottom and field-level)
    $(document).on('click', '.aiseo-regenerate, .aiseo-regenerate-single', function () {
        var $btn = $(this);
        var postId = $btn.data('id');
        var $suggestionRow = $('#aiseo-suggestion-' + postId);
        var $spinner = $suggestionRow.find('.aiseo-regenerate-spinner');

        $btn.prop('disabled', true);
        $spinner.addClass('is-active');

        $.ajax({
            url: the_aiseo_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'the_aiseo_generate',
                nonce: the_aiseo_ajax.nonce,
                post_id: postId
            },
            success: function (response) {
                if (response.success) {
                    $suggestionRow.find('.aiseo-suggested-title').val(response.data.title);
                    $suggestionRow.find('.aiseo-suggested-desc').val(response.data.description);

                    // If it was a field-level regenerate, show a success tick
                    if ($btn.hasClass('aiseo-regenerate-single')) {
                        var originalHtml = $btn.html();
                        $btn.html('<span class="dashicons dashicons-yes aiseo-success-icon"></span>');
                        setTimeout(function () {
                            $btn.html(originalHtml).prop('disabled', false);
                        }, 2000);
                    }
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function () {
                alert('Ajax error');
            },
            complete: function () {
                if (!$btn.hasClass('aiseo-regenerate-single')) {
                    $btn.prop('disabled', false);
                }
                $spinner.removeClass('is-active');
            }
        });
    });

    $(document).on('click', '.aiseo-cancel', function () {
        var postId = $(this).data('id');
        $('#aiseo-suggestion-' + postId).hide();
    });

    // Bulk actions
    $('.aiseo-bulk-btn').on('click', function () {
        if (!confirm('Are you sure you want to bulk generate meta for this post type? This might take a few minutes.')) {
            return;
        }

        var $btn = $(this);
        var postType = $btn.data('post-type');
        var fieldType = $btn.data('type');
        var total = parseInt($btn.data('total'));
        var processed = 0;

        $('.aiseo-bulk-btn').prop('disabled', true);
        $('#aiseo-bulk-progress').show();
        updateProgress(0, total);

        function processBatch() {
            $.ajax({
                url: the_aiseo_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'the_aiseo_bulk_process',
                    nonce: the_aiseo_ajax.nonce,
                    post_type: postType,
                    field_type: fieldType
                },
                success: function (response) {
                    if (response.success) {
                        if (response.data.done) {
                            $('.aiseo-progress-text').text('Bulk process complete! Refreshing...');
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            processed += response.data.processed;
                            updateProgress(processed, total);
                            processBatch();
                        }
                    } else {
                        var errorMsg = (response.data && response.data.message) ? response.data.message : response.data;
                        $('.aiseo-progress-text').text('Bulk error: ' + errorMsg);
                        $('.aiseo-bulk-btn').prop('disabled', false);
                    }
                },
                error: function () {
                    $('.aiseo-progress-text').text('Bulk ajax error occurred.');
                    $('.aiseo-bulk-btn').prop('disabled', false);
                }
            });
        }

        function updateProgress(current, total) {
            var displayTotal = Math.max(current, total);
            var percent = displayTotal > 0 ? Math.min(100, Math.round((current / displayTotal) * 100)) : 100;
            $('.aiseo-progress-inner').css('width', percent + '%');
            $('.aiseo-progress-text').text('Processing ' + current + ' / ' + displayTotal + ' items (' + percent + '%)');
        }

        processBatch();
    });

    // Apply & Save
    $(document).on('click', '.aiseo-save', function () {
        var $btn = $(this);
        var postId = $btn.data('id');
        var $suggestionRow = $('#aiseo-suggestion-' + postId);
        var title = $suggestionRow.find('.aiseo-suggested-title').val();
        var description = $suggestionRow.find('.aiseo-suggested-desc').val();

        $btn.prop('disabled', true);

        $.ajax({
            url: the_aiseo_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'the_aiseo_save',
                nonce: the_aiseo_ajax.nonce,
                post_id: postId,
                title: title,
                description: description
            },
            success: function (response) {
                if (response.success) {
                    $('#aiseo-item-' + postId).fadeOut(function () {
                        $(this).remove();
                        $suggestionRow.remove();
                    });
                } else {
                    alert('Error: ' + response.data);
                    $btn.prop('disabled', false);
                }
            }
        });
    });

    // Save single field
    $(document).on('click', '.aiseo-save-single', function () {
        var $btn = $(this);
        var postId = $btn.data('id');
        var type = $btn.data('type');
        var $suggestionRow = $('#aiseo-suggestion-' + postId);
        var $input = type === 'title' ? $suggestionRow.find('.aiseo-suggested-title') : $suggestionRow.find('.aiseo-suggested-desc');
        var value = $input.val();

        $btn.prop('disabled', true);

        var ajaxData = {
            action: 'the_aiseo_save',
            nonce: the_aiseo_ajax.nonce,
            post_id: postId
        };
        ajaxData[type] = value;

        $.ajax({
            url: the_aiseo_ajax.ajax_url,
            type: 'POST',
            data: ajaxData,
            success: function (response) {
                if (response.success) {
                    var originalHtml = $btn.html();
                    $btn.html('<span class="dashicons dashicons-yes aiseo-success-icon"></span>');
                    setTimeout(function () {
                        $btn.html(originalHtml).prop('disabled', false);
                    }, 2000);
                } else {
                    alert('Error: ' + response.data);
                    $btn.prop('disabled', false);
                }
            }
        });
    });
});
