$(document).ready(function(){;
    SelectAlpha();
    ScorllBallItem();
    SettingShapeObject();
    NumberElement();
    ObjectTemplateTitle();
    HoverSlider();
    CloseSlider();
    PressSearchInput();
});

function SelectAlpha(){
    $('.alpha-filter li').click(function(){
        $(this).removeClass('select');
        $(this).addClass('select');

        $(this).click(function(){
            $(this).removeClass('select');
        });
    });
}

function ScorllBallItem(){
    // .sider-bar ul li.parent-sider-bar:hover .child-sider-bar li
    $('.parent-sider-bar').hover(function(){
        if($(this).children('.child-sider-bar').children().length > 9){
            $(this).children('.child-sider-bar').addClass('scroll-bar-item');
        }
    });
}

function SettingShapeObject(){

    if ($('.item-child-object').length > 0 && $('.child-img-object').length)
    {
        if ($('#thumbnail-area-cities .item-child-object').attr('ImageType') === 'circle')
        {
            $('#thumbnail-area-cities .child-img-object').toggleClass('circle');
            $('#thumbnail-area-cities .button-child-object').toggleClass('circle');
            $('#thumbnail-area-cities .button-child-object.main').removeClass('circle');
        }

        if ($('#thumbnail-area-activities .item-child-object').attr('ImageType') === 'circle')
        {
            $('#thumbnail-area-activities .child-img-object').toggleClass('circle');
            $('#thumbnail-area-activities .button-child-object').toggleClass('circle');
            $('#thumbnail-area-activities .button-child-object.main').removeClass('circle');
        }
    }
}

var flanking = 3;
var currentTitle = null;
var numElFlag = 0;

function HoverSlider(){
    if($( window ).width() <= 991){
        flanking = 2;
    }
    if($( window ).width() <= 767){
        flanking = 1;
    }
    if($( window ).width() <= 640){
        flanking = 0;
    }

    if($( window ).width() >= 641){
        setTimeout(function(){
            $('div.child-img-object.city').hover(function(){
                if(numElFlag != 1) {
                    var idImtem = $(this).attr('id').substring(5);
                    $('#slider-area-cities').css('display', 'block');
                    CreateSliderCities(idImtem, flanking);
                    $('#carousel-cities').css('visibility', 'visibile');
                    $('#carousel-cities img').css('visibility', 'visibile');
                    $('#carousel-cities #prev').css('display', 'block');
                    $('#carousel-cities #next').css('display', 'block');
                    $('#thumbnail-area-cities').css('display', 'none');

                    /*Close slider when move mouse*/
                    var y_begin = $('#slider-area-cities').offset().top;
                    var y_end = y_begin + $('#slider-area-cities').height();
                    var x_begin = $('#slider-area-cities').offset().left;
                    var x_end = x_begin + $('#slider-area-cities').width() + 15;

                    var currentMousePos = { x: -1, y: -1 };
                    $(document).mousemove(function(event) {
                        currentMousePos.x = event.pageX;
                        currentMousePos.y = event.pageY;
                        if(currentMousePos.y < y_begin || currentMousePos.y > y_end)
                        {
                            // console.log('close');
                            $('#thumbnail-area-cities').css('display','block');
                            $('#slider-area-cities').css('display','none');
                        }
                        if(currentMousePos.x < x_begin || currentMousePos.x > x_end)
                        {
                            // console.log('close');
                            $('#thumbnail-area-cities').css('display','block');
                            $('#slider-area-cities').css('display','none');
                        }
                    });
                }
            });

            $('div.child-img-object.activity').hover(function(){
                var idImtem = $(this).attr('id').substring(5);
                $('#slider-area-activities').css('display','block');
                CreateSliderActivities(idImtem);
                $('#carousel-activities').css('visibility','visibile');
                $('#carousel-activities img').css('visibility','visibile');
                $('#carousel-activities #prev').css('display','block');
                $('#carousel-activities #next').css('display','block');
                $('#thumbnail-area-activities').css('display','none');

                /*Close slider when move mouse*/
                var y_begin = $('#slider-area-activities').offset().top;
                var y_end = y_begin + $('#slider-area-activities').height();
                var x_begin = $('#slider-area-activities').offset().left;
                var x_end = x_begin + $('#slider-area-activities').width() + 15;

                var currentMousePos = { x: -1, y: -1 };
                $(document).mousemove(function(event) {
                    currentMousePos.x = event.pageX;
                    currentMousePos.y = event.pageY;
                    if(currentMousePos.y < y_begin || currentMousePos.y > y_end)
                    {
                        // console.log('close');
                        $('#thumbnail-area-activities').css('display','block');
                        $('#slider-area-activities').css('display','none');
                    }
                    if(currentMousePos.x < x_begin || currentMousePos.x > x_end)
                    {
                        // console.log('close');
                        $('#thumbnail-area-activities').css('display','block');
                        $('#slider-area-activities').css('display','none');
                    }
                });
            });
        }, 500);
    }
}

