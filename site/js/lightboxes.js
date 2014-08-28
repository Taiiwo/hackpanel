$(document).ready(function() {
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

    $('#eventName').on("keyup", function (e) {
      var removeSpaces = $('#eventName').val().replace(/\s/g,"")
      var removePunctuation = removeSpaces.replace(/[\.,-\/#!$%\^&\*;:{}£€=\-_`~()@\+\?><\[\]\+]/g, '')
      $('#eventURL').val(removePunctuation);
      var length = this.value.length;
      var remain = 30 - length;
      $('.nameRemaining').text(remain + " characters remaining");
    })

    $('#eventURL').on("keypress", function(e) {
      if (e.keyCode == 32) {
        return false;
      }
    })

    $('#eventDescription').on("keyup", function(e) {
      var length = this.value.length;
      var remain = 350 - length
      $('.descRemaining').text(remain + " characters remaining");
    })

    $('#imageButton').click(function(e) {
      e.preventDefault();
      $('#imageInput').click();
      $('#imageInput').change(function() {
        var value = this.value;
        var fileName = typeof value == 'string' ? value.match(/[^\/\\]+$/)[0] : value[0]
        $('.imagePath').text(fileName)
      })
    })
});
