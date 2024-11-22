

(function() {
    'use strict';
    window.addEventListener('load', function() {

        var forms = document.getElementsByClassName('needs-validation');

        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }else{
                    event.preventDefault();

                    loading(true);

                    const csrf = getCsrf();

                    $.ajax({
                        type: "POST",
                        url: "/api/check-retail-store",
                        data: {"_token": csrf, "rto_code": getValue('store_code')},
                        success: res=> {
                            alertify.set('notifier','position', 'top-center');
                            loading(false);
                            if(res.success){
                                const storeModal = document.getElementById('confirmStore');
                                const modal = new bootstrap.Modal(storeModal);
                                setText('retailStationConfirm', res.store.retail_station);
                                setText('distributorConfirm', res.store.distributor);
                                setText('rtoCodeConfirm', res.store.rto_code);

                                modal.show();
                            }else{
                                alertify.error(res.message);
                            }
                        }, error: xhr=> console.log(xhr.responseText)
                    })

                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();


function saveRegistration(){
    loading(true)
    $.ajax({
        type: "POST",
        url: "/api/register-raffle-entry",
        data: $('#registrationForm').serialize(),
        success: res=> {
            loading(false);
            alertify.set('notifier','position', 'top-center');

            if(res.success){
                alertify.success(res.message);
                window.location.href = `/registration-complete/coupon-serial-number/${res.customer_id}`;
            }else{
                alertify.error(res.message);
            }

        }, error: xhr=> console.log(xhr.responseText)
    });
}

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

let regionCode;
let regionId;
let provinceId;
let cityId;
let brgyId;

document.addEventListener("DOMContentLoaded", function() {


    dataGetter('/js/address_json/region.json').then( data => {
        const regionSelect = document.getElementById("region");

        const province = document.getElementById('province');
        const city = document.getElementById('city');
        const baranggay = document.getElementById('baranggay');

        clearOption(province, 'Select a Province');
        clearOption(city, 'Select a City');
        clearOption(baranggay, 'Select a Baranggay');

        data.forEach( r => {
            const option = createOption(r.name, `${r.code}-${r.id}-${r.name}`);

             regionSelect.append(option);
          });
    });

});

document.getElementById('region').addEventListener('change', (e)=> {

    const regionValue = e.target.value.split('-');
    regionCode = regionValue[0];
    regionId = regionValue[1];

    dataGetter(`/js/address_json/province_region/${getFile(regionCode)}`).then(data=> {
        const province = document.getElementById('province');

        const city = document.getElementById('city');
        const baranggay = document.getElementById('baranggay');

        if(regionCode == '1300000000'){
            enable('province', true);
            setText('provinceLable', 'PROVINCE (NA)');
            provinceId = 65;
            loadCity(65);
        }else{
            enable('province', false);
            setText('provinceLable', 'PROVINCE')
        }

        clearOption(province, 'Select a Province');
        clearOption(city, 'Select a City');
        clearOption(baranggay, 'Select a Baranggay');

        setValue('regionId', regionValue[2]);
        setValue('provinceId', '');
        setValue('cityId', '');
        setValue('baranggayId', '');

        data.forEach(p => {
            const option = createOption(p.name, `${p.id}-${p.name}`);
            province.append(option);
        });

    });
});


document.getElementById('province').addEventListener('change', e => {
    const provinceValue = e.target.value.split('-');
    provinceId = provinceValue[0];
    loadCity(provinceId);
});



function loadCity(prov_id){
    dataGetter(`/js/address_json/city_region/${getFile(regionCode)}`).then(data => {
        const filterCityProvince = data.filter(x => x.province_id == prov_id);

        const city = document.getElementById('city');

        const baranggay = document.getElementById('baranggay');

        clearOption(city, 'Select a City');
        clearOption(baranggay, 'Select a Baranggay');

        setValue('provinceId', provinceId);
        setValue('cityId', '');
        setValue('baranggayId', '');

        const manila = [
            1350,
            1351,
            1352,
            1353,
            1354,
            1355,
            1356,
            1357,
            1358,
            1359,
            1360,
            1361,
            1362,
            1363,
        ];

        filterCityProvince.forEach(c => {
            if(c.id != 1349){
                const option = createOption(manila.includes(c.id) ? `${c.name} (City of Manila)`: c.name, `${c.id}-${manila.includes(c.id) ? `${c.name} (City of Manila)`: c.name}`);

                city.append(option);
            }
        });

    });
}

document.getElementById('city').addEventListener('click', e => {
    const cityValue = e.target.value.split('-');
    cityId = cityValue[0];

    dataGetter(`/js/address_json/brgy_region/${getFile(regionCode)}`).then(data => {
        setValue('cityId', cityValue[1]);
        setValue('baranggayId', '');

        const filterCityBaranggay = data.filter(x => x.region_id == regionId && x.province_id == provinceId && x.city_municipality_id == cityId);

        const baranggay = document.getElementById('baranggay');

        clearOption(baranggay, 'Select a Baranggay');
        filterCityBaranggay.forEach(b => {
            const option = createOption(b.name, b.name);

            baranggay.append(option);
        });

    });
});

document.getElementById('baranggay').addEventListener('change', e => {
    setValue('baranggayId', e.target.value);
});


document.getElementById('confirmRegistrationBtn').addEventListener('click', ()=> {
    saveRegistration();
});
