const cards = document.querySelectorAll(".reveal-card");

cards.forEach((card, index) => {

    setTimeout(() => {

        card.classList.add("show");

    }, 500 + index * 350);

});

// Make whole card clickable
const foodCards = document.querySelectorAll(".food-card");

foodCards.forEach(card => {

    card.addEventListener("click", () => {

        const foodId = card.dataset.id;
        const mode = card.dataset.mode;

        window.location.href =
            "food.php?id=" + foodId +
            "&from=result" +
            "&mode=" + mode;

    });

});