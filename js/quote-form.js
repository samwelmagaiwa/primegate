document.addEventListener('DOMContentLoaded', function () {
  var forms = document.querySelectorAll('.hp-quote-standalone-form');
  if (!forms.length) return;

  forms.forEach(function (form) {
    form.addEventListener('submit', function (event) {
      event.preventDefault();

      var formEl = this;
      var endpoint = formEl.getAttribute('action');
      if (!endpoint) return;

      var feedback = formEl.querySelector('.hp-quote-feedback');
      if (!feedback) {
        feedback = document.createElement('div');
        feedback.className = 'hp-quote-feedback';
        formEl.appendChild(feedback);
      }

      feedback.className = 'hp-quote-feedback hp-quote-feedback--loading';
      feedback.innerHTML = '<span class="hp-quote-icon">⏳</span> Sending your mission brief...';

      var formData = new FormData(formEl);

      fetch(endpoint, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      }).then(function (response) {
        return response.json();
      }).then(function (data) {
        var status = data && data.status ? data.status : 'error';
        var message = data && data.message ? data.message : 'We could not send your request. Please try again.';

        if (status === 'success') {
          feedback.className = 'hp-quote-feedback hp-quote-feedback--success';
          feedback.innerHTML = '<span class="hp-quote-icon">✔</span> ' + message;
          try { formEl.reset(); } catch (e) {}
        } else {
          feedback.className = 'hp-quote-feedback hp-quote-feedback--error';
          feedback.innerHTML = '<span class="hp-quote-icon">⚠</span> ' + message;
        }
      }).catch(function () {
        feedback.className = 'hp-quote-feedback hp-quote-feedback--error';
        feedback.innerHTML = '<span class="hp-quote-icon">⚠</span> We could not send your request. Please try again or email info@primegateinternational.co.tz.';
      });
    });
  });
});