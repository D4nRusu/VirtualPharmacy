function navigate(numberOfPages){
    let page = document.getElementById('page').value;
    if(page > numberOfPages){
        page = numberOfPages;
    } else {
        if(page < 1){
            page = 1;
        }
    }
    window.location.href = 'index.php?page=' + page;
}