jQuery(document).ready(function ($) {

    //* Get the floorplans parameter in case we need it
    var urlParams = new URLSearchParams(window.location.search);
    var currentOnLoad = urlParams.get('floorplans');
    console.log(currentOnLoad);

    if (currentOnLoad) {
        updatePlans(currentOnLoad);
        updateButton(currentOnLoad);
    }


    $('.filter-select').click(function (e) {
        e.preventDefault;
        var current = $(this).data('filter');
        console.log(current);
        updatePlans(current);
        updateButton(current);
    });

    function updatePlans(current) {
        $('.floorplan').hide();
        $('.' + current).show();

        if ('URLSearchParams' in window) {
            var searchParams = new URLSearchParams(window.location.search);
            searchParams.set("floorplans", current);
            window.history.pushState("", "", "?floorplans=" + current);
        }

    }

    //* Update the active class on the button
    function updateButton(current) {
        $('.filter-select').removeClass('active');
        $('[data-filter=' + current + ']').addClass('active');
    }

    //* Update the URL
    function UpdateQueryString(key, value, url) {
        if (!url) url = window.location.href;
        var re = new RegExp("([?&])" + key + "=.*?(&|#|$)(.*)", "gi"),
            hash;

        if (re.test(url)) {
            if (typeof value !== 'undefined' && value !== null) {
                return url.replace(re, '$1' + key + "=" + value + '$2$3');
            }
            else {
                hash = url.split('#');
                url = hash[0].replace(re, '$1$3').replace(/(&|\?)$/, '');
                if (typeof hash[1] !== 'undefined' && hash[1] !== null) {
                    url += '#' + hash[1];
                }
                return url;
            }
        }
        else {
            if (typeof value !== 'undefined' && value !== null) {
                var separator = url.indexOf('?') !== -1 ? '&' : '?';
                hash = url.split('#');
                url = hash[0] + separator + key + '=' + value;
                if (typeof hash[1] !== 'undefined' && hash[1] !== null) {
                    url += '#' + hash[1];
                }
                return url;
            }
            else {
                return url;
            }
        }
    }

});


// jQuery(document).ready(function ($) {

//     //* Initial load
//     var currentitems = '<?php echo $currentfloorplans; ?>';
//     if (currentitems)
//         updateFloorplans(currentitems);

//     //* Clicking a specific link
//     $('.floorplanlink').click(function () {
//         event.preventDefault();
//         var currentitems = $(this).attr('floorplans');

//         updateFloorplans(currentitems);
//     });

//     //* Clicking "view all"
//     $('.viewalllink').click(function () {
//         event.preventDefault();
//         resetFloorplans();
//     });

//     function updateFloorplans(currentitems) {

//         classofcurrentlink = '.floorplanlink.' + currentitems;

//         //* Buttons
//         $('.floorplanlink').addClass('inactive');
//         $(classofcurrentlink).removeClass('inactive');
//         $('.viewalllink').addClass('inactive');

//         //* Floorplans
//         $('.loop-layout-floorplans-detailed .floorplans').hide();
//         $('.loop-layout-floorplans-detailed .sizes-' + currentitems).show("medium");
//     }

//     function resetFloorplans() {

//         //* Buttons
//         $('.floorplanlink').addClass('inactive');
//         $('.viewalllink').removeClass('inactive');

//         //* Floorplans
//         $('.loop-layout-floorplans-detailed .floorplans').hide();
//         $('.loop-layout-floorplans-detailed .floorplans').show("medium");

//     }
// });