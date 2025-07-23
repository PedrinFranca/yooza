function closeModal(id_modal) {
    $(document).ready(x => {
        $(`#${id_modal}`).modal('hide');
    });
}

function openModal(id_modal) {
    $(document).ready(x => {
        $(`#${id_modal}`).modal('show');
    });
}

function initRelation(id_to){
    closeModal('searchModal');
    let url = new URL(window.location);
    url.search = "";
    url.searchParams.set('id_to', id_to);
    window.history.pushState({}, '', url);
}


document.addEventListener("DOMContentLoaded", () => {

    document.getElementById('inputSearchUser').addEventListener('input', event => {
        let value = event.target.value;
        
        let url = new URL(window.location);
        url.searchParams.set('modal', 'search');
        url.searchParams.set('username', value);

        window.history.pushState({}, '', url);
    
        let urlXhr = url.pathname+url.search;

        let xhr = new XMLHttpRequest();
        xhr.open('POST', urlXhr, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById('result_search').innerHTML = xhr.responseText;
            }
        };
        xhr.send(url.search.replace('?modal=search&', ''));
        

    });


    let url = new URL(window.location.href);

    if(url.searchParams.get("modal")){
        
        switch(url.searchParams.get("modal")){
            case 'search':
                openModal('searchModal');
                break;
        }
    }



})