// Animasi Text Footer
document.addEventListener('DOMContentLoaded', function() {
    const scrollingText = document.querySelector('.scrolling-text');
    const textWidth = scrollingText.offsetWidth;
    const containerWidth = scrollingText.parentElement.offsetWidth;

    function resetAnimation() {
        scrollingText.style.animation = 'none';
        scrollingText.offsetHeight; // Trigger reflow
        scrollingText.style.animation = null;
    }

    function updateAnimationDuration() {
        const duration = (textWidth + containerWidth) / 100; // Adjust speed as needed
        scrollingText.style.animationDuration = `${duration}s`;
    }

    updateAnimationDuration();
    window.addEventListener('resize', updateAnimationDuration);

    scrollingText.addEventListener('animationiteration', resetAnimation);
});