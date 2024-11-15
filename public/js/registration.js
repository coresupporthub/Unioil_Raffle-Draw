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
                    $.ajax({
                        type: "POST",
                        url: "/api/register-raffle-entry",
                        data: $('#registrationForm').serialize(),
                        success: res=> {
                            loading(false);
                        }, error: xhr=> console.log(xhr.responseText)
                    });
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

let regionCode;
let regionId;
let provinceId;
let cityId;
let brgyId;

document.addEventListener("DOMContentLoaded", function() {

    dataGetter('https://psgc.cloud/api/regions').then( data => {
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

    dataGetter(`https://psgc.cloud/api/regions/${regionCode}/provinces`).then(data=> {
        const province = document.getElementById('province');

        const city = document.getElementById('city');
        const baranggay = document.getElementById('baranggay');

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
    dataGetter(`https://psgc.cloud/api/regions/${regionCode}/cities-municipalities`).then(data => {
        const filterCityProvince = data.filter(x => x.province_id == provinceId);

        const city = document.getElementById('city');

        const baranggay = document.getElementById('baranggay');

        clearOption(city, 'Select a City');
        clearOption(baranggay, 'Select a Baranggay');

        setValue('provinceId', provinceValue[1]);
        setValue('cityId', '');
        setValue('baranggayId', '');

        filterCityProvince.forEach(c => {
            const option = createOption(c.name, `${c.id}-${c.name}`);

            city.append(option);
        });

    });
});

document.getElementById('city').addEventListener('click', e => {
    const cityValue = e.target.value.split('-');
    cityId = cityValue[0];

    dataGetter(`https://psgc.cloud/api/regions/${regionCode}/barangays`).then(data => {
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
