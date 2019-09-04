import '../css/article_show.scss';
import $ from 'jquery';
//import 'bootstrap';

$(document).ready(function() {
    $('.js-visit-article').tooltip();
    $('.js-visit-article').on('click', function(e) {
        //e.preventDefault();
        var $link = $(e.currentTarget);
        //$link.toggleClass('fa-heart-o').toggleClass('fa-heart');
        $.ajax({
            method: 'POST',
            url: $link.attr('href')
        }).done(function(data) {
            $('.js-visit-article-count').html(data.visits);
        })
    });
});