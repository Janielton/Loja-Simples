<?php
$total = 0.0;
$site = $config['site'];
function fMoeda($valor)
{
    return "R$ " . number_format($valor, 2, ',', '.');
}
function isAberto($conf)
{
    if ($conf['aberto'] == false) {
        return "Fechado";
    }
    $data = new \DateTime('NOW');
    $hoje = $data->format('w');
    return in_array($hoje, $conf['funcionamento']) ? "Aberto" : "Fechado";
}
$isAberto = isAberto($config);
$class = "aberto";
if ($isAberto === "Fechado") {
    $class = "fechado";
}

function Endereco()
{
    if (isset($_COOKIE['endereco'])) {
        $obj = json_decode($_COOKIE['endereco']);
        return $obj->endereco;
    } else {
        return "Local";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <title><?php echo $config['nome']; ?> - <?php echo $pagina; ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo $site; ?>/assets/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&family=Lato:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons%7CMaterial+Icons+Outlined%7CMaterial+Icons+Two+Tone%7CMaterial+Icons+Round%7CMaterial+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $site; ?>/css/style.css?versao=5">
    <script>

    </script>

    <style>
        .noscript {
            display: none;
        }
    </style>
</head>

<body style="width:100%;" onhashchange="VoltarHome(null);">
    <div id="menu-mobile" style="display:none">
        <nav class="nav-mobile"></nav>
    </div>
    <header>
        <nav>
            <div class="box-info">
                <div class="info-local"><i class="material-icons-outlined">location_on</i> <span id="endereco"><?php echo Endereco(); ?></span></div>
                <div class="info-funcionamento <?php echo $class; ?>"><i class="material-icons-round">store</i> <span><?php echo $isAberto; ?></span>
                </div>
            </div>
            <div class="box-menu">
                <ul>
                    <li>
                        <a href="<?php echo $site; ?>/contato">Contato</a>
                    </li>
                    <?php if ($config['sobre']) : ?> <span class="separador">|</span>
                        <li><a href="<?php echo $site; ?>/sobre">Sobre</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
        <div id="toggle-menu" class="navbar-burger burger"><span></span><span></span><span></span></div>
    </header>
    <div class="container-logo">
        <div class="box-logo">
            <a href="<?php echo $site; ?>" class="mad-logo"><img src="<?php echo $site . "/assets/logo.png"; ?>" alt="Logo"></a>
        </div>

        <div class="box-busca hide">
            <section style="display:flex">
                <input type="text" id="busca" placeholder="Busca produto" />
                <button type="submit" class="btn-busca" onclick="Buscar(document.getElementById('busca').value)"><i class="material-icons">search</i></button>
            </section>
        </div>
        <div class="box-carrinho">
            <div class="mad-item">
                <a href="#" class="mad-item-link" id="bt-busca"><i class="material-icons">search</i></a>
                <a id="bt-carrinho" href="#" type="button" class="mad-item-link carrinho-dropdown-title"><i class="material-icons-outlined">shopping_cart</i></a>
                <div class="carrinho-dropdown">

                    <div class="container-carrinho carrinho-dropdown-element">
                        <div class="lista-produtos mad-produto-small">
                        </div>
                        <div class="sc-footer">
                            <div class="subtotal">Total: <span id="total">R$ 0,00</span></div>
                            <section id="finalizar">
                                <span style="color:white;font-size:17px;">Nenhum item adicionado ao carrinho ainda</span>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="noscript">
        <noscript>
            <style>
                #container_home,
                .loading {
                    display: none;
                }

                .noscript {
                    display: block;
                    position: absolute;
                    margin: 200px auto;
                    width: 100%;
                    text-align: center;
                    color: #eb0029;
                }
            </style>
        </noscript>
        <h2>Você precisar ativar o javascript para usar o site</h2>
    </div>
    <div class="loading"><svg version="1.1" id="L1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
            <circle fill="none" stroke="#eb0029" stroke-width="6" stroke-miterlimit="15" stroke-dasharray="14.2472,14.2472" cx="50" cy="50" r="47">
                <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="5s" from="0 50 50" to="360 50 50" repeatCount="indefinite"></animateTransform>
            </circle>
            <circle fill="none" stroke="#eb0029" stroke-width="1" stroke-miterlimit="10" stroke-dasharray="10,10" cx="50" cy="50" r="39">
                <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="5s" from="0 50 50" to="-360 50 50" repeatCount="indefinite"></animateTransform>
            </circle>
            <g fill="#eb0029">
                <rect x="30" y="35" width="5" height="30">
                    <animateTransform attributeName="transform" dur="1s" type="translate" values="0 5 ; 0 -5; 0 5" repeatCount="indefinite" begin="0.1"></animateTransform>
                </rect>
                <rect x="40" y="35" width="5" height="30">
                    <animateTransform attributeName="transform" dur="1s" type="translate" values="0 5 ; 0 -5; 0 5" repeatCount="indefinite" begin="0.2"></animateTransform>
                </rect>
                <rect x="50" y="35" width="5" height="30">
                    <animateTransform attributeName="transform" dur="1s" type="translate" values="0 5 ; 0 -5; 0 5" repeatCount="indefinite" begin="0.3"></animateTransform>
                </rect>
                <rect x="60" y="35" width="5" height="30">
                    <animateTransform attributeName="transform" dur="1s" type="translate" values="0 5 ; 0 -5; 0 5" repeatCount="indefinite" begin="0.4"></animateTransform>
                </rect>
                <rect x="70" y="35" width="5" height="30">
                    <animateTransform attributeName="transform" dur="1s" type="translate" values="0 5 ; 0 -5; 0 5" repeatCount="indefinite" begin="0.5"></animateTransform>
                </rect>
            </g>
        </svg></div>