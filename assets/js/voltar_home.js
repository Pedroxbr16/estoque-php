document.addEventListener("DOMContentLoaded", function () {
    const backButton = document.querySelector('.back-button');
    
    if (backButton) {
        backButton.addEventListener('click', function () {
            if (homeUrl) {
                window.location.href = homeUrl;
            } else {
                console.error("URL da home não foi definida.");
            }
        });
    }
});
