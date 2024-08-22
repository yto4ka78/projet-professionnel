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