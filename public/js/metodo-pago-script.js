document.addEventListener("DOMContentLoaded", function () {
    const card = document.querySelector(".credit-card");

    if (card) {
        card.addEventListener("click", function () {
            this.style.animation = "pulse 0.5s ease";
            setTimeout(() => {
                this.style.animation =
                    "fadeInUp 0.8s ease-out, float 6s ease-in-out infinite";
            }, 500);
        });

        const brand = card.getAttribute("data-brand");
        switch (brand) {
            case "visa":
                card.style.background =
                    "linear-gradient(135deg, #1a1f71 0%, #0066b2 50%, #0099cc 100%)";
                break;
            case "mastercard":
                card.style.background =
                    "linear-gradient(135deg, #1c1c1c 0%, #eb001b 50%, #f79e1b 100%)";
                break;
            case "amex":
                card.style.background =
                    "linear-gradient(135deg, #006fcf 0%, #0099dd 50%, #00c2ff 100%)";
                break;
        }

        card.addEventListener("mousemove", function (e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = (y - centerY) / 10;
            const rotateY = (centerX - x) / 10;

            this.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.05)`;
        });

        card.addEventListener("mouseleave", function () {
            this.style.transform = "";
        });

        const chip = document.querySelector(".card-chip");
        if (chip) {
            chip.addEventListener("mouseenter", function () {
                this.style.transform = "scale(1.1) rotate(5deg)";
            });
            chip.addEventListener("mouseleave", function () {
                this.style.transform = "scale(1) rotate(0deg)";
            });
        }

        const numberGroups = document.querySelectorAll(".number-group");
        numberGroups.forEach((group, index) => {
            group.style.animation = `fadeInUp 0.5s ease-out ${
                index * 0.1
            }s both`;
        });
    }

    const securityItems = document.querySelectorAll(".security-item");
    securityItems.forEach((item, index) => {
        item.style.animation = `fadeInUp 0.6s ease-out ${
            0.8 + index * 0.1
        }s both`;
    });

    const modalHasErrors = document.getElementById("editCardModal").classList.contains("show");
    if (modalHasErrors) {
        initEditFormListeners();
        
        const cardNumberInput = document.getElementById("cardNumber");
        if (cardNumberInput && cardNumberInput.value) {
            const event = new Event("input", { bubbles: true });
            cardNumberInput.dispatchEvent(event);
        }
    }
});

function editCard() {
    const editModal = document.getElementById("editCardModal");
    if (editModal) {
        editModal.classList.add("show");
        document.body.style.overflow = "hidden";

        initEditFormListeners();
    }
}

function cancelEdit() {
    const editModal = document.getElementById("editCardModal");
    if (editModal) {
        editModal.classList.remove("show");
        document.body.style.overflow = "";

        document.getElementById("updateCardForm").reset();
        document.getElementById("cardBrandLogo").style.display = "none";
    }
}

function initEditFormListeners() {
    const cardNumberInput = document.getElementById("cardNumber");
    const cardBrandLogo = document.getElementById("cardBrandLogo");
    const logoImg = cardBrandLogo.querySelector("img");

    function detectCardBrand(number) {
        const cleaned = number.replace(/\s/g, "");

        if (/^4/.test(cleaned)) {
            return "visa";
        } else if (/^5[1-5]/.test(cleaned)) {
            return "mastercard";
        } else if (/^3[47]/.test(cleaned)) {
            return "amex";
        }
        return null;
    }

    cardNumberInput.addEventListener("input", function (e) {
        let value = e.target.value.replace(/\s/g, "");
        value = value.replace(/\D/g, "");
        value = value.match(/.{1,4}/g)?.join(" ") || value;
        e.target.value = value;

        const brand = detectCardBrand(value);

        if (brand) {
            cardBrandLogo.style.display = "flex";
            logoImg.src = `images/cards/${brand}.png`;
            logoImg.alt = brand.charAt(0).toUpperCase() + brand.slice(1);
        } else {
            cardBrandLogo.style.display = "none";
        }
    });

    const expiryInput = document.getElementById("expiryDate");
    expiryInput.addEventListener("input", function (e) {
        let value = e.target.value.replace(/\D/g, "");
        if (value.length >= 2) {
            value = value.substring(0, 2) + "/" + value.substring(2, 4);
        }
        e.target.value = value;
    });

    const cvvInput = document.getElementById("cvv");
    cvvInput.addEventListener("input", function (e) {
        e.target.value = e.target.value.replace(/\D/g, "");
    });

    const cardHolderInput = document.getElementById("cardHolder");
    cardHolderInput.addEventListener("input", function (e) {
        e.target.value = e.target.value.toUpperCase();
    });
}

function showSuccessModal() {
    document.getElementById("editCardModal").classList.remove("show");

    const successModal = document.getElementById("successModal");
    successModal.classList.add("show");

    setTimeout(() => {
        location.reload();
    }, 3000);
}

function closeSuccessModal() {
    location.reload();
}

document.querySelectorAll(".btn-action").forEach((button) => {
    button.addEventListener("click", function (e) {
        const ripple = document.createElement("span");
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;

        ripple.style.width = ripple.style.height = size + "px";
        ripple.style.left = x + "px";
        ripple.style.top = y + "px";
        ripple.classList.add("ripple-effect");

        this.appendChild(ripple);

        setTimeout(() => ripple.remove(), 600);
    });
});