/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/bootstrap-icons.css';
// start the Stimulus application
import './stimulus';
//load bootstrap
require('bootstrap');
// add global $ for jquery
import $ from 'jquery';
//load js
require('./js/jquery.sticky.js');
require('./js/click-scroll.js');
require('./js/counter.js');
require('./js/custom.js');

