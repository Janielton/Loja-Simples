<?php include 'header-page.php';

function Cidade($enderecos)
{
    $enderecos;
    if (isset($_COOKIE['endereco'])) {
        $obj = json_decode($_COOKIE['endereco']);
        return $obj->cidade;
    } else {
        return $enderecos[0]['cidade'];
    }
}
?>

<style type="text/css">
    .lista-produtos {
        background: #383636;
        max-height: 423px;
        overflow: auto;
        padding: 8px;

    }

    .btn {
        margin: 25px auto;
        min-width: 300px;
        background: #63c319;
        border: 0;
        color: white;
        cursor: pointer;
    }

    .produtos .mad-close-item {
        position: absolute;
        right: -2px;
        top: 0.25rem;
        font-size: 1.25rem;
        color: #ef1919;
        cursor: pointer;
        border: 0;
    }

    .mad-produto img {
        vertical-align: top;
        max-height: 70px;
        border-radius: 50%;
    }

    .mad-produto-title {
        margin-bottom: 2px;
    }

    .mad-produto-description {
        padding-left: 8px;
        margin-top: 2px;
    }

    .lista-produtos.mad-produto-small .mad-produto {
        border-bottom: solid 1px #444;
        padding: 0 0 5px 0;
    }

    .mad-col {
        margin-bottom: 5px;
    }

    .sc-footer {
        background: #e24931;
        padding: 5px;
    }

    .no-produto {
        color: white;
        font-size: 18px;
    }

    .sessao {
        border: solid 1px #f1ba5d;
        border-bottom: 3px solid #f1ba5d;
        padding: 5px;
        margin: 10px;
        display: grid;
    }

    .sessao h6 {
        font-size: 18px;
    }

    .cidade {
        margin-left: 10px;
        background: #418eef;
        padding: 3px;
        width: 50%;
        text-align: center;
        border-radius: 100px;
        color: white;
        margin-bottom: 10px;
        cursor: pointer;
    }

    .pagamento {
        text-align: center;
        margin: 8px auto;
        border-radius: 8px;
    }

    input[type="radio"] {
        appearance: auto;
        cursor: pointer;
    }

    .pagamento label {
        margin-right: 10px;
        cursor: pointer;
    }

    .add {
        min-width: 150px;
    }

    .tab ul {
        list-style: none;
        display: flex;
    }

    .tab ul li {
        margin: 0 3px;
    }

    .tab ul li button {
        background: #cfe231;
        padding: 8px 12px 7px 12px;
        color: #181818;
        font-size: 18px;
        cursor: pointer;
        border: 0;
        border-radius: 4px;
    }

    .active-nav {
        background: #333 !important;
        color: #fff !important;
    }
</style>

<div id="app">

    <div class="title-detalhe">
        <h4>Carrinho</h4>
    </div>
    <div class="conteudo-car">

        <section class="produtos">
            <div class="lista-produtos mad-produto-small">
                <?php foreach ($carrinho as $item) :
                    $total += $item->valor * $item->quantidade;
                ?>
                    <div class="mad-col" id="itemcar_<?php echo $item->id; ?>">
                        <div class="mad-produto">
                            <button class="mad-close-item" onclick="RemoveItem(<?php echo $item->id; ?>)"><i class="material-icons-outlined">cancel</i></button>
                            <img src="<?php echo $item->image; ?>" alt="<?php echo $item->nome; ?>">
                            <div class="mad-produto-description">
                                <h6 class="mad-produto-title"><?php echo $item->nome; ?></h6>
                                <span class="mad-produto-price"><?php echo $item->quantidade; ?> × <?php echo fMoeda($item->valor); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php
                if ($total == 0.0) {
                    echo '<div class="no-produto">Nenhum produto no carrinho ainda<a href="' . $config['site'] . '" class="btn add">Adicionar item</a></div>';
                }
                ?>
            </div>
            <div class="sc-footer">
                <div class="subtotal"><span style="color:#cfe231;font-size:20px;">Total:</span> <span id="total"><?php echo fMoeda($total); ?></span></div>
            </div>
        </section>
        <div class="formulario">
            <nav class="tab">
                <ul>
                    <li>
                        <button class="active-nav" onclick="setNav(this,1)">Receber em casa</button>
                    </li>
                    <li>
                        <button onclick="setNav(this,2)">Retirar no local</button>
                    </li>
                </ul>
            </nav>
            <form onsubmit="EnviarPedido(event)" id="form-delivery" class="show">
                <input type="text" name="nome" id="nome" Placeholder="Seu nome" />
                <div class="sessao">
                    <h6>Endereço</h6>
                    <input type="text" name="rua" id="rua" Placeholder="Rua" />
                    <input type="text" name="bairro" id="bairro" Placeholder="Bairro" />
                    <div style="display: flex">
                        <input type="text" name="ponto" id="ponto" Placeholder="Ponto de referência" style="width: 80%;" />
                        <input type="text" name="numero" id="numero" Placeholder="Número" style="width: 20%;" />
                    </div>
                    <span class="cidade" onclick="ShowCidade()"><?php echo Cidade($config['enderecos']); ?></span>
                </div>
                <div class="sessao">
                    <h6>Forma de pagamento</h6>
                    <div class="pagamento">
                        <?php
                        foreach ($config['pagamentos'] as $pag) {
                            echo '<input type="radio" id="' . strtolower($pag) . '" name="pagamento" value="' . $pag . '"> <label for="' . strtolower($pag) . '">' . $pag . '</label>';
                        }
                        ?>
                    </div>
                </div>
                <textarea name="observacao" id="tx-observacao" Placeholder="Observação"></textarea>
                <button type="submit" class="btn">Enviar</button>
            </form>
            <form onsubmit="EnviarPedidoLocal(event)" id="form-local" class="hide">
                <input type="text" name="nome" id="nome-local" Placeholder="Seu nome" />
                <input type="number" name="mesa" id="mesa-local" Placeholder="Mesa" />
                <textarea name="observacao" id="observacao-local" Placeholder="Observação"></textarea>
                <button type="submit" class="btn">Enviar</button>
            </form>
            <form onsubmit="EnviarPedidoRetirar(event)" id="form-retirar" class="hide">
                <input type="text" name="nome" id="nome-retirar" Placeholder="Seu nome" />
                <textarea name="observacao" id="observacao-retirar" Placeholder="Observação"></textarea>
                <button type="submit" class="btn">Enviar</button>
            </form>
        </div>
    </div>
</div>

<?php
$page = 3;
include 'footer-page.php';
?>