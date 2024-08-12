<?php

namespace App\TvSchedule\Controller\ValueResolver;

use App\TvSchedule\Model\Schedule;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class TvScheduleValueResolver implements ValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private HttpClientInterface $tvScheduleClient,
        private CacheInterface $cache
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!is_a($argument->getType(), Schedule::class, true)) {
            return;
        }

        yield $this->cache->get('tv_schedule', function (ItemInterface $item) {
            $item->expiresAfter(3600 * 10);

            $xml = $this->tvScheduleClient->request("GET", "")->getContent();

            return $this->serializer->deserialize($xml, Schedule::class, "xml", [
                XmlEncoder::DECODER_IGNORED_NODE_TYPES => [
                    XML_DOCUMENT_TYPE_NODE,
                    XML_COMMENT_NODE,
                ],
                DateTimeNormalizer::FORMAT_KEY         => 'YmdHis O',
            ]);
        });
    }

}
