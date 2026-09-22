// resources/js/app-global.js
const initializeAppScripts = () => {
    initDoubleSubmitProtection();
    initGlobalScripts();
};

document.addEventListener("DOMContentLoaded", initializeAppScripts);
document.addEventListener("livewire:navigated", initializeAppScripts);

function initGlobalScripts() {
    // Auto-fill Stock Adjustment
    var selectElement = document.getElementById("child_part_select");
    var currentStockInput = document.getElementById("current_stock_input");
    var partNameInput = document.getElementById("part_name_input");

    if (selectElement && (currentStockInput || partNameInput)) {
        function updateValues() {
            var selectedOption =
                selectElement.options[selectElement.selectedIndex];
            partNameInput.value =
                selectedOption.getAttribute("data-name") || "";
            if (currentStockInput) {
                currentStockInput.value =
                    selectedOption.getAttribute("data-qty") || "";
            }
        }
        selectElement.removeEventListener("change", updateValues);
        selectElement.addEventListener("change", updateValues);
        if (selectElement.value) {
            updateValues();
        }
    }
}

function initDoubleSubmitProtection() {
    const forms = document.querySelectorAll("form:not([data-protected])");
    forms.forEach((form) => {
        form.setAttribute("data-protected", "true");
        form.addEventListener("submit", function (e) {
            const submitBtn = form.querySelector(
                'button[type="submit"], input[type="submit"]',
            );
            if (submitBtn) {
                if (submitBtn.dataset.submitted === "true") {
                    e.preventDefault();
                    return;
                }

                submitBtn.dataset.submitted = "true";
                submitBtn.disabled = true;
                submitBtn.classList.add("opacity-75", "cursor-not-allowed");

                if (submitBtn.tagName === "BUTTON") {
                    submitBtn.innerHTML = `
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block align-middle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Memproses...</span>
                            `;
                } else if (submitBtn.tagName === "INPUT") {
                    submitBtn.value = "Memproses...";
                }
            }
        });
    });
}
