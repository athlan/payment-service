# Payment Service

This service handles payment registration, processing and puhing notification back to originator system.

It's using [Omnipay](https://omnipay.thephpleague.com/) on top making service configurable with multiple vendors
of gateways interacting with unified API.

Service is exposed via REST API and broacasting notification events on queue sytems using
[Enqueue](https://github.com/php-enqueue).

* REST API Documentation: http://localhost:8081/api/doc

### Flow

1. Payment is **Registered** first by originator system specifying: amount, currency, description, payment type
   (transfer).

   Originator system calls `POST /api/payment`
   
   Payment service returns _paymentId_ and processing url to redirect user to process payment.
   
2. Payment is **Processed** by gateway.
   
   User is redirected to processing url.
   
   Payment Service redirects user to _vendor gateway_ where user does the payment.
   
   After that user is redirected back to originator service where waits payment to be completed.
   
3. Once _vendor gateway_ received the payment and calling back Payment Service, event is broadcast over messaging
   system and originator system can mark paymentId as completed. 

### Assumptions

* Payment Service is responsible for doing payments using gateway vendors and do a notification after payment completion
  or failure.
* Payment Service have no knowledge about content of the client's order for that payment is beeing done.
  Thus originator system need to store that information and correlate with paymentId. After payment is completed
  originator system completed order it ships or activates products.

### Supportability

#### Manual interventions

##### Marking payment as completed success or failed

This manual intervention **simulates callback from vendor gateway**. Marks payment being IN_PROCESS state as COMPLETED
if completion of payment couldn't be achieved. It also sends notification back to source systems.

```
bin/console app:payment:complete:success PAYMENT_ID -a AUTHOR -m INTERVENTION_REASON
```

To mark completion as failed use:

```
bin/console app:payment:complete:failure PAYMENT_ID -a AUTHOR -m INTERVENTION_REASON
```

##### Sending notification about payment completion

This manual intervention resents notification to source systems about completion of the payment. Payment needs to be
in completed status. It's useful when payment has been completed, however sending notification failed.

```
bin/console app:payment:complete:success:notification PAYMENT_ID
```

To mark completion as failed use:

```
bin/console app:payment:complete:failure:notification PAYMENT_ID
```

### Development

To setup development environment, run Docker Compose:

```
cd docker/dev
docker-compose up -d
```
