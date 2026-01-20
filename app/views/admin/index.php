<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php require APPROOT . '/views/inc/admin_sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Admin Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="<?php echo URLROOT; ?>/posts/add" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Tạo bài viết
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Stat Card 1 -->
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-muted text-uppercase fs-6">Tổng số bài viết</h5>
                            <h2 class="mt-2 fw-bold"><?php echo $data['postCount']; ?></h2>
                            <span class="text-success small"><i class="fas fa-arrow-up"></i> +12 bài trong 30 ngày</span>
                        </div>
                    </div>
                </div>
                 <!-- Stat Card 2 -->
                 <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-muted text-uppercase fs-6">Lượt xem tháng này</h5>
                            <h2 class="mt-2 fw-bold">24.5K</h2>
                            <span class="text-success small"><i class="fas fa-arrow-up"></i> +18% so với tháng trước</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Placeholder -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">Hoạt động gần đây</h5>
                </div>
                <div class="card-body">
                   <p>User Admin logged in.</p>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
