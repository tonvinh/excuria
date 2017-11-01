var _GET = queryString();

var paths = window.location.pathname.substr(1, window.location.pathname.length).split("/");

var fullSuggestion = null;
var _updateFilter = false;
var flagLoad = true;

$(document).ready(function () {

    $.ajax(
        {
            type: "GET",
            url: "#",
            beforeSend: function () {

                TipsResize();
                SelectAlpha();

                loadHints();
                loadSort();
                loadPerPage();
                ScorllBallItem();

                /* auto set activeTAB is provider on url on search function access */
                if (typeof(_GET['activeTAB']) === "undefined") {
                    _GET['activeTAB'] = 'provider';
                    updateUrl();
                }

                if (typeof(_GET['activeTAB']) !== "undefined") {
                    document.title = " Excuri | " +  _GET['activeTAB'][0].toUpperCase() +  _GET['activeTAB'].slice(1) + " Search Results";
                }

                loadReady();
            },
            success: function () {
                flagLoad = false;
                loadingOverlay();
            }
        }
    );

});

function loadReady() {
    /* Receive all auto suggestion for search box */
    $.ajax({
        type: "GET",
        url: "/filter/autosuggest",
        data: 'keyword=%',
        beforeSend: function () {
            $("#search-keyword").css("background", "#FFF url('/img/loaderIcon.gif') no-repeat r");
        },
        success: function (data) {
            fullSuggestion = data;
        }
    });

    setupAutoComplete();

    loadFilters();

    loadTotal();
    updateResult();
    /* update display the result of page with an total */
    updatePagesLink();
    clearSearchBox();

}

function loadFilters() {
    paths[1] = _GET['activeTAB'];
    /**/
    switch (paths[1]) {
        case 'provider': {
            loadClassAvailable();
            loadCountry();
            loadCity();
            loadLocation();
            loadActivityType();
            loadActivityClassification();
            loadActivity();
            loadSearchKeyword();

            break;
        }
        case 'course': {
            loadFreeTrial();
            loadCountry();
            loadCity();
            loadLocation();
            loadActivityType();
            loadActivityClassification();
            loadActivity();
            loadEventType();
            loadFacility();
            loadCampus();
            loadArena();
            loadProvider();
            loadInstructor();
            loadProgram();
            loadDay();
            loadGender();
            loadGeneration();
            loadTimeStart();
            loadTimeEnd();
            loadAgeFrom();
            loadAgeTo();
            loadSearchKeyword();
            loadKeyword();
            break;
        }
        case 'instructor': {
            loadClassAvailable();
            loadCountry();
            loadCity();
            loadLocation();
            loadActivityType();
            loadActivityClassification();
            loadActivity();
            loadSearchKeyword();
            loadProvider();

            break;
        }
    }
}

function queryString() {
    var queryString = window.location.search.substr(1, window.location.search.length);
    var varArray = queryString.split("&");

    var arr = [];
    for (var i = 0; i < varArray.length; i++) {
        var item = varArray[i].split("=");

        arr[item[0]] = decodeURIComponent(item[1]);
    }
    return arr;
}

function loadTotal() {
    $.ajax({
        type: "GET",
        url: "/total/provider",
        success: function (response) {
            $('#entity-provider').html('Providers (' + response + ')');
        },
        error: function (result) {/**/
        }
    });

    $.ajax({
        type: "GET",
        url: "/total/course",
        success: function (response) {
            $('#entity-course').html('Courses (' + response + ')');
        },
        error: function (result) {/**/
        }
    });

    $.ajax({
        type: "GET",
        url: "/total/instructor",
        success: function (response) {
            $('#entity-instructor').html('Instructors (' + response + ')');
        },
        error: function (result) {/**/
        }
    });
}

function updateTitleWithTotal() {
    //var paths = window.location.pathname.substr(1, window.location.pathname.length).split("/");

    switch (paths[1]) {
        case 'provider': {
            $('a.button-active').html('Providers (' + $('#total').val() + ')');
            break;
        }
        case 'course': {
            $('a.button-active').html('Courses (' + $('#total').val() + ')');
            break;
        }
        case 'instructor': {
            $('a.button-active').html('Instructors (' + $('#total').val() + ')');
            break;
        }
    }

    CheckNumPage();
}

function updateResult() {

    //var paths = window.location.pathname.substr(1,window.location.pathname.length).split("/");

    switch (paths[1]) {
        case 'provider': {
            $.ajax({
                type: "GET",
                url: "/searchUpdate/provider" + location.search,
                beforeSend: function () {
                    _updateFilter = true;
                    loadingOverlay();
                },
                success: function (response) {
                    $('.result-list').html("").append(response);

                    SelectAlpha();
                    loadHints();
                    updatePagesLink();
                    updateTitleWithTotal();

                    if (_updateFilter === true) {
                        _updateFilter = false;
                        loadFilters();
                        loadingOverlay();
                    }

                },
                error: function (result) {/**/
                }
            });
            break;
        }
        case 'course': {
            $.ajax({
                type: "GET",
                url: "/searchUpdate/course" + location.search,
                beforeSend: function () {
                    _updateFilter = true;
                    loadingOverlay();
                },
                success: function (response) {
                    $('.result-list').html("").append(response);

                    SelectAlpha();
                    loadHints();
                    updatePagesLink();
                    updateTitleWithTotal();

                    if (_updateFilter === true) {
                        _updateFilter = false;
                        loadFilters();
                        loadingOverlay();
                    }
                },
                error: function (result) {/**/
                }
            });
            break;
        }
        case 'instructor': {
            $.ajax({
                type: "GET",
                url: "/searchUpdate/instructor" + location.search,
                beforeSend: function () {
                    _updateFilter = true;
                    loadingOverlay();
                },
                success: function (response) {
                    $('.result-list').html("").append(response);

                    SelectAlpha();
                    loadHints();
                    updatePagesLink();
                    updateTitleWithTotal();

                    if (_updateFilter === true) {
                        _updateFilter = false;
                        loadFilters();
                        loadingOverlay();
                    }
                },
                error: function (result) {/**/
                }
            });
            break;
        }
    }
}

function updateUrl() {
    var _querypars = [];
    for (var key in _GET) {
        //console.log(_GET[key]);
        if (_GET[key] !== 'undefined' && _GET[key].length > 0) {
            /* none get page parame when apply any (new) filter */
            if (key !== 'page') {
                _querypars.push(key + "=" + _GET[key]);
            }
        }
    }

    var newURL = location.pathname + (_querypars.length > 0 ? ("?") : '') + _querypars.join('&');
    history.replaceState({}, '', newURL);
}

function updatePagesLink() {
    var newURL = window.location.search;

    var _querypars = [];
    for (var key in _GET) {
        if (_GET[key] !== 'undefined' && _GET[key].length > 0 && key !== 'page') {
            _querypars.push(key + "=" + _GET[key]);
        }
    }

    /* update pageing with filters */
    newURL = location.pathname + (_querypars.length > 0 ? ("?") : '') + _querypars.join('&');

    if ($('#pagesTop').length > 0) {
        $('#pagesTop li').each(function () {
            var linkPage = $(this).find("a").attr('href', newURL + (_querypars.length > 0 ? ('&page=' + $(this).index()) : '?page=' + $(this).index() ));
        });
    }

    if ($('#pagesBottom').length > 0) {
        $('#pagesBottom li').each(function () {
            var linkPage = $(this).find("a").attr('href', newURL + (_querypars.length > 0 ? ('&page=' + $(this).index()) : '?page=' + $(this).index() ));
        });
    }

    var pageprevious = $("#currentPage").val() - 1 >= 0 ? 1 : $("#currentPage").val() - 1;
    var pagenext = (($("#currentPage").val() + 1 > $("#totalPage").val()) ? $("#totalPage").val() : (parseInt($("#currentPage").val()) + 1));
    $('.pagination-arrow.left').attr('href', newURL + (_querypars.length > 0 ? '&page=' + pageprevious : '?page=' + pageprevious ));
    $('.pagination-arrow.right').attr('href', newURL + (_querypars.length > 0 ? '&page=' + pagenext : '?page=' + pagenext ));
}

function loadHints() {
    var notHints = ['searchKeywordBy', 'activeTAB', 'perPage', 'sortBy', 'page', 'ActiveTAB', 'hideHeatherRawTab', 'hideHeatherRawChoosenFilter', 'hideHeatherRawSort', 'hideLeftFilter', 'hideRightAplhabet'];
    /* Not display hints which filters on list */
    var notHintsValue = ['any'];
    $("#tips").html("");
    /* Display clear all when exits any valid filters */
    for (var key in _GET) {

        //if (_GET[key] !== 'undefined' && _GET[key].length > 0 && notHints.indexOf(key) === -1) {
        if (_GET[key] !== 'undefined' && _GET[key].length > 0 && notHints.indexOf(key) === -1) {
            // if (_GET[key] !== 'any') {
            $("#tips").html("").append('<div class="tips-element">Clear all' +
                '<div class="close-item" data-key="clear" data-value="all"><span class="close-left"></span><span class="close-right"></span></div></div>');
            break;
            //  }
        }
        else if (_GET[key] === 'any') {

            /* clear hint when empty parameters */
            $("#tips").html('');
        }
    }

    for (var key in _GET) {

        if (_GET[key] !== 'undefined' && _GET[key].length > 0 && notHints.indexOf(key) === -1) {
            filters = _GET[key].split(',');
            if (filters.length > 0) {

                filters.forEach(function (item) {

                    if (item !== 'any' && key === 'keyword') {
                        /* Separate the searching keywords */
                        var split_keyword = item.trim().split(' ');
                        var _keywords = [];

                        split_keyword.forEach(function (strKeyword) {
                            if (strKeyword.trim().length > 0) {
                                $("#tips").append('<div class="tips-element">' + strKeyword +
                                    '<div class="close-item" data-key="' + key + '" data-value="' + strKeyword + '"><span class="close-left"></span><span class="close-right"></span></div></div>');
                                _keywords.push(strKeyword);
                            }
                        });

                        _GET[key] = _keywords.join(' ');
                        $('#search-keyword').val(_GET[key]);
                        updateUrl();
                    }
                    else if (item !== 'any') {
                        /* Not display value 'any' on hints */
                        $("#tips").append('<div class="tips-element">' + item +
                            '<div class="close-item" data-key="' + key + '" data-value="' + item + '"><span class="close-left"></span><span class="close-right"></span></div></div>');
                    }

                });
            }
        }
    }

    $("#tips .close-item").each(function (index, element) {
        $(this).on('click', function (e) {
            if (_GET[$(this).attr('data-key')] !== undefined) {

                var _get = null;

                if ($(this).attr('data-key') === 'keyword') {
                    /* Separate the searching keywords */
                    _get = removeValueOnArray(_GET[$(this).attr('data-key')].split(' '), $(this).attr('data-value'));

                    /* remove one by one word on keyword */
                    _get.forEach(function (index, element) {
                        if (element.length === 0) {
                            _get.splice(index, 1);
                        }
                    });
                }
                else {
                    _get = removeValueOnArray(_GET[$(this).attr('data-key')].split(','), $(this).attr('data-value'));
                }

                _GET[$(this).attr('data-key')] = _get.join(' ');

                updateUrl();
                clearFilterFromHints();
                $(this).parent().remove();

                /* clear group keyword and contain filters */
                if ($(this).attr('data-key') === 'searchKeywordBy') {
                    _GET['keyword'] = '';
                    $("#tips .close-item").each(function (index, element) {
                        if ($(this).attr('data-key') === 'keyword') {
                            $(this).parent().remove();
                            $(".clear-btn").trigger('click');
                            return;
                        }
                    });
                    updateUrl();
                    clearFilterFromHints();
                }
                else if ($(this).attr('data-key') === 'keyword') {
                    /* Empty searchKeyWordBy */
                    _GET['searchKeywordBy'] = (_GET['keyword'] !== undefined ? _GET['searchKeywordBy'] : '');
                    $("#tips .close-item").each(function (index, element) {
                        if ($(this).attr('data-key') === 'searchKeywordBy') {
                            $(this).parent().remove();
                            $(".clear-btn").trigger('click');
                            return;
                        }
                    });

                    $('#search-keyword').val(_GET['keyword'] !== undefined ? _GET['keyword'].replace(',', ' ') : '');

                    updateUrl();
                    clearFilterFromHints();
                }
                /**/

                /* Clear lastest hint if none filters remain */
                var _query = queryString();
                var clearLastedHint = true;
                for (var key in _query) {
                    if (_query[key] !== 'undefined' && _query[key].length > 0 && notHints.indexOf(key) === -1) {
                        clearLastedHint = false;
                        break;
                    }
                }

                if (clearLastedHint) {
                    $("#tips .close-item").each(function (index, element) {
                        $(this).parent().remove();
                    });
                }
                /**/
            }
            else if ($(this).attr('data-key') === 'clear') {

                /**** CLEAR ALL HINTS ****/

                /* update url */
                var newUrl = location.pathname + '?activeTAB=' + paths[1];

                if (_GET['hideHeatherRawTab'] !== undefined && _GET['hideHeatherRawTab'].toLowerCase() === 'true') {
                    newUrl += '&hideHeatherRawTab=TRUE';
                }

                if (_GET['hideHeatherRawChoosenFilter'] !== undefined && _GET['hideHeatherRawChoosenFilter'].toLowerCase() === 'true') {
                    newUrl += '&hideHeatherRawChoosenFilter=TRUE';
                }

                if (_GET['hideHeatherRawSort'] !== undefined && _GET['hideHeatherRawSort'].toLowerCase() === 'true') {
                    newUrl += '&hideHeatherRawSort=TRUE';
                }

                if (_GET['hideLeftFilter'] !== undefined && _GET['hideLeftFilter'].toLowerCase() === 'true') {
                    newUrl += '&hideLeftFilter=TRUE';
                }

                if (_GET['hideRightAplhabet'] !== undefined && _GET['hideRightAplhabet'].toLowerCase() === 'true') {
                    newUrl += '&hideRightAplhabet=TRUE';
                }

                /* update url when clear all hints */
                history.replaceState({}, '', newUrl);

                /* remove all hints */
                $("#tips .close-item").each(function () {
                    $(this).parent().remove();
                });

                /* restore default filters */
                $('input[type="checkbox"]').each(function () {
                    if ($(this).attr('value') !== 'any')
                        $(this).prop('checked', false);
                    else
                        $(this).prop('checked', true);
                });

                $("select").each(function () {
                    if ($(this).attr('id') !== 'perPage' && $(this).attr('id') !== 'sort') {
                        $(this).val('any');
                    }
                });

                $('ul#activityClassification').html("").append('<li><input name="checkresult" value="any" checked="" class="checkbox-default" type="checkbox"><lable><span>Any</span></lable></li>');
                $('ul#activity').html("").append('<li><input name="checkresult" value="any" checked="" class="checkbox-default" type="checkbox"><lable><span>Any</span></lable></li>');
                $("#keyword").val('');
                $("#search-keyword").val('');

                $(".clear-btn").trigger('click');
            }

            _GET = queryString();

            loadReady();
        });
    });
}

