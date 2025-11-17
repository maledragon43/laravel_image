

<?php $__env->startSection('content'); ?>
<div style="padding: 20px; max-width: 1200px; margin: 0 auto;">
    <h1>Image Storage Verification</h1>
    <p style="margin-bottom: 30px; color: #666;">
        This page shows all images stored on the server. Modified images are saved in the <strong>working</strong> directory.
    </p>

    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 30px;">
        <h2 style="margin-top: 0;">Summary</h2>
        <ul style="list-style: none; padding: 0;">
            <li><strong>Working Images (Modified):</strong> <?php echo e($totals['working']); ?></li>
            <li><strong>Original Images:</strong> <?php echo e($totals['originals']); ?></li>
            <li><strong>History Files (for Undo):</strong> <?php echo e($totals['history']); ?></li>
            <li><strong>Final Saved Images:</strong> <?php echo e($totals['final']); ?></li>
        </ul>
    </div>

    <!-- Working Images (Modified) -->
    <div style="margin-bottom: 40px;">
        <h2>Working Images (Modified/Cropped/Rotated)</h2>
        <p style="color: #666; margin-bottom: 15px;">
            These are the current modified versions of your images. When you rotate or crop, the changes are saved here.
        </p>
        <?php if(count($storageInfo['working']) > 0): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                <?php $__currentLoopData = $storageInfo['working']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="border: 1px solid #ddd; border-radius: 5px; padding: 15px; background: white;">
                        <img src="<?php echo e($image['url']); ?>?t=<?php echo e(time()); ?>" alt="Working Image" 
                             style="width: 100%; height: auto; border-radius: 5px; margin-bottom: 10px;">
                        <div style="font-size: 12px; color: #666;">
                            <p><strong>File:</strong> <?php echo e(basename($image['path'])); ?></p>
                            <p><strong>Size:</strong> <?php echo e(number_format($image['size'] / 1024, 2)); ?> KB</p>
                            <p><strong>Last Modified:</strong> <?php echo e($image['last_modified_formatted']); ?></p>
                            <p><strong>Path:</strong> <code style="font-size: 10px;"><?php echo e($image['path']); ?></code></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <p style="color: #999; font-style: italic;">No working images found.</p>
        <?php endif; ?>
    </div>

    <!-- Original Images -->
    <div style="margin-bottom: 40px;">
        <h2>Original Images</h2>
        <p style="color: #666; margin-bottom: 15px;">
            These are the original uploaded images (unchanged).
        </p>
        <?php if(count($storageInfo['originals']) > 0): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                <?php $__currentLoopData = $storageInfo['originals']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="border: 1px solid #ddd; border-radius: 5px; padding: 15px; background: white;">
                        <img src="<?php echo e($image['url']); ?>?t=<?php echo e(time()); ?>" alt="Original Image" 
                             style="width: 100%; height: auto; border-radius: 5px; margin-bottom: 10px;">
                        <div style="font-size: 12px; color: #666;">
                            <p><strong>File:</strong> <?php echo e(basename($image['path'])); ?></p>
                            <p><strong>Size:</strong> <?php echo e(number_format($image['size'] / 1024, 2)); ?> KB</p>
                            <p><strong>Last Modified:</strong> <?php echo e($image['last_modified_formatted']); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <p style="color: #999; font-style: italic;">No original images found.</p>
        <?php endif; ?>
    </div>

    <!-- History Files -->
    <?php if(count($storageInfo['history']) > 0): ?>
    <div style="margin-bottom: 40px;">
        <h2>History Files (for Undo)</h2>
        <p style="color: #666; margin-bottom: 15px;">
            These are backup copies created before each edit operation (rotate/crop) to enable undo functionality.
        </p>
        <p style="color: #999; font-size: 14px;">
            <strong>Total:</strong> <?php echo e($totals['history']); ?> history files
        </p>
    </div>
    <?php endif; ?>

    <!-- Final Images -->
    <?php if(count($storageInfo['final']) > 0): ?>
    <div style="margin-bottom: 40px;">
        <h2>Final Saved Images</h2>
        <p style="color: #666; margin-bottom: 15px;">
            These are images that were explicitly saved (if save functionality was used).
        </p>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            <?php $__currentLoopData = $storageInfo['final']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="border: 1px solid #ddd; border-radius: 5px; padding: 15px; background: white;">
                    <img src="<?php echo e($image['url']); ?>?t=<?php echo e(time()); ?>" alt="Final Image" 
                         style="width: 100%; height: auto; border-radius: 5px; margin-bottom: 10px;">
                    <div style="font-size: 12px; color: #666;">
                        <p><strong>File:</strong> <?php echo e(basename($image['path'])); ?></p>
                        <p><strong>Size:</strong> <?php echo e(number_format($image['size'] / 1024, 2)); ?> KB</p>
                        <p><strong>Last Modified:</strong> <?php echo e($image['last_modified_formatted']); ?></p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd;">
        <a href="<?php echo e(route('images.index')); ?>" style="display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;">
            ← Back to Upload Page
        </a>
        <button onclick="location.reload()" style="margin-left: 10px; padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
            🔄 Refresh
        </button>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\XAMPP\htdocs\laravel_image\resources\views/images/verify.blade.php ENDPATH**/ ?>