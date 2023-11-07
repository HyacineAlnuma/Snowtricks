//const { className } = require("postcss-selector-parser");

const addVideoFormDeleteLink = (item) => {
    const removeFormButton = document.createElement('button');
    removeFormButton.innerText = 'Supprimer la vidéo';
    removeFormButton.classList.add('delete_link');
    removeFormButton.classList.add('delete_video_link');

    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        item.remove();
    });
}


const addImageFormDeleteLink = (item, preview) => {
    const removeFormButton = document.createElement('button');
    removeFormButton.innerText = 'Supprimer l\'image';
    removeFormButton.classList.add('delete_link');

    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        if (item !== undefined) {
            item.remove();
        }
        if (preview !== undefined) {
            preview.remove();
        }
    });
}

document
    .querySelectorAll('ul.videos div')
    .forEach((video) => {
        addVideoFormDeleteLink(video)
    })

document
    .querySelectorAll('ul.images div.form-row')
    .forEach((image) => {
        const originalPreview = image.nextElementSibling;
        addImageFormDeleteLink(image, originalPreview)
    })

const addFormVideoToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('li');


    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
        /__name__/g,
        collectionHolder.dataset.index
        );

    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;
    addVideoFormDeleteLink(item);
};

const addPreviewToExistingImagesRow = (e) => {
    const existingImageRow = document.querySelectorAll('.images .form-row');

    for (let i = 0; i < existingImageRow.length; i++) {
        const preview = document.createElement('div');
        const imagePreview = document.createElement('img');

        preview.setAttribute('id', 'trick_images_'+i+'_file-preview');
        preview.classList.add('preview-image');
    
        preview.appendChild(imagePreview);
        existingImageRow[i].appendChild(preview);

        previewBeforeUpload(i, 'trick_images_'+i+'_file');
    }
}

addEventListener("load",(event) => {
    addPreviewToExistingImagesRow();
});

function previewBeforeUpload(index, id) {
    document.querySelector("#"+id).addEventListener("change", function(e){
        if(e.target.files.length == 0){
            return;
        }
        const originalPreview = document.querySelector("#original-preview-"+(index + 1));
        if(originalPreview !== null){
            originalPreview.remove();
        }
        let file = e.target.files[0];
        let url = URL.createObjectURL(file);
        document.querySelector("#"+id+"-preview img").src = url;
    })
}

const addFormImageToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('li');
    const preview = document.createElement('div');
    const imagePreview = document.createElement('img');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
        /__name__/g,
        collectionHolder.dataset.index
        );
    
    preview.setAttribute('id', 'trick_images_'+collectionHolder.dataset.index+'_file-preview');
    preview.classList.add('preview-image');

    collectionHolder.appendChild(item);
    preview.appendChild(imagePreview);
    collectionHolder.appendChild(preview);   

    collectionHolder.dataset.index++;
    addImageFormDeleteLink(item, preview);
    previewBeforeUpload(collectionHolder.dataset.index, 'trick_images_'+(collectionHolder.dataset.index - 1)+'_file');
};

document
    .querySelectorAll('.add_video_link')
    .forEach(btn => {
        btn.addEventListener("click", addFormVideoToCollection)
    });

document
    .querySelectorAll('.add_image_link')
    .forEach(btn => {
        btn.addEventListener("click", addFormImageToCollection)
    });







