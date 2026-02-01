<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="container-fluid">
    <div class="row">
         <?php require APPROOT . '/views/inc/admin_sidebar.php'; ?>
         
         <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
             <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Add Post</h1>
                <a href="<?php echo URLROOT; ?>/admin/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
            </div>

            <div class="card card-body bg-light mt-5">
                <form action="<?php echo URLROOT; ?>/admin/add_post" method="post" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="title">Title: <sup>*</sup></label>
                        <input type="text" name="title" class="form-control form-control-lg <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['title']; ?>">
                        <span class="invalid-feedback"><?php echo $data['title_err']; ?></span>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="category_id">Category:</label>
                        <select name="category_id" class="form-control">
                            <?php foreach($data['categories'] as $category) : ?>
                                <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="status">Status:</label>
                        <select name="status" class="form-control">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="thumbnail">Thumbnail Image:</label>
                        <input type="file" name="thumbnail" class="form-control">
                    </div>

                    <div class="form-group mb-3">
                        <label for="content">Content: <sup>*</sup></label>
                        <textarea name="content" class="form-control form-control-lg <?php echo (!empty($data['content_err'])) ? 'is-invalid' : ''; ?>" rows="10"><?php echo $data['content']; ?></textarea>
                        <span class="invalid-feedback"><?php echo $data['content_err']; ?></span>
                    </div>

                    <input type="submit" class="btn btn-success" value="Submit">
                    <button type="button" class="btn btn-secondary ms-2" onclick="window.scrollTo({top: 0, behavior: 'smooth'});">Lên đầu trang</button>
                </form>
            </div>
         </main>
    </div>
</div>

<!-- CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('textarea[name="content"]'))
        .catch(error => {
            console.error(error);
        });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