function removeValueOnArray(_array, value) {
    for (_item in _array) {
        if (_array[_item] === value) {
            _array.splice(_item, 1);
            break;
        }
    }
    return _array;
}

function clearFilterFromHints() {
    _GET = queryString();

    $('ul#freeTrial li').each(function (index, element) {
        var input = $(this).find('input.checkbox-default');
        input.prop("checked", false);
    });

    /* pre-select filter items */
    if (_GET['freeTrial'] !== undefined) {
        var seleted = _GET.freeTrial.split(',');
        seleted.forEach(function (value) {
            $('ul#freeTrial li').each(function (index, element) {
                var input = $(this).find('input.checkbox-default');

                if (input.val() === value) {
                    input.prop("checked", true);
                }
            });
        });
    }
    else {
        $('ul#freeTrial li').find('input.checkbox-default').first().prop("checked", true);
    }

    $('ul#classAvailable li').each(function (index, element) {
        var input = $(this).find('input.checkbox-default');
        input.prop("checked", false);
    });

    /* pre-select filter items */
    if (_GET['classAvailable'] !== undefined) {
        var seleted = _GET.classAvailable.split(',');
        seleted.forEach(function (value) {
            $('ul#classAvailable li').each(function (index, element) {
                var input = $(this).find('input.checkbox-default');

                if (input.val() === value) {
                    input.prop("checked", true);
                }
            });
        });
    }
    else {
        $('ul#classAvailable li').find('input.checkbox-default').first().prop("checked", true);
    }

    $('ul#country li').each(function (index, element) {
        var input = $(this).find('input.checkbox-default');
        input.prop("checked", false);
    });

    /* pre-select filter items */
    if (_GET['country'] !== undefined) {
        var seleted = _GET.country.split(',');
        seleted.forEach(function (value) {
            $('ul#country li').each(function (index, element) {
                var input = $(this).find('input.checkbox-default');

                if (input.val() === value) {
                    input.prop("checked", true);
                }
            });
        });
    }
    else {
        $('ul#country li').find('input.checkbox-default').first().prop("checked", true);
    }

    $('ul#city li').each(function (index, element) {
        var input = $(this).find('input.checkbox-default');
        input.prop("checked", false);
    });
    if (_GET['city'] !== undefined) {
        var seleted = _GET.city.split(',');
        seleted.forEach(function (value) {
            $('ul#city li').each(function (index, element) {
                var input = $(this).find('input.checkbox-default');
                if (input.val() === value) {
                    input.prop("checked", true);
                }
            });
        });
    }
    else {
        $('ul#city li').find('input.checkbox-default').first().prop("checked", true);
    }

    $('ul#location li').each(function (index, element) {
        var input = $(this).find('input.checkbox-default');
        input.prop("checked", false);
    });
    if (_GET['location'] !== undefined) {
        var seleted = _GET.location.split(',');
        seleted.forEach(function (value) {
            $('ul#location li').each(function (index, element) {
                var input = $(this).find('input.checkbox-default');
                if (input.val() === value) {
                    input.prop("checked", true);
                }
            });
        });
    }
    else {
        $('ul#location li').find('input.checkbox-default').first().prop("checked", true);
    }

    $('ul#activityType li').each(function (index, element) {
        var input = $(this).find('input.checkbox-default');
        input.prop("checked", false);
    });
    if (_GET['activityType'] !== undefined) {
        var seleted = _GET.activityType.split(',');
        seleted.forEach(function (value) {
            $('ul#activityType li').each(function (index, element) {
                var input = $(this).find('input.checkbox-default');
                if (input.val() === value) {
                    input.prop("checked", true);
                }
            });
        });
    }
    else {
        $('ul#activityType li').find('input.checkbox-default').first().prop("checked", true);
    }

    $('ul#activity li').each(function (index, element) {
        var input = $(this).find('input.checkbox-default');
        input.prop("checked", false);
    });
    if (_GET['activity'] !== undefined) {
        var seleted = _GET.activity.split(',');
        seleted.forEach(function (value) {
            $('ul#activity li').each(function (index, element) {
                var input = $(this).find('input.checkbox-default');
                if (input.val() === value) {
                    input.prop("checked", true);
                }
            });
        });
    }
    else {
        $('ul#activity li').find('input.checkbox-default').first().prop("checked", true);
    }

    $('ul#activityClassification li').each(function (index, element) {
        var input = $(this).find('input.checkbox-default');
        input.prop("checked", false);
    });
    if (_GET['activityClassification'] !== undefined) {
        var seleted = _GET.activityClassification.split(',');
        seleted.forEach(function (value) {
            $('ul#activityClassification li').each(function (index, element) {
                var input = $(this).find('input.checkbox-default');
                if (input.val() === value) {
                    input.prop("checked", true);
                }
            });
        });
    }
    else {
        $('ul#activityClassification li').find('input.checkbox-default').first().prop("checked", true);
    }

    $('ul#eventType li').each(function (index, element) {
        var input = $(this).find('input.checkbox-default');
        input.prop("checked", false);
    });
    if (_GET['eventType'] !== undefined) {
        var seleted = _GET.eventType.split(',');
        seleted.forEach(function (value) {
            $('ul#eventType li').each(function (index, element) {
                var input = $(this).find('input.checkbox-default');
                if (input.val() === value) {
                    input.prop("checked", true);
                }
            });
        });
    }
    else {
        $('ul#eventType li').find('input.checkbox-default').first().prop("checked", true);
    }

    $('ul#facility li').each(function (index, element) {
        var input = $(this).find('input.checkbox-default');
        input.prop("checked", false);
    });
    if (_GET['facility'] !== undefined) {
        var seleted = _GET.facility.split(',');
        seleted.forEach(function (value) {
            $('ul#facility li').each(function (index, element) {
                var input = $(this).find('input.checkbox-default');
                if (input.val() === value) {
                    input.prop("checked", true);
                }
            });
        });
    }
    else {
        $('ul#facility li').find('input.checkbox-default').first().prop("checked", true);
    }

    $('ul#campus li').each(function (index, element) {
        var input = $(this).find('input.checkbox-default');
        input.prop("checked", false);
    });
    if (_GET['campus'] !== undefined) {
        var seleted = _GET.campus.split(',');
        seleted.forEach(function (value) {
            $('ul#campus li').each(function (index, element) {
                var input = $(this).find('input.checkbox-default');
                if (input.val() === value) {
                    input.prop("checked", true);
                }
            });
        });
    }
    else {
        $('ul#campus li').find('input.checkbox-default').first().prop("checked", true);
    }

    $('ul#arena li').each(function (index, element) {
        var input = $(this).find('input.checkbox-default');
        input.prop("checked", false);
    });
    if (_GET['arena'] !== undefined) {
        var seleted = _GET.arena.split(',');
        seleted.forEach(function (value) {
            $('ul#arena li').each(function (index, element) {
                var input = $(this).find('input.checkbox-default');
                if (input.val() === value) {
                    input.prop("checked", true);
                }
            });
        });
    }
    else {
        $('ul#arena li').find('input.checkbox-default').first().prop("checked", true);
    }

    $('ul#provider li').each(function (index, element) {
        var input = $(this).find('input.checkbox-default');
        input.prop("checked", false);
    });
    if (_GET['provider'] !== undefined) {
        var seleted = _GET.provider.split(',');
        seleted.forEach(function (value) {
            $('ul#provider li').each(function (index, element) {
                var input = $(this).find('input.checkbox-default');
                if (input.val() === value) {
                    input.prop("checked", true);
                }
            });
        });
    }
    else {
        $('ul#provider li').find('input.checkbox-default').first().prop("checked", true);
    }

    $('ul#instructor li').each(function (index, element) {
        var input = $(this).find('input.checkbox-default');
        input.prop("checked", false);
    });
    if (_GET['instructor'] !== undefined) {
        var seleted = _GET.instructor.split(',');
        seleted.forEach(function (value) {
            $('ul#instructor li').each(function (index, element) {
                var input = $(this).find('input.checkbox-default');
                if (input.val() === value) {
                    input.prop("checked", true);
                }
            });
        });
    }
    else {
        $('ul#instructor li').find('input.checkbox-default').first().prop("checked", true);
    }

    $('ul#program li').each(function (index, element) {
        var input = $(this).find('input.checkbox-default');
        input.prop("checked", false);
    });
    if (_GET['program'] !== undefined) {
        var seleted = _GET.program.split(',');
        seleted.forEach(function (value) {
            $('ul#program li').each(function (index, element) {
                var input = $(this).find('input.checkbox-default');
                if (input.val() === value) {
                    input.prop("checked", true);
                }
            });
        });
    }
    else {
        $('ul#program li').find('input.checkbox-default').first().prop("checked", true);
    }

    $('ul#day li').each(function (index, element) {
        var input = $(this).find('input.checkbox-default');
        input.prop("checked", false);
    });
    if (_GET['day'] !== undefined) {
        var seleted = _GET.day.split(',');
        seleted.forEach(function (value) {
            $('ul#day li').each(function (index, element) {
                var input = $(this).find('input.checkbox-default');
                if (input.val() === value) {
                    input.prop("checked", true);
                }
            });
        });
    }
    else {
        $('ul#day li').find('input.checkbox-default').first().prop("checked", true);
    }

    $('ul#gender li').each(function (index, element) {
        var input = $(this).find('input.checkbox-default');
        input.prop("checked", false);
    });
    if (_GET['gender'] !== undefined) {
        var seleted = _GET.gender.split(',');
        seleted.forEach(function (value) {
            $('ul#gender li').each(function (index, element) {
                var input = $(this).find('input.checkbox-default');
                if (input.val() === value) {
                    input.prop("checked", true);
                }
            });
        });
    }
    else {
        $('ul#gender li').find('input.checkbox-default').first().prop("checked", true);
    }

    $('ul#generation li').each(function (index, element) {
        var input = $(this).find('input.checkbox-default');
        input.prop("checked", false);
    });
    if (_GET['generation'] !== undefined) {
        var seleted = _GET.generation.split(',');
        seleted.forEach(function (value) {
            $('ul#generation li').each(function (index, element) {
                var input = $(this).find('input.checkbox-default');
                if (input.val() === value) {
                    input.prop("checked", true);
                }
            });
        });
    }
    else {
        $('ul#generation li').find('input.checkbox-default').first().prop("checked", true);
    }
}

