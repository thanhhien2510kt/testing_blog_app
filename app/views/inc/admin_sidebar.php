<div class="col-md-3 col-lg-2 d-md-block bg-white sidebar border-end collapse" id="sidebarMenu">
    <div class="position-sticky pt-3 d-flex flex-column justify-content-between" style="height: calc(100vh - 60px);">
        <ul class="nav flex-column">
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                <span>Dashboard</span>
            </h6>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="<?php echo URLROOT; ?>/admin">
                    <i class="fas fa-th-large me-2"></i> Tổng quan
                </a>
            </li>
            
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                <span>Content</span>
            </h6>
            <li class="nav-item">
                <a class="nav-link text-dark" href="<?php echo URLROOT; ?>/admin/posts">
                    <i class="fas fa-file-alt me-2"></i> Bài viết
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="<?php echo URLROOT; ?>/admin/categories">
                    <i class="fas fa-tags me-2"></i> Chủ đề & Tag
                </a>
            </li>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                <span>Community</span>
            </h6>
            <li class="nav-item">
                <a class="nav-link text-dark" href="<?php echo URLROOT; ?>/admin/users">
                    <i class="fas fa-users me-2"></i> Người dùng
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="<?php echo URLROOT; ?>/admin/comments">
                    <i class="fas fa-comments me-2"></i> Bình luận
                </a>
            </li>
        </ul>

        <ul class="nav flex-column mt-auto mb-3">
            <li class="nav-item border-top pt-3">
                 <a class="nav-link text-danger fw-bold" href="<?php echo URLROOT; ?>/users/logout">
                    <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                </a>
            </li>
        </ul>
    </div>
</div>
