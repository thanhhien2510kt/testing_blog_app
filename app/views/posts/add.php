<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2>Create Post</h2>
                <p>Create a post with this form</p>
                <form action="<?php echo URLROOT; ?>/posts/add" method="post">
                    <div class="form-group mb-3">
                        <label for="title">Title: <sup>*</sup></label>
                        <input type="text" name="title" class="form-control form-control-lg <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['title']; ?>">
                        <span class="invalid-feedback"><?php echo $data['title_err']; ?></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="category_id">Category:</label>
                        <select name="category_id" class="form-control form-control-lg">
                            <?php if(isset($data['categories'])) : ?>
                                <?php foreach($data['categories'] as $category) : ?>
                                    <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="content">Body: <sup>*</sup></label>
                        <textarea name="content" class="form-control form-control-lg <?php echo (!empty($data['content_err'])) ? 'is-invalid' : ''; ?>" rows="10"><?php echo $data['content']; ?></textarea>
                        <span class="invalid-feedback"><?php echo $data['content_err']; ?></span>
                    </div>
                    <input type="submit" class="btn btn-success" value="Submit">
                </form>
            </div>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
