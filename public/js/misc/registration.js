const agreeButton = document.getElementById('agreeButton');
const privacyConsent = document.getElementById('privacyConsent');

agreeButton.addEventListener('click', function () {

    privacyConsent.checked = true;
});
