window.addEventListener('DOMContentLoaded', () => {
    const depositSection = document.getElementById('container-account');
    if (depositSection) {
        depositSection.scrollIntoView({
            behavior: 'smooth'
        });
    }
});