function SelectAlpha() {
    if ($('.alpha-filter').length > 0) {
        var liIndex;

        if (_GET['alphabet'] !== undefined) {
            var seletedAlphebet = _GET.alphabet.split(',');

            seletedAlphebet.forEach(function (value) {
                $('.alpha-filter li').each(function (index, element) {
                    var input = $(this);
                    if ($(this).text() === value) {
                        input.addClass('select');
                    }
                });
            });
        }

        $('.alpha-filter li').click(function () {
            $(this).toggleClass('select');

            if ($(this).hasClass('select')) {
                liIndex = $(this).index();

                var seletedItem = '';
                /* Collect all selected filter item */
                $('.alpha-filter li').each(function (index, element) {
                    var input = $(this);
                    if ($(this).index() !== liIndex && input.hasClass('select')) {
                        seletedItem += (seletedItem.length > 0 ? ',' : "" ) + input.text();
                    }
                });
                seletedItem += (seletedItem.length > 0 ? ',' : "" ) + $(this).text();
                _GET.alphabet = seletedItem;
            }
            else {
                var deSeletedItem = '';

                $('.alpha-filter li').each(function (index, element) {
                    var input = $(this);

                    if (input.hasClass('select')) {
                        deSeletedItem += (deSeletedItem.length > 0 ? ',' : "" ) + input.text();
                    }
                });
                _GET.alphabet = deSeletedItem;
            }

            updateUrl();
            updateResult();

        });

    }
}

function TipsResize() {
    $('.tips-area').change(function () {
        alert($('.tips-area').alert());
    });
}

function loadSort() {
    if ($('#sort').length > 0) {

        /* pre-select filter items */
        if (_GET['sortBy'] !== undefined) {
            $('#sort').val(_GET['sortBy']);
        }
        if (_GET['searchKeywordBy'] !== undefined && _GET.keyword !== undefined) {
            var seletedkeyword = _GET.keyword.split(',');
            seletedkeyword.forEach(function (value) {
                $('select#sort option').each(function (index, element) {
                    var input = $(this);
                    if (input.val() === value) {
                        input.attr("selected", "true");
                        return;
                    }
                });
            });
        }

        /* Update result when any item selected */
        $('#sort').change(function () {
            if ($('#sort').val() != null) {
                _GET.sortBy = $('#sort').val();
            }
            else {
                _GET.sortBy = null;
            }
            updateUrl();
            updateResult();

        });
    }
}

function loadPerPage() {
    if ($('#perPage').length > 0) {

        /* pre-select filter items */
        if (_GET['perPage'] !== undefined) {
            $('#perPage').val(_GET['perPage']);
        }
        if (_GET['perPage'] !== undefined) {
            var seletedperPage = _GET.perPage.split(',');
            seletedperPage.forEach(function (value) {
                $('select#perPage option').each(function (index, element) {
                    var input = $(this);
                    if (input.val() === value) {
                        input.attr("selected", "true");
                        return;
                    }
                });
            });
        }

        /* Update result when any item selected */
        $('#perPage').change(function () {
            if ($('#perPage').val() != null) {
                _GET.perPage = $('#perPage').val();
            }
            else {
                _GET.perPage = null;
            }
            updateUrl();
            updateResult();

        });
    }
}

function loadFreeTrial() {
    if ($('#freeTrial').length > 0) {
        $.ajax({
            type: "GET",
            url: "/filter/freeTrial",
            data: window.location.search.substr(1, window.location.search.length),
            success: function (data) {
                var len = data.length;
                $('ul#freeTrial').html("").append('<li><input name="checkresult" value="any" checked="" class="checkbox-default" type="checkbox"><lable><span>Any</span></lable></li>');

                var total = 0;
                for (var i = 0; i < len; i++) {
                    if (data[i]['counter'] === 0) continue;
                    $('#freeTrial').append('<li><input type="checkbox" name="checkresult" value="' + data[i]['name'] + '" class="checkbox-default"><lable><span>' + data[i]['name'] + ' (' + data[i]['counter'] + ')' + '</span></lable></li>')
                    total += parseInt(data[i]['counter']);
                }
                $('ul#freeTrial li').first().find('lable span').text('Any (' + total + ')');

                /* pre-select filter items */
                if (_GET['freeTrial'] !== undefined) {
                    var seletedfreeTrial = _GET.freeTrial.split(',');
                    if (seletedfreeTrial.length > 0) {
                        $('ul#freeTrial li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedfreeTrial.forEach(function (value) {
                        $('ul#freeTrial li').each(function (index, element) {
                            var input = $(this).find('input.checkbox-default');
                            if (input.val() === value) {
                                input.attr("checked", "true");
                            }
                        });
                    });
                }

                /* pre-select filter items */
                if (_GET['freeTrial'] !== undefined) {
                    var seletedfreeTrial = _GET.freeTrial.split(',');
                    if (seletedfreeTrial.length > 0 && _GET.freeTrial.length > 0) {
                        $('ul#freeTrial li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedfreeTrial.forEach(function (value) {
                        $('ul#freeTrial li').each(function (index, element) {
                            var input = $(this).first().find('input.checkbox-default');
                            if (input.val() === value) {
                                input.prop("checked", true);
                            }
                        });
                    });
                }

                /* Update result when any item selected */
                $('ul#freeTrial li').each(function (index, element) {

                    var liIndex = index;
                    $(element).find('input.checkbox-default').click(function () {
                        /* select others except 'any item' */
                        if (liIndex > 0 && $(this).is(":checked")) {
                            var anyCheck = $('ul#freeTrial li').first().find('input.checkbox-default');
                            anyCheck.prop("checked", false);
                        }
                        else if (liIndex === 0 && $(this).is(":checked")) {
                            $('ul#freeTrial li').each(function () {
                                $(this).find('input.checkbox-default').prop("checked", false);
                            });
                            $('ul#freeTrial li:first-child').find('input.checkbox-default').prop("checked", true);
                        }
                        /**/
                        if ($(this).is(":checked")) {
                            var seletedItem = '';
                            /* Collect all selected filter item */
                            $('ul#freeTrial li').each(function (index, element) {
                                var input = $(this).find('input.checkbox-default');
                                if ($(this).index() !== liIndex && input.is(":checked")) {
                                    seletedItem += (seletedItem.length > 0 ? ',' : "" ) + input.val();
                                }
                            });
                            seletedItem += (seletedItem.length > 0 ? ',' : "" ) + $(this).val();
                            _GET.freeTrial = seletedItem;
                        }
                        else {
                            var _get = removeValueOnArray(_GET.freeTrial.split(','), $(this).val());
                            _GET.freeTrial = ( _get.join(',') ? _get.join(',') : '');
                            console.log(_GET.freeTrial);

                            if (_get.length === 0) {
                                $('ul#freeTrial li').find('input.checkbox-default').first().prop("checked", true);
                            }
                        }
                        _updateFilter = true;
                        updateUrl();
                        updateResult();

                    });
                });
            },
            error: function (result) {/**/
            }
        });
    }
}

function loadClassAvailable() {
    if ($('#classAvailable').length > 0) {

        $.ajax({
            type: "GET",
            url: "/filter/classAvailable",
            data: window.location.search.substr(1, window.location.search.length),
            success: function (data) {
                var len = data.length;
                $('ul#classAvailable').html("").append('<li><input name="checkresult" value="any" checked="" class="checkbox-default" type="checkbox"><lable><span>Any</span></lable></li>');

                var total = 0;
                for (var i = 0; i < len; i++) {
                    if (data[i]['counter'] === 0) continue;
                    $('#classAvailable').append('<li><input type="checkbox" name="checkresult" value="' + data[i]['name'].toLowerCase() + '" class="checkbox-default"><lable><span>' + data[i]['name'] + ' (' + data[i]['counter'] + ')' + '</span></lable></li>')
                    total += parseInt(data[i]['counter']);
                }
                $('ul#classAvailable li').first().find('lable span').text('Any (' + total + ')');

                /* pre-select filter items */
                if (_GET['classAvailable'] !== undefined) {
                    var seletedclassAvailable = _GET.classAvailable.split(',');
                    if (seletedclassAvailable.length > 0 && _GET.classAvailable.length > 0) {
                        $('ul#classAvailable li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedclassAvailable.forEach(function (value) {
                        $('ul#classAvailable li').each(function (index, element) {
                            var input = $(this).first().find('input.checkbox-default');
                            if (input.val() === value) {
                                input.prop("checked", true);
                            }
                        });
                    });
                }

                /* Update result when any item selected */
                $('ul#classAvailable li').each(function (index, element) {

                    var liIndex = index;
                    $(element).find('input.checkbox-default').click(function () {
                        /* select others except 'any item' */
                        if (liIndex > 0 && $(this).is(":checked")) {
                            var anyCheck = $('ul#classAvailable li').first().find('input.checkbox-default');
                            anyCheck.prop("checked", false);
                        }
                        else if (liIndex === 0 && $(this).is(":checked")) {
                            $('ul#classAvailable li').each(function () {
                                $(this).find('input.checkbox-default').prop("checked", false);
                            });
                            $('ul#classAvailable li:first-child').find('input.checkbox-default').prop("checked", true);
                        }
                        /**/
                        if ($(this).is(":checked")) {
                            var seletedItem = '';
                            /* Collect all selected filter item */
                            $('ul#classAvailable li').each(function (index, element) {
                                var input = $(this).find('input.checkbox-default');
                                if ($(this).index() !== liIndex && input.is(":checked")) {
                                    seletedItem += (seletedItem.length > 0 ? ',' : "" ) + input.val();
                                }
                            });
                            seletedItem += (seletedItem.length > 0 ? ',' : "" ) + $(this).val();
                            _GET.classAvailable = seletedItem;
                        }
                        else {
                            var _get = removeValueOnArray(_GET.classAvailable.split(','), $(this).val());
                            _GET.classAvailable = ( _get.join(',') ? _get.join(',') : '');
                            console.log(_GET.classAvailable);

                            if (_get.length === 0) {
                                $('ul#classAvailable li').find('input.checkbox-default').first().prop("checked", true);
                            }
                        }
                        _updateFilter = true;
                        updateUrl();
                        updateResult();

                    });
                });
            },
            error: function (result) {/**/
            }
        });
    }
}

function loadCountry() {
    if ($('#country').length > 0) {

        $.ajax({
            type: "GET",
            url: "/filter/country",
            data: window.location.search.substr(1, window.location.search.length),
            success: function (data) {
                var len = data.length;

                $('ul#country').html("").append('<li><input name="checkresult" value="any" checked="" class="checkbox-default" type="checkbox"><lable><span>Any</span></lable></li>');

                var total = 0;
                for (var i = 0; i < len; i++) {
                    if (data[i]['counter'] === 0) continue;
                    $('#country').append('<li><input type="checkbox" name="checkresult" value="' + data[i]['name'] + '" class="checkbox-default"><lable> <span>' + data[i]['name'] + ' (' + data[i]['counter'] + ')' + '</span></lable></li>')
                    total += parseInt(data[i]['counter']);
                }
                $('ul#country li').first().find('lable span').text('Any (' + total + ')');

                /* pre-select filter items */
                if (_GET['country'] !== undefined) {
                    var seletedcountry = _GET.country.split(',');
                    //console.log(seletedcountry);
                    if (seletedcountry.length > 0 && _GET.country.length > 0) {
                        $('ul#country li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedcountry.forEach(function (value) {
                        $('ul#country li').each(function (index, element) {
                            var input = $(this).find('input.checkbox-default');
                            if (input.val() === value) {
                                input.prop("checked", true);
                            }
                        });
                    });
                    loadCity(_GET['country']);
                }

                /* Update result when any item selected */
                $('ul#country li').each(function (index, element) {

                    var liIndex = index;
                    $(element).find('input.checkbox-default').click(function () {
                        /* select others except 'any item' */
                        if (liIndex > 0 && $(this).is(":checked")) {
                            var anyCheck = $('ul#country li').first().find('input.checkbox-default');
                            anyCheck.prop("checked", false);

                        }
                        else if (liIndex === 0 && $(this).is(":checked")) {
                            $('ul#country li').each(function () {
                                $(this).find('input.checkbox-default').prop("checked", false);
                            });
                            $('ul#country li:first-child').find('input.checkbox-default').prop("checked", true);
                        }
                        /**/
                        if ($(this).is(":checked")) {
                            var seletedItem = '';
                            /* Collect all selected filter item */
                            $('ul#country li').each(function (index, element) {
                                var input = $(this).find('input.checkbox-default');
                                if ($(this).index() !== liIndex && input.is(":checked")) {
                                    seletedItem += (seletedItem.length > 0 ? ',' : "" ) + input.val();
                                }
                            });
                            seletedItem += (seletedItem.length > 0 ? ',' : "" ) + $(this).val();
                            _GET.country = seletedItem;
                        }
                        else {
                            var _get = removeValueOnArray(_GET.country.split(','), $(this).val());
                            _GET.country = _get.join(',');

                            if (_get.length === 0) {
                                $('ul#country li').find('input.checkbox-default').first().prop("checked", true);
                            }
                        }

                        _updateFilter = true;
                        updateUrl();
                        updateResult();

                        loadCity(_GET['country']);
                    });
                });
            },
            error: function (result) {/**/
            }
        });
    }
}

