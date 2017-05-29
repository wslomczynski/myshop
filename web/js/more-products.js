$(document).ready(function() {


  $('#singlebutton').on('click', function(event) {
    var latest_post_id = $('.row > :last-child').attr('data-post-id');

    jQuery.ajax({
      url: '',
      type: 'POST',
      data: {latest_post_id: latest_post_id },
      success: function(products){
        $('.row').append(products);

        console.log(noMoreProducts);


      }
    });


  });
});


//$('.row:last-child').attr('data-post-id');

//'<div class="col-md-3"><img src="{{ asset("img/macbook 179x91.jpg") }}" alt=""></div>'
//  $('.row').append('<div class="col-md-3"><img src="{{ asset("img/macbook 179x91.jpg") }}" alt=""></div>');
