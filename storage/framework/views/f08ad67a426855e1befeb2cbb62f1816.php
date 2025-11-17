

<?php $__env->startSection('title', 'Edit Image'); ?>

<?php $__env->startSection('content'); ?>
<h1>Edit Image</h1>

<div style="margin-bottom: 20px;">
    <button id="rotateBtn" class="btn btn-primary">Rotate 90°</button>
    <button id="cropBtn" class="btn btn-success">Enable Crop</button>
    <button id="undoBtn" class="btn btn-secondary" disabled>Undo</button>
    <button id="saveBtn" class="btn btn-success">Save Image</button>
    <a href="/" class="btn btn-secondary">Back to Upload</a>
</div>

<div id="imageContainer" style="position: relative; display: inline-block; border: 2px solid #ddd; padding: 10px; background: #f9f9f9; border-radius: 4px;">
    <img id="editableImage" src="<?php echo e($imageUrl); ?>" alt="Image to edit" style="max-width: 100%; height: auto; display: block;">
    <div id="cropOverlay" style="display: none; position: absolute; border: 2px dashed #007bff; background: rgba(0,123,255,0.1); pointer-events: none;"></div>
</div>

<div id="message" style="margin-top: 20px;"></div>

<?php $__env->startSection('styles'); ?>
<style>
    #cropOverlay {
        box-sizing: border-box;
    }
    .crop-active {
        cursor: crosshair;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
const imageId = '<?php echo e($imageId); ?>';
let isCropping = false;
let cropStartX = 0;
let cropStartY = 0;
let cropOverlay = document.getElementById('cropOverlay');
let image = document.getElementById('editableImage');
let imageContainer = document.getElementById('imageContainer');
let canUndo = false;

// Rotate button
document.getElementById('rotateBtn').addEventListener('click', async function() {
    try {
        const response = await fetch(`/images/${imageId}/rotate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || 
                               '<?php echo e(csrf_token()); ?>'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            image.src = data.url;
            canUndo = data.canUndo;
            document.getElementById('undoBtn').disabled = !canUndo;
            showMessage('Image rotated successfully', 'success');
        } else {
            showMessage('Rotation failed: ' + (data.message || 'Unknown error'), 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showMessage('Rotation failed. Please try again.', 'error');
    }
});

// Crop button
document.getElementById('cropBtn').addEventListener('click', function() {
    isCropping = !isCropping;
    if (isCropping) {
        imageContainer.classList.add('crop-active');
        cropOverlay.style.display = 'block';
        this.textContent = 'Cancel Crop';
        this.classList.remove('btn-success');
        this.classList.add('btn-danger');
    } else {
        imageContainer.classList.remove('crop-active');
        cropOverlay.style.display = 'none';
        this.textContent = 'Enable Crop';
        this.classList.remove('btn-danger');
        this.classList.add('btn-success');
        resetCropOverlay();
    }
});

// Mouse events for cropping
let isDragging = false;

imageContainer.addEventListener('mousedown', function(e) {
    if (!isCropping) return;
    
    isDragging = true;
    const rect = imageContainer.getBoundingClientRect();
    cropStartX = e.clientX - rect.left;
    cropStartY = e.clientY - rect.top;
    
    cropOverlay.style.left = cropStartX + 'px';
    cropOverlay.style.top = cropStartY + 'px';
    cropOverlay.style.width = '0px';
    cropOverlay.style.height = '0px';
});

imageContainer.addEventListener('mousemove', function(e) {
    if (!isCropping || !isDragging) return;
    
    const rect = imageContainer.getBoundingClientRect();
    const currentX = e.clientX - rect.left;
    const currentY = e.clientY - rect.top;
    
    const width = Math.abs(currentX - cropStartX);
    const height = Math.abs(currentY - cropStartY);
    
    cropOverlay.style.left = Math.min(cropStartX, currentX) + 'px';
    cropOverlay.style.top = Math.min(cropStartY, currentY) + 'px';
    cropOverlay.style.width = width + 'px';
    cropOverlay.style.height = height + 'px';
});

imageContainer.addEventListener('mouseup', async function(e) {
    if (!isCropping || !isDragging) return;
    
    isDragging = false;
    
    const rect = imageContainer.getBoundingClientRect();
    const imageRect = image.getBoundingClientRect();
    
    // Calculate crop coordinates relative to the actual image
    const scaleX = image.naturalWidth / imageRect.width;
    const scaleY = image.naturalHeight / imageRect.height;
    
    const overlayRect = cropOverlay.getBoundingClientRect();
    const relativeX = (overlayRect.left - imageRect.left) * scaleX;
    const relativeY = (overlayRect.top - imageRect.top) * scaleY;
    const relativeWidth = overlayRect.width * scaleX;
    const relativeHeight = overlayRect.height * scaleY;
    
    // Apply crop
    try {
        const response = await fetch(`/images/${imageId}/crop`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || 
                               '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({
                x: Math.max(0, relativeX),
                y: Math.max(0, relativeY),
                width: Math.min(relativeWidth, image.naturalWidth - Math.max(0, relativeX)),
                height: Math.min(relativeHeight, image.naturalHeight - Math.max(0, relativeY))
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            image.src = data.url;
            canUndo = data.canUndo;
            document.getElementById('undoBtn').disabled = !canUndo;
            showMessage('Image cropped successfully', 'success');
            
            // Reset crop mode
            isCropping = false;
            imageContainer.classList.remove('crop-active');
            cropOverlay.style.display = 'none';
            document.getElementById('cropBtn').textContent = 'Enable Crop';
            document.getElementById('cropBtn').classList.remove('btn-danger');
            document.getElementById('cropBtn').classList.add('btn-success');
            resetCropOverlay();
        } else {
            showMessage('Crop failed: ' + (data.message || 'Unknown error'), 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showMessage('Crop failed. Please try again.', 'error');
    }
});

// Undo button
document.getElementById('undoBtn').addEventListener('click', async function() {
    if (!canUndo) return;
    
    try {
        const response = await fetch(`/images/${imageId}/undo`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || 
                               '<?php echo e(csrf_token()); ?>'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            image.src = data.url;
            canUndo = data.canUndo;
            document.getElementById('undoBtn').disabled = !canUndo;
            showMessage('Undo successful', 'success');
        } else {
            showMessage('Undo failed: ' + (data.message || 'Unknown error'), 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showMessage('Undo failed. Please try again.', 'error');
    }
});

// Save button
document.getElementById('saveBtn').addEventListener('click', async function() {
    try {
        const response = await fetch(`/images/${imageId}/save`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || 
                               '<?php echo e(csrf_token()); ?>'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showMessage('Image saved successfully!', 'success');
            setTimeout(() => {
                window.location.href = '/';
            }, 2000);
        } else {
            showMessage('Save failed: ' + (data.message || 'Unknown error'), 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showMessage('Save failed. Please try again.', 'error');
    }
});

function resetCropOverlay() {
    cropOverlay.style.left = '0px';
    cropOverlay.style.top = '0px';
    cropOverlay.style.width = '0px';
    cropOverlay.style.height = '0px';
}

function showMessage(message, type) {
    const messageDiv = document.getElementById('message');
    messageDiv.textContent = message;
    messageDiv.className = 'alert alert-' + (type === 'success' ? 'success' : 'error');
    messageDiv.style.display = 'block';
    
    setTimeout(() => {
        messageDiv.style.display = 'none';
    }, 3000);
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\XAMPP\htdocs\laravel_image\resources\views/images/edit.blade.php ENDPATH**/ ?>