function loadCity(country) {
    if ($('#city').length > 0) {

        $.ajax({
            type: "GET",
            url: "/filter/city",
            data: window.location.search.substr(1, window.location.search.length) + ( _GET['country'] != null ? '' : "&country=" + country),
            success: function (data) {
                var len = data.length;

                $('ul#city').html("").append('<li><input name="checkresult" value="any" checked="" class="checkbox-default" type="checkbox"><lable><span>Any</span></lable></li>');

                var total = 0;
                for (var i = 0; i < len; i++) {
                    if (data[i]['counter'] === 0) continue;
                    $('#city').append('<li><input type="checkbox" name="checkresult" value="' + data[i]['name'] + '" class="checkbox-default"><lable> <span>' + data[i]['name'] + ' (' + data[i]['counter'] + ')' + '</span></lable></li>')
                    total += parseInt(data[i]['counter']);
                }
                $('ul#city li').first().find('lable span').text('Any (' + total + ')');

                /* pre-select filter items */
                if (_GET['city'] !== undefined) {
                    var seletedcity = _GET.city.split(',');
                    if (seletedcity.length > 0 && _GET.city.length > 0) {
                        $('ul#city li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedcity.forEach(function (value) {
                        $('ul#city li').each(function (index, element) {
                            var input = $(this).find('input.checkbox-default');
                            if (input.val() === value) {
                                input.prop("checked", true);
                            }
                        });
                    });
                    loadLocation(_GET['city']);
                }

                /* Update result when any item selected */
                $('ul#city li').each(function (index, element) {

                    var liIndex = index;
                    $(element).find('input.checkbox-default').click(function () {
                        /* select others except 'any item' */
                        if (liIndex > 0 && $(this).is(":checked")) {
                            $('ul#city li').first().find('input.checkbox-default').prop("checked", false);
                        }
                        else if (liIndex === 0 && $(this).is(":checked")) {
                            $('ul#city li').each(function () {
                                $(this).find('input.checkbox-default').prop("checked", false);
                            });
                            $('ul#city li:first-child').find('input.checkbox-default').prop("checked", true);
                        }
                        /**/
                        if ($(this).is(":checked")) {
                            var seletedItem = '';
                            /* Collect all selected filter item */
                            $('ul#city li').each(function (index, element) {
                                var input = $(this).find('input.checkbox-default');
                                if ($(this).index() !== liIndex && input.is(":checked")) {
                                    seletedItem += (seletedItem.length > 0 ? ',' : "" ) + input.val();
                                }
                            });
                            seletedItem += (seletedItem.length > 0 ? ',' : "" ) + $(this).val();
                            _GET.city = seletedItem;
                        }
                        else {
                            var _get = removeValueOnArray(_GET.city.split(','), $(this).val());
                            _GET.city = _get.join(',');

                            if (_get.length === 0) {
                                $('ul#city li').find('input.checkbox-default').first().prop("checked", true);
                            }
                        }

                        _updateFilter = true;
                        updateUrl();
                        updateResult();

                        loadLocation(_GET['city']);
                    });
                });
            },
            error: function (result) {/**/
            }
        });
    }
}

function loadLocation(city) {
    if ($('#location').length > 0) {
        $.ajax({
            type: "GET",
            url: "/filter/location",
            data: window.location.search.substr(1, window.location.search.length) +
            (_GET['city'] !== null ? '' : "&city=" + city),
            success: function (data) {
                var len = data.length;
                $('ul#location').html("").append('<li><input name="checkresult" value="any" checked="" class="checkbox-default" type="checkbox"><lable><span>Any</span></lable></li>');

                var total = 0;
                for (var i = 0; i < len; i++) {
                    if (data[i]['counter'] === 0) continue;
                    $('#location').append('<li><input type="checkbox" name="checkresult" value="' + data[i]['name'] + '" class="checkbox-default"><lable><span>' + data[i]['name'] + ' (' + data[i]['counter'] + ')' + '</span></lable></li>')
                    total += parseInt(data[i]['counter']);
                }
                $('ul#location li').first().find('lable span').text('Any (' + total + ')');

                /* pre-select filter items */
                if (_GET['location'] !== undefined) {
                    var seletedlocation = _GET.location.split(',');
                    if (seletedlocation.length > 0 && _GET.location.length > 0) {
                        $('ul#location li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedlocation.forEach(function (value) {
                        $('ul#location li').each(function (index, element) {
                            var input = $(this).find('input.checkbox-default');
                            if (input.val() === value) {
                                input.prop("checked", true);
                            }
                        });
                    });
                }

                /* Update result when any item selected */
                $('ul#location li').each(function (index, element) {

                    var liIndex = index;
                    $(element).find('input.checkbox-default').click(function () {
                        /* select others except 'any item' */
                        if (liIndex > 0 && $(this).is(":checked")) {
                            var anyCheck = $('ul#location li').first().find('input.checkbox-default');
                            anyCheck.prop("checked", false);
                        }
                        else if (liIndex === 0 && $(this).is(":checked")) {
                            $('ul#location li').each(function () {
                                $(this).find('input.checkbox-default').prop("checked", false);
                            });
                            $('ul#location li:first-child').find('input.checkbox-default').prop("checked", true);
                        }
                        /**/
                        if ($(this).is(":checked")) {
                            var seletedItem = '';
                            /* Collect all selected filter item */
                            $('ul#location li').each(function (index, element) {
                                var input = $(this).find('input.checkbox-default');
                                if ($(this).index() !== liIndex && input.is(":checked")) {
                                    seletedItem += (seletedItem.length > 0 ? ',' : "" ) + input.val();
                                }
                            });
                            seletedItem += (seletedItem.length > 0 ? ',' : "" ) + $(this).val();
                            _GET.location = seletedItem;
                        }
                        else {
                            var _get = removeValueOnArray(_GET.location.split(','), $(this).val());
                            _GET.location = _get.join(',');

                            if (_get.length === 0) {
                                $('ul#location li').find('input.checkbox-default').first().prop("checked", true);
                            }
                        }
                        _updateFilter = true;
                        updateUrl();
                        updateResult();

                    });
                });
            },
            error: function (result) {/**/
            }
        });
    }
}

function loadActivityType() {
    if ($('#activityType').length > 0) {
        $.ajax({
            type: "GET",
            url: "/filter/activityType",
            data: window.location.search.substr(1, window.location.search.length),
            success: function (data) {
                var len = data.length;
                $('ul#activityType').html("").append('<li><input name="checkresult" value="any" checked="" class="checkbox-default" type="checkbox"><lable><span>Any</span></lable></li>');

                var total = 0;
                for (var i = 0; i < len; i++) {
                    if (data[i]['counter'] === 0) continue;
                    $('#activityType').append('<li><input type="checkbox" name="checkresult" value="' + data[i]['name'] + '" class="checkbox-default"><lable><span>' + data[i]['name'] + ' (' + data[i]['counter'] + ')' + '</span></lable></li>')
                    total += parseInt(data[i]['counter']);
                }
                $('ul#activityType li').first().find('lable span').text('Any (' + total + ')');

                /* pre-select filter items */
                if (_GET['activityType'] !== undefined) {
                    var seletedactivityType = _GET.activityType.split(',');
                    if (seletedactivityType.length > 0 && _GET.activityType.length > 0) {
                        $('ul#activityType li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedactivityType.forEach(function (value) {
                        $('ul#activityType li').each(function (index, element) {
                            var input = $(this).find('input.checkbox-default');
                            if (input.val() === value) {
                                input.prop("checked", true);
                            }
                        });
                    });
                    loadActivityClassification(_GET['activityType']);
                }

                /* Update result when any item selected */
                $('ul#activityType li').each(function (index, element) {

                    var liIndex = index;
                    $(element).find('input.checkbox-default').click(function () {
                        /* select others except 'any item' */
                        if (liIndex > 0 && $(this).is(":checked")) {
                            var anyCheck = $('ul#activityType li').first().find('input.checkbox-default');
                            anyCheck.prop("checked", false);
                        }
                        else if (liIndex === 0 && $(this).is(":checked")) {
                            $('ul#activityType li').each(function () {
                                $(this).find('input.checkbox-default').prop("checked", false);
                            });
                            $('ul#activityType li:first-child').find('input.checkbox-default').prop("checked", true);
                        }
                        /**/
                        if ($(this).is(":checked")) {
                            var seletedItem = '';
                            /* Collect all selected filter item */
                            $('ul#activityType li').each(function (index, element) {
                                var input = $(this).find('input.checkbox-default');
                                if ($(this).index() !== liIndex && input.is(":checked")) {
                                    seletedItem += (seletedItem.length > 0 ? ',' : "" ) + input.val();
                                }
                            });
                            seletedItem += (seletedItem.length > 0 ? ',' : "" ) + $(this).val();
                            _GET.activityType = seletedItem;
                        }
                        else {
                            var _get = removeValueOnArray(_GET.activityType.split(','), $(this).val());
                            _GET.activityType = _get.join(',');

                            if (_get.length === 0) {
                                $('ul#activityType li').find('input.checkbox-default').first().prop("checked", true);
                            }
                        }
                        _updateFilter = true;
                        updateUrl();
                        updateResult();

                        loadActivityClassification(_GET['activityType']);
                    });
                });
            },
            error: function (result) {/**/
            }
        });
    }
}

function loadActivityClassification(activityType) {
    if ($('#activityClassification').length > 0) {
        $.ajax({
            type: "GET",
            url: "/filter/activityClassification",
            data: window.location.search.substr(1, window.location.search.length) +
            (_GET['activityType'] !== null ? '' : "&activityType=" + activityType),
            success: function (data) {
                var len = data.length;
                $('ul#activityClassification').html("").append('<li><input name="checkresult" value="any" checked="" class="checkbox-default" type="checkbox"><lable><span>Any</span></lable></li>');

                var total = 0;
                for (var i = 0; i < len; i++) {
                    if (data[i]['counter'] === 0) continue;
                    $('#activityClassification').append('<li><input type="checkbox" name="checkresult" value="' + data[i]['name'] + '" class="checkbox-default"><lable><span> ' + data[i]['name'] + ' (' + data[i]['counter'] + ')' + '</span></lable></li>')
                    total += parseInt(data[i]['counter']);
                }
                $('ul#activityClassification li').first().find('lable span').text('Any (' + total + ')');

                /* pre-select filter items */
                if (_GET['activityClassification'] !== undefined) {
                    var seletedactivityClassification = _GET.activityClassification.split(',');
                    if (seletedactivityClassification.length > 0 && _GET.activityClassification.length > 0) {
                        $('ul#activityClassification li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedactivityClassification.forEach(function (value) {
                        $('ul#activityClassification li').each(function (index, element) {
                            var input = $(this).find('input.checkbox-default');
                            if (input.val() === value) {
                                input.prop("checked", true);

                            }
                        });
                    });
                    loadActivity(_GET['activityClassification']);
                }

                /* Update result when any item selected */
                $('ul#activityClassification li').each(function (index, element) {

                    var liIndex = index;
                    $(element).find('input.checkbox-default').click(function () {
                        /* select others except 'any item' */
                        if (liIndex > 0 && $(this).is(":checked")) {
                            var anyCheck = $('ul#activityClassification li').first().find('input.checkbox-default');
                            anyCheck.prop("checked", false);
                        }
                        else if (liIndex === 0 && $(this).is(":checked")) {
                            $('ul#activityClassification li').each(function () {
                                $(this).find('input.checkbox-default').prop("checked", false);
                            });
                            $('ul#activityClassification li:first-child').find('input.checkbox-default').prop("checked", true);
                        }
                        /**/
                        if ($(this).is(":checked")) {
                            var seletedItem = '';
                            /* Collect all selected filter item */
                            $('ul#activityClassification li').each(function (index, element) {
                                var input = $(this).find('input.checkbox-default');
                                if ($(this).index() !== liIndex && input.is(":checked")) {
                                    seletedItem += (seletedItem.length > 0 ? ',' : "" ) + input.val();
                                }
                            });
                            seletedItem += (seletedItem.length > 0 ? ',' : "" ) + $(this).val();
                            _GET.activityClassification = seletedItem;
                        }
                        else {
                            var _get = removeValueOnArray(_GET.activityClassification.split(','), $(this).val());
                            _GET.activityClassification = _get.join(',');

                            if (_get.length === 0) {
                                $('ul#activityClassification li').find('input.checkbox-default').first().prop("checked", true);
                            }
                        }
                        _updateFilter = true;
                        updateUrl();
                        updateResult();

                        loadActivity(_GET['activityClassification']);
                    });
                });
            },
            error: function (result) {/**/
            }
        });
    }
}

