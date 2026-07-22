// Run the script after the HTML document has fully loaded
document.addEventListener("DOMContentLoaded", function () {
    // sidebar
    // Get the sidebar and toggle button elements
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggle-btn');

    // Toggle the sidebar when the button is clicked
    toggleBtn.addEventListener('click', () => {

        // Add or remove the "collapsed" class
        sidebar.classList.toggle('collapsed');

        // Save the sidebar state in Local Storage
        localStorage.setItem(
            'sidebarCollapsed',
            sidebar.classList.contains('collapsed')
        );
    });

    // Restore the previous sidebar state after refreshing the page
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        sidebar.classList.add('collapsed');
    }

    // Highlight the currently selected menu item
    document.querySelectorAll('.menu-item').forEach(item => {

        item.addEventListener('click', function() {

            // Remove the active class from all menu items
            document.querySelectorAll('.menu-item').forEach(i =>
                i.classList.remove('active')
            );

            // Add the active class to the clicked menu item
            this.classList.add('active');
        });
    });

    // Change the menu icon when the mouse hovers over it
    document.querySelectorAll(".menu-item").forEach(item => {

        // Get the icon inside the current menu item
        const icon = item.querySelector(".menu-icon");

        // Change the icon to the gold version when hovering
        item.addEventListener("mouseenter", () => {

            if (!item.classList.contains("active")) {
                icon.src = icon.src.replace(".png", "-gold.png");
            }

        });

        // Restore the original icon when the mouse leaves
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