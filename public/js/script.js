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

function openChat(id_to){
    closeModal('searchModal');
    let url = new URL(window.location);
    url.search = "";
    url.searchParams.set('id_to', id_to);
    window.history.pushState({}, '', url);
    location.reload();
}

function scrollToBottom() {
    const messagesContainer = document.querySelector('div.messages');
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function scrollToBottomIfNearEnd() {
    const messagesContainer = document.querySelector('div.messages');
    
    // Diferença pequena (ex: 100px) indica que o usuário está perto do fim
    const nearBottom = messagesContainer.scrollHeight - messagesContainer.scrollTop - messagesContainer.clientHeight < 100;

    if (nearBottom) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
}


function getNewMessages(){
    let url = new URL(window.location);
    let id_to = url.searchParams.get('id_to');
    let lastMessageHTML = '';

    let urlXhr = `/chat_list?id_to=${id_to}`;
    let xhr = new XMLHttpRequest();
    xhr.open('POST', urlXhr, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            let html = xhr.responseText;
            if(html != lastMessageHTML){
                document.querySelector('div.messages>div').innerHTML = xhr.responseText;
                scrollToBottomIfNearEnd();
                lastMessageHTML = html;
            }
        }
    };
    xhr.send('action=get_new_messages');
    
}

function getNewRelations(){
    let url = new URL(window.location);
    let id_to = url.searchParams.get('id_to');
    let lastRelationHTML = '';

    let urlXhr = `/chat_list?id_to=${id_to}`;
    let xhr = new XMLHttpRequest();
    xhr.open('POST', urlXhr, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            let html = xhr.responseText;
            
            if(html != lastRelationHTML){
                const hrElement = document.querySelector('section#chat_list-chat_list > div > hr');
                document.querySelectorAll('section#chat_list-chat_list .mb-3').forEach(el => el.remove());
                let tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                Array.from(tempDiv.querySelectorAll('.mb-3')).reverse().forEach(el => {
                    hrElement.parentNode.insertBefore(el, hrElement.nextSibling);
                });
                lastRelationHTML = html;
            }
        }
    };
    xhr.send('action=get_new_relations');
    
}


let currentMessage = null;
let current_id_message = null;

function showCustomMenu(e, id_message, message) {
    e.preventDefault();
    e.stopPropagation();
    
    const menu = document.getElementById('custom_menu_mouse');
    menu.style.display = "block";
    menu.style.left = e.pageX + 'px';
    menu.style.top = e.pageY + 'px';
    document.getElementById('id_message').value = id_message;
    currentMessage = message;
    current_id_message = id_message;
}


document.addEventListener("DOMContentLoaded", () => {
    getNewRelations();
    setInterval(getNewRelations, 1000);

    getNewMessages();
    setInterval(getNewMessages, 1000);


    document.querySelector("div.input_chat>div>form")?.addEventListener('click', e => {
        e.stopPropagation();
    });

    document.getElementById('editMessageLI').addEventListener("click", e => {
        if (currentMessage !== null) {
            console.log(currentMessage);
            e.stopPropagation();

            const form = document.querySelector('div.input_chat>div>form');

            form.querySelector('input.chat-input').value = currentMessage;

            if (!document.getElementById('input_id')) {
                let input_id = document.createElement('input');
                input_id.id = "input_id";
                input_id.type = "hidden";
                input_id.name = "id_message";
                form.appendChild(input_id);
            }

            form.querySelector('input[type="hidden"][name="action"]').value = "edit";
            form.querySelector('input[type="hidden"][name="id_message"]').value = current_id_message;
            document.getElementById('custom_menu_mouse').style.display = 'none';
        }
    });

    document.addEventListener('click', event => {
        const inputId = document.getElementById('input_id');
        if (inputId) {
            document.querySelector('div.input_chat>div>form input.chat-input').value = "";
            document.querySelector('div.input_chat>div>form input[type="hidden"][name="action"]').value = "send";
            inputId.remove();
        }

        const idMessageField = document.getElementById('id_message');
        if (idMessageField) {
            idMessageField.value = "";
        }

        const menu = document.getElementById('custom_menu_mouse');
        if (menu) {
            menu.style.display = 'none';
        }
    });



    window.addEventListener("load", () => {
        let url = new URL(window.location);
        if(url.searchParams.get('id_to')){
            document.querySelector('input.chat-input')?.focus();
            scrollToBottom();
        }
    });


    document.getElementById('inputSearchUser')?.addEventListener('input', event => {
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
        xhr.send(url.search.replace('?modal=search&', 'action=get_user_to_search&'));
        

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