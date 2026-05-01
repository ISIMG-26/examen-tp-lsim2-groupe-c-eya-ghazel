const form = document.getElementById("commandeForm");

if (form) {
    const qteInput = document.getElementById("quantite");
    const prixUnitaireInput = document.getElementById("prix_unitaire");
    const livraisonInput = document.getElementById("livraison");
    const totalFinalInput = document.getElementById("total_final");
    const prixAffiche = document.getElementById("prix_affiche");
    const livraisonAffiche = document.getElementById("livraison_affiche");
    const totalAffiche = document.getElementById("total_affiche");

    function refreshTotal() {
        const qty = Math.max(1, Number(qteInput?.value || 1));
        const prixUnitaire = Number(prixUnitaireInput?.value || 0);
        const livraison = Number(livraisonInput?.value || 0);
        const total = prixUnitaire * qty + livraison;

        if (prixAffiche) prixAffiche.value = `${prixUnitaire.toFixed(2)} DT`;
        if (livraisonAffiche) livraisonAffiche.value = `${livraison.toFixed(2)} DT`;
        if (totalAffiche) totalAffiche.value = `${total.toFixed(2)} DT`;
        if (totalFinalInput) totalFinalInput.value = total.toFixed(2);
    }

    if (qteInput) {
        qteInput.addEventListener("input", refreshTotal);
        qteInput.addEventListener("change", refreshTotal);
        qteInput.addEventListener("keyup", refreshTotal);
    }
    refreshTotal();

    form.addEventListener("submit", function (e) {
        const tel = document.getElementById("tel");
        const qte = qteInput;

        const telValue = (tel.value || "").replace(/\s+/g, "");
        if (!/^\+?\d{8,15}$/.test(telValue)) {
            e.preventDefault();
            alert("Numero de telephone invalide.");
            tel.focus();
            return;
        }

        if (Number(qte.value) < 1) {
            e.preventDefault();
            alert("La quantite doit etre superieure a 0.");
            qte.focus();
        }
    });
}
