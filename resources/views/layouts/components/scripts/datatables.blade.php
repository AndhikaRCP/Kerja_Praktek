<script>
    $(document).ready(function () {
        $('#add-row').DataTable({ pageLength: 10 });

        // Buka modal jika ada error validasi
        @if ($errors->any())
            $('#addRowModal').modal('show');
        @endif
    });
</script>
