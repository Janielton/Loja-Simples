function getItem(nome) {
    return document.getElementById(nome);
}

const App = getItem('app'),
    containerHome = getItem('container_home'),
    containerDetalhe = getItem('container_detalhe'),
    btCarrinho = getItem('bt-carrinho'),
    btBusca = getItem('bt-busca'),
    inputBusca = getItem('busca'),
    containerBusca = document.querySelector('.box-busca'),
    produtosCar = document.querySelector('.lista-produtos'),
    loading = document.querySelector('.loading'),
    containerCar = document.querySelector('.container-carrinho');

var carOpen = false,
    jsonProdutos = [],
    saboresDetalhe = [],
    quantidadeIncar = getCarrinhoQuant();

var isCat = typeof categoria != 'undefined',
    isHome = typeof home != 'undefined',
    isDetalhe = typeof detalhe != 'undefined';

let itemp = getItem('cls-p'),
    itemb = getItem('cls-b');

if (isDetalhe) {
    jsonProdutos = JSON.parse(produto);
}
btBusca.addEventListener('click', function(e) {
    e.preventDefault();
    let width = window.screen.width;
    let logo = document.querySelector('.box-logo');
    if (containerBusca.classList.contains('hide')) {
        containerBusca.classList.replace('hide', 'show');
        btBusca.innerHTML = '<i class="material-icons-outlined">close</i>';
        inputBusca.focus();
        if (width <= 750) {
            let logo = document.querySelector('.box-logo')
            if (logo.classList.contains('show')) {
                logo.classList.replace('show', 'hide');
            } else {
                logo.classList.add('hide')
            }
        }
    } else {
        inputBusca.value = '';
        containerBusca.classList.add('hide');
        containerBusca.classList.remove('show');
        btBusca.innerHTML = '<i class="material-icons-outlined">search</i>';
        if (width <= 750) {
            logo.classList.replace('hide', 'show');
        }
    }

}, false)

document.addEventListener('click', function(e) {
    let drop = document.querySelector('.carrinho-dropdown');
    var fora = !containerCar.contains(e.target);
    var bt = btCarrinho.contains(e.target);
    if (bt) return;
    if (fora) {
        drop.classList.remove('show');
        carOpen = false;
    }
}, true)

btCarrinho.addEventListener('click', function(e) {
    e.preventDefault();
    let drop = document.querySelector('.carrinho-dropdown');
    if (!carOpen) {
        drop.classList.add('show');
        carOpen = true;
        if (produtosCar.innerText == 'produtos...') {
            getCarrinho();
        }
    } else {
        drop.classList.remove('show');
        carOpen = false;
    }
}, false)

inputBusca.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        Buscar(getItem('busca').value)
    }
}, true);

//FUNCTIONS

function VoltarHome(event) {
    if (event != null) event.preventDefault();
    if (!window.history.back()) return;
}

function getProdutos() {
    apiGet(urlHome + '/produtos').then(data => {
        jsonProdutos = data;
        if (jsonProdutos.length > 0) {
            serializeHtml(data);
        } else {
            loading.style.display = 'none';
            containerHome.innerHTML = '<section id="container_home"><div class="row"><div class="col-detalhe"><div class="produto-single" style="text-align:center;"><h2>Nenhum produto cadastrado</h2></div></div></div></section>';
        }

    });
}

function getProdutosInCat(id) {
    apiGet(urlHome + '/produtos/cat/' + id).then(data => {
        jsonProdutos = data;
        if (jsonProdutos.length > 0) {
            serializeHtml(data);
        } else {
            containerHome.innerHTML = '<div class="row"><div class="col-detalhe"><div class="produto-single" style="text-align: center;"><h2>Nenhum produto cadastrado</h2></div></div></div>';
            loading.style.display = 'none';
        }

    });
}

function Selecionar(event, id) {
    event.preventDefault();
    let p = jsonProdutos[id];
    loading.style.display = 'flex';
    containerHome.style.display = 'none';
    ShowDetalhe(p);

}

