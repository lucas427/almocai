document.addEventListener('DOMContentLoaded', function () {
  var elems = document.querySelectorAll('.sidenav');
  var instances = M.Sidenav.init(elems);
});

document.addEventListener('DOMContentLoaded', function() {
  var elems = document.querySelectorAll('.modal');
  var instances = M.Modal.init(elems);
});

if (document.getElementById('ul_intolerancia').getElementsByTagName("LI").length == 0) {
  document.getElementById('ul_intolerancia').remove();
}