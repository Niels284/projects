<?php
session_start();
$_SESSION['current_page'] = 'error';

require_once '../header.php';

?>
<main>
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="text-center">
            <h1 class="display-1 fw-bold">404</h1>
            <p class="fs-3"> <span class="text-danger">Opps!</span> Page not found.</p>
            <p class="lead">
                The page you’re looking for doesn’t exist.
            </p>
            <a href="../dashboard" class="btn btn-primary">Go Back</a>
        </div>
    </div>
</main>

<?php require_once '../footer.php'; ?>