function addPedido(event, id) {
    event.preventDefault();
    let p = jsonProdutos[id];
    let q = getItem('quantidade_item').value;
    var dados = serializeData(p, q);
    apiPost(urlHome + '/carrinho/add', dados).then(data => {
        if (data.status == 1) {
            Mensagem('Produto adicionado ao carrinho', true);
            toTop();
            setItemCar(p, q, false);
        } else if (data.status == 2) {
            Mensagem('Produto acrescentado no carrinho', true);
            if (produtosCar.innerText.toString().length > 20) SetNovaQuant(p.id_produto, q, p.valor_produto)
        } else {
            Mensagem('Erro ao adicionar pedido', false);
        }
    });
}

function getCarrinhoQuant() {
    let quant = getCookie('carrinho')
    if (quant == 'notenabled') return 0;
    if (quant == '') return 0;
    return parseInt(quant)
}

function toTop() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}

function SetNovaQuant(id, q, valor) {
    let quant = parseInt(q);
    let item = getItem('quantidade_' + id);
    item.innerText = parseInt(item.innerText) + quant;
    total += valor * quant;
    getItem('total').innerText = fMoeda(total);
}

function AlterarQuantidade(subir) {
    let q = document.querySelector('#quantidade_item');
    if (subir) {
        q.value = parseInt(q.value) + 1;
    } else {
        if (q.value === "1") return;
        q.value = parseInt(q.value) - 1;
    }
    let valorFloat = valorDetalhe * parseInt(q.value);
    getItem('preco_detalhe').innerHTML = fMoeda(valorFloat);
}

function ShowDetalhe(p) {
    let templateShare = '<div class="share"> <a class="icon-share" id="whats" href="https://api.whatsapp.com/send?text=Comprar {nome} - {url}" target="_blank" title="Compartilhar no Whatsapp"><i class="material-icons">whatsapp</i></a><a class="icon-share" id="face" href="http://www.facebook.com/sharer.php?u={url}" target="_blank" title="Compartilhar no Facebook"><i class="material-icons">facebook</i></a> <a class="icon-share" id="telegram" href="https://t.me/share/url?url={url}&text=Comprar {nome}" target="_blank" title="Compartilhar no Telegram"><i class="material-icons">telegram</i></a> <a class="icon-share" id="twitter" href="https://twitter.com/share?url={url}&text=Comprar {nome}" target="_blank" title="Compartilhar no Twitter"><i class="material-icons"><img src="https://abs.twimg.com/favicons/twitter.2.ico"/></i></a></div>';
    var template = '<div class="title-detalhe" id="title-segundo"><a href="' + urlHome + '" id="voltar" onclick="VoltarHome(event)"><i class="material-icons-outlined">arrow_back</i></a> <h1 class="titulo">{nome}</h1></div><div class="row"> <div class="col-detalhe"> <div class="produto-single"><span class="desc-detalhe" style="display:none"></span><span class="produto-thumb img-detalhe"><img src="{image}" alt="produto-imagem"></span> <div class="produto-body"> <div class="produto-desc"> <div class="descricao">{descricao}</div> <div class="container-quantidade"> <h3>Selecione quantitade</h3> <div class="box-quantity"> <div class="input-group bootstrap-touchspin"><input id="quantidade_item" type="text" value="1" class="form-control" disabled=""><span class="input-group-btn-vertical"><button class="btn-control control-up" type="button" onclick="AlterarQuantidade(true)"><i class="material-icons-outlined">add</i></button><button class="btn-control control-down" type="button" onclick="AlterarQuantidade(false)"><i class="material-icons-outlined">remove</i></button></span></div> </div> </div> </div> <div class="produto-controls"> <p class="produto-price" id="preco_detalhe">{valor}</p><button onclick="addPedido(event, 0)" class="btn-add btn-detalhe">Add Pedido<i class="fas fa-shopping-cart"></i></button></div></div></div></div></div>';

    valorDetalhe = p.valor_produto;
    var corpo = template.replace('{nome}', p.nome_produto)
        .replace('{img}', p.imagem_produto)
        .replace('{descricao}', p.descricao_produto)
        .replace('{image}', p.imagem_produto)
        .replace('{valor}', fMoeda(valorDetalhe));

    let slug = "/produto/" + p.slug_produto;

    var corpoShare = templateShare
        .replaceAll('{nome}', p.nome_produto)
        .replaceAll('{url}', urlHome + slug);
    getItem('pos-app').innerHTML = corpoShare;
    containerDetalhe.innerHTML = corpo;
    containerDetalhe.style.display = 'block';
    loading.style.display = 'none';
    if (getItem('title-primario')) {
        getItem('title-primario').style.display = 'none';
    }
    window.history.pushState("object or string", p.nome_produto + ' - ' + nomeApp, slug);
    document.title = p.nome_produto + ' - ' + nomeApp;
    share = document.querySelector('.share');
    setapShare(share);
}


