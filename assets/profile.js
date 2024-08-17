
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form_setting_profile');
    const btnModifierProfile = document.querySelector('.btn_modifier_profile');

    if (btnModifierProfile) {
        btnModifierProfile.addEventListener('click', function(event) {
            event.preventDefault();
            form.submit();
        });
    }
});

document.getElementById('avatar_upload_file_avatarFile_file').addEventListener('change', function(event) {
    if (this.files.length > 0) {
        this.form.submit();
    }
});

document.getElementById('back_ground_avatar_upload_file_backGroundAvatarFile_file').addEventListener('change', function(event) {
    if (this.files.length > 0) {
        this.form.submit();
    }
});