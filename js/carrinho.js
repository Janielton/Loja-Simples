const nome = getItem('nome'),
    rua = getItem('rua'),
    bairro = getItem('bairro'),
    ponto = getItem('ponto'),
    observacao = getItem('tx-observacao'),
    numero = getItem('numero');
var pag = getPagamento("pagamento");

function getItem(nome) {
    return document.getElementById(nome);
}

function getPagamento(nome) {
    var botoes = document.getElementsByName(nome);
    for (var i = 0 in botoes) {
        if (botoes[i].checked) return botoes[i].value;
    }
    return 'Indisponível';
}

function setPagamento(nome) {
    var botoes = document.getElementsByName('pagamento');
    for (var i = 0 in botoes) {
        if (botoes[i].value === nome) {
            botoes[i].checked = true;
        }
    }
}

function EnviarPedido(e) {
    e.preventDefault();

    if (!validarForm(nome, rua, bairro)) return;
    let cid = document.querySelector('.cidade').innerText;
    pag = getPagamento("pagamento");
    var dados = { nome: nome.value, rua: rua.value, bairro: bairro.value, ponto: ponto.value, observacao: observacao.value, numero: numero.value, pagamento: pag, cidade: cid }
    let msg = MontarMensagem(dados);

    if (telefone === '') return;
    if (IsMobile()) {
        SendMobile(msg);
    } else {
        SendDesktop(msg);
    }

}

function EnviarPedidoLocal(e) {
    e.preventDefault();
    let Nome = getItem('nome-local');
    if (Nome.value == "") {
        Mensagem('Preencha o nome')
        Nome.focus();
        return;
    }
    var dados = { nome: Nome.value, mesa: getItem('mesa-local').value, observacao: getItem('observacao-local').value }
    let msg = MontarMensagemLocal(dados, 1);
    if (telefone === '') return;
    if (IsMobile()) {
        SendMobile(msg);
    } else {
        SendDesktop(msg);
    }

}

function EnviarPedidoRetirar(e) {
    e.preventDefault();
    let Nome = getItem('nome-retirar');
    if (Nome.value == "") {
        Mensagem('Preencha o nome')
        Nome.focus();
        return;
    }
    var dados = { nome: Nome.value, observacao: getItem('observacao-retirar').value }
    let msg = MontarMensagemLocal(dados, 2);
    if (telefone === '') return;
    if (IsMobile()) {
        SendMobile(msg);
    } else {
        SendDesktop(msg);
    }

}

function setDados() {
    var dados = null;
    try {
        dados = JSON.parse(getCookie('dados'));
    } catch {}
    if (dados == null) return;
    nome.value = dados.nome;
    rua.value = dados.rua;
    bairro.value = dados.bairro;
    ponto.value = dados.ponto;
    numero.value = dados.numero;
    observacao.value = dados.observacao;
    getItem('nome-retirar').value = dados.nome;
    getItem('nome-local').value = dados.nome;
    setPagamento(dados.pagamento)
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

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

function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min)) + min;
}

function MontarMensagem(d) {
    setCookie('dados', JSON.stringify(d), 120);
    var template = '👉 {quant} {prod} - {val}';
    var produtos = '';
    for (var i = 0 in carrinho) {
        produtos += template.replace('{quant}', carrinho[i].quantidade)
            .replace('{prod}', carrinho[i].nome)
            .replace('{val}', fMoeda(parseFloat(carrinho[i].valor)));
        produtos += '\n';
    }
    let num = getRandomInt(1000, 5000);
    let msg = `📝 *PEDIDO ${num}*\n\n👤‍ *Nome*: ${d.nome}\n`;
    msg += `🏠 *Entrega*: ${d.rua}, ${d.bairro}, ${d.numero}, ${d.cidade}`;
    if (d.ponto) msg += ` (${d.ponto})`;
    msg += '\n';
    msg += `💸 *Pagamento*: ${d.pagamento}\n`;
    msg += '\n';
    msg += `📦 *Produtos* \n${produtos}`;
    msg += '\n';
    if (observacao.value !== '') msg += `*Observação:* ${observacao.value}\n\n`;
    msg += '💲 *Total*: ' + fMoeda(total);
    return msg;
}