function setItemCar(dados, q, setap) {
    if (produtosCar.innerText == 'produtos...') {
        quantidadeIncar++;
        setCookie('carrinho', quantidadeIncar, 1)
        getItem('quantidade').innerText = quantidadeIncar;
        return;
    }
    var template = '<div class="mad-col" id="itemcar_{id}"> <div class="mad-produto"> <button class="mad-close-item" onclick="RemoveItem({id})"><i class="material-icons-outlined">cancel</i></button> <img src="{img}" alt=""> <div class="mad-produto-description"><h6 class="mad-produto-title">{nome}</h6><span class="mad-produto-price"><b id="quantidade_{id}">{q}</b> × {valor}</span></div></div></div>';
    if (quantidadeIncar > 0) {
        let quantCar = getItem('quantidade');
        total += dados.valor_produto * parseInt(q);
        if (!setap) quantidadeIncar++;
        setCookie('carrinho', quantidadeIncar, 1)
        quantCar.innerText = quantidadeIncar;
        getItem('total').innerText = fMoeda(total);
    } else {
        if (!setap) {
            quantidadeIncar = 1;
            setCookie('carrinho', quantidadeIncar, 1)
        }
        var badge = '<i class="material-icons-outlined">shopping_cart</i><span id="quantidade">1</span>';
        btCarrinho.innerHTML = badge;
        getItem('finalizar').innerHTML = '<a href="' + urlHome + '/carrinho" class="btn" id="checkout"><span>Finalizar</span></a>';
        total = dados.valor_produto;
        getItem('total').innerText = fMoeda(total);
    }

    produtosCar.innerHTML += template.replaceAll('{id}', dados.id_produto)
        .replace('{valor}', fMoeda(dados.valor_produto))
        .replace('{img}', dados.imagem_produto)
        .replaceAll('{nome}', dados.nome_produto)
        .replace('{q}', q);

}

function serializeData(d, q) {
    var dados = 'id=' + d.id_produto;
    dados += '&nome=' + d.nome_produto;
    dados += '&valor=' + d.valor_produto;
    dados += '&cat=' + d.nome_categoria;
    dados += '&image=' + d.imagem_produto;
    dados += '&quantidade=' + q;
    return dados;
}

async function apiGet(url) {
    const response = await fetch(url);
    return response.json();
}
async function apiPost(url, data) {
    const response = await fetch(url, {
        method: "POST",
        body: data,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        },
    });
    // console.log(response.text())
    return response.json();
}



function fMoeda(valor) {
    if (typeof valor === 'string') {
        // valor = parseFloat(valor)
    }
    var valorFormatado = valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    return valorFormatado;
}

