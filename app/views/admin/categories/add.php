<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="container-fluid">
    <div class="row">
         <?php require APPROOT . '/views/inc/admin_sidebar.php'; ?>
         
         <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
             <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Add Category</h1>
                <a href="<?php echo URLROOT; ?>/admin/categories" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
            </div>

            <div class="card card-body bg-light mt-5">
                <form action="<?php echo URLROOT; ?>/admin/add_category" method="post">
                    <div class="form-group mb-3">
                        <label for="name">Name: <sup>*</sup></label>
                        <input type="text" name="name" class="form-control form-control-lg <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
                        <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="parent_id">Danh mục cha:</label>
                        <select name="parent_id" class="form-control form-control-lg">
                            <option value="">-- Không có (Danh mục gốc) --</option>
                            <?php if(isset($data['categories'])) : ?>
                                <?php foreach($data['categories'] as $category) : ?>
                                    <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <input type="submit" class="btn btn-success" value="Submit">
                </form>
            </div>
         </main>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
