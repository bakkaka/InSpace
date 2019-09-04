/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');
import $ from 'jquery';
import 'bootstrap'; // adds functions to jQuery
require('select2')

$('select').select2()
console.log('Hello Abdeaziz Mostafaoui Encore! Edit me in assets/js/app.js!!!!!!!!!!');
import getNiceMessage from './components/get_nice_message';
console.log(getNiceMessage(5));

 
                $('.dropdown-toggle').dropdown();
                $('.custom-file-input').on('change', function(event) {
                    var inputFile = event.currentTarget;
                    $(inputFile).parent()
                        .find('.custom-file-label')
                        .html(inputFile.files[0].name);
                });
				//article_show.js
				$('.js-like-article').on('click', function(e) {
        e.preventDefault();

        var $link = $(e.currentTarget);
        $link.toggleClass('fa-heart-o').toggleClass('fa-heart');
        $.ajax({
            method: 'POST',
            url: $link.attr('href')
        }).done(function(data) {
            $('.js-like-article-count').html(data.hearts);
        })
    });
            