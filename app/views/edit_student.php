<?php require_once __DIR__ . '/../views/header.php'; ?>

<header class="bg-primary text-white py-5">
    <div class="container">
        <h1 class="display-5 fw-bold mb-1">Edit Student Record</h1>
        <p class="lead mb-0">Update the selected student marks record</p>
    </div>
</header>

<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form method="post" action="index.php?action=edit_student&id=<?php echo (int) $student['id']; ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="<?php echo htmlspecialchars($student['name']); ?>" maxlength="100" required>
                            </div>

                            <div class="mb-3">
                                <label for="ic" class="form-label">IC</label>
                                <input type="text" class="form-control" id="ic" name="ic"
                                    value="<?php echo htmlspecialchars($student['ic']); ?>" pattern="[0-9]{12}"
                                    maxlength="12" required>
                            </div>

                            <div class="mb-4">
                                <label for="marks" class="form-label">Marks</label>
                                <input type="number" class="form-control" id="marks" name="marks"
                                    value="<?php echo (int) $student['marks']; ?>" min="0" max="100" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning btn-lg fw-semibold">Save Changes</button>
                                <a href="index.php?action=dashboard" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../views/footer.php'; ?>
