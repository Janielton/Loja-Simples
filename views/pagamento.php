<?php
if ($tipo == 1) :
    require_once('vendor/autoload.php');
    $apiKey = 'sk_test_51L39tCJYkesZVeFPwExMsM127Sd1gsmC7C7g3V8kQk8dBYPzpL0ZX82zu9UuDT2T1I8hRT9YRISzluZBuqj8V2Q400j2Z49FMy';

    function CreateCliente($key)
    {
        $stripe = new \Stripe\StripeClient($key);
        $customer = $stripe->customers->create([
            'description' => 'example customer',
            'email' => 'email@example.com',
            'payment_method' => 'pm_card_visa',
        ]);
        echo $customer;
    }


    function getCliente($id, $key)
    {
        $stripe = new \Stripe\StripeClient($key);
        $customer = $stripe->customers->retrieve($id);
        echo $customer;
    }

    function CreatePagamento($key, $valor)
    {
        $stripe = new \Stripe\StripeClient($key);
        $intent = $stripe->paymentIntents->create([
            'amount' => $valor,
            'currency' => 'brl',
            'payment_method_types' => ['boleto', 'card'],
            'capture_method' => 'automatic',
        ]);
        $output = [
            'clientSecret' => $intent->client_secret,
        ];
        return $output;
    }

    function confirmePagamento($id, $key)
    {
        $stripe = new \Stripe\StripeClient($key);
        $intent = $stripe->paymentIntents->confirm(
            $id,
            ['payment_method' => 'pm_card_visa',]
        );
        return $intent->id;
        echo $intent->status;
    }
    //getCliente("cus_LkfuqXl0DOCcpK", $apiKey);

    //CreatePagamento($apiKey);
    //confirmePagamento('pi_3L3AwYJYkesZVeFP0KICgbNX', $apiKey);
    header('Content-Type: application/json');

    try {
        $pagamento = CreatePagamento($apiKey, 500);
        echo json_encode($pagamento);
    } catch (Error $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }

else :
?>
    <?php
    include 'header-page.php';
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

        .produtos {
            margin-right: 5px;
        }

        .formulario {
            margin-left: 5px;
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
            background: #505050;
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

        .subtotal {
            font-size: 25px;
            text-align: center;
        }

        .pag-success {
            background: #cddc39;
            padding: 15px;
            margin: 0 auto;
            font-size: 19px;
            color: #383535;
        }

        .pag-unsuccess {
            background: #ef1919;
            padding: 15px;
            margin: 0 auto;
            font-size: 19px;
            color: #ffffff;
        }

        .bt-enviar,
        .bt-novamente {
            max-width: 400px;
            margin: 50px auto;
        }
    </style>
    <div id="app">
        <?php
        if (isset($_GET['payment_intent'])) :
            $dados = (object)[
                'payment' => $_GET['payment_intent'],
                'client_secret' => $_GET['payment_intent_client_secret'],
                'status' => $_GET['redirect_status'],
            ];
            if ($dados->status == 'succeeded') {
                echo '<div class="pag-success">Pagamento efetuado com sucesso. Obrigado pela preferência!</div><button class="bt-enviar">Enviar pedido</button>';
            } else {
                echo '<div class="pag-unsuccess">Pagamento não foi efetuado. Ocorreu um erro!</div><button class="bt-novamente">Tentar novamente</button>';
            }
        else :
        ?>
            <div class="title-detalhe">
                <h4>Realizar pagamento da compra</h4>
            </div>

            <div class="conteudo-car">

                <section class="produtos">
                    <div class="lista-produtos mad-produto-small">
                        <?php foreach ($carrinho as $item) :
                            $total += $item->valor * $item->quantidade;
                        ?>
                            <div class="mad-col" id="itemcar_<?php echo $item->id; ?>">
                                <div class="mad-produto">

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
                        <div class="subtotal"><span id="total"><?php echo fMoeda($total); ?></span></div>
                    </div>
                </section>
                <div class="formulario">
                    <div class="box-pagamento" style="
    display: grid;
">
                        <!-- Display a payment form -->
                        <form id="payment-form">
                            <div id="payment-element">
                                <!--Stripe.js injects the Payment Element-->
                            </div>
                            <button id="submit">
                                <div class="spinner hidden" id="spinner"></div>
                                <span id="button-text">Pagar</span>
                            </button>
                            <div id="payment-message" class="hidden"></div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php
    $page = 4;
    include 'footer-page.php';
    ?>
<?php endif; ?>