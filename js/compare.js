// Run the script after the page has finished loading
document.addEventListener("DOMContentLoaded", () => {

    // Get all sorting buttons
    const buttons = document.querySelectorAll(".sort-buttons button");
    // Get the container that holds all comparison cards
    const compareList = document.querySelector(".compare-list");

    buttons.forEach(button => {

        // Execute sorting when a button is clicked
        button.addEventListener("click", () => {

            // Remove the active style from all buttons
            buttons.forEach(btn => btn.classList.remove("active"));
            // Highlight the selected button
            button.classList.add("active");

            // Get all cards ; convert NodeList into array
            let cards = [...document.querySelectorAll(".compare-card")];

            // Check if the user selects Lowest Price sorting
            if (button.textContent.includes("Lowest")) {

                // Sort cards by comparing two cards at a time
                cards.sort((a, b) =>
                    // a.dataset.price 
                    // Get the price stored in the card's data-price attribute
                    // Sort cards in ascending order by price
                    parseFloat(a.dataset.price) - parseFloat(b.dataset.price)
                );

            }

            // Check if the user selects Highest Rating sorting
            else if (button.textContent.includes("Highest")) {

                cards.sort((a, b) =>
                    // Sort cards by highest rating
                    parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating)
                );

            }

            // Sort A-Z
            else {

                // Sort cards alphabetically by restaurant name
                // Because it is designed for comparing strings alphabetically. 
                // It provides more accurate text sorting than using normal comparison operators 
                // like < or >
                cards.sort((a, b) =>
                    a.dataset.name.localeCompare(b.dataset.name)
                );

            }

            // Loop through all sorted cards
            // Re-append the sorted cards to update their order on the page
            cards.forEach(card => compareList.appendChild(card));

        });

    });

});