function CloseSlider(){
    $('#slider-area-cities .thumbnail-close').click(function(){
        setTimeout(function(){
            $('#thumbnail-area-cities').css('display','block');
            $('#slider-area-cities').css('display','none');
        }, 700);
    });

    $('#slider-area-activities .thumbnail-close').click(function(){
        setTimeout(function(){
            $('#thumbnail-area-activities').css('display','block');
            $('#slider-area-activities').css('display','none');
        }, 700);
    });
}

function NumberElement(){

    if ( $('.item-child-object').length > 0) {

        /* Reset class of NumberOfObjects */
        for(var i=1; i<=12; i++)
        {
            $('#thumbnail-area-cities .item-child-object').removeClass('col-md-' + i);
            $('#thumbnail-area-activities .item-child-object').removeClass('col-md-' + i);
        }

        /* Apply NumberOfObjects setting */
        switch($('#thumbnail-area-cities .item-child-object').attr('NumberOfObjects'))
        {
            case '1': {
                $('#thumbnail-area-cities .item-child-object').addClass('col-md-12');
                if($('#thumbnail-area-cities .item-child-object').attr('imagetype') === 'square'){
                    $('#thumbnail-area-cities .item-child-object .button-child-object').addClass('block-square-item');
                }
            };break;

            case '2': {
                $('#thumbnail-area-cities .item-child-object').addClass('col-md-6');
                if($('#thumbnail-area-cities .item-child-object').attr('imagetype') === 'square'){
                    $('#thumbnail-area-cities .item-child-object .button-child-object').addClass('block-square-item');
                }
            };break;

            case '3': {
                $('#thumbnail-area-cities .item-child-object').addClass('col-md-4');
                if($('#thumbnail-area-cities .item-child-object').attr('imagetype') === 'square'){
                    $('#thumbnail-area-cities .item-child-object .button-child-object').addClass('block-square-item');
                }
            };break;

            case '4': {
                $('#thumbnail-area-cities .item-child-object').addClass('col-md-3');
                if($('#thumbnail-area-cities .item-child-object').attr('imagetype') === 'square'){
                    $('#thumbnail-area-cities .item-child-object .button-child-object').addClass('block-square-item');
                }
            };break;

            case '5': {
                $('#thumbnail-area-cities .item-child-object').addClass('col-md-2').addClass('col-md5');
                if($('#thumbnail-area-cities .item-child-object').attr('imagetype') === 'square'){
                    $('#thumbnail-area-cities .item-child-object .button-child-object').addClass('block-square-item');
                }
            };break;

            case '6': {
                $('#thumbnail-area-cities .item-child-object').addClass('col-md-2');
                $('#thumbnail-area-cities .item-child-object .child-img-object').addClass('col-md6');
                if($('#thumbnail-area-cities .item-child-object').attr('imagetype') === 'square'){
                    $('#thumbnail-area-cities .item-child-object .button-child-object').addClass('block-square-item');
                }
            };break;
            default:break;
        }

        switch($('#thumbnail-area-activities .item-child-object').attr('NumberOfObjects'))
        {
            case '1': {
                $('#thumbnail-area-activities .item-child-object').addClass('col-md-12');
                if($('#thumbnail-area-activities .item-child-object').attr('imagetype') === 'square'){
                    $('#thumbnail-area-activities .item-child-object .button-child-object').addClass('block-square-item');
                }
                
            };break;

            case '2': {
                $('#thumbnail-area-activities .item-child-object').addClass('col-md-6');
                if($('#thumbnail-area-activities .item-child-object').attr('imagetype') === 'square'){
                    $('#thumbnail-area-activities .item-child-object .button-child-object').addClass('block-square-item');
                }
                
            };break;

            case '3': {
                $('#thumbnail-area-activities .item-child-object').addClass('col-md-4');
                if($('#thumbnail-area-activities .item-child-object').attr('imagetype') === 'square'){
                    $('#thumbnail-area-activities .item-child-object .button-child-object').addClass('block-square-item');
                }
                
            };break;

            case '4': {
                $('#thumbnail-area-activities .item-child-object').addClass('col-md-3');
                if($('#thumbnail-area-activities .item-child-object').attr('imagetype') === 'square'){
                    $('#thumbnail-area-activities .item-child-object .button-child-object').addClass('block-square-item');
                }
                
            };break;

            case '5': {
                $('#thumbnail-area-activities .item-child-object').addClass('col-md-2').addClass('col-md5');
                if($('#thumbnail-area-activities .item-child-object').attr('imagetype') === 'square'){
                    $('#thumbnail-area-activities .item-child-object .button-child-object').addClass('block-square-item');
                }
            };break;

            case '6': {
                $('#thumbnail-area-activities .item-child-object').addClass('col-md-2');
                $('#thumbnail-area-activities .item-child-object .child-img-object').addClass('col-md6');
                if($('#thumbnail-area-activities .item-child-object').attr('imagetype') === 'square'){
                    $('#thumbnail-area-activities .item-child-object .button-child-object').addClass('block-square-item');
                }
            };break;
            default:break;
        }


     /*   if ($('#thumbnail-area-cities .item-child-object').attr('NumberOfObjects') === '1') {
            $('#thumbnail-area-cities .item-child-object').addClass('col-md-12').removeClass('col-md-3');
            $('#thumbnail-area-cities .button-child-object').addClass('block-item-12');
        }
        else if ($('#thumbnail-area-cities .item-child-object').attr('NumberOfObjects') === '4') {
            $('#thumbnail-area-cities .item-child-object').addClass('col-md-3').removeClass('col-md-12');
            $('#thumbnail-area-cities .button-child-object').removeClass('block-item-12');
            $('#thumbnail-area-cities .circle').removeClass('block-item-12');
        }*/

      /*  if ($('#thumbnail-area-activities .item-child-object').attr('NumberOfObjects') === '1') {
            $('#thumbnail-area-activities .item-child-object').addClass('col-md-12').removeClass('col-md-3');
            $('#thumbnail-area-activities .button-child-object').addClass('block-item-12');

        }
        else if ($('#thumbnail-area-activities .item-child-object').attr('NumberOfObjects') === '4') {
            $('#thumbnail-area-activities .item-child-object').addClass('col-md-3').removeClass('col-md-12');
            $('#thumbnail-area-activities .button-child-object').removeClass('block-item-12');
            $('#thumbnail-area-activities .circle').removeClass('block-item-12');
        }*/

        /* DisplayObjectIncluded setting is used when NumberOfObjects = 1 */
        if ($('#thumbnail-area-cities .item-child-object').attr('NumberOfObjects') === '1' &&
            $('#thumbnail-area-cities .item-child-object').attr('DisplayObjectIncluded') === 'false') {
            numElFlag = 1;
        }

        /*This case check shape: circle, objectNum: 1*/
        if($('.button-child-object.circle.block-square-item').length > 0){
            $('.button-child-object.circle.block-square-item').removeClass('block-square-item');
        }
    }
}

