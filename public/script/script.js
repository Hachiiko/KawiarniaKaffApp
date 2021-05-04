var navbarText = document.getElementById('navbarText');
var carouselCaptions = document.getElementsByClassName('carousel-caption');

navbarText.addEventListener('show.bs.collapse', function () {
    for (var i = 0; i < carouselCaptions.length; i++) {
        carouselCaptions[i].style.visibility = 'hidden';
    }
});

navbarText.addEventListener('hidden.bs.collapse', function () {
    for (var i = 0; i < carouselCaptions.length; i++) {
        carouselCaptions[i].style.visibility = 'visible';
    }
});