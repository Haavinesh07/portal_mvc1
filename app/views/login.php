<?php require_once __DIR__ . '/../views/header.php'; ?>

<header class="bg-primary text-white py-5">
    <div class="container">
        <h1 class="display-5 fw-bold mb-1">Login</h1>
        <p class="lead mb-0">Access the PSP Student Portal</p>
    </div>
</header>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h2 class="h4 text-center mb-4">Student Login</h2>

                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                        <?php endif; ?>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form method="post" action="index.php?action=login">
                            <div class="mb-3">
                                <label for="username" class="form-label">NRIC</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Example: 010515084455" pattern="[0-9]{12}" maxlength="12" required>
                                <div class="form-text">Use the 12-digit NRIC registered in the database.</div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    autocomplete="current-password" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Login</button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <p class="text-center mb-0">
                            New student?
                            <a href="index.php?action=register" class="text-decoration-none">Register here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../views/footer.php'; ?>
