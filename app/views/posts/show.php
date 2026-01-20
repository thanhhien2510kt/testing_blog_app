<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="container my-5">
    <a href="<?php echo URLROOT; ?>" class="btn btn-light mb-4"><i class="fa fa-backward"></i> Back</a>
    
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="fw-bold mb-3"><?php echo $data['post']->title; ?></h1>
            
            <div class="d-flex align-items-center text-muted mb-4">
               <span><i class="fas fa-user-circle"></i> <?php echo $data['user']->name; ?></span>
               <span class="mx-2">&bull;</span>
               <span><?php echo date('d/m/Y', strtotime($data['post']->created_at)); ?></span>
            </div>

            <?php if(!empty($data['post']->thumbnail)) : ?>
                <img src="<?php echo URLROOT; ?>/uploads/<?php echo $data['post']->thumbnail; ?>" class="img-fluid rounded mb-4 w-100 object-fit-cover" style="max-height: 400px;" alt="<?php echo $data['post']->title; ?>">
            <?php endif; ?>

            <div class="post-content bg-white p-4 rounded shadow-sm">
                <?php echo nl2br($data['post']->content); ?>
            </div>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
