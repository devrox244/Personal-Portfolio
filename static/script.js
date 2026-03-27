//js for appearing and disappearing text
var typed = new Typed('#element', {
    strings: ['I am a Web Developer....', 'I am an ML Engineer....', 'I am an IoT Developer....'],
    typeSpeed: 50,
});


//js for contents in about
function opencon(tab) {
    var links = document.getElementsByClassName("tab-links");
    var contents = document.getElementsByClassName("tab-content");

    for (link of links) {
        link.classList.remove("active-title");
    }
    for (content of contents) {
        content.classList.remove("active-tab");
    }
    event.currentTarget.classList.add("active-title");
    document.getElementById(tab).classList.add("active-tab");
}

const scriptURL = 'https://script.google.com/macros/s/AKfycbzho5xT2r5bCBOTs6Ab0DqtE8_8ixOqifRs6CI3UAdHsgsyIkcBO4IdqQrWhRMbLtbXIQ/exec';
const form = document.forms['submit-to-google-sheet'];

form.addEventListener('submit', e => {
    e.preventDefault(),
        fetch(scriptURL, {
            method: 'POST',
            body: new FormData(form)
        })
            .then(response => console.log('Success!', response))
            .catch(error => console.error('Error!', error.message))
})

// saves the edited content
function saveSection(sectionName, elementId) {
    const element = document.getElementById(elementId);
    const content = element.innerHTML; // Saves the <li> tags exactly as they are

    fetch('/save_section', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name: sectionName, content: content })
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            alert(sectionName + " updated permanently!");
        }
    })
    .catch(err => console.error("Save failed:", err));
}

function openAchievementForm() {
    let form = document.getElementById("achievement-form");
    let overlay = document.getElementById("popup-overlay");

    form.classList.add("show-popup");
    form.style.display = "block";  // Ensure it appears
    overlay.style.display = "block"; // Show the overlay
}

function closeAchievementForm() {
    let form = document.getElementById("achievement-form");
    let overlay = document.getElementById("popup-overlay");

    form.classList.remove("show-popup");
    form.style.display = "none";  // Hide form
    overlay.style.display = "none"; // Hide overlay
}

function deleteAchievement(id) {
    fetch(`/delete_achievement/${id}`, {
        method: "DELETE"
    })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            location.reload();  // Refresh page after deletion
        })
        .catch(error => console.error("Error:", error));
}

function toggleAchievements() {
    let cards = document.querySelectorAll(".card");
    let button = document.getElementById("toggle-btn");

    // Show all if currently collapsed
    if (button.innerText === "Show More") {
        cards.forEach(card => card.style.display = "block");
        button.innerText = "Show Less";
    }
    // Hide extra rows if expanded
    else {
        cards.forEach((card, index) => {
            if (index >= 3) card.style.display = "none"; // Hide extra cards
        });
        button.innerText = "Show More";
    }
}

// Initially hide extra rows
document.addEventListener("DOMContentLoaded", function () {
    let cards = document.querySelectorAll(".card");
    cards.forEach((card, index) => {
        if (index >= 3) card.style.display = "none";
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const editableSections = document.querySelectorAll("[contenteditable='true']");
    
    editableSections.forEach(section => {
        const key = section.id;
        if (localStorage.getItem(key)) {
            section.innerHTML = localStorage.getItem(key);
        }

        section.addEventListener("input", function () {
            localStorage.setItem(key, section.innerHTML);
        });
    });
});

// Open Edit Form
function openEditForm(id, name, description, date) {
    document.getElementById("edit-id").value = id;
    document.getElementById("edit-name").value = name;
    document.getElementById("edit-description").value = description;
    document.getElementById("edit-date").value = date;
    document.getElementById("edit-achievement-form").style.display = "block";
}

// Close Edit Form
function closeEditForm() {
    document.getElementById("edit-achievement-form").style.display = "none";
}