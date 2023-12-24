<script>
    function logoutConfirmation() {
        Swal.fire({
            title: "Logout",
            text: "Are you sure to logout?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Logout now"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Success!',
                    'You\'re successfully logged out.',
                    'success'
                ).then(function() {
                    location.href = '../logout.php';
                })
            }
        });
    }
</script>