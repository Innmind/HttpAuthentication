# Changelog

## [Unreleased]

### Changed

- `Innmind\HttpAuthentication\Authenticator::__invoke()` now returns an `Innmind\Immutable\Attempt`

### Removed

- All resolvers have been replaced by `callable`s
- `Innmind\HttpAuthentication\ViaStorage`
- `Innmind\HttpAuthentication\Any`
- `Innmind\HttpAuthentication\ValidateAuthorizationHeader`

## 4.0.0 - 2023-11-01

### Changed

- Requires `innmind/http:~7.0`

### Removed

- Support for PHP `8.1`
