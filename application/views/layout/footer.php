</div> <!-- main-content div ends -->
</div> <!-- d-flex wrapper div ends -->

<!-- jQuery & Bootstrap 5 Bundle JS (Ek hi baar load honge) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Global Customer Payment History Loader -->
<script>
function loadCustomerHistory(invoiceOrCustId) {
    const historyBox = document.getElementById('history_content');
    if(!historyBox) return;

    historyBox.innerHTML = '<p class="text-center text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Loading details...</p>';

    fetch('<?= base_url("ManagementController/get_payment_history/") ?>' + invoiceOrCustId)
        .then(response => response.json())
        .then(data => {
            let html = '';
            if(data.history && data.history.length > 0) {
                html += '<ul class="list-group list-group-flush">';
                data.history.forEach(row => {
                    html += `<li class="list-group-item d-flex justify-content-between align-items-center py-2 px-1">
                                <div>
                                    <span class="fw-bold text-success">₹ ${parseFloat(row.amount_paid).toFixed(2)}</span> paid
                                    <br><small class="text-muted"><i class="far fa-calendar-alt me-1"></i>${row.payment_date || ''}</small>
                                </div>
                                <span class="badge bg-light text-secondary border">${row.created_at || 'Recorded'}</span>
                             </li>`;
                });
                html += '</ul>';
            } else {
                html = '<div class="text-center text-muted py-3"><i class="fas fa-info-circle fa-2x mb-2 text-secondary"></i><br>No payment history recorded yet.</div>';
            }
            historyBox.innerHTML = html;
        })
        .catch(err => {
            historyBox.innerHTML = '<div class="text-center text-danger py-3"><i class="fas fa-exclamation-triangle me-1"></i>Unable to load history.</div>';
        });
}
</script>

<!-- Billing / Invoice Dynamic Calculation Script -->
<script>
$(document).ready(function() {
    console.log("Management System Loaded Successfully.");

    // Row add karne ke liye
    $("#add-item-row").click(function() {
        var row = $('#invoice-items tr:first').clone();
        row.find('input').val('');
        row.find('.subtotal').val('0');
        $('#invoice-items').append(row);
    });

    // Row remove karne ke liye
    $(document).on('click', '.remove-row', function() {
        if($('#invoice-items tr').length > 1) {
            $(this).closest('tr').remove();
            calculateTotal();
        }
    });

    // Rate aur Qty change hone par calculate karna
    $(document).on('change keyup', '.qty, .rate, select[name="product_id[]"]', function() {
        var row = $(this).closest('tr');
        var price = row.find('select option:selected').data('price');
        
        // Agar rate empty hai to default price uthao
        if(row.find('.rate').val() == "") {
            row.find('.rate').val(price);
        }

        var qty = row.find('.qty').val() || 0;
        var rate = row.find('.rate').val() || 0;
        var total = qty * rate;
        
        row.find('.subtotal').val(total.toFixed(2));
        calculateTotal();
    });

    function calculateTotal() {
        var grandTotal = 0;
        $('.subtotal').each(function() {
            grandTotal += parseFloat($(this).val()) \vert{}\vert{} 0;         });$('#grand-total').text('₹ ' + grandTotal.toFixed(2));
        $('#total_input').val(grandTotal.toFixed(2));
        
        var paid = $('#paid_amount').val() || 0;
        var due = grandTotal - paid;
        $('#due-total').text('₹ ' + due.toFixed(2));
        $('#due_input').val(due.toFixed(2));
    }

    $('#paid_amount').on('keyup', calculateTotal);
});
</script>

</body>
</html>