document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('logado') == 'false') {
        window.location.href = 'http://localhost:8080/html/login/login.html';
    }
});

const nickname = localStorage.getItem('nickname');

async function getInfoFromPerfil() {
    const sendNickname = new URLSearchParams();
    sendNickname.append('nickname', nickname);
    try{
        const response = await fetch('http://localhost:8000/back-end/manage/vnevizinhos/perfil.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body : sendNickname.toString()
        });

        if (response.ok){
            const data = await response.json();
            
            return data['infoUser'][0];
        }
    } catch (error) {
        alert('Ocorreu um erro: ' + error);

    }
}
const info = getInfoFromPerfil();
console.log(info);