function MontarMensagemLocal(d, tipo) {
    var template = '👉 {quant} {prod} - {val}';
    var produtos = '';
    for (var i = 0 in carrinho) {
        produtos += template.replace('{quant}', carrinho[i].quantidade)
            .replace('{prod}', carrinho[i].nome)
            .replace('{val}', fMoeda(parseFloat(carrinho[i].valor)));
        produtos += '\n';
    }
    let num = getRandomInt(1000, 5000);
    let msg = `📝 *PEDIDO ${num}*\n\n👤‍ *Nome*: ${d.nome}\n`;
    msg += `🏠 *Entrega*: ${tipo == 1 ? "Está no local - Mesa: "+d.mesa:"Vai retirar no local"}`;
    msg += '\n';
    msg += `📦 *Produtos* \n${produtos}`;
    msg += '\n';
    if (observacao.value !== '') msg += `*Observação:* ${observacao.value}\n\n`;
    msg += '💲 *Total*: ' + fMoeda(total);
    return msg;
}

function validarForm(n, r, b) {
    if (n.value === '') {
        n.focus();
        Mensagem('Você deve informar seu nome');
        return false;
    }
    if (r.value === '') {
        r.focus();
        Mensagem('Você deve informar a rua');
        return false;
    }
    if (b.value === '') {
        b.focus();
        Mensagem('Você deve informar o bairro');
        return false;
    }
    if (carrinho.length <= 0) {
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

function RemoveItem(id) {
    dados = 'id=' + id;
    try {
        for (var i = 0; i < carrinho.length; i++) {
            if (carrinho[i].id === id.toString()) delete carrinho.splice(i, 1);
        }
    } catch (ex) {
        Mensagem('Erro ao apagar item');
        return;
    }

    apiPost(urlHome + '/carrinho/remove', dados).then(data => {
        if (data.sucesso) {
            var tl = total - data.valor;
            document.getElementById('itemcar_' + id).remove();
            document.getElementById('total').innerText = fMoeda(tl);
            total = tl;
            setCookie('carrinho', carrinho.length, 20);
        } else {
            Mensagem('Erro ao remover item');
        }
    });
}

async function apiPost(url, data) {
    const response = await fetch(url, {
        method: "POST",
        body: data,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        },
    });
    return response.json();
}

function fMoeda(valor) {
    var valorFormatado = valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    return valorFormatado;
}

document.addEventListener("DOMContentLoaded", function(event) {
    if (enderecos.length > 1) {
        if (getCookie('endereco') === '') ShowCidade()
    } else {
        getItem('endereco').innerText = enderecos[0].endereco;
    }
});

function ShowCidade() {
    let container = getItem('lightbox_modal');
    container.style.display = 'block';
    container.classList.add('lightbox-on');
    let cidades = getItem('list-cidade');
    let template = '<li><button class="bt-cidade" onclick="setCidade({index})">{cidade}</button></li>';
    let items = '';
    enderecos.forEach(function(item, i) {
        items += template.replace('{cidade}', item.cidade).replace('{index}', i);
    })
    cidades.innerHTML = items;

}

function setCidade(ind) {
    setCookie('endereco', JSON.stringify(enderecos[ind]), 300);
    getItem('endereco').innerText = enderecos[ind].endereco;
    getItem('lightbox_modal').classList.remove('lightbox-on');
    document.querySelector('.cidade').innerText = enderecos[ind].cidade;
}
setDados();

var botaoAtual = document.querySelector('.active-nav');
var NavContainer = getItem('form-delivery');

function setNav(bt, id) {
    console.log(id, "ss")
    if (bt === botaoAtual) return;
    botaoAtual.classList.remove('active-nav');
    bt.classList.add('active-nav');
    botaoAtual = bt;
    NavContainer.classList.replace('show', 'hide');
    if (id == 1) {
        NavContainer = getItem('form-delivery');
        NavContainer.classList.replace('hide', 'show');
    } else {
        NavContainer = getItem('form-retirar');
        NavContainer.classList.replace('hide', 'show');
    }

}