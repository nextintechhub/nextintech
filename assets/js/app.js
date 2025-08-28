// assets/js/app.js
document.addEventListener('DOMContentLoaded', function () {
  // focus style
  document.querySelectorAll('.field').forEach(function (el) {
    el.addEventListener('focus', function () {
      el.classList.add('focus');
    });
    el.addEventListener('blur', function () {
      el.classList.remove('focus');
    });
  });

  // reduce motion preference
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.querySelectorAll('.fade-in, .slide-up, .bob').forEach(function (el) {
      el.style.animation = 'none';
    });
  }

  // simple accessible key handler: Enter on any field inside form should submit (native behavior),
  // but we'll keep the button ripple accessible visually via :active in CSS.
});
