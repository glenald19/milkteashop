// Initialize Fancybox
Fancybox.bind("[data-fancybox='gallery']", {
    Thumbs: {
        autoStart: false
    }
});

// Accordion toggle
$(document).ready(function () {
    $(".accordion").click(function () {
        $(this).toggleClass("active");
        const panel = $(this).next(".panel");
        panel.slideToggle(200);

        if (panel.is(":visible")) {
            $("html, body").animate({
                scrollTop: $(this).offset().top - 100
            }, 300);
        }
    });

    // Form submission via AJAX
    $(".add-to-cart-form").on("submit", function (e) {
        e.preventDefault();
        const form = $(this);
        const productId = form.data("product-id");
        const sizeId = form.find(".size-select").val();
        const sugarId = form.find(".sugar-select").val();

        if (!sizeId || !sugarId) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select both size and sugar level.'
            });
            return;
        }

        // AJAX request to add to cart
        $.ajax({
            url: 'add_to_cart.php',
            method: 'POST',
            data: form.serialize(),
            success: function (response) {
                $("#cart-count").text(`(${response.cartCount})`);
                Swal.fire({
                    icon: 'success',
                    title: 'Added to Cart',
                    text: 'Your item has been added to the cart!'
                });
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again.'
                });
            }
        });
    });
});

// Dynamic price update
function updatePrice(select) {
    const price = parseFloat($(select).find(":selected").data("price")) || 0;
    $(select).closest(".product-card").find(".price").text("₱" + price.toFixed(2));
}

function confirmRemove() {
    return confirm("Remove this item from cart?");
}

// Optionally: animate cards on scroll (if many items)
document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll(".cart-card");
    cards.forEach((card, i) => {
        setTimeout(() => {
            card.classList.add("fade-in");
        }, i * 100);
    });
});
