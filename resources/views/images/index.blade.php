@extends('layouts.app')

@section('title', 'Upload Images')

@section('content')
<h1>Upload Images (Max 5)</h1>

<form id="uploadForm" enctype="multipart/form-data">
    @csrf
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
        const card = document.createElement('div');
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
        
        card.appendChild(img);
        card.appendChild(editBtn);
        imageList.appendChild(card);
    });
}
</script>
@endsection

