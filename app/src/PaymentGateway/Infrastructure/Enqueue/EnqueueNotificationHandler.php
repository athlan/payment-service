<?php


namespace App\PaymentGateway\Infrastructure\Enqueue;


use App\PaymentGateway\Domain\Usecase\Notification\NotificationEvent;
use Enqueue\Client\ProducerInterface;
use Interop\Amqp\AmqpDestination;
use Interop\Amqp\AmqpMessage;
use Interop\Amqp\Impl\AmqpTopic;
use Interop\Queue\Context;
use Interop\Queue\Topic;
use Symfony\Component\Serializer\SerializerInterface;

class EnqueueNotificationHandler
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var ProducerInterface
     */
    private $producer;

    /**
     * @var Topic
     */
    private $topic;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * EnqueueNotificationHandler constructor.
     */
    public function __construct(string $dsn, string $topicName, SerializerInterface $serializer)
    {
        $this->context = (new \Enqueue\ConnectionFactoryFactory())
            ->create($dsn)
            ->createContext();

        $this->producer = $this->context->createProducer();
        $this->topic = new AmqpTopic($topicName);
        $this->topic->setFlags(AmqpDestination::FLAG_DURABLE);
        $this->topic->setType(AmqpTopic::TYPE_HEADERS);

        $this->serializer = $serializer;
    }

    public function __invoke(NotificationEvent $notificationEvent)
    {
        $event = $notificationEvent->getEvent();

        $body = $this->serializer->serialize($event, 'json');
        $properties = [
            'message_type' => get_class($event),
            'source_system' => $event->sourceSystem,
        ];
        $headers = [];

        /* @var $message AmqpMessage */
        $message = $this->context->createMessage($body, $properties, $headers);
        $message->setDeliveryMode(AmqpMessage::DELIVERY_MODE_PERSISTENT);
        $message->setRoutingKey($event->sourceSystem);

        $this->producer->send($this->topic, $message);
    }
}
