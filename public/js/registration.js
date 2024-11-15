(function() {
    'use strict';
    window.addEventListener('load', function() {

        var forms = document.getElementsByClassName('needs-validation');

        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

function validatePhoneNumber(input) {
    const phoneNumber = input.value;
    const phoneError = document.getElementById('phoneError');

    const isValid = /^9\d{9}$/.test(phoneNumber);

    if (!isValid) {
        phoneError.style.display = 'block';
    } else {
        phoneError.style.display = 'none';
    }
}

const addressData = {
    "Region I - Ilocos Region": {
        "Ilocos Norte": {
            "Laoag City": ["Barangay 1 San Lorenzo", "Barangay 2 San Pablo"],
            "Batac City": ["Barangay 1", "Barangay 2"]
        },
        "Ilocos Sur": {
            "Vigan City": ["Barangay 1", "Barangay 2"],
            "Candon City": ["Barangay A", "Barangay B"]
        },
        "La Union": {
            "San Fernando City": ["Barangay Tanqui", "Barangay Pagdalagan"],
            "Agoo": ["Barangay Consolacion", "Barangay San Manuel Norte"]
        },
        "Pangasinan": {
            "Dagupan City": ["Barangay Bonuan Boquig", "Barangay Calmay"],
            "Alaminos City": ["Barangay Poblacion", "Barangay Baley-a-Daan"]
        }
    },
    "Region II - Cagayan Valley": {
        "Batanes": {
            "Basco": ["Kaychanarianan", "Chanarian"],
            "Mahatao": ["Barangay 1", "Barangay 2"]
        },
        "Cagayan": {
            "Tuguegarao City": ["Barangay Bagay", "Barangay Annafunan East"],
            "Aparri": ["Barangay Punta", "Barangay Maura"]
        },
        "Isabela": {
            "Ilagan City": ["Barangay Calamagui", "Barangay San Vicente"],
            "Cauayan City": ["Barangay District 1", "Barangay District 2"]
        },
        "Nueva Vizcaya": {
            "Bayombong": ["Barangay Don Mariano Perez", "Barangay Salvacion"],
            "Solano": ["Barangay Poblacion North", "Barangay Poblacion South"]
        },
        "Quirino": {
            "Cabarroguis": ["Barangay Burgos", "Barangay Zamora"],
            "Maddela": ["Barangay San Pedro", "Barangay San Salvador"]
        }
    },
    "NCR - National Capital Region": {
        "Manila": {
            "Tondo": ["Barangay 1", "Barangay 2"],
            "Malate": ["Barangay 701", "Barangay 702"]
        },
        "Quezon City": {
            "Novaliches": ["Barangay Gulod", "Barangay San Bartolome"],
            "Diliman": ["Barangay Central", "Barangay UP Campus"]
        },
        "Makati": {
            "Poblacion": ["Barangay Poblacion"],
            "Bel-Air": ["Barangay Bel-Air"]
        },
        "Pasig": {
            "Ortigas Center": ["Barangay San Antonio"],
            "Rosario": ["Barangay Rosario"]
        }
    },
    "Region IV-A - CALABARZON": {
        "Cavite": {
            "Bacoor City": ["Barangay Molino III", "Barangay Queens Row East"],
            "Dasmariñas City": ["Barangay Zone I", "Barangay Zone II"]
        },
        "Laguna": {
            "Calamba City": ["Barangay Real", "Barangay Canlubang"],
            "Santa Rosa City": ["Barangay Balibago", "Barangay Pulong Santa Cruz"]
        },
        "Batangas": {
            "Batangas City": ["Barangay Pallocan West", "Barangay Santa Rita Karsada"],
            "Lipa City": ["Barangay Balintawak", "Barangay Tambo"]
        },
        "Rizal": {
            "Antipolo City": ["Barangay Cupang", "Barangay San Roque"],
            "Cainta": ["Barangay San Andres", "Barangay Santo Domingo"]
        },
        "Quezon": {
            "Lucena City": ["Barangay Dalahican", "Barangay Ilayang Dupay"],
            "Tayabas City": ["Barangay Angustias Zone I", "Barangay Ipilan"]
        }
    }
};


document.addEventListener("DOMContentLoaded", function() {
    const regionSelect = document.getElementById("region");
    const provinceSelect = document.getElementById("province");
    const citySelect = document.getElementById("city");
    const barangaySelect = document.getElementById("baranggay");

    for (const region in addressData) {
        const option = new Option(region, region);
        regionSelect.add(option);
    }

    regionSelect.addEventListener("change", function() {
        provinceSelect.length = 1;
        citySelect.length = 1;
        barangaySelect.length = 1;

        const selectedRegion = regionSelect.value;
        if (selectedRegion && addressData[selectedRegion]) {
            for (const province in addressData[selectedRegion]) {
                const option = new Option(province, province);
                provinceSelect.add(option);
            }
        }
    });

    provinceSelect.addEventListener("change", function() {
        citySelect.length = 1;
        barangaySelect.length = 1;

        const selectedRegion = regionSelect.value;
        const selectedProvince = provinceSelect.value;
        if (selectedProvince && addressData[selectedRegion][selectedProvince]) {
            for (const city in addressData[selectedRegion][selectedProvince]) {
                const option = new Option(city, city);
                citySelect.add(option);
            }
        }
    });

    citySelect.addEventListener("change", function() {
        barangaySelect.length = 1;

        const selectedRegion = regionSelect.value;
        const selectedProvince = provinceSelect.value;
        const selectedCity = citySelect.value;
        if (selectedCity && addressData[selectedRegion][selectedProvince][selectedCity]) {
            addressData[selectedRegion][selectedProvince][selectedCity].forEach(function(barangay) {
                const option = new Option(barangay, barangay);
                barangaySelect.add(option);
            });
        }
    });
});
