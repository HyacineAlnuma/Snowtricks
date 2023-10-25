const addVideoFormDeleteLink = (item) => {
    const removeFormButton = document.createElement('button');
    removeFormButton.innerText = 'Delete this video';

    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        item.remove();
    });
}


const addImageFormDeleteLink = (item) => {
    const removeFormButton = document.createElement('button');
    removeFormButton.innerText = 'Delete this image';

    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        item.remove();
    });
}

document
    .querySelectorAll('ul.videos div')
    .forEach((video) => {
        addVideoFormDeleteLink(video)
    })

document
    .querySelectorAll('ul.images div')
    .forEach((image) => {
        addImageFormDeleteLink(image)
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

const addFormImageToCollection = (e) => {
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
    addImageFormDeleteLink(item);
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




