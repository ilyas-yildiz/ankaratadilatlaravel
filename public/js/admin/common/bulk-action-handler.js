/**
 * Tablodaki checkbox'ların yönetimini ve toplu işlem butonunun görünürlüğünü ayarlar.
 * @param {string} selectAllId - Tümünü seç checkbox'ının ID'si.
 * @param {string} rowCheckboxClass - Satır checkbox'larının class'ı.
 * @param {string} bulkActionBtnId - Toplu işlem butonunun ID'si.
 */
export function initCheckboxManagement(selectAllId, rowCheckboxClass, bulkActionBtnId) {
    const selectAllCheckbox = document.getElementById(selectAllId);
    const rowCheckboxes = document.querySelectorAll(rowCheckboxClass);
    const bulkActionBtn = document.getElementById(bulkActionBtnId);

    if (!selectAllCheckbox || !bulkActionBtn) return;

    function toggleBulkDeleteButton() {
        const anyChecked = document.querySelectorAll(`${rowCheckboxClass}:checked`).length > 0;
        bulkActionBtn.classList.toggle('d-none', !anyChecked);
    }

    selectAllCheckbox.addEventListener('change', () => {
        rowCheckboxes.forEach(checkbox => checkbox.checked = selectAllCheckbox.checked);
        toggleBulkDeleteButton();
    });

    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            selectAllCheckbox.checked = rowCheckboxes.length === document.querySelectorAll(`${rowCheckboxClass}:checked`).length;
            toggleBulkDeleteButton();
        });
    });
}
