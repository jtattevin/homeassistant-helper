<?php

namespace App\Tests\TvSchedule\Controller\ValueResolver;

use App\TvSchedule\Controller\ValueResolver\TvScheduleValueResolver;
use App\TvSchedule\Model\Schedule;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Cache\Adapter\NullAdapter;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class TvScheduleValueResolverTest extends KernelTestCase
{
    /**
     * @testWith ["schedule"]
     *            ["otherName"]
     */
    public function testResolveCorrectType(string $name)
    {
        $resolved = $this->resolveValue($name, Schedule::class, "basic-tv-schedule.xml");
        $this->assertCount(1, iterator_to_array($resolved));
    }

    /**
     * @testWith ["int"]
     *            ["string"]
     *            ["Schedule"]
     */
    public function testResolveWrongType(string $type)
    {
        $resolved = $this->resolveValue("schedule", $type, "basic-tv-schedule.xml");
        $this->assertCount(0, iterator_to_array($resolved));
    }

    public function resolveValue(string $name, string $type, string $sampleFile): iterable
    {
        self::bootKernel();
        $container = static::getContainer();

        $valueResolver = new TvScheduleValueResolver(
            $container->get(SerializerInterface::class),
            new MockHttpClient(MockResponse::fromFile(__DIR__ . "/sample/" . $sampleFile)),
            new NullAdapter
        );
        $request       = Request::create('/');

        return $valueResolver->resolve(
            $request,
            new ArgumentMetadata($name, $type, false, false, null)
        );
    }
}