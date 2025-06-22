<script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    @if (session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: '{{ session('warning') }}'
        });
    @endif

    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan!',
            html: `{!! implode('<br>', $errors->all()) !!}`
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: @json(session('error'))
        });
    @endif

    @if (session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Info',
            text: '{{ session('info') }}'
        });
    @endif

    @if (session('deleted'))
        Swal.fire({
            icon: 'success',
            title: 'Data Dihapus!',
            text: '{{ session('deleted') }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif
</script>
