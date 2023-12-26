document.addEventListener('DOMContentLoaded', function () {
    const costTypeInfo = document.querySelector("#costTypeInfo");;
    const overlay = document.querySelector("#costTypeOverlay");;

    costTypeInfo.addEventListener("click", showCostTypes);
    costTypeInfo.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            showCostTypes();
        }
    });

    overlay.addEventListener("click", hideCostTypes);

    function showCostTypes() {
        overlay.classList.remove("hidden");
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            hideCostTypes();
        }
    });

    function hideCostTypes() {
        overlay.classList.add("hidden");
    }
});