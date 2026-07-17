console.log("dashboard.js loaded");

document.addEventListener("DOMContentLoaded", function () {
    
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggle-btn');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    });

    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        sidebar.classList.add('collapsed');
    }

    document.querySelectorAll('.menu-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });

    document.querySelectorAll(".menu-item").forEach(item => {

    const icon = item.querySelector(".menu-icon");

    item.addEventListener("mouseenter", () => {

        if (!item.classList.contains("active")) {
            icon.src = icon.src.replace(".png", "-gold.png");
        }

    });

    item.addEventListener("mouseleave", () => {

        if (!item.classList.contains("active")) {
            icon.src = icon.src.replace("-gold.png", ".png");
        }

    });

});
});

const filterBtn = document.getElementById("filter-btn");
const filterPanel = document.getElementById("filter-panel");
const closeFilter = document.getElementById("close-filter");

if(filterBtn && filterPanel){

    filterBtn.addEventListener("click",()=>{

        filterPanel.classList.toggle("show");

    });

}

if(closeFilter){

    closeFilter.addEventListener("click",()=>{

        filterPanel.classList.remove("show");

    });

}

// ===========================
// Cuisine Filter (Multi Select)
// ===========================

const cuisineButtons = document.querySelectorAll(".cuisine-buttons button");

console.log(cuisineButtons.length);
const selectedCuisine = document.getElementById("selectedCuisine");

let selectedCuisineList = [];

cuisineButtons.forEach(button => {

    button.addEventListener("click", function () {

        console.log("CLICK");

        const value = this.dataset.value;

        // All
        if (value === "All") {

            selectedCuisineList = [];

            cuisineButtons.forEach(btn => btn.classList.remove("active"));

            this.classList.add("active");

            selectedCuisine.value = "";

            return;
        }

        // 取消 All
        cuisineButtons[0].classList.remove("active");

        this.classList.toggle("active");

        if (selectedCuisineList.includes(value)) {

            selectedCuisineList =
                selectedCuisineList.filter(item => item !== value);

        } else {

            selectedCuisineList.push(value);

        }

        selectedCuisine.value = selectedCuisineList.join(",");

        console.log(selectedCuisine.value);

    });

});

// =========================
// Rating Filter (Single Select)
// =========================

const ratingButtons = document.querySelectorAll(".rating-buttons button");
const selectedRating = document.getElementById("selectedRating");

ratingButtons.forEach(button => {

    button.addEventListener("click", function () {

        // 取消所有按钮
        ratingButtons.forEach(btn => btn.classList.remove("active"));

        // 当前按钮变成 active
        this.classList.add("active");

        // 保存到 Hidden Input
        selectedRating.value = this.dataset.value;

    });

});

// Success Alert Auto Hide
const successAlert = document.querySelector(".success-alert");

if (successAlert) {

    setTimeout(() => {

        successAlert.remove();

    }, 4000);

}