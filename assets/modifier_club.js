//Script for confirmation form

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form_creation_new_club');
    const btnModifierProfile = document.getElementById('btn_for_creer_club');

    if (btnModifierProfile) {
        btnModifierProfile.addEventListener('click', function(event) {
            event.preventDefault();
            form.submit();
        });
    }
});

//Script FOR PREVIEW AVATAR CLUB


document.addEventListener('DOMContentLoaded', function() {
    const imageUpload = document.getElementById('creer_club_avatarFile_file');
    const imagePreview = document.getElementById('creer_club_photo_size');

    imageUpload.addEventListener('change', function(event) {
        imagePreview.innerHTML = ''; 

        const file = event.target.files[0]; 
        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '100%';
                imagePreview.appendChild(img); 
            };

            reader.readAsDataURL(file); 
        } else {
            imagePreview.innerHTML = '<p>Preview will appear here</p>'; 
        }
    });
});

// SCRIPT FOR PREVIEW BACKGROUND AVATAR CLUB

document.addEventListener('DOMContentLoaded', function() {
    const imageUpload = document.getElementById('creer_club_backGroundAvatarFile_file');
    const imagePreview = document.getElementById('background_photo_profile');

    imageUpload.addEventListener('change', function(event) {
        imagePreview.innerHTML = '';

        const file = event.target.files[0]; 
        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '100%';
                imagePreview.appendChild(img);
            };

            reader.readAsDataURL(file); 
        } else {
            imagePreview.innerHTML = '<p>Error</p>'; 
        }
    });
});