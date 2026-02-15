# Changelog

## [Unreleased]

### Changed

- Requires PHP `8.4`
- Requires `innmind/foundation:~2.1`

## 5.0.0 - 2025-08-24

### Changed

- All authenticators now return an `Innmind\Immutable\Attempt`
- All authenticators can return any type inside the `Attempt`
- Requires `innmind/foundation:~1.9`
- `Innmind\HttpAuthentication\ViaAuthorization` constructor is private, use `::of()` instead
- `Innmind\HttpAuthentication\ViaBasicAuthorization` constructor is private, use `::of()` instead
- `Innmind\HttpAuthentication\ViaForm` constructor is private, use `::of()` instead
- `Innmind\HttpAuthentication\ViaUrlAuthority` constructor is private, use `::of()` instead

### Removed

- All resolvers have been replaced by `callable`s
- `Innmind\HttpAuthentication\ViaStorage`
- `Innmind\HttpAuthentication\Any`
- `Innmind\HttpAuthentication\ValidateAuthorizationHeader`
- `Innmind\HttpAuthentication\Authenticator`
- `Innmind\HttpAuthentication\Identity`

## 4.0.0 - 2023-11-01

### Changed

- Requires `innmind/http:~7.0`

### Removed

- Support for PHP `8.1`
