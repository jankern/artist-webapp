// Main ES6 file (strict mode - default)

// Import vendor files
import 'jquery';
import 'materialize-css';
import 'materialize-css/sass/materialize.scss';

// Import custom files
import '../scss/styles.scss';

// JQuery $(document).ready function 
$(function() {

    let width = $(window).width();

    $(window).resize(() => {
        width = $(window).width();
    });
    
    // transition scroll effect for various elements
    let transitionLogoEl = $('.logo-transition');
    let transitionAside = $('aside');
    let transitionHeader = $('.header-background');

    $(window).scroll(() => {
        
        if(width > 992){
            if($(this).scrollTop() >= 5){
                transitionLogoEl.stop().animate({width:'40px', height:'40px', marginRight: '-450px'});
                transitionAside.stop().animate({marginRight: '-540px'});
                transitionHeader.stop().animate({opacity:0.5});
            }else if ($(this).scrollTop() <= 40){
                transitionLogoEl.stop().animate({width:'84px', height:'84px', marginRight: '-480px'});
                transitionAside.stop().animate({marginRight: '-480px'});
                transitionHeader.stop().animate({opacity:0});
            }
        }
    });

    // init scrollspy
    $('.scrollspy').scrollSpy();
    $('.parallax').parallax();

});