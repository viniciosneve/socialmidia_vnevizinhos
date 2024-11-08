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
    document.getElementById("sendRegister").removeChild(alertFieldNotFilled);
}

document.getElementById('sendRegister').addEventListener('submit', async function (event){
    event.preventDefault();

    const nameUser = document.getElementById('name');
    const lastname = document.getElementById('lastname');
    const birthdate = document.getElementById('birthdate');
    const nickname = document.getElementById('nickname');
    const password = document.getElementById('password');
    const confirnPassword = document.getElementById('confirnPassword');

    const listWithInfoRegister = [nameUser, lastname, birthdate, nickname, password, confirnPassword];

    listWithInfoRegister.forEach(key => {

        if (key.value.trim() == '' && document.getElementById('alertFieldNotFilled_'+key.id) == null) {
            fieldNotFilled(key, key.id);
        } else if (key.value.trim() !== '' && document.getElementById('alertFieldNotFilled_'+key.id)) {
            removeAlertEmptyField(key.id);
        }

    });

    const formData = new URLSearchParams();
    formData.append('nameUser', nameUser.value.trim());
    formData.append('lastname', lastname.value.trim());
    formData.append('birthdate', birthdate.value.trim());
    formData.append('nickname', nickname.value.trim());
    formData.append('password', password.value.trim());
    formData.append('confirnPassword', confirnPassword.value.trim());

    try {
        const response = await fetch('http://localhost:8000/back-end/manage/cadastro/getCadastro.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: formData.toString()
        });
     
        if (response.ok) {
            document.getElementById('sendRegister').reset();

            const data = await response.json();
            if (data.nicknameExiste == 'true' && document.getElementById('wrongNickname') == null) {
                const nicknameExiste = creatingMensageError (data.messageNickname, 'wrongNickname');
                nickname.insertAdjacentElement('afterend', nicknameExiste);
            } else if (data.nicknameExiste == 'false' && document.getElementById('wrongNickname')) {
                document.getElementById('sendRegister').removeChild(document.getElementById('wrongNickname'));
            }

            if (data.passwordIgual == 'false' && document.getElementById('wrongPassword') == null) {
                const passwordDiferent = creatingMensageError (data.messagePassword, 'wrongPassword');
                password.insertAdjacentElement('afterend', passwordDiferent);
            } else if (data.passwordIgual == 'true' && document.getElementById('wrongPassword')) {
                document.getElementById('sendRegister').removeChild(document.getElementById('wrongPassword'));
            }

            if (data.dataisValida == 'false' && document.getElementById('wrongBirthdate') == null) {
                const dataInvalida = creatingMensageError (data.messageData, 'wrongBirthdate');
                birthdate.insertAdjacentElement('afterend', dataInvalida);
            } else if (data.dataisValida == 'true' && document.getElementById('wrongBirthdate')) {
                document.getElementById('sendRegister').removeChild(document.getElementById('wrongBirthdate'));
            }
            
            
        } else {
            alert('Erro ao enviar o formulário. Tente novamente.');
        }

        
    } catch (error) {
        alert('Ocorreu um erro: ' + error.message);

    }
});