document.addEventListener('DOMContentLoaded', function () {
    const net = document.querySelector("#net");
    const tax = document.querySelector("#tax");
    const tax_rate = document.querySelector("#tax_rate");
    const gross = document.querySelector("#gross");

    net.addEventListener("keyup", calcValuesForNet);
    net.addEventListener("change", calcValuesForNet);
    tax_rate.addEventListener("keyup", calcValuesForNet);
    tax_rate.addEventListener("change", calcValuesForNet);
    gross.addEventListener("keyup", calcValuesForGross);
    gross.addEventListener("change", calcValuesForGross);

    function calcValuesForNet() {
        tax.value = (parseFloat(net.value) * parseFloat(tax_rate.value) / 100).toFixed(2);
        gross.value = (parseFloat(net.value) + parseFloat(tax.value)).toFixed(2);
    }
    function calcValuesForGross() {
        tax.value = (parseFloat(gross.value) * parseFloat(tax_rate.value) / (100 + parseFloat(tax_rate.value))).toFixed(2);
        net.value = (parseFloat(gross.value) - parseFloat(tax.value)).toFixed(2);
    }
});