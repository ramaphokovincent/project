<script>
  function switchTab(tabId) {
    const tabs = document.querySelectorAll('.tab');
    const forms = document.querySelectorAll('.tab-content');

    // Remove active class from all tabs
    tabs.forEach(tab => tab.classList.remove('active'));

    // Fade out all forms
    forms.forEach(form => {
      form.classList.remove('active');
    });

    // Delay before adding the active class to allow animation to restart
    setTimeout(() => {
      document.getElementById(tabId).classList.add('active');
    }, 50);

    // Add active to clicked tab
    document.querySelector(`.tab[onclick*="${tabId}"]`).classList.add('active');
  }
</script>
