<?php

interface SubscriberInterface
{
    public function update(string $event, array $data): void;
}

class EmailNotifier implements SubscriberInterface
{
    public function update(string $event, array $data): void
    {
        echo "EmailNotifier: An email would be sent for event '{$event}'.\n";
    }
}

class AuditLogger implements SubscriberInterface
{
    public array $logs = [];

    public function update(string $event, array $data): void
    {
        $this->logs[] = ['event' => $event, 'data' => $data, 'time' => time()];
        echo "AuditLogger: Logged event '{$event}'.\n";
    }
}

class EventPublisher
{
    private array $subscribers = [];

    public function subscribe(string $eventType, SubscriberInterface $subscriber): void
    {
        $this->subscribers[$eventType][] = $subscriber;
    }

    public function unsubscribe(string $eventType, SubscriberInterface $subscriber): void
    {
        if (!isset($this->subscribers[$eventType])) {
            return;
        }

        foreach ($this->subscribers[$eventType] as $key => $sub) {
            if ($sub === $subscriber) {
                unset($this->subscribers[$eventType][$key]);
            }
        }
    }

    public function notify(string $eventType, array $data = []): void
    {
        if (empty($this->subscribers[$eventType])) {
            return;
        }

        foreach ($this->subscribers[$eventType] as $subscriber) {
            $subscriber->update($eventType, $data);
        }
    }
}

$publisher = new EventPublisher();
$emailNotifier = new EmailNotifier();
$auditLogger = new AuditLogger();

$publisher->subscribe('user_registered', $emailNotifier);
$publisher->subscribe('user_registered', $auditLogger);
$publisher->subscribe('user_deleted', $auditLogger);

echo "--- Publishing user_registered ---\n";
$publisher->notify('user_registered', ['userId' => 42]);

$publisher->unsubscribe('user_registered', $emailNotifier);
echo "\n--- Publishing user_registered (EmailNotifier removed) ---\n";
$publisher->notify('user_registered', ['userId' => 43]);
