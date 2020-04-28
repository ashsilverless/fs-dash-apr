/* CLASS AND FOCUS ON CLICK */

$('.toggle').on('click', function() {
  $( '.data-table__account-wrapper.active' ).removeClass('active');
  $(this).closest( '.data-table__account-wrapper' ).addClass('active');
})

$('.asset-wrapper__table .toggle').on('click', function() {
    $('.item.active').removeClass('active');
    $(this).closest( '.item' ).toggleClass('active');
})

$('circle.donut-segment').on('click', function() {
    $('circle.donut-segment.selected').removeClass('selected');
    $(this).addClass('selected');
    $('.item.active, text.active').removeClass('active');
    var selectedId = '.' + $(this).attr('id');
    $(selectedId).toggleClass('active');
    $('text' + selectedId).addClass('active');
})

$.fn.toggleText = function(t1, t2){
  if (this.text() == t1) this.text(t2);
  else                   this.text(t1);
  return this;
};

$('.data-toggle').on('click', function() {
    $('.main-content').toggleClass('show-chart');
    $(this).toggleText('View Tables', 'View Charts');
})

$(".add").click(function(e){
  e.preventDefault();
  $("#staffdetails").load("add_staff.php");
});

$(".edit").click(function(e){
  e.preventDefault();
  var staff_id = getParameterByName('id',$(this).attr('href'));
    console.log(staff_id);
  $("#staffdetails").load("edit_staff.php?id="+staff_id);
});

$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

$('.fund-toggle').on('click', function() {
    $(this).closest('form.fund').toggleClass('active');
})
/*$( document ).ready(function() {
    $(".table").tablesorter();
});*/

function getParameterByName(name, url) {
if (!url) url = window.location.href;
name = name.replace(/[\[\]]/g, "\\$&");
var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
    results = regex.exec(url);
if (!results) return null;
if (!results[2]) return '';
return decodeURIComponent(results[2].replace(/\+/g, " "));
}
