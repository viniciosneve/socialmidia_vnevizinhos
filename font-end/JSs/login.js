document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('logado') == 'true') {
        window.location.href = 'http://localhost:8080/html/vnevizinhos/vnevizinhos.html';
    }
});

function creatingMensageError (text, id_element){

    const elementMensageError = document.createElement('p');
    elementMensageError.id = id_element;
    elementMensageError.innerText = text;
    elementMensageError.style = 'color: red;';

    return elementMensageError;

}

function fieldNotFilled (emptyField, campo) {
    mensageErrorFieldNotFilled = 'Por favor você precisa preencher este campo';
    const alertFieldNotFilled = creatingMensageError(mensageErrorFieldNotFilled, 'alertFieldNotFilled_'+campo);
    emptyField.insertAdjacentElement('afterend', alertFieldNotFilled);
}

function removeAlertEmptyField (campo) {
    const alertFieldNotFilled = document.getElementById('alertFieldNotFilled_'+campo);
    document.getElementById("sendLogin").removeChild(alertFieldNotFilled);
}

document.getElementById('sendLogin').addEventListener('submit', async function (event){
    event.preventDefault();

    const nickname = document.getElementById('nickname');
    const password = document.getElementById('password');

    const listWithInfoLogin = [nickname, password];

    listWithInfoLogin.forEach(key => {

        if (key.value.trim() == '' && document.getElementById('alertFieldNotFilled_'+key.id) == null) {
            fieldNotFilled(key, key.id);
        } else if (key.value.trim() !== '' && document.getElementById('alertFieldNotFilled_'+key.id)) {
            removeAlertEmptyField(key.id);
        }

    });

    const formData = new URLSearchParams();
    formData.append('nickname', nickname.value.trim());
    formData.append('password', password.value.trim());

    try {
        const response = await fetch('http://localhost:8000/back-end/manage/login/getLogin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: formData.toString()
        });
     
        if (response.ok) {
            document.getElementById('sendLogin').reset();

            const data = await response.json();
            if (data["login"] == 'false') {
                if (document.getElementById('AlertLoginNotExist') == null) {
                    const alertLoginNotExist = creatingMensageError('login ou senha incorreta', 'AlertLoginNotExist');
                    document.getElementById('sendLogin').appendChild(alertLoginNotExist);
                }
                localStorage.setItem('logado', 'false');

            } else if (data["login"] == 'true') {
                if (document.getElementById('AlertLoginNotExist')) {
                    document.getElementById('sendLogin').removeChild(document.getElementById('AlertLoginNotExist'));
                }
                localStorage.setItem('logado', 'true');
                localStorage.setItem('nickname', data['nickname']);
                window.location.href = 'http://localhost:8080/html/vnevizinhos/vnevizinhos.html';
            }

        } else {
            alert('Erro ao enviar o formulário. Tente novamente.');
        }

        

    } catch (error) {
        alert('Ocorreu um erro: ' + error.message);

    }
});











/*fetch('../Jsons/jsonReciveCreateAcont.json')
.then(response => response.json())
.then(data => {
    if(data["Alert"] && document.getElementById('AlertLoginNotExist') == null){
        const gettingForm = document.getElementById('sendLogin');
        const creatingAlertError = document.createElement('p');
        creatingAlertError.id = "AlertLoginNotExist";
        creatingAlertError.style= "color: red;";
        creatingAlertError.innerText = 'login ou senha incorreta';
        gettingForm.appendChild(creatingAlertError);

        localStorage.setItem('logado', 'false');

    } else if (data["Alert"] == undefined){
        if (document.getElementById('AlertLoginNotExist')) {
            const gettingForm = document.getElementById('sendLogin');
            const gettingMensageError = document.getElementById('AlertLoginNotExist');

            gettingForm.removeChild(gettingMensageError);
        }
        document.getElementById('sendLogin').addEventListener('submit', function(event) {
            event.preventDefault();
            localStorage.setItem('logado', 'true');
            localStorage.setItem('nickname', data[0].nickname);
            this.submit();
        });
    }
})*/