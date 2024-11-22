function GetAllClusterSelect() {
    $.ajax({
        url: "/api/get-cluster/draw", // Replace with your endpoint URL
        type: "GET",
        success: function (response) {
            const data = response.data;

            const selectElement = document.getElementById("selectCluster");

            selectElement.innerHTML = "";

            const defaultOption = document.createElement("option");
            defaultOption.text = "Select a cluster";
            defaultOption.value = "";
            selectElement.appendChild(defaultOption);

            data.forEach((element) => {
                const newOption = document.createElement("option");
                newOption.value = element.cluster_id;
                newOption.text = element.cluster_name;
                selectElement.appendChild(newOption);
            });
        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
    });
}
let serial_number = [];
let cluster_id = '';
function SelectEntry(id){
    cluster_id = id.value;
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const formData = new FormData();
    formData.append("_token", csrfToken);
    formData.append('id',id.value);

    $.ajax({
        url: "/api/get-raflle-entry",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if(response.length == 0){
                document.getElementById("drawButton").disabled=true;
            }else{
                document.getElementById("drawButton").disabled=false;
            }
            serial_number.length=0;
            response.forEach((element) => {
                serial_number.push(element.serial_number);
            });
        },
        error: function (xhr, status, error) {
            console.error("Error posting data:", error);
        },
    });

}

const raffleInput = document.getElementById("raffleInput");
const drawButton = document.getElementById("drawButton");
let interval;
function startRaffle() {
    drawButton.disabled = true;
    let counter = 0;

    if (!document.fullscreenElement) {
        exec('fullscreenButton');
    }

    const drumrolls = new Audio("/sounds/machine.mp3");
    drumrolls.loop = true;
    drumrolls.volume = 1;
    drumrolls.play();

    const shuffleInterval = setInterval(() => {
        const randomIndex = Math.floor(Math.random() * serial_number.length);
        raffleInput.value = serial_number[randomIndex];
        counter++;
    }, 100);

    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const formData = new FormData();
    formData.append("_token", csrfToken);
    formData.append("id", cluster_id);
    let celebrate = new Audio("/sounds/winning.mp3");
    celebrate.volume = 1;

    startRandomizing();

    $.ajax({
        url: "/api/raffle-draw",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if(response.success){
                setTimeout(() => {
                    clearInterval(shuffleInterval);
                    raffleInput.value = response.winner_serial_number;
                    drawButton.disabled = false;
                    show('confetti', 'flex');
                    drumrolls.pause();
                    setText('winner-name', response.winner_details.full_name);
                    setText('serial-number-winner', response.winner_serial_number);
                    setText('product-purchased-winner', response.product.product_name);
                    setText('cluster-winner', response.cluster_name.cluster_name);
                    setText('retail-station', response.cluster_name.retail_station);
                    setText('distributor', response.cluster_name.distributor);
                    GetAllWinner();
                    celebrate.play();
                    stopRandomizing();
                }, 5000);
            }else{
                $('#RedrawPrompt').modal('show');
                // alertify.alert('Warning',response.message, function () {
                // });
                stopRandomizing();
                hide('colorOverlay');
                drumrolls.pause();
                drawButton.disabled = false;
                clearInterval(shuffleInterval);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error posting data:", error);
        },
    });

}

 function getRandomColor() {
     return `#${Math.floor(Math.random() * 16777215).toString(16)}`;
 }

 // Generate random horizontal and vertical shadow offsets
 function getRandomOffset() {
     return Math.floor(Math.random() * 20) - 10; // Offset range (-10 to 10)
 }

 // Generate a random shadow including horizontal and vertical offsets
 function getRandomShadow() {
     const hOffset = getRandomOffset(); // Horizontal offset
     const vOffset = getRandomOffset(); // Vertical offset
     const blur = Math.floor(Math.random() * 30); // Blur radius (0 to 30)
     const spread = Math.floor(Math.random() * 20) - 10; // Spread radius (-10 to 10)
     const color = getRandomColor(); // Shadow color
     return `${hOffset}px ${vOffset}px ${blur}px ${spread}px ${color}`;
 }

function startRandomizing() {
    if (!interval) {
        interval = setInterval(() => {
            raffleInput.style.color = getRandomColor(); // Randomize text color
            raffleInput.style.borderColor = getRandomColor(); // Randomize border color
            raffleInput.style.boxShadow = getRandomShadow(); // Randomize shadow
            const colorOverlay = document.getElementById('colorOverlay');
            show('colorOverlay');
            colorOverlay.style.backgroundColor = getRandomColor();
        }, 100); // Change properties every 500ms
    }
}

function stopRandomizing() {
    clearInterval(interval);
    interval = null;
    // Reset styles to default
    raffleInput.style.color = "black";
    raffleInput.style.borderColor = "black";
    raffleInput.style.boxShadow = "none";
}



function GetAllWinner() {
    $.ajax({
        url: "/api/get-all-winner", // Replace with your endpoint URL
        type: "GET",
        success: function (response) {
            const tableBody = document.getElementById("winnerList");
            while (tableBody.firstChild) {
                tableBody.removeChild(tableBody.firstChild);
            }

            const colors = [
                "#4A4A4A", // Dark gray
                "#B24C63", // Muted rose
                "#3E8E41", // Fresh green
                "#28527A", // Deep blue
                "#D2691E", // Rustic chestnut brown
                "#1C1C1C", // Deep onyx black
                "#6D6D6D", // Medium gray
                "#FFD700", // Vibrant gold
                "#FF6347", // Tomato red
                "#87CEEB", // Sky blue
            ]
            const randomizer = array => {
                return Math.floor(Math.random() * array.length);
            };

            response.forEach((element) => {

                var newRow = document.createElement("tr");

                var clusterCell = document.createElement("td");
                newRow.style.color = colors[randomizer(colors)];
                newRow.style.width = '100%';
                clusterCell.textContent = element.cluster;
                newRow.appendChild(clusterCell);

                var nameCell = document.createElement("td");
                nameCell.textContent = element.customer_name;
                newRow.appendChild(nameCell);

                tableBody.appendChild(newRow);

            });
        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
    });
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
    GetAllClusterSelect();
    GetAllWinner();
    if (serial_number.length == 0) {
        document.getElementById("drawButton").disabled = true;
    } else {
        document.getElementById("drawButton").disabled = false;
    }
});

document.getElementById('confetti').addEventListener('click', ()=> {
    hide('confetti');
    hide('colorOverlay');
});
