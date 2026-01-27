<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php require APPROOT . '/views/inc/admin_sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 bg-light">
            
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/admin" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Danh sách bài viết</li>
                </ol>
            </nav>

            <!-- Header & Add Button -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold">Tất cả bài viết</h1>
                <a href="<?php echo URLROOT; ?>/admin/add_post" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tạo bài viết mới
                </a>
            </div>

            <?php flash('post_message'); ?>

            <!-- Filters Bar -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
            <!-- Filters Bar -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <!-- Main Filter Form (declarative) -->
                    <form id="filterForm" action="<?php echo URLROOT; ?>/admin/posts" method="GET"></form>

                    <div class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <div class="input-group">
                                <button class="btn btn-light border border-end-0" type="submit" form="filterForm"><i class="fas fa-search text-muted"></i></button>
                                <input type="text" name="search" form="filterForm" class="form-control border-start-0 ps-0" placeholder="Tìm kiếm theo tiêu đề..." value="<?php echo isset($data['filters']['search']) ? $data['filters']['search'] : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="category" form="filterForm" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Tất cả chủ đề</option>
                                <?php if(isset($data['categories'])) : ?>
                                    <?php foreach($data['categories'] as $category) : ?>
                                        <option value="<?php echo $category->id; ?>" <?php echo (isset($data['filters']['category_id']) && $data['filters']['category_id'] == $category->id) ? 'selected' : ''; ?>><?php echo $category->name; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" form="filterForm" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Tất cả trạng thái</option>
                                <option value="published" <?php echo (isset($data['filters']['status']) && $data['filters']['status'] == 'published') ? 'selected' : ''; ?>>Published</option>
                                <option value="draft" <?php echo (isset($data['filters']['status']) && $data['filters']['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                            </select>
                        </div>
                        <div class="col-md-3 text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <!-- Import Form -->
                                <form action="<?php echo URLROOT; ?>/admin/import_posts" method="POST" enctype="multipart/form-data">
                                    <input type="file" name="import_file" id="importFile" class="d-none" accept=".csv" onchange="this.form.submit()">
                                    <label for="importFile" class="btn btn-outline-success" title="Import từ CSV">
                                        <i class="fas fa-file-upload"></i> Import
                                    </label>
                                </form>

                                <!-- Export Button linked to filterForm -->
                                <button type="submit" form="filterForm" name="action" value="export" class="btn btn-outline-secondary" title="Export ra CSV">
                                    <i class="fas fa-download"></i> Export
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                </div>
            </div>

            <!-- Posts Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted sticky-top">
                                <tr>
                                    <th style="width: 50px;" class="text-center">
                                        <input class="form-check-input" type="checkbox">
                                    </th>
                                    <th style="width: 40%;">Bài viết</th>
                                    <th>Chủ đề</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày cập nhật</th>
                                    <th class="text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($data['posts'])): ?>
                                    <tr><td colspan="6" class="text-center py-5 text-muted">Chưa có bài viết nào</td></tr>
                                <?php else: ?>
                                    <?php foreach($data['posts'] as $post) : ?>
                                        <tr>
                                            <td class="text-center">
                                                <input class="form-check-input" type="checkbox">
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <a href="<?php echo URLROOT; ?>/admin/edit_post/<?php echo $post->postId; ?>" class="fw-bold text-dark text-decoration-none mb-1 text-truncate" style="max-width: 350px;">
                                                        <?php echo $post->title; ?>
                                                    </a>
                                                    <small class="text-muted text-truncate" style="max-width: 350px;">
                                                        <?php echo substr(strip_tags($post->content), 0, 60); ?>...
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-primary small">
                                                    <?php echo !empty($post->categoryName) ? $post->categoryName : 'Chưa phân loại'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if($post->status == 'published') : ?>
                                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Published</span>
                                                <?php else : ?>
                                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">Draft</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-muted">
                                                <?php echo date('d/m/Y', strtotime($post->postCreated)); ?>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo URLROOT; ?>/admin/edit_post/<?php echo $post->postId; ?>" class="btn btn-sm btn-outline-secondary" title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="<?php echo URLROOT; ?>/posts/show/<?php echo $post->postId; ?>" class="btn btn-sm btn-outline-secondary" title="Xem" target="_blank">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form action="<?php echo URLROOT; ?>/admin/delete_post/<?php echo $post->postId; ?>" method="post" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary border-start-0" title="Xóa">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
