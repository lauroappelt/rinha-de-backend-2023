<?php

declare(strict_types=1);

namespace App\Service;

use App\Job\ExampleJob;
use App\Job\PersonJob;
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\Driver\DriverInterface;

class PersonQueueService
{
    /**
     * @var DriverInterface
     */
    protected $driver;

    public function __construct(DriverFactory $driverFactory)
    {
        $this->driver = $driverFactory->get('default');
    }

    /**
     * Publish the message.
     */
    public function push($params, int $delay = 0): bool
    {
        // The `ExampleJob` here will be serialized and stored in Redis, so internal variables of the object are best passed only normal data.
        // Similarly, if the annotation is used internally, @Value will serialize the corresponding object, causing the message body to become larger.
        // So it is NOT recommended to use the `make` method to create a `Job` object.
        return $this->driver->push(new PersonJob($params), $delay);
    }
}