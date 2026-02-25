$(function () {

  // ── Chatbot Widget Toggle ────────────────────────────────────
  $('#chatToggle, #chatToggleBtn').on('click', function (e) {
    e.preventDefault();
    $('#chatWidget').toggleClass('d-none');
    if (!$('#chatWidget').hasClass('d-none')) {
      $('#chatInput').focus();
    }
  });

  $('#chatClose').on('click', function () {
    $('#chatWidget').addClass('d-none');
  });

  // ── Chatbot Send ─────────────────────────────────────────────
  function sendChat() {
    var msg = $('#chatInput').val().trim();
    if (!msg) return;

    // Show user bubble
    $('#chatMessages').append(
      '<div class="chat-bubble user">' + $('<div>').text(msg).html() + '</div>'
    );
    $('#chatInput').val('');
    $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);

    // Show typing indicator
    $('#chatMessages').append('<div class="chat-bubble bot" id="typing">Typing...</div>');
    $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);

    // Send to chat.php
    $.ajax({
      url: '/snacksonline/chat.php',
      method: 'POST',
      data: { message: msg },
      dataType: 'json',
      success: function (res) {
        $('#typing').remove();
        $('#chatMessages').append(
          '<div class="chat-bubble bot">' + $('<div>').text(res.answer).html() + '</div>'
        );
        $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
      },
      error: function () {
        $('#typing').remove();
        $('#chatMessages').append(
          '<div class="chat-bubble bot">Sorry, I couldn\'t connect. Please try again.</div>'
        );
      }
    });
  }

  $('#chatSend').on('click', sendChat);
  $('#chatInput').on('keypress', function (e) {
    if (e.which === 13) sendChat();
  });

  // ── Cart quantity +/- buttons ────────────────────────────────
  $(document).on('click', '.qty-plus', function () {
    var input = $(this).siblings('.qty-input');
    input.val(parseInt(input.val()) + 1);
  });

  $(document).on('click', '.qty-minus', function () {
    var input = $(this).siblings('.qty-input');
    var current = parseInt(input.val());
    if (current > 0) input.val(current - 1);
  });

  // ── Auto-dismiss alerts ──────────────────────────────────────
  setTimeout(function () {
    $('.alert').fadeOut('slow');
  }, 4000);

  // ── Image preview for file upload ────────────────────────────
  $('#photoInput').on('change', function () {
    var file = this.files[0];
    if (file) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#photoPreview').attr('src', e.target.result).removeClass('d-none');
        $('#photoPlaceholder').addClass('d-none');
      };
      reader.readAsDataURL(file);
    }
  });

  // ── Delete confirmation ──────────────────────────────────────
  $(document).on('click', '.btn-delete', function (e) {
    if (!confirm('Are you sure you want to delete this? This cannot be undone.')) {
      e.preventDefault();
    }
  });

});
