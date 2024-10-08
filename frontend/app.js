document.addEventListener("DOMContentLoaded", function () {
  const cartCount = document.getElementById("cartCount");
  const cartItems = document.getElementById("cartItems");
  const checkoutButton = document.querySelector(".checkout-button");

  function showToast(message, type = "success") {
    Toastify({
      text: message,
      duration: 3000,
      close: true,
      gravity: "bottom",
      position: "right",
      backgroundColor: type === "error" ? "#ff6b6b" : "#4CAF50",
      onclick: function () {
        this.hideToast();
      },
    }).showToast();
  }

  function fetchCartItems() {
    return fetch("http://localhost/wp-ecommerce/backend/view_cart.php")
      .then((response) => response.json())
      .then((data) => {
        return data;
      });
  }

  function updateCartDisplay(cart) {
    if (cartCount) {
      cartCount.textContent = cart.length;
    }
    if (cartItems) {
      cartItems.innerHTML = "";
      if (cart.length === 0) {
        cartItems.innerHTML =
          "<p>Ufff it feels so light here! Add some items to your cart.</p>";
        if (checkoutButton) {
          checkoutButton.style.display = "none";
        }
      } else {
        if (checkoutButton) {
          checkoutButton.style.display = "block";
        }
        cart.forEach((item) => {
          const li = document.createElement("li");
          li.classList.add("cart-item");
          li.innerHTML = `
          <span class="item-details">Item: ${item.item_name}, Quantity: ${item.quantity}</span>
          <button class="remove-button" data-item="${item.item_name}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash">
              <path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
              <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
            </svg>
          </button>`;
          cartItems.appendChild(li);
        });

        document.querySelectorAll(".remove-button").forEach((button) => {
          button.addEventListener("click", function () {
            const itemName = this.getAttribute("data-item");
            removeFromCart(itemName);
          });
        });
      }
    }
  }

  function removeFromCart(itemName) {
    const formData = new FormData();
    formData.append("item", itemName);
    fetch("http://localhost/wp-ecommerce/backend/remove_from_cart.php", {
      method: "POST",
      body: formData,
    })
      .then(() => {
        fetchCartItems().then((cart) => {
          updateCartDisplay(cart);
          showToast(`Removed ${itemName} from the cart`);
        });
      })
      .catch(() => {
        showToast(`Failed to remove ${itemName} from the cart`, "error");
      });
  }

  fetchCartItems().then((cart) => {
    updateCartDisplay(cart);
  });

  const addForm = document.getElementById("addForm");
  if (addForm) {
    addForm.addEventListener("submit", function (event) {
      event.preventDefault();
      const formData = new FormData(this);
      fetch("http://localhost/wp-ecommerce/backend/add_to_cart.php", {
        method: "POST",
        body: formData,
      })
        .then(() => {
          let currentCount = parseInt(cartCount.textContent);
          cartCount.textContent = currentCount + 1;
          fetchCartItems().then((cart) => {
            updateCartDisplay(cart);
            showToast("Item added to cart successfully");
          });
          this.reset();
        })
        .catch(() => {
          showToast("Failed to add item to the cart", "error");
        });
    });
  }
});
