// Main ES6 file (strict mode - default)

// Import vendor files
import 'jquery';
import 'materialize-css';
import 'materialize-css/sass/materialize.scss';

// Import custom files
import '../scss/styles.scss';
//import Navigation from './index.nav';

// Class initialization
// let nav = new Navigation();

// JQuery $(document).ready function 
$(function() {
    
    // navigation.initScrollSpy();
    // navigation.initSlide();
    // navigation.initModal();
    // navigation.initLogoSlide();
    // navigation.initMainMenu();
    // navigation.initSelect();
   
    $('.dropdown-trigger').dropdown();
    console.log('Rendered');

});