<?php require APPROOT . '/views/inc/header.php'; ?>

<main class="container my-5">
    <!-- Hero/Banner Section (Placeholder based on design) -->
    <div class="jumbotron p-5 mb-4 bg-dark text-white rounded bg-banner" style="min-height: 400px; display: flex; align-items: flex-end; background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.8)), url('<?php echo URLROOT; ?>/assets/images/banner_main.jpg') no-repeat center/cover;">
        <div>
            <span class="badge bg-primary mb-2">NỔI BẬT</span>
            <h1 class="display-4 fw-bold">Testing Blog</h1>
            <p class="lead"><i class="fas fa-user-circle"></i> Rosy Dương &bull; 20 Tháng 1, 2026 &bull; 8 phút đọc</p>
        </div>
    </div>

    <!-- Tags/Filters -->
    <div class="d-flex gap-2 flex-wrap mb-5 justify-content-center">
        <a href="<?php echo URLROOT; ?>" class="btn btn-dark rounded-pill px-4">Tất cả</a>
        <?php if(isset($data['nav_categories'])) : ?>
            <?php foreach($data['nav_categories'] as $category) : ?>
                <?php 
                    $displayName = trim(str_replace('Testing', '', $category->name));
                ?>
                <?php if(!empty($category->children)) : ?>
                    <div class="btn-group">
                        <button type="button" class="btn btn-light rounded-pill px-4 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $displayName; ?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/pages/category/<?php echo $category->slug; ?>">Tất cả <?php echo $displayName; ?></a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php foreach($category->children as $child) : ?>
                                <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/pages/category/<?php echo $child->slug; ?>"><?php echo $child->name; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php else : ?>
                    <a href="<?php echo URLROOT; ?>/pages/category/<?php echo $category->slug; ?>" class="btn btn-light rounded-pill px-4"><?php echo $displayName; ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-12">
            <h3 class="mb-4 fw-bold">Bài viết mới nhất</h3>
            
            <?php foreach($data['posts'] as $post) : ?>
                <div class="card border-0 mb-4">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <?php if(!empty($post->thumbnail)) : ?>
                                <img src="<?php echo URLROOT; ?>/uploads/<?php echo $post->thumbnail; ?>" class="img-fluid rounded-start h-100 object-fit-cover" alt="<?php echo $post->title; ?>" style="min-height: 200px;">
                            <?php else : ?>
                                <div class="bg-secondary h-100 rounded text-center d-flex align-items-center justify-content-center text-white" style="min-height: 200px;">
                                    <span>No Image</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <small class="text-primary fw-bold"><?php echo isset($post->categoryName) ? $post->categoryName : 'Blog'; ?></small>
                                <h4 class="card-title fw-bold mt-1">
                                    <a href="<?php echo URLROOT; ?>/posts/show/<?php echo $post->postId; ?>" class="text-decoration-none text-dark">
                                        <?php echo $post->title; ?>
                                    </a>
                                </h4>
                                <p class="card-text text-muted"><?php echo substr(strip_tags($post->content), 0, 150); ?>...</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="card-text mb-0"><small class="text-muted"><i class="fas fa-user-circle"></i> <?php echo $post->userName; ?> &bull; <?php echo date('d/m/Y', strtotime($post->postCreated)); ?></small></p>
                                    <a href="<?php echo URLROOT; ?>/posts/show/<?php echo $post->postId; ?>" class="btn btn-sm btn-outline-primary rounded-pill">Đọc thêm</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Pagination -->
            <?php if(isset($data['totalPages']) && $data['totalPages'] > 1) : ?>
                <nav aria-label="Page navigation" class="mt-5">
                    <ul class="pagination justify-content-center">
                        <?php for($i = 1; $i <= $data['totalPages']; $i++) : ?>
                            <li class="page-item <?php echo ($data['currentPage'] == $i) ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo URLROOT; ?>?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php require APPROOT . '/views/inc/footer.php'; ?>
