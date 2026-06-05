// Image preview for journey form
function previewImage(input) {
    const previewContainer = document.getElementById('imagePreviewContainer');
    const previewImg = document.getElementById('imagePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const btnRemove = document.getElementById('btnRemoveImage');

    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Cek ukuran (opsional) - contoh max 5MB
        if(file.size > 5 * 1024 * 1024){
            alert("Ukuran file terlalu besar. Maksimal 5MB.");
            input.value = "";
            return;
        }

        const reader = new FileReader();

        reader.onload = function(e) {
            previewImg.src = e.target.result;
            fileName.textContent = file.name;
            
            // Format file size
            let size = (file.size / 1024).toFixed(1) + ' KB';
            if (file.size > 1024 * 1024) {
                size = (file.size / (1024 * 1024)).toFixed(1) + ' MB';
            }
            fileSize.textContent = size;
            
            previewContainer.classList.remove('d-none');
            if(btnRemove) btnRemove.classList.remove('d-none');
        }

        reader.readAsDataURL(file);
    } else {
        previewContainer.classList.add('d-none');
    }
}

function removeImage() {
    const input = document.getElementById('foto');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const btnRemove = document.getElementById('btnRemoveImage');
    
    input.value = '';
    previewContainer.classList.add('d-none');
    if (btnRemove) {
        btnRemove.classList.add('d-none');
    }
}
