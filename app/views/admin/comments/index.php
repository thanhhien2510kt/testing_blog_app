<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php require APPROOT . '/views/inc/admin_sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Quản lý Bình luận</h1>
            </div>

            <?php flash('comment_message'); ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nội dung</th>
                            <th>Bài viết</th>
                            <th>Tác giả</th>
                            <th>Ngày tạo</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['comments'] as $comment) : ?>
                            <tr>
                                <td><?php echo substr($comment->content, 0, 50); ?>...</td>
                                <td><?php echo $comment->postTitle; ?></td>
                                <td>
                                    <?php echo $comment->author_name ? $comment->author_name : 'User ' . $comment->user_id; ?><br>
                                    <small class="text-muted"><?php echo $comment->author_email; ?></small>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($comment->commentCreated)); ?></td>
                                <td>
                                    <?php if($comment->commentStatus == 'approved') : ?>
                                        <span class="badge bg-success">Approved</span>
                                    <?php elseif($comment->commentStatus == 'pending') : ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php else : ?>
                                        <span class="badge bg-danger">Spam</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($comment->commentStatus == 'pending') : ?>
                                        <a href="<?php echo URLROOT; ?>/admin/approve_comment/<?php echo $comment->commentId; ?>" class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    <?php endif; ?>
                                    <form action="<?php echo URLROOT; ?>/admin/delete_comment/<?php echo $comment->commentId; ?>" method="post" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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
