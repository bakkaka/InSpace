 $(document).ready(function(){
       $("#search").on('keyup', function(e) { // everytime keyup event
           $('#loader').show();
           e.preventDefault();
           var input = $(this).val();// We take the input value
           var $search = $('#search');

           $.ajax({
               type: "GET",
               url: "{{ path('cherche') }}",
               dataType: "json",
               data: $search.serialize(),
               cache: false,
               success: function(response) {
                   $('.card-deck').replaceWith(response);
                   $('#loader').hide();
                   console.log(response);
               },
               error: function(response) {
                   console.log(response);
               }
           });
       });
   });