function serializeHtml(json) {
    var produtos = '<h2 class="tipo-produto"></h2><div class="row">';
    var template = '<div class="col-lg-4"><div class="produto"><a class="produto-thumb" href="{url}" onclick="Selecionar(event, {id})"><img src="{srcimg}" alt="menu item"></a><div class="produto-body"><div class="produto-desc"><h4><a href="{url}" onclick="Selecionar(event, {id})">{nome}</a></h4></div> <div class="produto-controls"><p class="produto-price">{valor}</p><button onclick="{acao-bt}(event, {id})" class="btn-add">{texto-bt}<i class="fas fa-shopping-cart"></i></button></div></div></div></div>';
    json.forEach(function(item, i) {
        produtos += template.replace('{nome}', item.nome_produto)
            .replace('{valor}', fMoeda(item.valor_produto))
            .replaceAll('{id}', i)
            .replaceAll('{url}', getUrlProduto(item.slug_produto))
            .replace('{texto-bt}', 'Add Pedido')
            .replace('{acao-bt}', 'addPedido')
            .replace('{srcimg}', item.imagem_produto);
    })
    produtos += '</div>';
    containerHome.innerHTML = produtos;
    loading.style.display = 'none';

}

function getUrlProduto(slug) {
    return urlHome + '/produto/' + slug;
}

function serializeHtmlBusca(json, s) {
    jsonProdutos = json;
    var resultados = '<div class="title-detalhe" id="title-segundo"><a href="' + urlHome + '"  id="voltar" onclick="VoltarHome(event)"><i class="material-icons-outlined">arrow_back</i></a><h4>Resultado da busca para "' + s + '"</h4></div><div class="row">';

    var template = '<div class="col-lg-4"><div class="produto"><input id="quantidade_item" type="hidden" value="1"><a class="produto-thumb" href="{url}" onclick="Selecionar(event, {id})"><img src="{srcimg}" alt="menu item"></a><div class="produto-body"><div class="produto-desc"><h4><a href="{url}" onclick="Selecionar(event, {id})">{nome}</a></h4><p>{descricao}</p></div> <div class="produto-controls"><p class="produto-price">{valor}</p><button onclick="addPedido(event, {id})" class="btn-add">Add Pedido<i class="fas fa-shopping-cart"></i></button></div></div></div></div>';
    if (json.length == 0) {
        loading.style.display = 'none';
        resultados += '<span style="margin: 20px; font-size: 25px; text-align: center; width: 100%;">Não foi encontrado nada com esse termo</span></div>';
        containerDetalhe.innerHTML = resultados;
        containerDetalhe.style.display = 'block';
        return;
    }
    json.forEach(function(item, i) {
        resultados += template.replace('{nome}', item.nome_produto)
            .replace('{descricao}', item.descricao_produto)
            .replace('{valor}', fMoeda(item.valor_produto))
            .replaceAll('{url}', getUrlProduto(item.slug_produto))
            .replaceAll('{id}', i)
            .replace('{srcimg}', item.imagem_produto);
    });
    resultados += '</div>';
    containerDetalhe.innerHTML = resultados;
    containerDetalhe.style.display = 'block';
    if (getItem('title-primario')) {
        getItem('title-primario').style.display = 'none';
    }
    loading.style.display = 'none';

}

function Buscar(s) {
    if (s !== '') {
        containerHome.style.display = 'none';
        loading.style.display = 'flex';
        apiGet(urlHome + '/produtos/s/' + s.replace(' ', '-')).then(data => {
            serializeHtmlBusca(data, s)
        });
    }
}

function RemoveItem(id) {
    dados = 'id=' + id;
    apiPost(urlHome + '/carrinho/remove', dados).then(data => {
        if (data.sucesso) {
            let quantCar = getItem('quantidade')
            Mensagem('Removido com sucesso', true);
            var tl = total - data.valor;
            getItem('itemcar_' + id).remove();
            getItem('total').innerText = fMoeda(tl);
            quantidadeIncar--;
            setCookie('carrinho', quantidadeIncar, 1);
            quantCar.innerText = quantidadeIncar;
            total = tl;
        } else {
            Mensagem('Erro ao remover item', false);
        }
    });
}

