function EnviarMensagem() {
    const nome = document.getElementById('nome'),
        assunto = document.getElementById('assunto'),
        mensagem = document.getElementById('tx-mensagem');
    if (!validarForm(nome, assunto, mensagem)) return;
    let msg = `*Nome*: ${nome.value}\n*Assunto*: ${assunto.value}\n*Mensagem*: ${mensagem.value}`;
    if (telefone === '') return;
    if (IsMobile()) {
        SendMobile(msg);
    } else {
        SendDesktop(msg);
    }

}

function getItem(nome) {
    return document.getElementById(nome);
}

function validarForm(n, a, m) {
    if (n.value === '') {
        n.focus();
        Mensagem('Você deve informar seu nome');
        return false;
    }
    if (a.value === '') {
        a.focus();
        Mensagem('Você deve informar o assunto');
        return false;
    }
    if (m.value === '') {
        m.focus();
        Mensagem('Você deve informar a mensagem');
        return false;
    }
    return true;
}

function SendMobile(msg) {
    let target = `https://api.whatsapp.com/send?text=${encodeURIComponent(msg)}&phone=${telefone}`;
    let targetM = `whatsapp://send?text=${encodeURIComponent(msg)}&phone=${telefone}`;

    window.location.href = target;
}

function SendDesktop(msg) {
    let target = `https://web.whatsapp.com/send?text=${encodeURIComponent(msg)}&phone=${telefone}`;
    let h = 650,
        w = 550,
        l = Math.floor(((screen.availWidth || 1024) - w) / 2),
        t = Math.floor(((screen.availHeight || 700) - h) / 2);
    let options = `height=600,width=550,top=${t},left=${l},location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=0`;
    let popup = window.open(target, 'self', options);
    if (popup) {
        popup.focus();
    }
}

function Mensagem(msg) {
    let element = document.getElementById('mensagem');
    element.innerHTML = '<i class="material-icons-outlined">warning</i>' + msg;
    element.style.background = '#ff1200';
    element.style.left = '0px';

    setTimeout(function() {
        element.style = 'left: -230px';
        element.innerHTML = '';
    }, 4000)

}

document.addEventListener("DOMContentLoaded", function(event) {
    if (enderecos.length > 0) {
        if (getCookie('endereco') === '') {
            getItem('endereco').innerText = enderecos[0].endereco;
        }
    }

});

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}