<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php require APPROOT . '/views/inc/admin_sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 bg-light">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
                <h1 class="h3 fw-bold">Quản lý Chủ đề & Tag</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button class="btn btn-sm btn-outline-secondary bg-white">
                        <i class="fas fa-sync-alt"></i> Đồng bộ
                    </button>
                </div>
            </div>

            <?php flash('category_message'); ?>

            <div class="row">
                <!-- Categories Column -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">Danh mục (Categories) <span class="badge bg-light text-dark rounded-pill"><?php echo count($data['categories']); ?> items</span></h5>
                        </div>
                        <div class="card-body">
                            <!-- Helper Form for Quick Add (Optional, keeping Simple) -->
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Tên danh mục mới..." disabled title="Use 'Add' button for now">
                                <a href="<?php echo URLROOT; ?>/admin/add_category" class="btn btn-primary fw-bold">+ Thêm</a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-uppercase small text-muted">Tên</th>
                                            <th class="text-uppercase small text-muted">Slug</th>
                                            <th class="text-uppercase small text-muted text-center">Bài viết</th>
                                            <th class="text-uppercase small text-muted text-end">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($data['categories'])): ?>
                                            <tr><td colspan="4" class="text-center text-muted">Chưa có danh mục nào</td></tr>
                                        <?php else: ?>
                                            <?php foreach($data['categories'] as $category) : ?>
                                                <tr>
                                                    <td class="fw-bold"><?php echo $category->name; ?></td>
                                                    <td class="text-muted small"><?php echo $category->slug; ?></td>
                                                    <td class="text-center">
                                                        <span class="badge bg-light text-secondary rounded-pill"><?php echo $category->post_count; ?></span>
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="<?php echo URLROOT; ?>/admin/edit_category/<?php echo $category->id; ?>" class="text-secondary me-2">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </a>
                                                        <!-- Delete form hidden/icon only for cleaner look -->
                                                        <form action="<?php echo URLROOT; ?>/admin/delete_category/<?php echo $category->id; ?>" method="post" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                                            <button type="submit" class="btn btn-link text-danger p-0 border-0">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tags Column -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                         <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">Thẻ (Tags) <span class="badge bg-light text-dark rounded-pill">Top 10</span></h5>
                        </div>
                        <div class="card-body">
                             <!-- Quick Add Tag Form -->
                             <form action="<?php echo URLROOT; ?>/admin/add_tag" method="post" class="d-flex mb-3">
                                <input type="text" name="name" class="form-control me-2" placeholder="Tên thẻ mới..." required>
                                <button type="submit" class="btn btn-primary fw-bold">+ Thêm</button>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-uppercase small text-muted">Tag</th>
                                            <th class="text-uppercase small text-muted text-center">Count</th>
                                            <th class="text-uppercase small text-muted text-end">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($data['tags'])): ?>
                                             <tr><td colspan="3" class="text-center text-muted">Chưa có thẻ nào</td></tr>
                                        <?php else: ?>
                                            <?php foreach($data['tags'] as $tag) : ?>
                                                <tr>
                                                    <td class="fw-bold text-primary">#<?php echo $tag->name; ?></td>
                                                    <td class="text-center">
                                                        <span class="badge bg-light text-secondary rounded-pill"><?php echo $tag->post_count; ?></span>
                                                    </td>
                                                    <td class="text-end">
                                                        <form action="<?php echo URLROOT; ?>/admin/delete_tag/<?php echo $tag->id; ?>" method="post" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tag này?');">
                                                            <button type="submit" class="btn btn-link text-secondary p-0 border-0">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
