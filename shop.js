document.addEventListener("click", function (event) {
    const buyBtn = event.target.closest(".buy-btn");
    if (!buyBtn) return;

    const product = buyBtn.getAttribute("data-product") || "Produit libre";
    const card = buyBtn.closest(".card");
    const qtyInput = card ? card.querySelector(".qty-input") : null;
    let quantity = qtyInput ? Number(qtyInput.value) : 1;
    if (!Number.isFinite(quantity) || quantity < 1) {
        quantity = 1;
    }

    const url = new URL("commande.php", window.location.href);
    url.searchParams.set("produit", product);
    url.searchParams.set("qte", String(Math.floor(quantity)));
    window.location.href = url.toString();
});
