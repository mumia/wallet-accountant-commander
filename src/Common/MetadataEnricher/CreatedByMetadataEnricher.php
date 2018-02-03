<?php

namespace WalletAccountant\Common\MetadataEnricher;

use Prooph\Common\Messaging\Message;
use Prooph\EventStore\Metadata\MetadataEnricher;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use function var_dump;
use WalletAccountant\Document\User;

/**
 * CreatedByMetadataEnricher
 */
class CreatedByMetadataEnricher implements MetadataEnricher
{
    public const METADATA_CREATED_BY = 'created_by';

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param Message $message
     *
     * @return Message
     */
    public function enrich(Message $message): Message
    {
        $token = $this->tokenStorage->getToken();

        if (!$token instanceof PostAuthenticationGuardToken) {
            return $message;
        }

        /** @var User $user */
        $user = $token->getUser();

        return $message->withAddedMetadata(self::METADATA_CREATED_BY, $user->getAggregateId());
    }
}
