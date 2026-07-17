document.addEventListener("DOMContentLoaded", () => {

    const buttons = document.querySelectorAll(".sort-buttons button");
    const compareList = document.querySelector(".compare-list");

    buttons.forEach(button => {

        button.addEventListener("click", () => {

            // Active Button
            buttons.forEach(btn => btn.classList.remove("active"));
            button.classList.add("active");

            // Get all cards
            let cards = [...document.querySelectorAll(".compare-card")];

            // Sort by Lowest Price
            if (button.textContent.includes("Lowest")) {

                cards.sort((a, b) =>
                    parseFloat(a.dataset.price) - parseFloat(b.dataset.price)
                );

            }

            // Sort by Highest Rating
            else if (button.textContent.includes("Highest")) {

                cards.sort((a, b) =>
                    parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating)
                );

            }

            // Sort A-Z
            else {

                cards.sort((a, b) =>
                    a.dataset.name.localeCompare(b.dataset.name)
                );

            }

            // Re-append cards
            cards.forEach(card => compareList.appendChild(card));

        });

    });

});