function loadActivity(activityClassification) {
    if ($('#activity').length > 0) {
        $.ajax({
            type: "GET",
            url: "/filter/activity",
            data: window.location.search.substr(1, window.location.search.length) +
            (_GET['activityClassification'] !== null ? '' : "&activityClassification=" + activityClassification),
            success: function (data) {
                var len = data.length;
                $('ul#activity').html("").append('<li><input name="checkresult" value="any" checked="" class="checkbox-default" type="checkbox"><lable><span>Any</span></lable></li>');

                var total = 0;
                for (var i = 0; i < len; i++) {
                    if (data[i]['counter'] === 0) continue;
                    $('#activity').append('<li><input type="checkbox" name="checkresult" value="' + data[i]['name'] + '" class="checkbox-default"><lable><span> ' + data[i]['name'] + ' (' + data[i]['counter'] + ')' + '</span></lable></li>')
                    total += parseInt(data[i]['counter']);
                }
                $('ul#activity li').first().find('lable span').text('Any (' + total + ')');

                /* pre-select filter items */
                if (_GET['activity'] !== undefined) {
                    var seletedactivity = _GET.activity.split(',');
                    if (seletedactivity.length > 0 && _GET.activity.length > 0) {
                        $('ul#activity li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedactivity.forEach(function (value) {
                        $('ul#activity li').each(function (index, element) {
                            var input = $(this).find('input.checkbox-default');
                            if (input.val() === value) {
                                input.prop("checked", true);
                            }
                        });
                    });
                }

                /* Update result when any item selected */
                $('ul#activity li').each(function (index, element) {

                    var liIndex = index;
                    $(element).find('input.checkbox-default').click(function () {
                        /* select others except 'any item' */
                        if (liIndex > 0 && $(this).is(":checked")) {
                            var anyCheck = $('ul#activity li').first().find('input.checkbox-default');
                            anyCheck.prop("checked", false);
                        }
                        else if (liIndex === 0 && $(this).is(":checked")) {
                            $('ul#activity li').each(function () {
                                $(this).find('input.checkbox-default').prop("checked", false);
                            });
                            $('ul#activity li:first-child').find('input.checkbox-default').prop("checked", true);
                        }
                        /**/
                        if ($(this).is(":checked")) {
                            var seletedItem = '';
                            /* Collect all selected filter item */
                            $('ul#activity li').each(function (index, element) {
                                var input = $(this).find('input.checkbox-default');
                                if ($(this).index() !== liIndex && input.is(":checked")) {
                                    seletedItem += (seletedItem.length > 0 ? ',' : "" ) + input.val();
                                }
                            });
                            seletedItem += (seletedItem.length > 0 ? ',' : "" ) + $(this).val();
                            _GET.activity = seletedItem;
                        }
                        else {
                            var _get = removeValueOnArray(_GET.activity.split(','), $(this).val());
                            _GET.activity = _get.join(',');

                            if (_get.length === 0) {
                                $('ul#activity li').find('input.checkbox-default').first().prop("checked", true);
                            }
                        }
                        _updateFilter = true;
                        updateUrl();
                        updateResult();
                    });
                });
            }
        });
    }
}

function loadEventType() {
    if ($('#eventType').length > 0) {
        $.ajax({
            type: "GET",
            url: "/filter/eventType",
            data: window.location.search.substr(1, window.location.search.length),
            success: function (data) {
                var len = data.length;
                $('ul#eventType').html("").append('<li><input name="checkresult" value="any" checked="" class="checkbox-default" type="checkbox"><lable><span>Any</span></lable></li>');

                var total = 0;
                for (var i = 0; i < len; i++) {
                    if (data[i]['counter'] === 0) continue;
                    $('#eventType').append('<li><input type="checkbox" name="checkresult" value="' + data[i]['name'] + '" class="checkbox-default"><lable><span> ' + data[i]['name'] + ' (' + data[i]['counter'] + ')' + '</span></lable></li>')
                    total += parseInt(data[i]['counter']);
                }
                $('ul#eventType li').first().find('lable span').text('Any (' + total + ')');

                /* pre-select filter items */
                if (_GET['eventType'] !== undefined) {
                    var seletedeventType = _GET.eventType.split(',');
                    if (seletedeventType.length > 0 && _GET.eventType.length > 0) {
                        $('ul#eventType li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedeventType.forEach(function (value) {
                        $('ul#eventType li').each(function (index, element) {
                            var input = $(this).find('input.checkbox-default');
                            if (input.val() === value) {
                                input.prop("checked", true);
                            }
                        });
                    });
                }

                /* Update result when any item selected */
                $('ul#eventType li').each(function (index, element) {

                    var liIndex = index;
                    $(element).find('input.checkbox-default').click(function () {
                        /* select others except 'any item' */
                        if (liIndex > 0 && $(this).is(":checked")) {
                            var anyCheck = $('ul#eventType li').first().find('input.checkbox-default');
                            anyCheck.prop("checked", false);
                        }
                        else if (liIndex === 0 && $(this).is(":checked")) {
                            $('ul#eventType li').each(function () {
                                $(this).find('input.checkbox-default').prop("checked", false);
                            });
                            $('ul#eventType li:first-child').find('input.checkbox-default').prop("checked", true);
                        }
                        /**/
                        if ($(this).is(":checked")) {
                            var seletedItem = '';
                            /* Collect all selected filter item */
                            $('ul#eventType li').each(function (index, element) {
                                var input = $(this).find('input.checkbox-default');
                                if ($(this).index() !== liIndex && input.is(":checked")) {
                                    seletedItem += (seletedItem.length > 0 ? ',' : "" ) + input.val();
                                }
                            });
                            seletedItem += (seletedItem.length > 0 ? ',' : "" ) + $(this).val();
                            _GET.eventType = seletedItem;
                        }
                        else {
                            var _get = removeValueOnArray(_GET.eventType.split(','), $(this).val());
                            _GET.eventType = _get.join(',');

                            if (_get.length === 0) {
                                $('ul#eventType li').find('input.checkbox-default').first().prop("checked", true);
                            }
                        }
                        _updateFilter = true;
                        updateUrl();
                        updateResult();
                    });
                });
            },
            error: function (result) {/**/
            }
        });
    }
}

function loadFacility() {
    if ($('#facility').length > 0) {
        $.ajax({
            type: "GET",
            url: "/filter/facility",
            data: window.location.search.substr(1, window.location.search.length),
            success: function (data) {
                var len = data.length;
                $('ul#facility').html("").append('<li><input name="checkresult" value="any" checked="" class="checkbox-default" type="checkbox"><lable><span>Any</span></lable></li>');

                var total = 0;
                for (var i = 0; i < len; i++) {
                    if (data[i]['counter'] === 0) continue;
                    $('#facility').append('<li><input type="checkbox" name="checkresult" value="' + data[i]['name'] + '" class="checkbox-default"><lable><span>' + data[i]['name'] + ' (' + data[i]['counter'] + ')' + '</span></lable></li>')
                    total += parseInt(data[i]['counter']);
                }
                $('ul#facility li').first().find('lable span').text('Any (' + total + ')');

                /* pre-select filter items */
                if (_GET['facility'] !== undefined) {
                    var seletedfacility = _GET.facility.split(',');
                    if (seletedfacility.length > 0 && _GET.facility.length > 0) {
                        $('ul#facility li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedfacility.forEach(function (value) {
                        $('ul#facility li').each(function (index, element) {
                            var input = $(this).find('input.checkbox-default');
                            if (input.val() === value) {
                                input.prop("checked", true);
                            }
                        });
                    });
                    loadCampus(_GET['facility']);
                }

                /* Update result when any item selected */
                $('ul#facility li').each(function (index, element) {

                    var liIndex = index;
                    $(element).find('input.checkbox-default').click(function () {
                        /* select others except 'any item' */
                        if (liIndex > 0 && $(this).is(":checked")) {
                            var anyCheck = $('ul#facility li').first().find('input.checkbox-default');
                            anyCheck.prop("checked", false);
                        }
                        else if (liIndex === 0 && $(this).is(":checked")) {
                            $('ul#facility li').each(function () {
                                $(this).find('input.checkbox-default').prop("checked", false);
                            });
                            $('ul#facility li:first-child').find('input.checkbox-default').prop("checked", true);
                        }
                        /**/
                        if ($(this).is(":checked")) {
                            var seletedItem = '';
                            /* Collect all selected filter item */
                            $('ul#facility li').each(function (index, element) {
                                var input = $(this).find('input.checkbox-default');
                                if ($(this).index() !== liIndex && input.is(":checked")) {
                                    seletedItem += (seletedItem.length > 0 ? ',' : "" ) + input.val();
                                }
                            });
                            seletedItem += (seletedItem.length > 0 ? ',' : "" ) + $(this).val();
                            _GET.facility = seletedItem;
                        }
                        else {
                            var _get = removeValueOnArray(_GET.facility.split(','), $(this).val());
                            _GET.facility = _get.join(',');

                            if (_get.length === 0) {
                                $('ul#facility li').find('input.checkbox-default').first().prop("checked", true);
                            }
                        }
                        _updateFilter = true;
                        updateUrl();
                        updateResult();

                        loadCampus(_GET['facility']);
                    });
                });
            },
            error: function (result) {/**/
            }
        });
    }
}

function loadCampus(facility) {
    if ($('#campus').length > 0) {
        $.ajax({
            type: "GET",
            url: "/filter/campus",
            data: window.location.search.substr(1, window.location.search.length) +
            (_GET['facility'] !== null ? '' : "&facility=" + facility),
            success: function (data) {
                var len = data.length;
                $('ul#campus').html("").append('<li><input name="checkresult" value="any" checked="" class="checkbox-default" type="checkbox"><lable><span>Any</span></lable></li>');

                var total = 0;
                for (var i = 0; i < len; i++) {
                    if (data[i]['counter'] === 0) continue;
                    $('#campus').append('<li><input type="checkbox" name="checkresult" value="' + data[i]['name'] + '" class="checkbox-default"><lable><span>' + data[i]['name'] + ' (' + data[i]['counter'] + ')' + '</span></lable></li>')
                    total += parseInt(data[i]['counter']);
                }
                $('ul#campus li').first().find('lable span').text('Any (' + total + ')');

                /* pre-select filter items */
                if (_GET['campus'] !== undefined) {
                    var seletedcampus = _GET.campus.split(',');
                    if (seletedcampus.length > 0 && _GET.campus.length > 0) {
                        $('ul#campus li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedcampus.forEach(function (value) {
                        $('ul#campus li').each(function (index, element) {
                            var input = $(this).find('input.checkbox-default');
                            if (input.val() === value) {
                                input.prop("checked", true);
                            }
                        });
                    });

                    loadArena(_GET['campus']);
                }

                /* Update result when any item selected */
                $('ul#campus li').each(function (index, element) {

                    var liIndex = index;
                    $(element).find('input.checkbox-default').click(function () {
                        /* select others except 'any item' */
                        if (liIndex > 0 && $(this).is(":checked")) {
                            var anyCheck = $('ul#campus li').first().find('input.checkbox-default');
                            anyCheck.prop("checked", false);
                        }
                        else if (liIndex === 0 && $(this).is(":checked")) {
                            $('ul#campus li').each(function () {
                                $(this).find('input.checkbox-default').prop("checked", false);
                            });
                            $('ul#campus li:first-child').find('input.checkbox-default').prop("checked", true);
                        }
                        /**/
                        if ($(this).is(":checked")) {
                            var seletedItem = '';
                            /* Collect all selected filter item */
                            $('ul#campus li').each(function (index, element) {
                                var input = $(this).find('input.checkbox-default');
                                if ($(this).index() !== liIndex && input.is(":checked")) {
                                    seletedItem += (seletedItem.length > 0 ? ',' : "" ) + input.val();
                                }
                            });
                            seletedItem += (seletedItem.length > 0 ? ',' : "" ) + $(this).val();
                            _GET.campus = seletedItem;
                        }
                        else {
                            var _get = removeValueOnArray(_GET.campus.split(','), $(this).val());
                            _GET.campus = _get.join(',');

                            if (_get.length === 0) {
                                $('ul#campus li').find('input.checkbox-default').first().prop("checked", true);
                            }
                        }
                        _updateFilter = true;
                        updateUrl();
                        updateResult();

                        loadArena(_GET['campus']);
                    });
                });
            },
            error: function (result) {/**/
            }
        });
    }
}

