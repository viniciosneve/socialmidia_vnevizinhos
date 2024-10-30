document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('logado') == 'false') {
        window.location.href = 'file:///C:/Users/vinic/Desktop/codigos/projetos_oficiais/tentando_criar_portifolio/redesocial/font-end/html/login.html';
    }

    fetch("http://localhost:8000/back-end/manage/updatePublis.php")
    .catch(error => console.error("Erro ao rodar o arquivo PHP:", error));
});



const nickname = localStorage.getItem('nickname');

const InputNickname = document.createElement('input');
InputNickname.type = 'hidden';
InputNickname.name = 'nickname';
InputNickname.value = nickname;
document.getElementById('commentForm').appendChild(InputNickname);


//enviando o nickname para o back-end para pegar os comentarios e trazer para o front-end.

/*fetch('http://localhost:8000/back-end/manage/getComments.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: new URLSearchParams({
        'nickname': nickname
    }).toString()
})
.then(response => response.json())
.then(data => {

    data[0].forEach(comments => {
        const creatingDivToEachComment = document.createElement('div');
        creatingDivToEachComment.classList.add('creatingDivToEachComment');
        creatingDivToEachComment.innerHTML = `
        <h3 class="Comment's_Nickname">Comentário de ${comments.nickname}</h3>

        <h4 class="h4_Comment's_Title">Título: </h4>
        <p class="Comment's_Title">${comments.title}</p>

        <h4 class="h4_Comment's_Comment">Comentário: </h4>
        <p class="Comment's_Comment">${comments.comment}</p>
        `;
        document.getElementById('comments').appendChild(creatingDivToEachComment);
    });
})*/



document.getElementById('commentForm').addEventListener('submit', async function(event) {
    event.preventDefault();

    const titleInput = document.getElementById('title');
    const commentInput = document.getElementById('comment');

    const warningElementTitle = document.getElementById('moreThenAHandrand');
    const warningElementComment = document.getElementById('moreThen8Thausand');

    if (titleInput.value.length > 100) {
        if (!warningElementTitle) {
            const warningParagraph = document.createElement('p');
            warningParagraph.id = 'moreThenAHandrand';
            warningParagraph.style.color = 'red';
            warningParagraph.textContent = 'O título tem mais de 100 caracteres e não pode ser enviado.';
            this.appendChild(warningParagraph);
        }
    } else if (titleInput.value.length <= 100) {
        if (warningElementTitle) {
            warningElementTitle.remove();
        }
    }

    if (commentInput.value.length > 8000) {
        
        if (!warningElementComment) {
            const warningParagraph = document.createElement('p');
            warningParagraph.id = 'moreThen8Thausand';
            warningParagraph.style.color = 'red';
            warningParagraph.textContent = 'O comentário tem mais de 8.000 caracteres e não pode ser enviado.';
            this.appendChild(warningParagraph);
        }
    } else if (commentInput.value.length <= 8000) {
        if (warningElementComment) {
            warningElementComment.remove();
        }
    }

    if (warningElementTitle == null && warningElementComment == null){

        const informationsComments = new URLSearchParams();
        informationsComments.append('titleInput', titleInput.value.trim());
        informationsComments.append('commentInput', commentInput.value.trim());
        informationsComments.append('nickname', nickname);
        
        try {
            const response = await fetch('http://localhost:8000/back-end/manage/getComment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: informationsComments.toString()
            });
        
            if (response.ok) {
                document.getElementById('commentForm').reset();
            } else {
                alert('Erro ao enviar o formulário. Tente novamente.');
            }
        } catch (error) {
            alert('Ocorreu um erro: ' + error.message);

        }
    }

});

/*function logout() {
    localStorage.setItem('logado', 'false');
    window.location.href = 'http://localhost:8000/manage/deslogando.php';
}

function deleteAccount() {
    localStorage.setItem('logado', 'false');
    window.location.href = 'http://localhost:8000/manage/deleteAccount.php';
}*/