function CreateSliderCities(startItem, flanking){
    var carousel = $("#carousel-cities").waterwheelCarousel({
        flankingItems: 3,
        startingItem: startItem,
        speed: 100,
        movedToCenter: function ($item) {
            $('#slider-area-cities .title-itemslider').text($item.attr('title'));
            $('#slider-area-cities .title-itemslider').attr('href',$item.parent().attr('href'));
        }
    });

    titleItem = $('#slider-area-cities #item-' + startItem).attr('title');
    href = $('#slider-area-cities #item-' + startItem).parent().attr('href');
    $('#slider-area-cities .title-itemslider').text(titleItem);
    $('#slider-area-cities .title-itemslider').attr('href',href);

    $('#carousel-cities #prev').bind('click', function () {
        carousel.prev();
        return false
    });

    $('#carousel-cities #next').bind('click', function () {
        carousel.next();
        return false;
    });

    $('#carousel-cities #reload').bind('click', function () {
        newOptions = eval("(" + $('#newoptions').val() + ")");
        carousel.reload(newOptions);
        return false;
    });
}

function CreateSliderActivities(startItem){
    var carousel = $("#carousel-activities").waterwheelCarousel({
        flankingItems: 3,
        startingItem: startItem,
        speed: 100,
        movedToCenter: function ($item) {
            $('#slider-area-activities .title-itemslider').text($item.attr('title'));
            $('#slider-area-activities .title-itemslider').attr('href',$item.parent().attr('href'));
        }
    });

    titleItem = $('#slider-area-activities #item-' + startItem).attr('title');
    href = $('#slider-area-activities #item-' + startItem).parent().attr('href');
    $('#slider-area-activities .title-itemslider').text(titleItem);
    $('#slider-area-activities .title-itemslider').attr('href',href);

    $('#carousel-activities #prev').bind('click', function () {
        carousel.prev();
        return false
    });

    $('#carousel-activities #next').bind('click', function () {
        carousel.next();
        return false;
    });

    $('#carousel-activities #reload').bind('click', function () {
        newOptions = eval("(" + $('#newoptions').val() + ")");
        carousel.reload(newOptions);
        return false;
    });
}

