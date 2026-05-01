document.addEventListener("click", function (event) {
    const deleteBtn = event.target.closest("[data-delete]");
    if (!deleteBtn) return;

    const label = deleteBtn.getAttribute("data-delete") || "cet element";
    const ok = confirm("Supprimer " + label + " ?");
    if (!ok) {
        event.preventDefault();
    }
});

document.addEventListener("input", function (event) {
    if (!event.target.matches("[data-number]")) return;

    const value = Number(event.target.value);
    if (Number.isNaN(value) || value < 0) {
        event.target.value = 0;
    }
});
