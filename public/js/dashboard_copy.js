/**
 * Otorisasi / Approve Purchase Order secara interaktif
 */
function approvePO(cardId) {
    const card = document.getElementById(cardId);
    if (card) {
        card.style.transition = 'all 0.3s ease';
        card.style.opacity = '0.4';
        card.style.transform = 'scale(0.98)';

        setTimeout(() => {
            card.innerHTML = `
                <div class="flex items-center justify-between p-2 rounded bg-primary-container text-on-primary-container shadow-sm">
                    <span class="font-label-sm text-label-sm font-code-tabular flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span> Otorisasi PO Terverifikasi
                    </span>
                    <span class="text-label-sm font-code-tabular">SENT TO ERP CORE</span>
                </div>
            `;
            card.style.opacity = '1';
            card.style.transform = 'scale(1)';
        }, 300);
    }
}

/**
 * Tolak / Reject Purchase Order secara interaktif
 */
function rejectPO(cardId) {
    const card = document.getElementById(cardId);
    if (card) {
        card.style.transition = 'all 0.3s ease';
        card.style.opacity = '0.3';

        setTimeout(() => {
            card.innerHTML = `
                <div class="flex items-center justify-between p-2 rounded bg-error-container text-on-error-container shadow-sm">
                    <span class="font-label-sm text-label-sm flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">cancel</span> Dokumen PO Ditolak / Revisi
                    </span>
                    <span class="text-label-sm font-code-tabular">FLAGGED</span>
                </div>
            `;
            card.style.opacity = '1';
        }, 250);
    }
}

/**
 * Event Listener untuk Toggle Gudang / Warehouse Switcher
 */
document.addEventListener('DOMContentLoaded', function() {
    const warehouseButtons = ['wh-btn-01', 'wh-btn-02', 'wh-btn-03'];

    warehouseButtons.forEach(id => {
        const btn = document.getElementById(id);
        if (btn) {
            btn.addEventListener('click', function() {
                warehouseButtons.forEach(b => {
                    const el = document.getElementById(b);
                    if (el) {
                        el.className = "px-space-md py-1 rounded text-on-surface-variant hover:text-on-surface transition-all";
                    }
                });
                this.className = "px-space-md py-1 rounded bg-primary-container text-on-primary-container transition-all font-semibold";
            });
        }
    });
});