function ObjectTemplateTitle(){
    if($('.main-img-object').length > 0){
        $('#template-title').css('display','none');
        $('#thumbnail-area-activities').css('margin-top','0');
        $('#slider-area-activities').css('margin-top','0');
    }else{
        $('#template-title').css('display','block');
        $('#thumbnail-area-activities').css('margin-top','15px');
        $('#slider-area-activities').css('margin-top','15px');
    }
}

function PressSearchInput(){
    $( "#search-press" )
    .keyup(function() {
        // console.log($(this).val());
        if($(this).val().trim().length != 0){
            $('.search-btn-area .close-press-icon').css('display','block');
            $('.search-btn-area .close-press-icon').click(function(){
                $( ".search-btn" ).val(null);
                $('.search-btn-area .close-press-icon').css('display','none');
            });
        }
        else{
            $('.search-btn-area .close-press-icon').css('display','none');
        }
    })
    .keydown(function() {
        if($(this).val().trim().length != 0){
            $('.search-btn-area .close-press-icon').css('display','block');
            $('.search-btn-area .close-press-icon').click(function(){
                $( ".search-btn" ).val(null);
                $('.search-btn-area .close-press-icon').css('display','none');
            });
        }else{
            $('.search-btn-area .close-press-icon').css('display','none');
        }
    });
}