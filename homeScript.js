function validateForm() {
    var tradeType = document.getElementById("tradeDescription");
    var profit = document.getElementById("profit");

    tradeType.classList.remove("unfilled-field");
    profit.classList.remove("unfilled-field");

    if (tradeType.value === "") {
        tradeType.classList.add("unfilled-field");
    }

    if (profit.value === "") {
        profit.classList.add("unfilled-field");
    }

    if (tradeType.value === "" || profit.value === "") {
        var alertDiv = document.getElementById("customAlert");
        alertDiv.style.display = "block";
        return false;
    }

    return true;
}

function closeAlert() {
    var alertDiv = document.getElementById("customAlert");
    alertDiv.style.display = "none";
}

function toggleSections(sectionId) {
    var dateFilterSection = document.getElementById("dateFilterSection");
    var allTradesSection = document.getElementById("allTradesSection");
    var dateFilterToggle = document.querySelector('.toggle-item[data-type="dateFilter"]');
    var allTreadesToggle = document.querySelector('.toggle-item[data-type="allTrades"]');

    if (sectionId === "dateFilterSection") {
        dateFilterSection.style.display = "block";
        allTradesSection.style.display = "none";
        dateFilterToggle.classList.add('active');
        allTreadesToggle.classList.remove('active');
    } else if (sectionId === "allTradesSection") {
        dateFilterSection.style.display = "none";
        allTradesSection.style.display = "block";
        dateFilterToggle.classList.remove('active');
        allTreadesToggle.classList.add('active');
    }

}
