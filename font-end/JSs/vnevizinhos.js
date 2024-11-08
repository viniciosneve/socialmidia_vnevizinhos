document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('logado') == 'false') {
        window.location.href = 'http://localhost:8080/html/login/login.html';
    }
    
});


const nickname = localStorage.getItem('nickname');

const InputNickname = document.createElement('input');
InputNickname.type = 'hidden';
InputNickname.name = 'nickname';
InputNickname.value = nickname;
document.getElementById('commentForm').appendChild(InputNickname);

async function gettingComments() {
    try {
        const response = await fetch('http://localhost:8000/back-end/manage/vnevizinhos/updateComments.php', {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });
        
        if (response.ok) {
        } else {
            alert('Erro ao enviar o formulário. Tente novamente.');
        }

        const data = await response.json();
        const comments = data["comments"];

        comments.forEach(item => {
            const divHaveEachComment = document.createElement('div');
            divHaveEachComment.classList.add('divToEachComment');
            divHaveEachComment.id = 'postFrom'+item['nickname'];
            divHaveEachComment.innerHTML = '<p id= "CommentTitleFromUser'+ item['nickname']+'" class= "Comments_Title">'+item["title"]+'</p><p id= "CommentFromUser'+ item['nickname']+'" class= "Comments_Comment">'+item["comment"]+'</p><p id= "PostFromUser'+ item['nickname']+'" class= "Comments_Nickname">'+item["nickname"]+'</p>';
            document.getElementById('comments').appendChild(divHaveEachComment);
        });

    } catch (error) {
        alert('Ocorreu um erro: ' + errormessage);

    }
}

gettingComments();

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
            const response = await fetch('http://localhost:8000/back-end/manage/vnevizinhos/getComment.php', {
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

async function logout() {
    localStorage.setItem('logado', 'false');
    const informationsToLogout = new URLSearchParams();
    informationsToLogout.append('nickname', nickname);
    try {
        const response = await fetch('http://localhost:8000/back-end/manage/vnevizinhos/deslogando.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: informationsToLogout.toString()
        });
    
        if (response.ok) {
        } else {
            alert('Erro ao tentar delogar o usuário. Tente novamente.');
        }
    } catch (error) {
        alert('Ocorreu um erro: ' + error.message);
    }

    window.location.href= 'http://localhost:8080/html/entrada/entrada.html';
}

async function deleteAccount() {
    localStorage.setItem('logado', 'false');
    const informationsToLogout = new URLSearchParams();
    informationsToLogout.append('nickname', nickname);
    try {
        const response = await fetch('http://localhost:8000/back-end/manage/vnevizinhos/deleteAccount.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: informationsToLogout.toString()
        });
    
        if (response.ok) {
        } else {
            alert('Erro ao tentar delogar o usuário. Tente novamente.');
        }
    } catch (error) {
        alert('Ocorreu um erro: ' + error.message);
    }

    window.location.href= 'http://localhost:8080/html/entrada/entrada.html';
}

