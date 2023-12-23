document.addEventListener('DOMContentLoaded', function () {
    const cost_type = document.querySelector("#cost_type");
    cost_type.addEventListener("change", toggleDepreciation);

    function toggleDepreciation() {
        const depreciation = document.querySelector("#depreciation");
        if (cost_type.value == 6) {
            depreciation.parentElement.classList.remove("hidden");
        } else {
            depreciation.parentElement.classList.add("hidden");
        }
    }
    toggleDepreciation();
});