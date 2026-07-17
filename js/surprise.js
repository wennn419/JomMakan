/* ===========================
   Hidden Inputs
=========================== */

const budgetInput = document.getElementById("budgetInput");
const qualityInput = document.getElementById("qualityInput");
const cuisineInput = document.getElementById("cuisineInput");


/* ===========================
   Budget (Single Select)
=========================== */

const budgetOptions = document.querySelectorAll(".budget-grid .option-card");

budgetOptions.forEach(option => {

    option.addEventListener("click", () => {

        budgetOptions.forEach(card => {

            card.classList.remove("active");

        });

        option.classList.add("active");

        budgetInput.value = option.dataset.value;

        console.log("Budget:", budgetInput.value);

    });

});


/* ===========================
   Restaurant Quality
=========================== */

const ratingOptions = document.querySelectorAll(".rating-grid .option-card");

ratingOptions.forEach(option => {

    option.addEventListener("click", () => {

        ratingOptions.forEach(card => {

            card.classList.remove("active");

        });

        option.classList.add("active");

        qualityInput.value = option.dataset.value;

        console.log("Quality:", qualityInput.value);

    });

});


/* ===========================
   Cuisine (Multi Select)
=========================== */

const cuisineChips = document.querySelectorAll(".cuisine-chip");

cuisineChips.forEach(chip => {

    chip.addEventListener("click", () => {

        chip.classList.toggle("active");

        const selected = [];

        cuisineChips.forEach(item => {

            if(item.classList.contains("active")){

                selected.push(item.dataset.value);

            }

        });

        cuisineInput.value = selected.join(",");

        console.log("Cuisine:", cuisineInput.value);

    });

});