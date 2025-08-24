# Changelog

## [Unreleased]

### Changed

- All authenticators now return an `Innmind\Immutable\Attempt`
- All authenticators can return any type inside the `Attempt`

### Removed

- All resolvers have been replaced by `callable`s
- `Innmind\HttpAuthentication\ViaStorage`
- `Innmind\HttpAuthentication\Any`
- `Innmind\HttpAuthentication\ValidateAuthorizationHeader`
- `Innmind\HttpAuthentication\Authenticator`

## 4.0.0 - 2023-11-01

### Changed

- Requires `innmind/http:~7.0`

### Removed

- Support for PHP `8.1`
