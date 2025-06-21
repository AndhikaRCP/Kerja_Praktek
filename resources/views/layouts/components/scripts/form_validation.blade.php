<script>
    document.querySelectorAll('input[required], select[required]').forEach(field => {
        field.oninvalid = function(e) {
            e.target.setCustomValidity('');
            if (!e.target.value) {
                e.target.setCustomValidity('Harap isi kolom ini');
            }
            if (e.target.type === 'number' && parseFloat(e.target.value) < 0) {
                e.target.setCustomValidity('tidak boleh negatif');
            }
        };
    });
</script>
