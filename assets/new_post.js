const clubInfoElement = document.getElementById('club-info');
const clubId = clubInfoElement.getAttribute('data-club-id');

const url = '/add_post/' + clubId;

function openPostForm() {
    document.getElementById('postForm').style.display = 'block';
}
window.openPostForm = openPostForm;

function closePostForm() {
    document.getElementById('postForm').style.display = 'none';
}
window.closePostForm = closePostForm;

//For preview post
document.addEventListener('DOMContentLoaded', function() {
    const imageUpload = document.getElementById('activite_file_input');
    const imagePreview = document.getElementById('activites_post_photos');
    const errordiv = document.getElementById('div_for_error');

    imageUpload.addEventListener('change', function(event) {
        imagePreview.innerHTML = ''; 
        const images = event.target.files;

        if (images.length === 1) {
            imagePreview.classList.add('activite_one_photo');
        } else if (images.length === 2) {
            imagePreview.classList.add('activite_less_2_photo');
        } else if (images.length > 2 && images.length <= 10) {
            imagePreview.classList.add('activite_more_2_photo');  
        } else if (images.length > 10) {
            const messageError = document.createElement('p');
            messageError.textContent = "Pas plus de 10 photos"; 
            messageError.style.color = "red";
            errordiv.appendChild(messageError); 
            return;  
        }

        Array.from(images).slice(0, 6).forEach((image, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '100%'; 

                const aElement = document.createElement('a');
                aElement.href = e.target.result;
                aElement.target = '_blank'; 

                aElement.appendChild(img);
                imagePreview.appendChild(aElement);
            };
            reader.readAsDataURL(image);  
        });

        if (images.length > 6) {
            const show_botton = document.getElementById('activite_regarder_photo_preview');
            show_botton.style.display = 'flex';
        }
    });
});

//For confirme post
document.getElementById('btn_submit_form_activite').addEventListener('click', function(e) {
    e.preventDefault();
    const errordiv = document.getElementById('div_for_error');
    const form = document.getElementById('newPostForm'); 
    let formData = new FormData(form);

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('newPostForm').reset();
            document.getElementById('postForm').style.display = 'none';
            window.location.reload();
        } else {
            const messageError = document.createElement('p');
            messageError.textContent = data.message;
            messageError.style.color = "red";
            errordiv.appendChild(messageError);
            console.log(data.message)
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

//For check all images
document.querySelectorAll('.activite_regarder_photo_res').forEach(function(link) {
    link.addEventListener('click', function(e) {
        e.preventDefault();

        const postId = this.getAttribute('data-post-id');
        const url_2 = '/post_all_photos/' + postId;

    
        const modal = document.getElementById('modal_for_post_photo');
        const imagesContainer = document.getElementById('modal-images-container');
        imagesContainer.innerHTML = '';
        fetch(url_2, {
            method: 'GET',
        })
        .then(response => response.json())
        .then(data =>{
            if(data.success){
            const images = data.post.images;
                images.forEach(image => {
                const imgElement = document.createElement('img');
                imgElement.src = image;
                imgElement.style.maxWidth = '100%';

                const aElement = document.createElement('a');
                aElement.href = image;
                aElement.target = '_blank'; 

                aElement.appendChild(imgElement);
                imagesContainer.appendChild(aElement);
                });
            }
            else{
                console.log("Error");
            }
        })
    })
})
