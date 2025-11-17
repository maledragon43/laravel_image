

<?php $__env->startSection('title', 'Upload Images'); ?>

<?php $__env->startSection('content'); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="margin: 0;">Upload Images (Max 5)</h1>
    <a href="<?php echo e(route('images.verify')); ?>" style="padding: 8px 16px; background: #17a2b8; color: white; text-decoration: none; border-radius: 5px; font-size: 14px;">
        🔍 Verify Stored Images
    </a>
</div>

<form id="uploadForm" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <div style="margin-bottom: 20px;">
        <input type="file" id="imageInput" name="images[]" multiple accept="image/*" style="margin-bottom: 10px;">
        <div id="preview" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; margin-top: 20px;"></div>
    </div>
    <button type="submit" class="btn btn-primary">Upload Images</button>
</form>

<div id="uploadedImages" style="margin-top: 30px;">
    <h2>Uploaded Images</h2>
    <div id="imageList" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;"></div>
</div>

<script>
// Load existing uploaded images on page load
<?php if(isset($uploadedImages) && count($uploadedImages) > 0): ?>
    const existingImages = <?php echo json_encode($uploadedImages, 15, 512) ?>;
    displayUploadedImages(existingImages);
<?php endif; ?>

document.getElementById('uploadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    const files = document.getElementById('imageInput').files;
    
    if (files.length === 0) {
        alert('Please select at least one image');
        return;
    }
    
    if (files.length > 5) {
        alert('Maximum 5 images allowed');
        return;
    }
    
    for (let i = 0; i < files.length; i++) {
        formData.append('images[]', files[i]);
    }
    
    try {
        const response = await fetch('/images/upload', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || 
                               document.querySelector('input[name="_token"]').value
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            displayUploadedImages(data.images);
            document.getElementById('uploadForm').reset();
            document.getElementById('preview').innerHTML = '';
        } else {
            alert('Upload failed: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Upload failed. Please try again.');
    }
});

// Preview images before upload
document.getElementById('imageInput').addEventListener('change', function(e) {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    
    Array.from(e.target.files).slice(0, 5).forEach(file => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100%';
                img.style.height = '150px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '4px';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });
});

function displayUploadedImages(images) {
    const imageList = document.getElementById('imageList');
    
    images.forEach(image => {
        // Check if image already exists to prevent duplicates
        const existingCard = imageList.querySelector(`[data-image-id="${image.id}"]`);
        if (existingCard) {
            return; // Skip if already displayed
        }
        
        const card = document.createElement('div');
        card.setAttribute('data-image-id', image.id);
        card.style.border = '1px solid #ddd';
        card.style.borderRadius = '8px';
        card.style.padding = '10px';
        card.style.textAlign = 'center';
        
        const img = document.createElement('img');
        img.src = image.url;
        img.style.width = '100%';
        img.style.height = '150px';
        img.style.objectFit = 'cover';
        img.style.borderRadius = '4px';
        img.style.marginBottom = '10px';
        
        const editBtn = document.createElement('a');
        editBtn.href = `/images/${image.id}/edit`;
        editBtn.className = 'btn btn-primary';
        editBtn.textContent = 'Edit Image';
        editBtn.style.display = 'block';
        editBtn.style.marginBottom = '5px';
        
        const deleteBtn = document.createElement('button');
        deleteBtn.className = 'btn btn-danger';
        deleteBtn.textContent = 'Delete';
        deleteBtn.style.display = 'block';
        deleteBtn.style.width = '100%';
        deleteBtn.onclick = function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this image?')) {
                deleteImage(image.id, card);
            }
        };
        
        const buttonContainer = document.createElement('div');
        buttonContainer.appendChild(editBtn);
        buttonContainer.appendChild(deleteBtn);
        
        card.appendChild(img);
        card.appendChild(buttonContainer);
        imageList.appendChild(card);
    });
}

async function deleteImage(imageId, cardElement) {
    try {
        const response = await fetch(`/images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || 
                               document.querySelector('input[name="_token"]').value
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Remove the card from the DOM
            cardElement.remove();
            showMessage('Image deleted successfully', 'success');
        } else {
            showMessage('Delete failed: ' + (data.message || 'Unknown error'), 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showMessage('Delete failed. Please try again.', 'error');
    }
}

function showMessage(message, type) {
    // Create or update message element
    let messageDiv = document.getElementById('deleteMessage');
    if (!messageDiv) {
        messageDiv = document.createElement('div');
        messageDiv.id = 'deleteMessage';
        messageDiv.style.marginTop = '20px';
        document.getElementById('uploadedImages').insertBefore(messageDiv, document.getElementById('imageList'));
    }
    
    messageDiv.textContent = message;
    messageDiv.className = 'alert alert-' + (type === 'success' ? 'success' : 'error');
    messageDiv.style.display = 'block';
    
    setTimeout(() => {
        messageDiv.style.display = 'none';
    }, 3000);
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\XAMPP\htdocs\laravel_image\resources\views/images/index.blade.php ENDPATH**/ ?>