function loadArena(campus) {
    if ($('#arena').length > 0) {
        $.ajax({
            type: "GET",
            url: "/filter/arena",
            data: window.location.search.substr(1, window.location.search.length) +
            (_GET['campus'] !== null ? '' : "&campus=" + campus),

            success: function (data) {
                var len = data.length;
                $('ul#arena').html("").append('<li><input name="checkresult" value="any" checked="" class="checkbox-default" type="checkbox"><lable><span>Any</span></lable></li>');

                var total = 0;
                for (var i = 0; i < len; i++) {
                    if (data[i]['counter'] === 0) continue;
                    $('#arena').append('<li><input type="checkbox" name="checkresult" value="' + data[i]['name'] + '" class="checkbox-default"><lable><span>' + data[i]['name'] + ' (' + data[i]['counter'] + ')' + '</span></lable></li>')
                    total += parseInt(data[i]['counter']);
                }
                $('ul#arena li').first().find('lable span').text('Any (' + total + ')');

                /* pre-select filter items */
                if (_GET['arena'] !== undefined) {
                    var seletedarena = _GET.arena.split(',');
                    if (seletedarena.length > 0 && _GET.arena.length > 0) {
                        $('ul#arena li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedarena.forEach(function (value) {
                        $('ul#arena li').each(function (index, element) {
                            var input = $(this).find('input.checkbox-default');
                            if (input.val() === value) {
                                input.prop("checked", true);
                            }
                        });
                    });
                }

                /* Update result when any item selected */
                $('ul#arena li').each(function (index, element) {

                    var liIndex = index;
                    $(element).find('input.checkbox-default').click(function () {
                        /* select others except 'any item' */
                        if (liIndex > 0 && $(this).is(":checked")) {
                            var anyCheck = $('ul#arena li').first().find('input.checkbox-default');
                            anyCheck.prop("checked", false);
                        }
                        else if (liIndex === 0 && $(this).is(":checked")) {
                            $('ul#arena li').each(function () {
                                $(this).find('input.checkbox-default').prop("checked", false);
                            });
                            $('ul#arena li:first-child').find('input.checkbox-default').prop("checked", true);
                        }
                        /**/
                        if ($(this).is(":checked")) {
                            var seletedItem = '';
                            /* Collect all selected filter item */
                            $('ul#arena li').each(function (index, element) {
                                var input = $(this).find('input.checkbox-default');
                                if ($(this).index() !== liIndex && input.is(":checked")) {
                                    seletedItem += (seletedItem.length > 0 ? ',' : "" ) + input.val();
                                }
                            });
                            seletedItem += (seletedItem.length > 0 ? ',' : "" ) + $(this).val();
                            _GET.arena = seletedItem;
                        }
                        else {
                            var _get = removeValueOnArray(_GET.arena.split(','), $(this).val());
                            _GET.arena = _get.join(',');

                            if (_get.length === 0) {
                                $('ul#arena li').find('input.checkbox-default').first().prop("checked", true);
                            }
                        }
                        _updateFilter = true;
                        updateUrl();
                        updateResult();

                    });
                });
            },
            error: function (result) {/**/
            }
        });
    }
}

function loadProvider() {
    if ($('#provider').length > 0) {
        $.ajax({
            type: "GET",
            url: "/filter/provider",
            data: window.location.search.substr(1, window.location.search.length),
            success: function (data) {
                var len = data.length;
                $('ul#provider').html("").append('<li><input name="checkresult" value="any" checked="" class="checkbox-default" type="checkbox"><lable><span>Any</span></lable></li>');

                var total = 0;
                for (var i = 0; i < len; i++) {
                    if (data[i]['counter'] === 0) continue;
                    $('#provider').append('<li><input type="checkbox" name="checkresult" value="' + data[i]['name'] + '" class="checkbox-default"><lable><span> ' + data[i]['name'] + ' (' + data[i]['counter'] + ')' + '</span></lable></li>')
                    total += parseInt(data[i]['counter']);
                }
                $('ul#provider li').first().find('lable span').text('Any (' + total + ')');


                /* pre-select filter items */
                if (_GET['provider'] !== undefined) {
                    var seletedprovider = _GET.provider.split(',');
                    if (seletedprovider.length > 0 && _GET.provider > 0) {
                        $('ul#provider li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedprovider.forEach(function (value) {
                        $('ul#provider li').each(function (index, element) {
                            var input = $(this).find('input.checkbox-default');
                            if (input.val() === value) {
                                input.prop("checked", true);
                            }
                        });
                    });
                }

                /* Update result when any item selected */
                $('ul#provider li').each(function (index, element) {

                    var liIndex = index;
                    $(element).find('input.checkbox-default').click(function () {
                        /* select others except 'any item' */
                        if (liIndex > 0 && $(this).is(":checked")) {
                            var anyCheck = $('ul#provider li').first().find('input.checkbox-default');
                            anyCheck.prop("checked", false);
                        }
                        else if (liIndex === 0 && $(this).is(":checked")) {
                            $('ul#provider li').each(function () {
                                $(this).find('input.checkbox-default').prop("checked", false);
                            });
                            $('ul#provider li:first-child').find('input.checkbox-default').prop("checked", true);
                        }
                        /**/

                        if ($(this).is(":checked")) {
                            var seletedItem = '';
                            /* Collect all selected filter item */
                            $('ul#provider li').each(function (index, element) {
                                var input = $(this).find('input.checkbox-default');
                                if ($(this).index() !== liIndex && input.is(":checked")) {
                                    seletedItem += (seletedItem.length > 0 ? ',' : "" ) + input.val();
                                }
                            });
                            seletedItem += (seletedItem.length > 0 ? ',' : "" ) + $(this).val();
                            _GET.provider = seletedItem;
                        }
                        else {
                            var _get = removeValueOnArray(_GET.provider.split(','), $(this).val());
                            _GET.provider = _get.join(',');

                            if (_get.length === 0) {
                                $('ul#provider li').find('input.checkbox-default').first().prop("checked", true);
                            }
                        }
                        _updateFilter = true;
                        updateUrl();
                        updateResult();

                    });
                });
            },
            error: function (result) {/**/
            }
        });
    }
}

function loadInstructor() {
    if ($('#instructor').length > 0) {
        $.ajax({
            type: "GET",
            url: "/filter/instructor",
            data: window.location.search.substr(1, window.location.search.length),
            success: function (data) {
                var len = data.length;
                $('ul#instructor').html("").append('<li><input name="checkresult" value="any" checked="" class="checkbox-default" type="checkbox"><lable><span>Any</span></lable></li>');

                var total = 0;
                for (var i = 0; i < len; i++) {
                    if (data[i]['counter'] === 0) continue;
                    $('#instructor').append('<li><input type="checkbox" name="checkresult" value="' + data[i]['first_name'] + " " + data[i]['last_name'] + '" class="checkbox-default"><lable><span>' + data[i]['first_name'] + " " + data[i]['middle_name'] + " " + data[i]['last_name'] + ' (' + data[i]['counter'] + ')' + '</span></lable></li>')
                    total += parseInt(data[i]['counter']);
                }
                $('ul#instructor li').first().find('lable span').text('Any (' + total + ')');

                /* pre-select filter items */
                if (_GET['instructor'] !== undefined) {
                    var seletedinstructor = _GET.instructor.split(',');
                    if (seletedinstructor.length > 0 && _GET.instructor.length > 0) {
                        $('ul#instructor li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedinstructor.forEach(function (value) {
                        $('ul#instructor li').each(function (index, element) {
                            var input = $(this).find('input.checkbox-default');
                            if (input.val() === value) {
                                input.prop("checked", true);
                            }
                        });
                    });
                }

                /* Update result when any item selected */
                $('ul#instructor li').each(function (index, element) {

                    var liIndex = index;
                    $(element).find('input.checkbox-default').click(function () {
                        /* select others except 'any item' */
                        if (liIndex > 0 && $(this).is(":checked")) {
                            var anyCheck = $('ul#instructor li').first().find('input.checkbox-default');
                            anyCheck.prop("checked", false);
                        }
                        else if (liIndex === 0 && $(this).is(":checked")) {
                            $('ul#instructor li').each(function () {
                                $(this).find('input.checkbox-default').prop("checked", false);
                            });
                            $('ul#instructor li:first-child').find('input.checkbox-default').prop("checked", true);
                        }
                        /**/
                        if ($(this).is(":checked")) {
                            var seletedItem = '';
                            /* Collect all selected filter item */
                            $('ul#instructor li').each(function (index, element) {
                                var input = $(this).find('input.checkbox-default');
                                if ($(this).index() !== liIndex && input.is(":checked")) {
                                    seletedItem += (seletedItem.length > 0 ? ',' : "" ) + input.val();
                                }
                            });
                            seletedItem += (seletedItem.length > 0 ? ',' : "" ) + $(this).val();
                            _GET.instructor = seletedItem;
                        }
                        else {
                            var _get = removeValueOnArray(_GET.instructor.split(','), $(this).val());
                            _GET.instructor = _get.join(',');

                            if (_get.length === 0) {
                                $('ul#instructor li').find('input.checkbox-default').first().prop("checked", true);
                            }
                        }
                        _updateFilter = true;
                        updateUrl();
                        updateResult();

                    });
                });
            },
            error: function (result) {/**/
            }
        });
    }
}

function loadProgram() {
    if ($('#program').length > 0) {
        $.ajax({
            type: "GET",
            url: "/filter/program",
            data: window.location.search.substr(1, window.location.search.length),
            success: function (data) {
                var len = data.length;
                $('ul#program').html("").append('<li><input name="checkresult" value="any" checked="" class="checkbox-default" type="checkbox"><lable><span>Any</span></lable></li>');

                var total = 0;
                for (var i = 0; i < len; i++) {
                    if (data[i]['counter'] === 0) continue;
                    $('#program').append('<li><input type="checkbox" name="checkresult" value="' + data[i]['name'] + '" class="checkbox-default"><lable><span>' + data[i]['name'] + ' (' + data[i]['counter'] + ')' + '</span></lable></li>')
                    total += parseInt(data[i]['counter']);
                }
                $('ul#program li').first().find('lable span').text('Any (' + total + ')');


                /* pre-select filter items */
                if (_GET['program'] !== undefined) {
                    var seletedprogram = _GET.program.split(',');
                    if (seletedprogram.length > 0 && _GET.program.length > 0) {
                        $('ul#program li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedprogram.forEach(function (value) {
                        $('ul#program li').each(function (index, element) {
                            var input = $(this).find('input.checkbox-default');
                            if (input.val() === value) {
                                input.prop("checked", true);
                            }
                        });
                    });
                }

                /* Update result when any item selected */
                $('ul#program li').each(function (index, element) {

                    var liIndex = index;
                    $(element).find('input.checkbox-default').click(function () {
                        /* select others except 'any item' */
                        if (liIndex > 0 && $(this).is(":checked")) {
                            var anyCheck = $('ul#program li').first().find('input.checkbox-default');
                            anyCheck.prop("checked", false);
                        }
                        else if (liIndex === 0 && $(this).is(":checked")) {
                            $('ul#program li').each(function () {
                                $(this).find('input.checkbox-default').prop("checked", false);
                            });
                            $('ul#program li:first-child').find('input.checkbox-default').prop("checked", true);
                        }
                        /**/
                        if ($(this).is(":checked")) {
                            var seletedItem = '';
                            /* Collect all selected filter item */
                            $('ul#program li').each(function (index, element) {
                                var input = $(this).find('input.checkbox-default');
                                if ($(this).index() !== liIndex && input.is(":checked")) {
                                    seletedItem += (seletedItem.length > 0 ? ',' : "" ) + input.val();
                                }
                            });
                            seletedItem += (seletedItem.length > 0 ? ',' : "" ) + $(this).val();
                            _GET.program = seletedItem;
                        }
                        else {
                            var _get = removeValueOnArray(_GET.program.split(','), $(this).val());
                            _GET.program = _get.join(',');

                            if (_get.length === 0) {
                                $('ul#program li').find('input.checkbox-default').first().prop("checked", true);
                            }
                        }
                        _updateFilter = true;
                        updateUrl();
                        updateResult();

                    });
                });
            },
            error: function (result) {/**/
            }
        });
    }
}

