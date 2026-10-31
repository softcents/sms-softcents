$(document).ready(function() {
    $(".tags-btn").on("click", function() {
        $(".tags-btn").removeClass("blogs-tag-btn-selected").addClass("blogs-tag-btn-unselected");
        $(this).removeClass("blogs-tag-btn-unselected").addClass("blogs-tag-btn-selected");
    });
});

//load more data Start
$(document).ready(function() {
    $('#loadMore').on("click", function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        var lastBlogId = $('.blog:fast').data('id');
        $.ajax({
            url: url,
            type: 'GET',
            dataType: "json",
            data: { last_blog_id: lastBlogId },
            success: function(response) {
                $('#blogContainer').append(response);
            }
        });
    });
});
//Load more data end
