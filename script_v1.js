const triggers = document.querySelectorAll('.popup-trigger');
const popups = document.querySelectorAll('.popup');

triggers.forEach(trigger => {
  trigger.addEventListener('mouseenter', function() {
    const triggerText = this.textContent;
    popups.forEach(popup => {
      if (popup.dataset.trigger === triggerText) {
        popup.classList.add('active');
      } else {
        popup.classList.remove('active');
      }
    });
  });

  trigger.addEventListener('mouseleave', function() {
    popups.forEach(popup => {
      popup.classList.remove('active');
    });
  });
});
