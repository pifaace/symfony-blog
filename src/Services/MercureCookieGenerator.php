<?php

namespace App\Services;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class MercureCookieGenerator
{
    const MERCURE_AUTHORIZATION_HEADER = 'mercureAuthorization';

    /**
     * @var string
     */
    private $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function generate(): string
    {
        $token = (new Builder())
            ->set('mercure', ['subscribe' => ['http://symfony-blog.fr/group/users']])
            ->sign(new Sha256(), $this->secret)
            ->getToken();

        return sprintf('%s=%s; path=hub/; httponly;', self::MERCURE_AUTHORIZATION_HEADER, $token);
    }
}
