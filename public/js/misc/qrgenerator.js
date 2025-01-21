function enforceLimit(input) {
    if (input.value > 25000) {
        input.value = 25000;
    }
}

function enforceLimitForPdf(input) {
    if (input.value > 1000) {
        input.value = 1000;
    }
}
