jQuery(function($){
  $('#loadmore').click(function(){
      $(this).text('Loading...'); 

      var data = {
          'action': 'loadmore',
          'query': posts_vars,
          'page' : current_page
      };
      
      $.ajax({
          url:ajaxurl,
          data:data,
          type:'POST',
          success:function(data){
              if(data) { 
                  $('#loadmore').text('Load More').before(data); 
                  current_page++; 
                  if (current_page == max_pages) $("#loadmore").remove();
              } else {
                  $('#loadmore').remove();
              }
          }
      });
  });
});