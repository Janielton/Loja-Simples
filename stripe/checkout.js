      // This is your test publishable API key.
      const stripe = Stripe("pk_test_51L39tCJYkesZVeFPpsot51aQV8pAPAKtjUoLlxpdBfuVTCDaKYHJmAG1i1tuXSQO1cu1CCldTSkqKfPoSkU3JNhA0080kNbha9");

      // The items the customer wants to buy
      const items = [{ id: "xl-tshirt" }];

      let elements;

      initialize();
      checkStatus();
      //teste();
      document
          .querySelector("#payment-form")
          .addEventListener("submit", handleSubmit);

      async function teste() {
          await fetch("https://xloja.appmania.com/pagamento", {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({ items }),
          }).
          then(response => response.json()).then(json => {
              console.log(json);
          });
      }

      // Fetches a payment intent and captures the client secret
      async function initialize() {
          const { clientSecret } = await fetch("https://xloja.appmania.com/pagamento", {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({ items }),
          }).then((r) => r.json());

          elements = stripe.elements({ clientSecret });

          const paymentElement = elements.create("payment");
          paymentElement.mount("#payment-element");
      }

      async function handleSubmit(e) {
          e.preventDefault();
          setLoading(true);

          const { error } = await stripe.confirmPayment({
              elements,
              confirmParams: {
                  // Make sure to change this to your payment completion page
                  return_url: "https://xloja.appmania.com/pagamento",
              },
          });

          // This point will only be reached if there is an immediate error when
          // confirming the payment. Otherwise, your customer will be redirected to
          // your `return_url`. For some payment methods like iDEAL, your customer will
          // be redirected to an intermediate site first to authorize the payment, then
          // redirected to the `return_url`.
          if (error.type === "card_error" || error.type === "validation_error") {
              showMessage(error.message);
          } else {
              showMessage("An unexpected error occured.");
          }

          setLoading(false);
      }

      // Fetches the payment intent status after payment submission
      async function checkStatus() {
          const clientSecret = new URLSearchParams(window.location.search).get(
              "payment_intent_client_secret"
          );

          if (!clientSecret) {
              return;
          }

          const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

          switch (paymentIntent.status) {
              case "succeeded":
                  showMessage("Pagamento realizado!");
                  break;
              case "processing":
                  showMessage("Seu pagamento está sendo processado.");
                  break;
              case "requires_payment_method":
                  showMessage("Seu pagamento não foi bem-sucedido, tente novamente.");
                  break;
              default:
                  showMessage("Algo deu errado.");
                  break;
          }
      }

      // ------- UI helpers -------

      function showMessage(messageText) {
          const messageContainer = document.querySelector("#payment-message");

          messageContainer.classList.remove("hidden");
          messageContainer.textContent = messageText;

          setTimeout(function() {
              messageContainer.classList.add("hidden");
              messageText.textContent = "";
          }, 4000);
      }

      // Show a spinner on payment submission
      function setLoading(isLoading) {
          if (isLoading) {
              // Disable the button and show a spinner
              document.querySelector("#submit").disabled = true;
              document.querySelector("#spinner").classList.remove("hidden");
              document.querySelector("#button-text").classList.add("hidden");
          } else {
              document.querySelector("#submit").disabled = false;
              document.querySelector("#spinner").classList.add("hidden");
              document.querySelector("#button-text").classList.remove("hidden");
          }
      }