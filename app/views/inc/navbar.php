<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="<?php echo URLROOT; ?>">
      <i class="fas fa-bug fa-lg me-2"></i> QA Insider
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo URLROOT; ?>">Trang chủ</a>
        </li>
        <?php if(isset($data['nav_categories'])) : ?>
            <?php foreach($data['nav_categories'] as $category) : ?>
                <?php if(!empty($category->children)) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown<?php echo $category->id; ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $category->name; ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown<?php echo $category->id; ?>">
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/pages/category/<?php echo $category->slug; ?>">Tất cả <?php echo $category->name; ?></a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php foreach($category->children as $child) : ?>
                                <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/pages/category/<?php echo $child->slug; ?>"><?php echo $child->name; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/pages/category/<?php echo $category->slug; ?>"><?php echo trim(str_replace('Testing', '', $category->name)); ?></a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
      </ul>
      
      <div class="d-flex align-items-center">
        <form action="<?php echo URLROOT; ?>/pages/search" method="get" class="d-flex me-3">
            <div class="input-group input-group-sm">
                <input type="text" name="q" class="form-control rounded-start" placeholder="Tìm kiếm..." aria-label="Search">
                <button class="btn btn-outline-secondary rounded-end" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <?php if(isset($_SESSION['user_id'])) : ?>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle rounded-pill" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user text-primary"></i> <?php echo $_SESSION['user_name']; ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/admin/index">Dashboard</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/users/logout">Đăng xuất</a></li>
                </ul>
            </div>
        <?php else : ?>
            <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-primary btn-sm px-3 rounded-pill">Đăng nhập</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
