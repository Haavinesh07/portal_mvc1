<?php require_once __DIR__ . '/../views/header.php'; ?>

<header class="bg-primary text-white py-5">
    <div class="container">
        <h1 class="display-5 fw-bold mb-1">Register</h1>
        <p class="lead mb-0">Create a secure student portal account</p>
    </div>
</header>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h2 class="h4 text-center mb-4">Student Registration</h2>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form method="post" action="index.php?action=register">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" maxlength="100" required>
                            </div>

                            <div class="mb-3">
                                <label for="ic" class="form-label">NRIC</label>
                                <input type="text" class="form-control" id="ic" name="ic"
                                    value="<?php echo htmlspecialchars($_POST['ic'] ?? ''); ?>" pattern="[0-9]{12}"
                                    maxlength="12" required>
                                <div class="form-text">Enter 12 digits without dashes.</div>
                            </div>

                            <div class="mb-3">
                                <label for="program" class="form-label">Program</label>
                                <select class="form-select" id="program" name="program" required>
                                    <option value="" disabled <?php echo empty($_POST['program']) ? 'selected' : ''; ?>>Select your program</option>
                                    <option value="JTMK" <?php echo (($_POST['program'] ?? '') === 'JTMK') ? 'selected' : ''; ?>>Jabatan Teknologi Maklumat & Komunikasi</option>
                                    <option value="JKE" <?php echo (($_POST['program'] ?? '') === 'JKE') ? 'selected' : ''; ?>>Jabatan Kejuruteraan Elektrik</option>
                                    <option value="JKM" <?php echo (($_POST['program'] ?? '') === 'JKM') ? 'selected' : ''; ?>>Jabatan Kejuruteraan Mekanikal</option>
                                    <option value="JP" <?php echo (($_POST['program'] ?? '') === 'JP') ? 'selected' : ''; ?>>Jabatan Perdagangan</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    minlength="8" autocomplete="new-password" required>
                            </div>

                            <div class="mb-4">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                    minlength="8" autocomplete="new-password" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Register</button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <p class="text-center mb-0">
                            Already have an account?
                            <a href="index.php?action=login" class="text-decoration-none">Login here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../views/footer.php'; ?>
