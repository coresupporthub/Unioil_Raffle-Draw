function GetAllEntry() {
    $.ajax({
        url: "/api/get-raflle-entry", // Replace with your endpoint URL
        type: "GET",
        success: function (response) {
            console.log(response)
        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
    });
}

const names = ["Alice", "Bob", "Charlie", "Diana", "Edward"];
const raffleInput = document.getElementById("raffleInput");
const drawButton = document.getElementById("drawButton");

function startRaffle() {
    drawButton.disabled = true;
    let counter = 0;

    // Shuffle names every 100ms
    const shuffleInterval = setInterval(() => {
        const randomIndex = Math.floor(Math.random() * names.length);
        raffleInput.value = names[randomIndex];
        counter++;
    }, 100);

    // After 3 seconds, pick the final name
    setTimeout(() => {
        clearInterval(shuffleInterval);
        const finalIndex = Math.floor(Math.random() * names.length);
        raffleInput.value = names[finalIndex];
        drawButton.disabled = false;

        // Trigger confetti when the final name is picked
        triggerConfetti();
    }, 5000);
}

function triggerConfetti() {
    const duration = 2 * 1000; // 2 seconds
    const end = Date.now() + duration;

    (function frame() {
        confetti({
            particleCount: 10,
            angle: 60,
            spread: 55,
            origin: { x: 0 },
        });
        confetti({
            particleCount: 10,
            angle: 120,
            spread: 55,
            origin: { x: 1 },
        });

        if (Date.now() < end) {
            requestAnimationFrame(frame);
        }
    })();
}

drawButton.addEventListener("click", startRaffle);

$(document).ready(function () {
    GetAllEntry();
});
