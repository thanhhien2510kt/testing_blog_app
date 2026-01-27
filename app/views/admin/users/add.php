<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php require APPROOT . '/views/inc/admin_sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Thêm Người dùng</h1>
                <a href="<?php echo URLROOT; ?>/admin/users" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="<?php echo URLROOT; ?>/admin/add_user" method="post">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên: <sup>*</sup></label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
                            <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email: <sup>*</sup></label>
                            <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
                            <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu: <sup>*</sup></label>
                            <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
                            <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                        </div>
                         <div class="mb-3">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu: <sup>*</sup></label>
                            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password']; ?>">
                            <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
                        </div>
                         <div class="mb-3">
                            <label for="role" class="form-label">Vai trò: <sup>*</sup></label>
                            <select name="role" class="form-select">
                                <option value="user" <?php echo ($data['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                                <option value="admin" <?php echo ($data['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Thêm Người dùng</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
