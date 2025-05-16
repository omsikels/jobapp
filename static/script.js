document.addEventListener("DOMContentLoaded", () => {
    console.log("Page has loaded!");

    const sections = document.querySelectorAll(".columns-rows-page");
    const paginationDots = document.querySelectorAll(".nav-dot");
    const leftArrow = document.querySelector(".left-arrow");
    const rightArrow = document.querySelector(".right-arrow");
    const seeJobsBtn = document.querySelector(".see-jobs-btn");
    const targetSection = document.querySelector("#we-are-hiring");
    let activeSectionIndex = 0;

    function showPage(index) {
        sections.forEach((section, i) => {
            section.classList.toggle("d-none", i !== index);
        });
        paginationDots.forEach((dot, i) => {
            dot.classList.toggle("active", i === index);
        });
        activeSectionIndex = index;
    }

    if (leftArrow) {
        leftArrow.addEventListener("click", () => {
            const index = activeSectionIndex > 0 ? activeSectionIndex - 1 : sections.length - 1;
            showPage(index);
        });
    }

    if (rightArrow) {
        rightArrow.addEventListener("click", () => {
            const index = activeSectionIndex < sections.length - 1 ? activeSectionIndex + 1 : 0;
            showPage(index);
        });
    }

    if (paginationDots.length > 0) {
        paginationDots.forEach((dot, index) => {
            dot.addEventListener("click", () => showPage(index));
        });
    }

    if (seeJobsBtn && targetSection) {
        seeJobsBtn.addEventListener("click", () => {
            targetSection.scrollIntoView({ behavior: "smooth", block: "start" });
        });
    }

    if (sections.length > 0) {
        showPage(activeSectionIndex);
    } else {
        console.warn("No sections found!");
    }
});

document.addEventListener("DOMContentLoaded", () => {
    try {
        // Initialize and show the Privacy Modal
        const privacyModal = new bootstrap.Modal(document.getElementById("privacyModal"));
        privacyModal.show();
    } catch (error) {
        console.error("Modal initialization failed:", error);
    }

    // Handle "Accept Policy" button logic
    const acceptButton = document.getElementById("acceptButton");

    // Simulate user acknowledgment delay to enable the Accept button
    setTimeout(() => {
        acceptButton.classList.remove("disabled");
    }, 3000); // 3 seconds delay for simulated review

    // Add click event listener to Accept button
    acceptButton.addEventListener("click", () => {
        if (!acceptButton.classList.contains("disabled")) {
            alert("You have accepted the Privacy Policy.");
        } else {
            alert("Please review the policy before accepting.");
        }
    });
});

// Form validation
(function() {
    'use strict';
    const form = document.getElementById('accountForm');
    
    form.addEventListener('submit', function(event) {
        let isValid = true;
        
        // Validate password
        const password = document.getElementById('password').value;
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        if (!passwordRegex.test(password)) {
            isValid = false;
            document.getElementById('password').classList.add('is-invalid');
        }
        
        // Validate confirm password
        const confirmPassword = document.getElementById('confirmPassword').value;
        if (password !== confirmPassword) {
            isValid = false;
            document.getElementById('confirmPassword').classList.add('is-invalid');
        }
        
        if (!isValid) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        form.classList.add('was-validated');
    }, false);
})();