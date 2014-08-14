$(document).ready(function(d) {
    $('#add-button').click(function() {
      $('#add').fadeIn();
    })

    $('#settings-button').click(function() {
      $('#settings').fadeIn();
    })

    $('#addClose').click(function() {
      $('#add').fadeOut();
    })

    $('#settingsClose').click(function() {
      $('#settings').fadeOut();
    })
});
