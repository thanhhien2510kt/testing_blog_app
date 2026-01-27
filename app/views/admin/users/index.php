<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php require APPROOT . '/views/inc/admin_sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Quản lý Người dùng</h1>
                <a href="<?php echo URLROOT; ?>/admin/add_user" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm Người dùng
                </a>
            </div>

            <?php flash('user_message'); ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Ngày tham gia</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['users'] as $user) : ?>
                            <tr>
                                <td><?php echo $user->id; ?></td>
                                <td><?php echo $user->name; ?></td>
                                <td><?php echo $user->email; ?></td>
                                <td>
                                    <?php if($user->role == 'admin') : ?>
                                        <span class="badge bg-primary">Admin</span>
                                    <?php else : ?>
                                        <span class="badge bg-secondary">User</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($user->created_at)); ?></td>
                                <td>
                                    <div class="d-flex justify-content-end">
                                        <a href="<?php echo URLROOT; ?>/admin/edit_user/<?php echo $user->id; ?>" class="btn btn-sm btn-outline-secondary me-2" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if($user->role != 'admin' || $user->id != $_SESSION['user_id']) : ?>
                                            <form action="<?php echo URLROOT; ?>/admin/delete_user/<?php echo $user->id; ?>" method="post" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
