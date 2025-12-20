</div><!-- End admin-content -->

<script src="js/mdb.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Toggle sidebar on mobile
    function toggleSidebar() {
        document.querySelector('.admin-sidebar').classList.toggle('show');
    }

    // Toast notification function
    function showToast(message, position, type) {
        const alertType = type === 'success' ? 'success' : type === 'danger' ? 'danger' : 'info';
        const alert = document.createElement('div');
        alert.className = `alert alert-${alertType} position-fixed top-0 end-0 m-3`;
        alert.style.zIndex = '9999';
        alert.innerHTML = message;
        document.body.appendChild(alert);

        setTimeout(() => {
            alert.remove();
        }, 3000);
    }

    // Confirm delete
    function confirmDelete(message) {
        return confirm(message || 'Bạn có chắc muốn xóa?');
    }
</script>

</body>

</html>