function loadDay() {
    if ($('#day').length > 0) {
        $.ajax({
            type: "GET",
            url: "/filter/day",
            data: window.location.search.substr(1, window.location.search.length),
            success: function (data) {
                var len = data.length;
                $('ul#day').html("").append('<li><input name="checkresult" value="any" checked="" class="checkbox-default" type="checkbox"><lable><span>Any</span></lable></li>');

                var total = 0;
                for (var i = 0; i < len; i++) {
                    if (data[i]['counter'] === 0) continue;
                    $('#day').append('<li><input type="checkbox" name="checkresult" value="' + data[i]['name'] + '" class="checkbox-default"><lable><span>' + data[i]['name'] + ' (' + data[i]['counter'] + ')' + '</span></lable></li>')
                    total += parseInt(data[i]['counter']);
                }
                $('ul#day li').first().find('lable span').text('Any (' + total + ')');


                /* pre-select filter items */
                if (_GET['day'] !== undefined) {
                    var seletedDay = _GET.day.split(',');
                    if (seletedDay.length > 0 && _GET.day.length > 0) {
                        $('ul#day li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedDay.forEach(function (value) {
                        $('ul#day li').each(function (index, element) {
                            var input = $(this).find('input.checkbox-default');
                            if (input.val() === value) {
                                input.prop("checked", true);
                            }
                        });
                    });
                }

                /* Update result when any item selected */
                $('ul#day li').each(function (index, element) {

                    var liIndex = index;
                    $(element).find('input.checkbox-default').click(function () {
                        /* select others except 'any item' */
                        if (liIndex > 0 && $(this).is(":checked")) {
                            var anyCheck = $('ul#day li').first().find('input.checkbox-default');
                            anyCheck.prop("checked", false);
                        }
                        else if (liIndex === 0 && $(this).is(":checked")) {
                            $('ul#day li').each(function () {
                                $(this).find('input.checkbox-default').prop("checked", false);
                            });
                            $('ul#day li:first-child').find('input.checkbox-default').prop("checked", true);
                        }
                        /**/
                        if ($(this).is(":checked")) {
                            var seletedItem = '';
                            /* Collect all selected filter item */
                            $('ul#day li').each(function (index, element) {
                                var input = $(this).find('input.checkbox-default');
                                if ($(this).index() !== liIndex && input.is(":checked")) {
                                    seletedItem += (seletedItem.length > 0 ? ',' : "" ) + input.val();
                                }
                            });
                            seletedItem += (seletedItem.length > 0 ? ',' : "" ) + $(this).val();
                            _GET.day = seletedItem;
                        }
                        else {
                            var _get = removeValueOnArray(_GET.day.split(','), $(this).val());
                            _GET.day = _get.join(',');

                            if (_get.length === 0) {
                                $('ul#day li').find('input.checkbox-default').first().prop("checked", true);
                            }
                        }
                        _updateFilter = true;
                        updateUrl();
                        updateResult();

                    });
                });
            },
            error: function (result) {/**/
            }
        });
    }
}

function loadGender() {
    if ($('#gender').length > 0) {

        $.ajax({
            type: "GET",
            url: "/filter/gender",
            data: window.location.search.substr(1, window.location.search.length),
            success: function (data) {
                var len = data.length;
                $('ul#gender').html("").append('<li><input name="checkresult" value="any" checked="" class="checkbox-default" type="checkbox"><lable><span>Any</span></lable></li>');

                var total = 0;
                for (var i = 0; i < len; i++) {
                    if (data[i]['counter'] === 0) continue;
                    $('#gender').append('<li><input type="checkbox" name="checkresult" value="' + data[i]['name'] + '" class="checkbox-default"><lable><span>' + data[i]['name'] + ' (' + data[i]['counter'] + ')' + '</span></lable></li>')
                    total += parseInt(data[i]['counter']);
                }
                $('ul#gender li').first().find('lable span').text('Any (' + total + ')');

                /* pre-select filter items */
                if (_GET['gender'] !== undefined) {
                    var seletedgender = _GET.gender.split(',');
                    if (seletedgender.length > 0 && _GET.gender > 0) {
                        $('ul#gender li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedgender.forEach(function (value) {
                        $('ul#gender li').each(function (index, element) {
                            var input = $(this).find('input.checkbox-default');
                            if (input.val() === value) {
                                input.prop("checked", true);
                            }
                        });
                    });
                }

                /* Update result when any item selected */
                $('ul#gender li').each(function (index, element) {

                    var liIndex = index;
                    $(element).find('input.checkbox-default').click(function () {
                        /* select others except 'any item' */
                        if (liIndex > 0 && $(this).is(":checked")) {
                            var anyCheck = $('ul#gender li').first().find('input.checkbox-default');
                            anyCheck.prop("checked", false);
                        }
                        else if (liIndex === 0 && $(this).is(":checked")) {
                            $('ul#gender li').each(function () {
                                $(this).find('input.checkbox-default').prop("checked", false);
                            });
                            $('ul#gender li:first-child').find('input.checkbox-default').prop("checked", true);
                        }
                        /**/
                        if ($(this).is(":checked")) {
                            var seletedItem = '';
                            /* Collect all selected filter item */
                            $('ul#gender li').each(function (index, element) {
                                var input = $(this).find('input.checkbox-default');
                                if ($(this).index() !== liIndex && input.is(":checked")) {
                                    seletedItem += (seletedItem.length > 0 ? ',' : "" ) + input.val();
                                }
                            });
                            seletedItem += (seletedItem.length > 0 ? ',' : "" ) + $(this).val();
                            _GET.gender = seletedItem;
                        }
                        else {
                            var _get = removeValueOnArray(_GET.gender.split(','), $(this).val());
                            _GET.gender = _get.join(',');

                            if (_get.length === 0) {
                                $('ul#gender li').find('input.checkbox-default').first().prop("checked", true);
                            }
                        }
                        _updateFilter = true;
                        updateUrl();
                        updateResult();

                    });
                });
            },
            error: function (result) {/**/
            }
        });
    }
}

function loadGeneration() {
    if ($('#generation').length > 0) {

        $.ajax({
            type: "GET",
            url: "/filter/generation",
            data: window.location.search.substr(1, window.location.search.length),
            success: function (data) {
                var len = data.length;
                $('ul#generation').html("").append('<li><input name="checkresult" value="any" checked="" class="checkbox-default" type="checkbox"><lable><span>Any</span></lable></li>');

                var total = 0;
                for (var i = 0; i < len; i++) {
                    if (data[i]['counter'] === 0) continue;
                    $('#generation').append('<li><input type="checkbox" name="checkresult" value="' + data[i]['name'] + '" class="checkbox-default"><lable><span>' + data[i]['name'] + ' (' + data[i]['counter'] + ')' + '</span></lable></li>')
                    total += parseInt(data[i]['counter']);
                }
                $('ul#generation li').first().find('lable span').text('Any (' + total + ')');

                /* pre-select filter items */
                if (_GET['generation'] !== undefined) {
                    var seletedgeneration = _GET.generation.split(',');
                    if (seletedgeneration.length > 0 && _GET.generation.length > 0) {
                        $('ul#generation li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedgeneration.forEach(function (value) {
                        $('ul#generation li').each(function (index, element) {
                            var input = $(this).find('input.checkbox-default');
                            if (input.val() === value) {
                                input.prop("checked", true);
                            }
                        });
                    });
                }

                /* Update result when any item selected */
                $('ul#generation li').each(function (index, element) {

                    var liIndex = index;
                    $(element).find('input.checkbox-default').click(function () {
                        /* select others except 'any item' */
                        if (liIndex > 0 && $(this).is(":checked")) {
                            var anyCheck = $('ul#generation li').first().find('input.checkbox-default');
                            anyCheck.prop("checked", false);
                        }
                        else if (liIndex === 0 && $(this).is(":checked")) {
                            $('ul#generation li').each(function () {
                                $(this).find('input.checkbox-default').prop("checked", false);
                            });
                            $('ul#generation li:first-child').find('input.checkbox-default').prop("checked", true);
                        }
                        /**/
                        if ($(this).is(":checked")) {
                            var seletedItem = '';
                            /* Collect all selected filter item */
                            $('ul#generation li').each(function (index, element) {
                                var input = $(this).find('input.checkbox-default');
                                if ($(this).index() !== liIndex && input.is(":checked")) {
                                    seletedItem += (seletedItem.length > 0 ? ',' : "" ) + input.val();
                                }
                            });
                            seletedItem += (seletedItem.length > 0 ? ',' : "" ) + $(this).val();
                            _GET.generation = seletedItem;
                        }
                        else {
                            var _get = removeValueOnArray(_GET.generation.split(','), $(this).val());
                            _GET.generation = _get.join(',');

                            if (_get.length === 0) {
                                $('ul#generation li').find('input.checkbox-default').first().prop("checked", true);
                            }
                        }
                        _updateFilter = true;
                        updateUrl();
                        updateResult();

                    });
                });
            },
            error: function (result) {/**/
            }
        });
    }
}

function loadAgeFrom() {
    if ($('#ageFrom').length > 0) {
        $.ajax({
            type: "GET",
            url: "/filter/ageFrom",
            data: window.location.search.substr(1, window.location.search.length),
            success: function (data) {
                var len = data.length;
                $('#ageFrom').html("").append("<option></option>");
                $('#ageFrom').prop("selectedIndex", -1);
                for (var i = 0; i < len; i++) {
                    $('#ageFrom').append('<option value="' + data[i]['age_range_top'] + '">' + data[i]['age_range_top'] + '</option>');
                }

                /* pre-select filter items */
                if (_GET['ageFrom'] !== undefined) {
                    var seletedageFrom = _GET.ageFrom.split(',');
                    if (seletedageFrom.length > 0) {
                        $('ul#ageFrom li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedageFrom.forEach(function (value) {
                        $('select#ageFrom option').each(function (index, element) {
                            var input = $(this);
                            if (input.val() === value) {
                                input.attr("selected", "true");
                                return;
                            }
                        });
                    });
                }

                /* Update result when any item selected */
                $('select#ageFrom').change(function () {
                    if ($('#ageFrom').val() != null) {
                        $("input#age").prop("checked", false);
                        _GET.ageFrom = $('#ageFrom').val()
                    }
                    else {
                        var _get = removeValueOnArray(_GET.ageFrom.split(','), $(this).val());
                        _GET.ageFrom = _get.join(',');

                        if (_get.length === 0) {
                            $('ul#ageFrom li').find('input.checkbox-default').first().prop("checked", true);
                        }
                    }
                    _updateFilter = true;
                    updateUrl();
                    updateResult();

                });
            },
            error: function (result) {/**/
            }
        });
    }
}

