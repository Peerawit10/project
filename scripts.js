document.addEventListener('DOMContentLoaded', function() {
    const productCards = document.querySelectorAll('.product-card');
    const popup = document.getElementById('popup');
    const popupImage = document.getElementById('popup-image');
    const popupName = document.getElementById('popup-name');
    const popupPrice = document.getElementById('popup-price');
    const closeBtn = document.querySelector('.close');

    productCards.forEach(card => {
        card.addEventListener('click', function() {
            const name = card.getAttribute('data-name');
            const price = card.getAttribute('data-price');
            const image = card.getAttribute('data-image');

            popupImage.src = image;
            popupName.textContent = name;
            popupPrice.textContent = `ราคา ${price} บาท`;

            popup.style.display = 'block';
        });
    });

    closeBtn.addEventListener('click', function() {
        popup.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === popup) {
            popup.style.display = 'none';
        }
    });

    document.getElementById('overlay').addEventListener('click', closeLoginPopup);
});

function showProductPopup(button) {
    const card = button.closest('.product-card');
    const name = card.getAttribute('data-name');
    const price = card.getAttribute('data-price');
    const image = card.getAttribute('data-image');

    const popup = document.getElementById('popup');
    const popupImage = document.getElementById('popup-image');
    const popupName = document.getElementById('popup-name');
    const popupPrice = document.getElementById('popup-price');

    popupImage.src = image;
    popupName.textContent = name;
    popupPrice.textContent = `ราคา ${price} บาท`;

    popup.style.display = 'block';
}

function scrollCarousel(direction) {
    const carousel = document.querySelector('.product-grid');
    const scrollAmount = 300; // ปรับค่าตามต้องการ
    carousel.scrollBy({
        left: direction * scrollAmount,
        behavior: 'smooth'
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const productCards = document.querySelectorAll('.product-card');
    const popup = document.getElementById('popup');
    const popupImage = document.getElementById('popup-image');
    const popupName = document.getElementById('popup-name');
    const popupPrice = document.getElementById('popup-price');
    const closeBtn = document.querySelector('.close');
    const searchForm = document.getElementById('search-form');
    const searchInput = document.getElementById('search-input');
    const searchPopup = document.getElementById('search-popup');

    productCards.forEach(card => {
        card.addEventListener('click', function() {
            const name = card.getAttribute('data-name');
            const price = card.getAttribute('data-price');
            const image = card.getAttribute('data-image');

            popupImage.src = image;
            popupName.textContent = name;
            popupPrice.textContent = `ราคา ${price} บาท`;

            popup.style.display = 'block';
        });
    });

    closeBtn.addEventListener('click', function() {
        popup.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === popup) {
            popup.style.display = 'none';
        }
    });

    document.getElementById('overlay').addEventListener('click', closeLoginPopup);

    searchForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const searchTerm = searchInput.value.toLowerCase();
        productCards.forEach(card => {
            const productName = card.getAttribute('data-name').toLowerCase();
            if (productName.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        searchPopup.style.display = 'none'; // ปิดป๊อบอัพหลังจากค้นหา
    });
});

function showProductPopup(button) {
    const card = button.closest('.product-card');
    const name = card.getAttribute('data-name');
    const price = card.getAttribute('data-price');
    const image = card.getAttribute('data-image');

    const popup = document.getElementById('popup');
    const popupImage = document.getElementById('popup-image');
    const popupName = document.getElementById('popup-name');
    const popupPrice = document.getElementById('popup-price');

    popupImage.src = image;
    popupName.textContent = name;
    popupPrice.textContent = `ราคา ${price} บาท`;

    popup.style.display = 'block';
}

function openLoginPopup() {
    document.getElementById('loginPopup').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
}

function closeLoginPopup() {
    document.getElementById('loginPopup').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}

function scrollCarousel(direction) {
    const carousel = document.querySelector('.product-grid');
    const scrollAmount = 300; // ปรับค่าตามต้องการ
    carousel.scrollBy({
        left: direction * scrollAmount,
        behavior: 'smooth'
    });
}

function openSearchPopup() {
    document.getElementById('search-popup').style.display = 'block';
}

function closeSearchPopup() {
    document.getElementById('search-popup').style.display = 'none';
}


// Get current path
const currentPath = window.location.pathname;
console.log("currentPath: ", currentPath);

// Get all nav cards
const hotPage = document.getElementById('nav-card1');
const driedFoodPage = document.getElementById('nav-card2');
const drinkPage = document.getElementById('nav-card3');
const condimentPage = document.getElementById('nav-card4');
const snackPage = document.getElementById('nav-card5');
const itemsPage = document.getElementById('nav-card6');

// Add active class based on current path
switch (currentPath) {
  case "/pro/hot.php":
    hotPage.classList.add('active');
    break;
  case "/pro/driedfood.php":
    driedFoodPage.classList.add('active');
    break;
  case "/pro/drink.php":
    drinkPage.classList.add('active');
    break;
  case "/pro/condiment.php":
    condimentPage.classList.add('active');
    break;
  case "/pro/snack.php":
    snackPage.classList.add('active');
    break;
  case "/pro/items.php":
    itemsPage.classList.add('active');
    break;
  default:
    // No active class if not on any of these pages
    break;
}

document.addEventListener('DOMContentLoaded', function() {
    const openModalButton = document.getElementById('openmodal');
    const logoutPopup = document.getElementById('logout-popup');
    const closeButtons = logoutPopup.querySelectorAll('.close');
    const noButton = logoutPopup.querySelector('button:last-child');
    const yesButton = logoutPopup.querySelector('button:first-child');

    openModalButton.addEventListener('click', function() {
        logoutPopup.style.display = 'block';
    });

    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            logoutPopup.style.display = 'none';
        });
    });

    noButton.addEventListener('click', function() {
        logoutPopup.style.display = 'none';
    });

    yesButton.addEventListener('click', function() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'logout.php';
        document.body.appendChild(form);
        form.submit();
    });

    logoutPopup.addEventListener('click', function(event) {
        if (event.target === this) {
            this.style.display = 'none';
        }
    });
});

