<?php
$page = 0;
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
        var openMenu = false;
        document.addEventListener("DOMContentLoaded", function(event) {
            let toggle = document.getElementById('toggle-menu');
            toggle.onclick = function() {
                let menuM = document.querySelector('.nav-mobile');
                if (menuM.innerText === '') setMenu(menuM);
                if (openMenu) {
                    menuM.classList.remove('show-menu')
                    document.body.style.position = 'unset';
                    toggle.innerHTML = '<span></span><span></span><span></span>';
                    document.getElementById('menu-mobile').style.display = 'none';
                    openMenu = false;
                } else {
                    document.getElementById('menu-mobile').style.display = 'block';
                    document.body.style.position = 'fixed';
                    toggle.innerHTML = '<span style="top: calc(50% - 1px);transform: rotate(45deg);"></span><span style="top: calc(50% - 1px);transform: rotate(-45deg);"></span>';
                    setTimeout(function() {
                        menuM.classList.add('show-menu')
                    }, 1)
                    openMenu = true;
                }
            };
        });

        function setMenu(el) {
            el.innerHTML = '<div class="box-mobile">' + document.querySelector('.box-menu').innerHTML + '</div>';
        }
    </script>

    <style>
        .title-detalhe {
            margin-left: 5px;
            padding-left: 5px;
        }
    </style>
</head>

<body style="width:100%;">
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
                        <li>
                            <a href="<?php echo $site; ?>/sobre">Sobre</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
        <div id="toggle-menu"><a href="<?php echo $site; ?>"><i class="material-icons-outlined" style="font-size:29px !important;color:white;padding:5px;">home</i></a></div>
    </header>
    <div class="container-logo">
        <div class="box-logo">
            <a href="<?php echo $site; ?>" class="mad-logo"><img src="<?php echo $site . "/assets/logo.png"; ?>" alt="Logo"></a>
        </div>
    </div>