<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

/**
 * @return array{validate_authorization_header: callable(Authenticator): Authenticator, any: callable(Authenticator...): Authenticator, via_url_authority: callable(ViaUrlAuthority\Resolver): Authenticator, via_basic_authorization: callable(ViaBasicAuthorization\Resolver): Authenticator, via_form: callable(ViaForm\Resolver): Authenticator, via_storage: callable(ViaStorage\Storage): (callable(Authenticator): Authenticator), via_authorization: callable(ViaAuthorization\Resolver): Authenticator}
 */
function bootstrap(): array
{
    return [
        'validate_authorization_header' => static function(Authenticator $authenticate): Authenticator {
            return new ValidateAuthorizationHeader($authenticate);
        },
        'any' => static function(Authenticator ...$authenticate): Authenticator {
            /** @psalm-suppress NamedArgumentNotAllowed */
            return new Any(...$authenticate);
        },
        'via_url_authority' => static function(ViaUrlAuthority\Resolver $resolver): Authenticator {
            return new ViaUrlAuthority($resolver);
        },
        'via_basic_authorization' => static function(ViaBasicAuthorization\Resolver $resolver): Authenticator {
            return new ViaBasicAuthorization($resolver);
        },
        'via_form' => static function(ViaForm\Resolver $resolver): Authenticator {
            return new ViaForm($resolver);
        },
        'via_storage' => static function(ViaStorage\Storage $storage): callable {
            return static function(Authenticator $authenticate) use ($storage): Authenticator {
                return new ViaStorage($authenticate, $storage);
            };
        },
        'via_authorization' => static function(ViaAuthorization\Resolver $resolver): Authenticator {
            return new ViaAuthorization($resolver);
        },
    ];
}