function Mensagem(msg, sucesso) {
    let element = getItem('mensagem');
    if (sucesso) {
        element.innerHTML = '<i class="material-icons-outlined">check_circle</i>' + msg;
        element.style.background = '#63c319';
    } else {
        element.innerHTML = '<i class="material-icons-outlined">warning</i>' + msg;
        element.style.background = '#ff1200';
    }
    element.style.left = '0px';

    setTimeout(function() {
        element.style = 'left: -230px';
        element.innerHTML = '';
        btCarrinho.style = 'position: relative';
    }, 4000)

}

function setCookie(cname, cvalue, exdays) {
    if (!navigator.cookieEnabled) return;
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    if (!navigator.cookieEnabled) return "notenabled";
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

function setCidade(ind) {
    setCookie('endereco', JSON.stringify(enderecos[ind]), 300);
    getItem('endereco').innerText = enderecos[ind].endereco;
    getItem('lightbox_modal').classList.remove('lightbox-on');
}

function ShowCidade() {
    let container = getItem('lightbox_modal')
    container.style.display = 'block';
    container.classList.add('lightbox-on')
    let cidades = getItem('list-cidade');
    let template = '<li><button class="bt-cidade" onclick="setCidade({index})">{cidade}</button></li>';
    let items = '';
    enderecos.forEach(function(item, i) {
        items += template.replace('{cidade}', item.cidade).replace('{index}', i);
    })
    cidades.innerHTML = items;

}

function setMenu(el) {
    let pages = '<div class="mobile-pagina">' + document.querySelector('.box-menu').innerHTML + '</div>';
    let nav = '<div class="mobile-nav">' + document.querySelector('.abas-menu').innerHTML + '</div>';
    nav = nav.replace('<button id="arrou_menu" style="display: block;"><i class="material-icons-outlined">arrow_forward</i></button>', '');
    // pages = pages.replace('<span class="separador">|</span>', '');
    el.innerHTML = '<div class="box-mobile">' + nav + pages + '</div>';
}
var openMenu = false;

function CloseMenu() {
    let toggle = getItem('toggle-menu');
    let menuM = document.querySelector('.nav-mobile');
    menuM.classList.remove('show-menu')
    document.body.style.position = 'unset';
    toggle.innerHTML = '<span></span><span></span><span></span>';
    setTimeout(function() {
        getItem('menu-mobile').style.display = 'none';
    }, 250)
    openMenu = false;
}
document.addEventListener("DOMContentLoaded", function(event) {
    var menu = getItem('menu-mobile');
    menu.addEventListener('click', function(evet) {
        if (evet.target.tagName == 'DIV') {
            CloseMenu();
        }
    })
    let toggle = getItem('toggle-menu');
    toggle.onclick = function() {
        let menuM = document.querySelector('.nav-mobile');
        if (menuM.innerText === '') setMenu(menuM);
        if (openMenu) {
            CloseMenu();
        } else {
            getItem('menu-mobile').style.display = 'block';
            document.body.style.position = 'fixed';
            toggle.innerHTML = '<span style="top: calc(50% - 1px);transform: rotate(45deg);"></span><span style="top: calc(50% - 1px);transform: rotate(-45deg);"></span>';
            setTimeout(function() {
                menuM.classList.add('show-menu')
            }, 1)
            openMenu = true;
        }
    };

    if (enderecos.length > 1) {
        if (getCookie('endereco') === '') ShowCidade()
    } else {
        getItem('endereco').innerText = enderecos[0].endereco;
    }
    if (getCookie('avisoLGPD') === '') ShowAlerta();
    if (isHome) getProdutos();
    if (isCat) getProdutosInCat(categoria.id_categoria)
    if (quantidadeIncar > 0) {
        btCarrinho.innerHTML = '<i class="material-icons-outlined">shopping_cart</i><span id="quantidade">' + quantidadeIncar + '</span>';
        produtosCar.innerHTML = '<span id="load_produtos" style="color: white;font-size: 23px;">produtos...</span>';
        getItem('finalizar').innerHTML = '<a href="' + urlHome + '/carrinho" class="btn" id="checkout"><span>Finalizar</span></a>';
        getItem('total').innerText = 'Carregando...';
    }

    var div = document.querySelector('.nav-u');
    var hasHorizontalScrollbar = div.scrollWidth > div.clientWidth;
    if (hasHorizontalScrollbar) {
        var arrow = getItem('arrou_menu');
        if (!arrow) return;
        arrow.style.display = 'block';
        div.addEventListener('scroll', function() {
            var scrollLeft = div.scrollLeft;
            var divWidth = window.outerWidth;
            var scrollWidth = div.scrollWidth;
            if ((scrollWidth - scrollLeft) + 20 <= divWidth) {
                arrow.style.display = 'none';
            }
        }, false)
        arrow.addEventListener('click', function(e) {
            div.scrollLeft += 50;
        })
    }
});

function getCarrinho() {
    apiGet(urlHome + '/carrinho/itens').then(data => {
        if (data.length == 0) {
            getItem('total').innerHTML = 'R$ 0,00';
            getItem('quantidade').innerHTML = '0';
            getItem('load_produtos').remove();
            getItem('checkout').remove();
            setCookie('carrinho', 0, 1)
            return;
        }
        let list = data.map(obj => {
            return {
                id_produto: parseInt(obj.id),
                nome_produto: obj.nome,
                valor_produto: parseFloat(obj.valor),
                quantidade: parseFloat(obj.quantidade),
                imagem_produto: obj.image
            }
        })
        getItem('load_produtos').remove();
        list.forEach(function(item) {
            setItemCar(item, item.quantidade, true)
        })
    });
}

function ShowAlerta() {
    let element = getItem('notice');
    element.innerHTML = '<div id="cookie-notice"> <div class="conteudo-notificao"> <div class="cookie-text"> Nós utilizamos cookies e outras tecnologias semelhantes para melhorar a sua experiência em nossos serviços. Ao utilizar nossos serviços, você aceita a política de monitoramento de cookies. Para mais informações, consulte nossa <a href="' + urlHome + '/privacidade">Política de Privacidade</a>.        </div> <div class="cookie-notice"> <button id="accept-cookie" class="btn" onclick="CloseNoticie(true)">Aceitar e continuar</button> </div> <button id="cookie-close" title="Fechar" onclick="CloseNoticie(false)"><span class="material-icons-outlined">cancel</span></button> </div> </div>';

    setTimeout(function() {
        getItem('cookie-notice').style.bottom = '10px'
    }, 2000)
}

function CloseNoticie(aceito) {
    let element = getItem('notice');
    element.innerHTML = '';
    if (aceito) {
        setCookie('avisoLGPD', 'aceito', 300)
    }
}

onpopstate = () => {
    if (isHome) {
        if (containerHome.style.display === 'none') {
            containerHome.style.display = 'block';
            containerDetalhe.style.display = 'none';
            if (share) share.remove();
        }
    } else if (isCat) {
        if (containerHome.style.display === 'none') {
            containerHome.style.display = 'block';
            containerDetalhe.style.display = 'none';
            getItem('title-primario').style.display = 'flex';
            getItem('title-segundo').style.display = 'none';
            if (share) share.remove();
        }
    } else if (isDetalhe) {
        window.location.href = urlHome
    }
};

function setapShare(elem) {
    if (window.innerWidth > 500) {
        let single = document.querySelector('.produto-single');
        elem.style.display = 'grid';
        elem.style.top = (single.offsetTop + 10) + 'px';
        elem.style.right = (single.offsetLeft - 25) + 'px';
    }
}

var share = document.querySelector('.share');
if (share) setapShare(share);
document.addEventListener('scroll', function() {
    if (share) {
        let single = document.querySelector('.produto-single');
        let bottom = (single.offsetTop + single.offsetHeight) - 420;
        let scroll = document.documentElement.scrollTop;
        setTimeout(function() {
            if (scroll > 150 && scroll < bottom) share.style.top = (scroll + 200) + 'px';
        }, 100)
    }
})