function loadAgeTo() {
    if ($('#ageTo').length > 0) {
        $.ajax({
            type: "GET",
            url: "/filter/ageTo",
            data: window.location.search.substr(1, window.location.search.length),
            success: function (data) {
                var len = data.length;
                $('#ageTo').html("").append("<option></option>")
                $('#ageTo').prop("selectedIndex", -1);

                for (var i = 0; i < len; i++) {
                    $('#ageTo').append('<option value="' + data[i]['age_range_bottom'] + '">' + data[i]['age_range_bottom'] + '</option>');
                }

                $('input#age').change(function () {
                    if ($(this).is(":checked")) {
                        $('#ageTo').prop("selectedIndex", -1);
                        $('#ageFrom').prop("selectedIndex", -1);
                    }
                });

                /* pre-select filter items */
                if (_GET['ageTo'] !== undefined) {
                    var seletedageTo = _GET.ageTo.split(',');
                    if (seletedageTo.length > 0) {
                        $('ul#ageTo li').first().find('input.checkbox-default').prop("checked", false);
                    }
                    seletedageTo.forEach(function (value) {
                        $('select#ageTo option').each(function (index, element) {
                            var input = $(this);
                            if (input.val() === value) {
                                input.attr("selected", "true");
                                return;
                            }
                        });
                    });
                }

                /* Update result when any item selected */
                $('select#ageTo').change(function () {
                    if ($('#ageTo').val() != null) {
                        $("input#age").prop("checked", false);
                        _GET.ageTo = $('#ageTo').val()
                    }
                    else {
                        var _get = removeValueOnArray(_GET.ageTo.split(','), $(this).val());
                        _GET.ageTo = _get.join(',');

                        if (_get.length === 0) {
                            $('ul#ageTo li').find('input.checkbox-default').first().prop("checked", true);
                        }
                    }
                    _updateFilter = true;
                    updateUrl();
                    updateResult();

                });
            },
            error: function (result) {/**/
            }
        });
    }
}

function loadTimeStart() {
    if ($('#timeStartHour').length > 0 && $('#timeStartMinute').length > 0) {

        /* pre-select filter items */
        if (_GET['timeStart'] !== undefined) {
            var seletedtimeStart = _GET.timeStart.split(',');

            seletedtimeStart.forEach(function (value) {
                var timeStart = value.split(':');
                var hour = timeStart[0];
                var minute = timeStart[1];
                $('select#timeStartHour option').each(function (index, element) {
                    var input = $(this);
                    if (input.val() === hour) {
                        input.attr("selected", "true");
                        $('input#timeStar-any').prop("checked", false);
                        return;
                    }
                });

                $('select#timeStartMinute option').each(function (index, element) {
                    var input = $(this);
                    if (input.val() === minute) {
                        input.attr("selected", "true");
                        $('input#timeStar-any').prop("checked", false);
                        return;
                    }
                });
            });
        }

        $('input#timeStar-any').change(function () {
            if ($(this).is(":checked")) {
                $('#timeStartHour').prop("selectedIndex", -1);
                $('#timeStartMinute').prop("selectedIndex", -1);

                _GET.timeStart = '';
                _updateFilter = true;
                updateUrl();
                updateResult()

            }
        });

        /* Update result when any item selected */
        $('select#timeStartHour').change(function () {
            if (($("#timeStartHour").val() !== '' && $("#timeStartHour").val() !== null) && ($("#timeStartMinute").val() !== '' && $("#timeStartMinute").val() !== null)) {
                _GET.timeStart = $("#timeStartHour").val() + ":" + ($("#timeStartMinute").val() !== '' && $("#timeStartMinute").val() !== null ? $("#timeStartMinute").val() : '00');
                $('input#timeStar-any').prop("checked", false);
            }
            _updateFilter = true;
            updateUrl();
            updateResult();


        });

        $('select#timeStartMinute').change(function () {
            if (($("#timeStartHour").val() !== '' && $("#timeStartHour").val() !== null) && ($("#timeStartMinute").val() !== '' && $("#timeStartMinute").val() !== null)) {
                _GET.timeStart = ($("#timeStartHour").val() !== '' && $("#timeStartHour").val() !== null ? $("#timeStartHour").val() : '00') + ":" + $("#timeStartMinute").val();
                $('input#timeStar-any').prop("checked", false);
            }
            _updateFilter = true;
            updateUrl();
            updateResult();


        });
    }
}

function loadTimeEnd() {
    if ($('#timeEndHour').length > 0 && $('#timeEndMinute').length > 0) {
        /* pre-select filter items */
        if (_GET['timeEnd'] !== undefined) {
            var seletedtimeEnd = _GET.timeEnd.split(',');

            seletedtimeEnd.forEach(function (value) {
                var timeEnd = value.split(':');
                var hour = timeEnd[0];
                var minute = timeEnd[1];
                $('select#timeEndHour option').each(function (index, element) {
                    var input = $(this);
                    if (input.val() === hour) {
                        input.attr("selected", "true");
                        $('input#timeEnd-any').prop("checked", false);
                        return;
                    }
                });

                $('select#timeEndMinute option').each(function (index, element) {
                    var input = $(this);
                    if (input.val() === minute) {
                        input.attr("selected", "true");
                        $('input#timeEnd-any').prop("checked", false);
                        return;
                    }
                });
            });
        }

        $('input#timeEnd-any').change(function () {
            if ($(this).is(":checked")) {
                $('#timeEndHour').prop("selectedIndex", -1);
                $('#timeEndMinute').prop("selectedIndex", -1);

                _GET.timeEnd = '';
                _updateFilter = true;
                updateUrl();
                updateResult();

            }
        });

        /* Update result when any item selected */
        $('select#timeEndHour').change(function () {
            if (($("#timeEndHour").val() !== '' && $("#timeEndHour").val() !== null) && ($("#timeEndMinute").val() !== '' && $("#timeEndMinute").val() !== null)) {
                _GET.timeEnd = $("#timeEndHour").val() + ":" + ($("#timeEndMinute").val() !== '' && $("#timeEndMinute").val() !== null ? $("#timeEndMinute").val() : '00');
                $('input#timeEnd-any').prop("checked", false);
            }
            _updateFilter = true;
            updateUrl();
            updateResult();

        });

        $('select#timeEndMinute').change(function () {
            //console.log($("#timeEndHour").val() !== '' && $("#timeEndMinute").val() !== '');
            if (($("#timeEndHour").val() !== '' && $("#timeEndHour").val() !== null) && ($("#timeEndMinute").val() !== '' && $("#timeEndMinute").val() !== null)) {
                _GET.timeEnd = ($("#timeEndMinute").val() !== '' && $("#timeEndMinute").val() !== null ? $("#timeEndHour").val() : '00') + ":" + $("#timeEndMinute").val();
                $('input#timeEnd-any').prop("checked", false);
            }
            _updateFilter = true;
            updateUrl();
            updateResult();

        });

    }
}

function loadKeyword() {
    if ($('#searchKeywordBy').length > 0) {
        $('#searchKeywordBy').prop("selectedIndex", -1);

        /* pre-select filter items */
        if (_GET['keyword'] !== undefined) {
            $('#search-keyword').val(_GET['keyword'].trim());
            $('#keyword').val(_GET['keyword'].trim());
        }
        if (_GET['searchKeywordBy'] !== undefined && _GET.keyword !== undefined) {

            var seletedkeyword = _GET.keyword.split(',');

            seletedkeyword.forEach(function (value) {
                $('select#searchKeywordBy option').each(function (index, element) {
                    var input = $(this);
                    if (input.val() === value) {
                        input.attr("selected", "true");
                        return;
                    }
                });
            });
        }

        /* Update result when any item selected */
        $('#searchKeywordBy').change(function () {
            if ($('#keyword').val().trim().length > 0) {
                _GET.searchKeywordBy = $('#searchKeywordBy').val().trim();
                _GET.keyword = $('#keyword').val().trim();

                $('#search-keyword').val(_GET['keyword'].trim());
                $('#keyword').val(_GET['keyword'].trim());
            }
            else {
                _GET.searchKeywordBy = '';
                _GET.keyword = '';
            }
            _updateFilter = true;
            updateUrl();
            updateResult();

        });

        /* enter on keyword input */
        $("#keyword").keydown(function (e) {
            if (e.keyCode == 13) {
                $('#keyword').change();
            }
        });

        $('#keyword').change(function () {
            if ($('#keyword').val().trim().length > 0) {
                _GET.searchKeywordBy = $('#searchKeywordBy').val().trim();
                _GET.keyword = $('#keyword').val().trim();
            }
            else {
                _GET.searchKeywordBy = '';
                _GET.keyword = '';
            }
            _updateFilter = true;
            updateUrl();
            updateResult();

        });
    }
}

function loadSearchKeyword() {

    /* enter on keyword input */
    $("#search-keyword").keydown(function (e) {
        if (e.keyCode == 13) {
            $('#search').click();
        }
    });

    /* pre-select filter items */
    if (_GET['keyword'] !== undefined) {
        $('#search-keyword').val(_GET['keyword'].trim());
        $('#keyword').val(_GET['keyword'].trim());
    }


    $('#search').click(function (e) {
        e.preventDefault();
        if ($('#search-keyword').val().trim().length > 0) {
            _GET.searchKeywordBy = 'contain';
            _GET.keyword = $('#search-keyword').val().trim();

            $('#search-keyword').val(_GET['keyword'].trim());
            $('#keyword').val(_GET['keyword'].trim());
        }
        else {
            _GET.searchKeywordBy = '';
            _GET.keyword = '';
        }
        _updateFilter = true;
        loadHints();
        updateUrl();
        updateResult();

    });

}

function CheckNumPage() {
    $('a.left').css('pointer-events', 'inherit');
    if ($("#currentPage").val() == 1) {
        $('a.left').css('pointer-events', 'none');
        $('a.left').parent().addClass('active');
        // $('.pagination-group').css('display','none');
    }
    $('a.right').css('pointer-events', 'inherit');
    if ($("#totalPage").val() == $("#currentPage").val()) {
        $('a.right').css('pointer-events', 'none');
        $('a.right').parent().addClass('active');
    }

    /*Hide pagination when 1 page*/
    /*Check total page has exist*/
    if ($('#totalPage').length > 0) {
        /*Check page number*/
        if ($('#totalPage').val() == 1) {
            /*hide pagination*/
            $('.pagination-group').css('display', 'none');
        }
    }
}

function clearSearchBox() {
    $(".clear-btn").click(function () {
        $('#search-keyword').val('');
        $('.deleteSearchBox').removeClass('clear-btn');
        $(this).css('display', 'none');
    });
    $('#search-keyword').on('input', function () {
        if ($('#search-keyword').val() !== null && $('#search-keyword').val().trim().length > 0) {
            // $('.deleteSearchBox').addClass('clear-btn');
            $(".clear-btn").css('display', 'inline-block');
            setupAutoComplete();
        }
        if ($('#search-keyword').val().length === 0) {
            // $('.deleteSearchBox').removeClass('clear-btn');
            $(".clear-btn").css('display', 'none');
        }
    });
}

function setupAutoComplete() {

    /** keypress, keyup, keydown**/
    $('#search-keyword').on('input', function (e) {
        var displayData = '';
        if ($(this).val().length === 0) {
            $("#suggesstion-box").hide();
            return;
        }

        for (var i = 0; i < fullSuggestion.length; i++) {
            var str = fullSuggestion[i];
            //console.log(str.indexOf($(this).val()) + '|' +  fullSuggestion[i]);
            if (str.indexOf($(this).val()) >= 0) {
                //it contains searchterm do something with it.
                displayData += '<li onClick="selectCountry(\'' + fullSuggestion[i] + '\')">' + fullSuggestion[i] + '</li>';
            }
            $("#suggesstion-box").show();
            $("#suggesstion-box").html('<ul id="data-list">' + displayData + '</ul>');
            $("#search-keyword").css("background", "#FFF");
        }
        ResizeSuggestSearch();
    });

    /* Hide suggest when move out of control*/
    $('#search-keyword').on('blur', function () {
        setTimeout(function () {
            $("#suggesstion-box").hide();
        }, 500);
    });

}

//To select country name
function selectCountry(val) {
    $("#search-keyword").val(val);
    $("#suggesstion-box").hide();
}

function ScorllBallItem() {
    // .sider-bar ul li.parent-sider-bar:hover .child-sider-bar li
    $('.parent-sider-bar').hover(function () {
        if ($(this).children('.child-sider-bar').children().length > 9) {
            $(this).children('.child-sider-bar').addClass('scroll-bar-item');
        }
    });
}

function ResizeSuggestSearch() {
    var widthSearch = $('#search-keyword').width() + 18;
    $('#data-list').css('width', widthSearch);

    $(window).resize(function () {
        var widthSearch = $('#search-keyword').width() + 18;
        $('#data-list').css('width', widthSearch);
    });
}

function loadingOverlay() {

    /* overlay spinner when on ready page load */
    if (flagLoad === false) {
        if ($('.loading').length > 0) {
            $('#loading').removeClass('loading');
            flagLoad = true;
        }
    }
    else {

        if ($('.loading').length > 0) {
            $('#loading').addClass('loading');
        }

        /* overlay spinner when update filter */
        if (_updateFilter === true) {
            $('#loading').addClass('loading');
        }
        else if (_updateFilter === false) {
            $('#loading').removeClass('loading');
        }
        /**/
    }
}