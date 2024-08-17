// FOR FIND A OWNER FOR NEW CLUB

document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('creer_club_Owner');
    const resultsDiv = document.getElementById('results');
    const photoDiv = resultsDiv.querySelector('.creer_club_info_form_owner_photo');
    const textDiv = resultsDiv.querySelector('.creer_club_info_form_owner_text');

    input.addEventListener('input', function() {
        const searchValue = this.value;

        if (searchValue) {
            fetch('/creerclub_admin_id/' + encodeURIComponent(searchValue))
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {

                        resultsDiv.style.display = 'flex';

                        const user = data[0];

                        photoDiv.innerHTML = '<img src="/uploads/avatars_user/' + user.avatar + '" alt="Аватар">';

                        textDiv.innerHTML = '<p class="creer_club_info_form_owner_text_name">' + user.name + '</p>' +
                                            '<p class="creer_club_info_form_owner_text_name">' + user.id + '</p>';

                        checkDiv.innerHTML = '<input type="checkbox" id="creer_club_form_check" name="creer_club_form_check">';
                    } else {
                        resultsDiv.style.display = 'none';
                        photoDiv.innerHTML = '';
                        textDiv.innerHTML = '<p>No user found with this ID.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    resultsDiv.style.display = 'none';
                    photoDiv.innerHTML = '';
                    textDiv.innerHTML = '';
                });
        } else {
            resultsDiv.style.display = 'none';
            photoDiv.innerHTML = '';
            textDiv.innerHTML = '';
        }
    });
});

//FOR PREVIEW AVATAR CLUB

document.addEventListener('DOMContentLoaded', function() {
    const imageUpload = document.getElementById('creer_club_avatarFile_file');
    const imagePreview = document.getElementById('creer_club_photo_size');

    imageUpload.addEventListener('change', function(event) {
        imagePreview.innerHTML = ''; 

        const file = event.target.files[0]; // Берем первый файл из списка (если выбрано несколько)
        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '100%';
                imagePreview.appendChild(img); // Добавляем изображение в div для предпросмотра
            };

            reader.readAsDataURL(file); // Читаем файл как Data URL
        } else {
            imagePreview.innerHTML = '<p>Yooo te t\'es trompe\' avec la photo </p>'; // Если файл не выбран, выводим сообщение
        }
    });
});


//FOR PREVIEW BACKGROUND AVATAR CLUB

document.addEventListener('DOMContentLoaded', function() {
    const imageUpload = document.getElementById('creer_club_backGroundAvatarFile_file');
    const imagePreview = document.getElementById('background_photo_profile');

    imageUpload.addEventListener('change', function(event) {
        imagePreview.innerHTML = ''; // Очищаем предыдущее содержимое

        const file = event.target.files[0]; // Берем первый файл из списка (если выбрано несколько)
        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '100%';
                imagePreview.appendChild(img); // Добавляем изображение в div для предпросмотра
            };

            reader.readAsDataURL(file); // Читаем файл как Data URL
        } else {
            imagePreview.innerHTML = '<p>Preview will appear here</p>'; // Если файл не выбран, выводим сообщение
        }
    });
});

//BOTTON FOR SUBMIT CREAT FORM

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