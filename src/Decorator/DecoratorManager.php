<?php

namespace src\Decorator;

use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use src\Integration\DataProvider;

class DecoratorManager extends DataProvider
{
    public $cache;
    public $logger;

    /**
     * @param string $host
     * @param string $user
     * @param string $password
     * @param CacheItemPoolInterface $cache
     */
    public function __construct($host, $user, $password)
    {
        parent::__construct($host, $user, $password);        
    }
	
	public function setCache(CacheItemPoolInterface $cache)
    {
        $this->cache = $cache;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(array $input)
    {
        try {
            $cacheKey = $this->getCacheKey($input);
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $result = parent::get($input);

			if(!$this->valid($result)) return $cacheItem->get();
			
            $cacheItem
                ->set($result)
                ->expiresAt(
                    (new DateTime())->modify('+1 day')
                );

            return $result;
        } catch (Exception $e) {
            $this->logger->critical('Error');
        }

        return $cacheItem->get();
    }

	
    public function getCacheKey(array $input)
    {
        return json_encode($input);
    }
	
	/**
     * @param array $result
     *
     * @return bool
     */
	private function valid(array $result) {
		//if result is valid returns true
	}
}

