$(document).ready(function()
{  
  $('.about').click(onClickAbout);
  $('.about_close_box').click(onClickAbout);

  function onClickAbout(e)
  {
    e.preventDefault();
    $('#about_box').fadeToggle();
  }

  if($('#warn'))
  {
    $('#warn').fadeOut(14400);
  }
});
