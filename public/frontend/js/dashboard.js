$(document).ready(function() {
    $("#menu-btn").click(function() {
        $("#sidebar").toggleClass("active-nav");
        $(".my-container").toggleClass("active-cont");
        if ($(window).width() < 567) {
            if ($("#main").hasClass("is_active")) {
                $("#main").removeClass("is_active");
                console.log('has');
            } else {
                console.log('no');
                $("#main").addClass("is_active");
            }
        }
    });

    $(window).on('resize', function() {
        if ($(window).width() < 767) {
            $("#sidebar").removeClass("active-nav");
            $(".my-container").removeClass("active-cont");
        } else if ($(window).width() >= 767 && $(window).width() < 992) {
            $("#sidebar").addClass("active-nav");
            $(".my-container").addClass("active-cont");
        }
    }).trigger('resize');
});
