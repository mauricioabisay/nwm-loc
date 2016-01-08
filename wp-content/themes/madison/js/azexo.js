(function($) {
    "use strict";

    $('#page').css('visibility', 'hidden');
    $("#status").css('display', 'block');
    $("#preloader").css('display', 'block');
    $(window).load(function() {
        $('#page').css('visibility', 'visible');
        $("#status").fadeOut("slow");
        $("#preloader").fadeOut("slow");
        initAzexoPostMasonry();
    });

    $(window).scroll(function() {

        if ($(window).scrollTop() > header_main_top) {
            $('.site-header').addClass('scrolled');
            if ($('nav.mobile-menu').css('display') == 'none')
                $('.site-header .header-main').addClass('animated fadeInDown');
        } else {
            $('.site-header').removeClass('scrolled');
            if ($('nav.mobile-menu').css('display') == 'none')
                $('.site-header .header-main').removeClass('animated fadeInDown');
        }

    });
    $(window).resize(function() {
        //initAzexoPostList();
        initAzexoPostMasonry();
    });
    function initProductCategoriesWidget() {
        $('ul.product-categories li.cat-parent a').click(function(event) {
            event.stopPropagation();
        });
        $('ul.product-categories li.cat-parent').click(function() {
            var item = this;
            var children = $(this).find('> ul.children');
            if (children.css('display') == 'none') {
                children.stop(true, true).slideDown();
                children.show();
                $(item).find('> a').addClass('open');
            } else {
                children.stop(true, true).slideUp(400, function() {
                    children.hide();
                    $(item).find('> a').removeClass('open');
                });
            }
        });
    }
    function initProductGallery() {
        if ('flexslider' in $.fn) {
            $('.product .images:not(.thumbnails)').each(function() {
                var gallery = this;
                if ($(gallery).find('.image').length > 1) {
                    $(gallery).flexslider({
                        selector: '.image',
                        prevText: '',
                        nextText: '',
                        touch: true,
                        mousewheel: false,
                        controlNav: false
                    }).show();
                }
            });
            $('.product .images.thumbnails').each(function() {
                var gallery = this;
                if ($(gallery).find('.image').length > 1) {
                    var thumbnails = $('<div id="' + $(gallery).attr('id') + '-thumbnails" class="thumbnails"></div>').append('<ul class="slides"></ul>').insertAfter(gallery);
                    $(gallery).find('.image').each(function() {
                        $(this).clone().appendTo($('<li></li>').appendTo($(thumbnails).find('.slides')));
                    });
                    var itemWidth = parseInt($(thumbnails).find('ul.slides li').css('width'), 10);
                    if (!itemWidth)
                        itemWidth = 150;
                    var itemHeight = parseInt($(thumbnails).find('ul.slides li').css('height'), 10);
                    if (!itemHeight)
                        itemHeight = 150;
                    $(thumbnails).flexslider({
                        prevText: '',
                        nextText: '',
                        animation: "slide",
                        controlNav: false,
                        animationLoop: false,
                        slideshow: false,
                        itemWidth: itemWidth,
                        itemHeight: itemHeight,
                        touch: true,
                        mousewheel: false,
                        asNavFor: '#' + $(gallery).attr('id')
                    });

                    $(gallery).flexslider({
                        selector: '.image',
                        prevText: '',
                        nextText: '',
                        touch: true,
                        mousewheel: false,
                        controlNav: false,
                        sync: '#' + $(gallery).attr('id') + '-thumbnails'
                    }).show();
                }
            });
        }
    }
    function initPostGallery() {
        if ('flexslider' in $.fn) {
            $('.entry-gallery:not(.thumbnails) .gallery').each(function() {
                var gallery = this;
                $(gallery).find('> br').remove();
                $(gallery).flexslider({
                    selector: '.gallery-item',
                    prevText: '',
                    nextText: '',
                    touch: true,
                    mousewheel: false,
                    controlNav: false
                }).show();
            });
            $('.entry-gallery.thumbnails .gallery').each(function() {
                var gallery = this;
                $(gallery).find('> br').remove();
                var thumbnails = $('<div id="' + $(gallery).attr('id') + '-thumbnails" class="thumbnails"></div>').append('<ul class="slides"></ul>').insertAfter(gallery);
                $(gallery).find('.gallery-item').each(function() {
                    $(this).find('img').clone().appendTo($('<li></li>').appendTo($(thumbnails).find('.slides')));
                });
                var itemWidth = parseInt($(thumbnails).find('ul.slides li').css('width'), 10);
                if (!itemWidth)
                    itemWidth = 150;
                $(thumbnails).flexslider({
                    prevText: '',
                    nextText: '',
                    animation: "slide",
                    controlNav: false,
                    animationLoop: false,
                    slideshow: false,
                    itemWidth: itemWidth,
                    touch: true,
                    mousewheel: false,
                    asNavFor: '#' + $(gallery).attr('id')
                });

                $(gallery).flexslider({
                    selector: '.gallery-item',
                    prevText: '',
                    nextText: '',
                    touch: true,
                    mousewheel: false,
                    controlNav: false,
                    sync: '#' + $(gallery).attr('id') + '-thumbnails'
                }).show();

            });
        }
    }
    function initAzexoPostList() {
        if ('owlCarousel' in $.fn) {
            $('.owl-carousel.posts-list').each(function() {
                var carousel = this;
                var width = $(carousel).attr('data-width');
                var height = $(carousel).attr('data-height');
                if (typeof width !== typeof undefined && width !== false && typeof height !== typeof undefined && height !== false) {
                    if (height != '') {
                        $(carousel).find('.item .image').each(function() {
                            $(this).height(height);
                        });
                    }
                    if (width == '')
                        width = $(carousel).width();
                    var items = Math.round($(carousel).width() / width);
                    if(items > 1)
                        items = Math.ceil($(carousel).width() / width);
                    $(carousel).owlCarousel({
                        items: items,
                        center: true,
//                        margin: $(carousel).attr('data-margin'),
                        loop: true,
                        autoplay: true,
                        autoplayHoverPause: true,
                        nav: true,
                        dots: true,
                        navText: ['', '']
                    }).on('translated.owl.carousel', function(event) {
                        BackgroundCheck.refresh();
                    }).show();
                    BackgroundCheck.init({
                        targets: '.owl-carousel .owl-controls .owl-prev, .owl-carousel .owl-controls .owl-next',
                        images: '.owl-carousel .item .image'
                    });
                }
            });
        }
    }
    function initAzexoPostMasonry() {
        if ('masonry' in $.fn) {
            $('.site-content.masonry-post').each(function() {
                var grid = this;
                var width = $(grid).find('article .entry-thumbnail .image[data-width]').attr('data-width');
                var height = $(grid).find('article .entry-thumbnail .image[data-height]').attr('data-height');
                var columns = Math.ceil($(grid).width() / width);
                var columnWidth = $(grid).width() / columns;
                $(grid).find('article').css('width', columnWidth + 'px');
                var ratio = columnWidth / width;
                $(grid).find('article .entry-thumbnail .image').css('height', height * ratio + 'px');
                $(grid).masonry({
                    columnWidth: columnWidth,
                    itemSelector: 'article'
                });
            });
        }
    }
    function initAzexoPostCarousel() {
        if ('owlCarousel' in $.fn) {
            $('.owl-carousel:not(.posts-list)').each(function() {
                var carousel = this;
                var width = $(carousel).attr('data-width');
                var height = $(carousel).attr('data-height');
                $(carousel).find('.item .image').each(function() {
                    $(this).height(height);
                });
                if (width == '')
                    width = $(carousel).width();
                $(carousel).show();
                $(carousel).owlCarousel({
                    items: Math.ceil($(carousel).width() / width),
                    loop: true,
                    autoplay: true,
                    autoplayHoverPause: true,
                    nav: true,
                    navText: ['', '']
                }).on('translated.owl.carousel', function(event) {
                    BackgroundCheck.refresh();
                });
                BackgroundCheck.init({
                    targets: '.owl-carousel .owl-controls .owl-prev, .owl-carousel .owl-controls .owl-next',
                    images: '.owl-carousel .item .image'
                });
            });
        }
    }
    function initAzexoPostPackery() {
        if ('imagesLoaded' in $.fn && 'packery' in $.fn) {
            $('.widget-posts-packery').each(function() {
                var packery = this;
                function update_sizes() {
                    var gutter = $(packery).attr('data-gutter');
                    var columns = $(packery).attr('data-columns');
                    if ($(packery).is('[data-size]')) {
                        var size = $(packery).attr('data-size');
                        if (columns > Math.floor($(packery).parent().width() / size))
                            columns = Math.floor($(packery).parent().width() / size);
                    }
                    var width = Math.floor($(packery).parent().width() / columns) * columns;
                    $(packery).width(width);

                    $(packery).find('.gutter-sizer').css('width', (gutter / width * 100) + '%');
                    $(packery).find('.gutter-sizer').css('height', gutter + 'px');

                    var grid_sizer = ((width - gutter * (columns - 1)) / columns);
                    $(packery).find('.grid-sizer').css('width', (grid_sizer / width * 100) + '%');
                    $(packery).find('.grid-sizer').css('height', grid_sizer + 'px');

                    $(packery).find('.item').each(function() {
                        var w = parseInt($(this).attr('data-width'), 10);
                        if (w > columns)
                            w = columns;
                        var h = parseInt($(this).attr('data-height'), 10);
                        if (h > columns)
                            h = columns;
                        $(this).css('width', ((w * grid_sizer + (w - 1) * gutter) / width * 100) + '%');
                        $(this).css('height', (h * grid_sizer + (h - 1) * gutter) + 'px');
                    });
                }
                $(window).on('resize', update_sizes);
                update_sizes();

                $(packery).packery({
                    itemSelector: '.item',
                    percentPosition: true,
                    columnWidth: '.grid-sizer',
                    rowHeight: '.grid-sizer',
                    gutter: '.gutter-sizer'
                }).show();
            });
        }
    }
    function initMobileMenu() {
        $(".mobile-menu-button span").on("tap click", function(e) {
            e.preventDefault();
            if ($(".mobile-menu > div > ul").is(":visible")) {
                $(".mobile-menu > div > ul").slideUp(200)
            } else {
                $(".mobile-menu > div > ul").slideDown(200)
            }
        });

        $('.mobile-menu .menu-item-has-children').append('<span class="mobile-arrow"><i class="fa fa-angle-right"></i><i class="fa fa-angle-down"></i></span>');
        $('.mobile-menu .mega').append('<span class="mobile-arrow"><i class="fa fa-angle-right"></i><i class="fa fa-angle-down"></i></span>');

        $(".mobile-menu > div > ul > li.menu-item-has-children > span.mobile-arrow").on("tap click", function(e) {
            e.preventDefault();
            if ($(this).closest("li.menu-item-has-children").find("> ul.sub-menu").is(":visible")) {
                $(this).closest("li.menu-item-has-children").find("> ul.sub-menu").slideUp(200);
                $(this).closest("li.menu-item-has-children").removeClass("open-sub")
            } else {
                $(this).closest("li.menu-item-has-children").addClass("open-sub");
                $(this).closest("li.menu-item-has-children").find("> ul.sub-menu").slideDown(200)
            }
        });
        $(".mobile-menu > div > ul > li.mega > span.mobile-arrow").on("tap click", function(e) {
            e.preventDefault();
            if ($(this).closest("li.mega").find("> .page").is(":visible")) {
                $(this).closest("li.mega").find("> .page").slideUp(200);
                $(this).closest("li.mega").removeClass("open-sub")
            } else {
                $(this).closest("li.mega").addClass("open-sub");
                $(this).closest("li.mega").find("> .page").slideDown(200)
            }
        });
        $(".mobile-menu > div > ul > li.menu-item-has-children > ul.sub-menu > li.menu-item-has-children > span.mobile-arrow").on("tap click", function(e) {
            e.preventDefault();
            if ($(this).parent().find("ul.sub-menu").is(":visible")) {
                $(this).parent().find("ul.sub-menu").slideUp(200);
                $(this).parent().removeClass("open-sub")
            } else {
                $(this).parent().addClass("open-sub");
                $(this).parent().find("ul.sub-menu").slideDown(200)
            }
        });
        $(".mobile-menu ul li > a").on("click", function() {
            if ($(this).attr("href") !== "http://#" && $(this).attr("href") !== "#") {
                //$(".mobile-menu > div > ul").slideUp()
            }
        })
    }
    var header_main_top = 0;
    $(function() {
        initMobileMenu();
        $('.primary-navigation .nav-menu').each(function() {
            var menu = this;
            $(menu).find('.page').each(function() {
                var page = this;
                function on_hover() {
                    $(page).css('width', $(menu).closest('.container').width() + 'px');
                    $(page).css('left', ($(menu).closest('.container').offset().left - $(this).offset().left) + 'px');
                }
                $(page).parent().hover(on_hover);
                $(window).load(function() {
                    on_hover.call($(page).parent());
                });
                $(window).resize(function() {
                    on_hover.call($(page).parent());
                });
            });
        });


        $('.searchform input[name="s"]').attr('placeholder', $('.searchform [type="submit"]').val()).on('keydown', function(event) {
            if (event.keyCode == 13) {
                $('.searchform [type="submit"]').click();
            }
        });
        $('.search-wrapper i').click(function() {
            $(this).parent().find('.searchform').show().find('input[name="s"]').focus();
            $(this).hide();
        });
        $('.search-wrapper input[name="s"]').blur(function() {
            $(this).closest('.search-wrapper').find('i').show();
            $(this).closest('.searchform').hide();
        });


        if ('stick_in_parent' in $.fn) {
            if ($(window).width() > 1100)
                $("#tertiary .sidebar-inner").stick_in_parent({
                    offset_top: $('.header-main').height()
                });
        }
        initPostGallery();
        initProductGallery();

        if($('.header-main').length) {
          header_main_top = $('.header-main').offset().top;
          $('.site-header').imagesLoaded(function() {
              var interval = setInterval(function() {
                  if (!$('.site-header').hasClass('scrolled')) {
                      header_main_top = $('.header-main').offset().top;
                      clearInterval(interval);
                  }
              }, 100);
          });
        }

        initProductCategoriesWidget();
        initAzexoPostList();
        initAzexoPostMasonry();
        initAzexoPostCarousel();
        initAzexoPostPackery();
    });
})(jQuery);
