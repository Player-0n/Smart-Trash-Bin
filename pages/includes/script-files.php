<!-- CDN JS Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<!-- Vendor JS Files -->
<script src="../../assets/main/vendor/apexcharts/apexcharts.min.js"></script>
<script src="../../assets/main/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/main/vendor/chart.js/chart.umd.js"></script>
<script src="../../assets/main/vendor/echarts/echarts.min.js"></script>
<script src="../../assets/main/vendor/quill/quill.js"></script>
<script src="../../assets/main/vendor/simple-datatables/simple-datatables.js"></script>
<script src="../../assets/main/vendor/tinymce/tinymce.min.js"></script>
<script src="../../assets/main/vendor/php-email-form/validate.js"></script>

<!-- JQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Main JS File -->
<script src="../../assets/main/js/main.js"></script>
<script src="../../assets/global/js/password.js"></script>
<script src="../../assets/main/js/profile-edit.js"></script>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_SESSION["alert-status"]) && $_SESSION["alert-status"] !== ""): ?>
    <script>
        const notification = JSON.parse('<?php echo json_encode(json_decode($_SESSION["alert-status"])); ?>');
        Swal.fire({
            icon: notification.icon || 'info',
            title: notification.title || '',
            text: notification.text || '',
            showConfirmButton: false,
            timer: 3000
        });
    </script>

    <?php unset($_SESSION["alert-status"]); ?>
<?php endif; ?>