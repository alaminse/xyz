<a href="<?php echo e(route('admin.mcqs.sample-download')); ?>" class="btn btn-info">Sample Download</a>

<form action="<?php echo e(route('admin.mcqs.bulk-upload.store', $mcq->id)); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <input type="file" name="file" required accept=".xlsx,.xls,.csv">
    <button type="submit" class="btn btn-success">Upload</button>
</form>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/mcq/bulk-upload.blade.php ENDPATH**/ ?>