const fullscreenButton = document.getElementById('fullscreenButton');
const cardBody = document.querySelector('.card-body');

fullscreenButton.addEventListener('click', () => {
    if (document.fullscreenElement) {
        // Exit fullscreen
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
        // Restore scrollable
        cardBody.style.overflow = 'auto';
    } else {
        // Enter fullscreen
        if (cardBody.requestFullscreen) {
            cardBody.requestFullscreen();
        } else if (cardBody.webkitRequestFullscreen) {
            cardBody.webkitRequestFullscreen();
        } else if (cardBody.mozRequestFullScreen) {
            cardBody.mozRequestFullScreen();
        } else if (cardBody.msRequestFullscreen) {
            cardBody.msRequestFullscreen();
        }
        // Remove scrollable
        cardBody.style.overflow = 'hidden';
    }
});
