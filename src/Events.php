<?php

namespace App;

final class Events
{
    /**
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    public const TOKEN_RESET = 'token.reseted';

    /**
     * @Event("App\Event\PasswordEncoderEvent")
     *
     * @var string
     */
    public const PASSWORD_ENCODER = 'password.encoder';
}
