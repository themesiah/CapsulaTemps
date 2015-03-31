function getItemElement() {
  var elem = document.createElement('div');
  elem.className = 'item';
  var $capsulaName = $('#capsulaName').val();
  var $name = $('#name').val();
  var $lugar = $('#lugar').val();
  
  $('#capsulaName').val("");
  $('#name').val("");
  $('#lugar').val("");
  
  elem.innerHTML = "<h1>" + $capsulaName + "</h1><p><img src='images/time-capsule.png'></img></p><p>Creador: "
  + $name + "</p><p>Puntos de interés: " + $lugar + "</p>";
  return elem;
}

$( function() {
  var $container = $('.masonry').masonry({
      isAnimated: true
  });
  
  $('#append-button').on( 'click', function() {
    var elems = [ getItemElement() ];
    //$container.prepend( elems ).masonry( 'reload' );
    $container.append( elems ).masonry( 'appended', elems );
  });
  
  $('#prepend-button').click(function(){
      var elems = [ getItemElement() ];
      $container.prepend( elems ).masonry('prepended', elems).masonry('reload');
    });
});