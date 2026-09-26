(async function () {
  let CATEGORIES = [];
  let PRODUCTS = [];
  const STORAGE_KEY = "timlyne_cart";

  const $ = (sel) => document.querySelector(sel);
  const $$ = (sel) => document.querySelectorAll(sel);

  let cart = loadCart();
  let activeCategory = "All";
  let searchQuery = "";

  const productGrid = $("#productGrid");
  const categoryPills = $("#categoryPills");
  const productCount = $("#productCount");
  const emptyState = $("#emptyState");
  const cartCount = $("#cartCount");
  const cartItems = $("#cartItems");
  const cartTotal = $("#cartTotal");
  const cartDrawer = $("#cartDrawer");
  const toast = $("#toast");

  function formatPrice(amount) {
    return "Ksh " + amount.toLocaleString("en-KE");
  }

  function productVisual(product) {
    if (product.image) {
      return `<img src="${product.image}" alt="${product.name}" class="product-card__img" loading="lazy" />`;
    }
    return `<span class="product-card__icon" aria-hidden="true">${product.icon || "📦"}</span>`;
  }

  function loadCart() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      return raw ? JSON.parse(raw) : [];
    } catch {
      return [];
    }
  }

  function saveCart() {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
  }

  function showToast(message) {
    toast.textContent = message;
    toast.classList.add("is-visible");
    clearTimeout(showToast._timer);
    showToast._timer = setTimeout(() => toast.classList.remove("is-visible"), 2800);
  }

  function getFilteredProducts() {
    return PRODUCTS.filter((p) => {
      const matchCategory = activeCategory === "All" || p.category === activeCategory;
      const q = searchQuery.toLowerCase().trim();
      const matchSearch =
        !q ||
        p.name.toLowerCase().includes(q) ||
        p.category.toLowerCase().includes(q) ||
        p.specs.some((s) => s.toLowerCase().includes(q));
      return matchCategory && matchSearch;
    });
  }

  function renderCategoryPills() {
    categoryPills.innerHTML = CATEGORIES.map(
      (cat) =>
        `<button type="button" class="pill${cat === activeCategory ? " is-active" : ""}" data-category="${cat}">${cat}</button>`
    ).join("");

    categoryPills.querySelectorAll(".pill").forEach((btn) => {
      btn.addEventListener("click", () => {
        activeCategory = btn.dataset.category;
        renderCategoryPills();
        renderProducts();
      });
    });
  }

  function renderProducts() {
    const list = getFilteredProducts();
    productCount.textContent = `${list.length} product${list.length !== 1 ? "s" : ""} found`;

    if (list.length === 0) {
      productGrid.innerHTML = "";
      emptyState.hidden = false;
      return;
    }

    emptyState.hidden = true;
    productGrid.innerHTML = list
      .map(
        (p) => `
      <article class="product-card" data-id="${p.id}">
        <div class="product-card__visual">
          <span class="product-card__category">${p.category}</span>
          ${productVisual(p)}
        </div>
        <div class="product-card__body">
          <h3><a href="/products/${p.slug}" style="text-decoration:none; color:inherit;">${p.name}</a></h3>
          <ul class="product-card__specs">
            ${p.specs.map((s) => `<li>${s}</li>`).join("")}
          </ul>
          <div class="product-card__footer">
            <div class="product-card__price">
              ${formatPrice(p.price)}
              <small> incl. display price</small>
            </div>
            <button type="button" class="btn btn--primary btn--sm add-to-cart" data-id="${p.id}">
              Add to cart
            </button>
          </div>
        </div>
      </article>`
      )
      .join("");

    productGrid.querySelectorAll(".add-to-cart").forEach((btn) => {
      btn.addEventListener("click", () => addToCart(btn.dataset.id));
    });
  }

  function addToCart(productId) {
    const product = PRODUCTS.find((p) => p.id === productId);
    if (!product) return;

    const existing = cart.find((item) => item.id === productId);
    if (existing) {
      existing.qty += 1;
    } else {
      cart.push({ id: product.id, name: product.name, price: product.price, qty: 1 });
    }

    saveCart();
    updateCartUI();
    showToast(`${product.name} added to cart`);
  }

  function removeFromCart(productId) {
    cart = cart.filter((item) => item.id !== productId);
    saveCart();
    updateCartUI();
  }

  function changeQty(productId, delta) {
    const item = cart.find((i) => i.id === productId);
    if (!item) return;
    item.qty += delta;
    if (item.qty <= 0) {
      removeFromCart(productId);
      return;
    }
    saveCart();
    updateCartUI();
  }

  function getCartTotal() {
    return cart.reduce((sum, item) => sum + item.price * item.qty, 0);
  }

  function getCartItemCount() {
    return cart.reduce((sum, item) => sum + item.qty, 0);
  }

  function updateCartUI() {
    const count = getCartItemCount();
    cartCount.textContent = count;
    cartCount.dataset.count = count;
    cartTotal.textContent = formatPrice(getCartTotal());

    if (cart.length === 0) {
      cartItems.innerHTML = '<p class="cart-empty">Your cart is empty.<br />Browse products and add items.</p>';
      return;
    }

    cartItems.innerHTML = cart
      .map(
        (item) => `
      <div class="cart-item" data-id="${item.id}">
        <div class="cart-item__info">
          <h4>${item.name}</h4>
          <p>${formatPrice(item.price)} each</p>
          <div class="cart-item__qty">
            <button type="button" aria-label="Decrease quantity" data-action="minus" data-id="${item.id}">−</button>
            <span>${item.qty}</span>
            <button type="button" aria-label="Increase quantity" data-action="plus" data-id="${item.id}">+</button>
          </div>
          <button type="button" class="cart-item__remove" data-action="remove" data-id="${item.id}">Remove</button>
        </div>
        <strong>${formatPrice(item.price * item.qty)}</strong>
      </div>`
      )
      .join("");

    cartItems.querySelectorAll("[data-action]").forEach((btn) => {
      btn.addEventListener("click", () => {
        const id = btn.dataset.id;
        if (btn.dataset.action === "remove") removeFromCart(id);
        else if (btn.dataset.action === "plus") changeQty(id, 1);
        else changeQty(id, -1);
      });
    });
  }

  function openCart() {
    cartDrawer.classList.add("is-open");
    cartDrawer.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";
  }

  function closeCart() {
    cartDrawer.classList.remove("is-open");
    cartDrawer.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
  }

  function buildWhatsAppMessage() {
    const lines = [
      "Hello Timlyne Computers,",
      "",
      "I would like a quote for the following items:",
      "",
      ...cart.map((item, i) => `${i + 1}. ${item.name} × ${item.qty} — ${formatPrice(item.price * item.qty)}`),
      "",
      `*Total: ${formatPrice(getCartTotal())}*`,
      "",
      "Please confirm availability. Thank you!",
    ];
    return encodeURIComponent(lines.join("\n"));
  }

  function checkoutWhatsApp() {
    if (cart.length === 0) {
      showToast("Your cart is empty");
      return;
    }
    const phone = "254724407638";
    const url = `https://wa.me/${phone}?text=${buildWhatsAppMessage()}`;
    window.open(url, "_blank", "noopener,noreferrer");
  }

  $("#cartToggle").addEventListener("click", openCart);
  $("#cartClose").addEventListener("click", closeCart);
  $("#cartOverlay").addEventListener("click", closeCart);
  $("#clearCartBtn").addEventListener("click", () => {
    cart = [];
    saveCart();
    updateCartUI();
    showToast("Cart cleared");
  });
  $("#checkoutBtn").addEventListener("click", checkoutWhatsApp);

  $("#searchInput").addEventListener("input", (e) => {
    searchQuery = e.target.value;
    renderProducts();
  });

  $("#menuBtn").addEventListener("click", () => {
    const nav = $("#mobileNav");
    const btn = $("#menuBtn");
    const open = nav.classList.toggle("is-open");
    btn.setAttribute("aria-expanded", open);
  });

  $$(".mobile-nav a").forEach((link) => {
    link.addEventListener("click", () => {
      $("#mobileNav").classList.remove("is-open");
      $("#menuBtn").setAttribute("aria-expanded", "false");
    });
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeCart();
  });

  $("#year").textContent = new Date().getFullYear();

  try {
    const [catRes, prodRes] = await Promise.all([
      fetch('/api/categories'),
      fetch('/api/products')
    ]);
    CATEGORIES = await catRes.json();
    PRODUCTS = await prodRes.json();
    
    renderCategoryPills();
    renderProducts();
  } catch (e) {
    console.error("Failed to load products", e);
    productGrid.innerHTML = "<p>Error loading products. Please try again later.</p>";
  }

  updateCartUI();
})();
