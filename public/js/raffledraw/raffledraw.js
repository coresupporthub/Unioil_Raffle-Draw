function GetAllClusterSelect() {
    $.ajax({
        url: "/api/get-cluster", // Replace with your endpoint URL
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

function startRaffle() {
    drawButton.disabled = true;
    let counter = 0;

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
                    show('confetti');
                    drumrolls.pause();
                    setText('winner-name', response.winner_details.full_name);
                    setText('serial-number-winner', response.winner_serial_number);
                    setText('product-purchased-winner', response.product.product_name);
                    setText('cluster-winner', response.cluster_name.cluster_name);
                    GetAllWinner();
                    
                    celebrate.play();
                }, 5000);
            }else{
                alertify.alert('Warning',response.message, function () {
                });
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

function GetAllWinner() {
    $.ajax({
        url: "/api/get-all-winner", // Replace with your endpoint URL
        type: "GET",
        success: function (response) {
            const tableBody = document.getElementById("winnerList");
            while (tableBody.firstChild) {
                tableBody.removeChild(tableBody.firstChild);
            }
            response.forEach((element) => {

                var newRow = document.createElement("tr");

                var clusterCell = document.createElement("td");
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
});
