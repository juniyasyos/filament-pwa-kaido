# Domain-Driven Design Architecture

## Overview

This package has been restructured using Domain-Driven Design (DDD) principles to improve maintainability, testability, and separation of concerns.

## Directory Structure

```
src/
├── Domain/                          # Domain Layer (Business Logic)
│   └── PWA/
│       ├── Entities/               # Domain Entities
│       │   ├── Icon.php
│       │   ├── PWAConfiguration.php
│       │   ├── Shortcut.php
│       │   └── SplashScreen.php
│       ├── Repositories/           # Repository Interfaces
│       │   └── PWASettingsRepositoryInterface.php
│       └── ValueObjects/           # Value Objects
│           ├── AppName.php
│           ├── Color.php
│           ├── IconPath.php
│           ├── IconSize.php
│           └── SplashSize.php
│
├── Application/                     # Application Layer (Use Cases)
│   ├── DTOs/                       # Data Transfer Objects
│   │   └── PWAConfigurationData.php
│   ├── Services/                   # Application Services
│   │   ├── ManifestGenerator.php
│   │   ├── PWAConfigurationBuilder.php
│   │   └── ServiceWorkerGenerator.php
│   └── UseCases/                   # Use Cases
│       ├── GenerateManifestUseCase.php
│       ├── GenerateServiceWorkerUseCase.php
│       └── InstallPWAUseCase.php
│
├── Infrastructure/                  # Infrastructure Layer
│   ├── Console/                    # Console Commands
│   │   └── FilamentPwaInstallCommand.php
│   ├── Http/                       # HTTP Controllers
│   │   └── Controllers/
│   │       └── PWAController.php
│   ├── Persistence/                # Repository Implementations
│   │   └── SpatieSettingsPWARepository.php
│   └── Providers/                  # Service Providers
│       └── FilamentPwaServiceProvider.php
│
├── Presentation/                    # Presentation Layer (UI)
│   └── Filament/                   # Filament Components
│       ├── FilamentPWAPlugin.php
│       └── Pages/
│           └── PWASettingsPage.php
│
└── Settings/                        # Legacy Settings (Spatie)
    └── PWASettings.php
```

## Layers Explanation

### 1. Domain Layer

**Purpose**: Contains pure business logic with no dependencies on external frameworks.

**Components**:

- **Entities**: Core business objects with identity
  - `PWAConfiguration`: Aggregate root for PWA configuration
  - `Icon`: Represents a PWA icon
  - `SplashScreen`: Represents a splash screen
  - `Shortcut`: Represents an app shortcut

- **Value Objects**: Immutable objects representing domain concepts
  - `AppName`: Application name with validation
  - `Color`: Hex color value with validation
  - `IconPath`: Icon file path with default handling
  - `IconSize`: Icon size with validation
  - `SplashSize`: Splash screen size with validation

- **Repositories**: Interfaces defining data access contracts
  - `PWASettingsRepositoryInterface`: Contract for settings persistence

**Key Principles**:
- No framework dependencies
- Immutable value objects
- Rich domain models
- Business rule validation

### 2. Application Layer

**Purpose**: Orchestrates domain objects to fulfill use cases.

**Components**:

- **Use Cases**: Single-purpose application actions
  - `GenerateManifestUseCase`: Generate PWA manifest
  - `GenerateServiceWorkerUseCase`: Generate service worker
  - `InstallPWAUseCase`: Install PWA assets

- **Services**: Complex application logic
  - `PWAConfigurationBuilder`: Builds PWA configuration from repository
  - `ManifestGenerator`: Generates manifest JSON
  - `ServiceWorkerGenerator`: Generates service worker JS

- **DTOs**: Data transfer between layers
  - `PWAConfigurationData`: Transfer object for PWA configuration

**Key Principles**:
- Thin layer coordinating domain objects
- Use case per feature
- No UI or persistence logic

### 3. Infrastructure Layer

**Purpose**: Implements technical details and external integrations.

**Components**:

- **Persistence**: Repository implementations
  - `SpatieSettingsPWARepository`: Spatie Settings implementation

- **HTTP**: Controllers and routes
  - `PWAController`: Handles HTTP requests

- **Console**: Artisan commands
  - `FilamentPwaInstallCommand`: Installation command

- **Providers**: Service container bindings
  - `FilamentPwaServiceProvider`: Registers all dependencies

**Key Principles**:
- Implements domain interfaces
- Framework-specific code
- External integrations

### 4. Presentation Layer

**Purpose**: User interface components.

**Components**:

- **Filament**: Filament-specific UI
  - `FilamentPWAPlugin`: Filament plugin
  - `PWASettingsPage`: Settings page

**Key Principles**:
- Thin presentation logic
- Delegates to use cases
- Framework-specific UI

## Dependency Flow

```
Presentation Layer
       ↓
Application Layer (Use Cases & Services)
       ↓
Domain Layer (Entities & Value Objects)
       ↑
Infrastructure Layer (Implements Domain Interfaces)
```

## Benefits of This Architecture

### 1. Maintainability
- Clear separation of concerns
- Easy to locate and modify code
- Changes isolated to specific layers

### 2. Testability
- Domain logic testable without framework
- Use cases testable with mocked repositories
- Each layer testable independently

### 3. Flexibility
- Easy to swap implementations (e.g., different settings storage)
- Framework-agnostic domain layer
- Can add new features without affecting existing code

### 4. Scalability
- Clear patterns for adding new features
- Easy to understand for new developers
- Supports complex business logic

## Usage Examples

### Using a Use Case

```php
use Juniyasyos\FilamentPWA\Application\UseCases\GenerateManifestUseCase;

// Resolve from container
$generateManifest = app(GenerateManifestUseCase::class);

// Execute use case
$manifest = $generateManifest->execute();
```

### Creating a Value Object

```php
use Juniyasyos\FilamentPWA\Domain\PWA\ValueObjects\Color;

// Create with validation
$color = Color::from('#FF5733');

// Use in domain logic
$config->withThemeColor($color);
```

### Implementing a New Repository

```php
use Juniyasyos\FilamentPWA\Domain\PWA\Repositories\PWASettingsRepositoryInterface;

class CustomPWARepository implements PWASettingsRepositoryInterface
{
    public function getAppName(): ?string
    {
        // Your custom implementation
    }
    
    // ... implement other methods
}
```

### Adding a New Use Case

1. Create use case in `Application/UseCases/`
2. Inject required services
3. Implement `execute()` method
4. Register in service provider if needed

```php
namespace Juniyasyos\FilamentPWA\Application\UseCases;

final readonly class YourNewUseCase
{
    public function __construct(
        private SomeService $service
    ) {}

    public function execute(YourDTO $data): YourResult
    {
        // Implementation
    }
}
```

## Testing Strategy

### Domain Layer Tests
- Test value object validation
- Test entity business logic
- No mocking needed

### Application Layer Tests
- Test use cases with mocked repositories
- Test services with mocked dependencies
- Focus on orchestration logic

### Infrastructure Layer Tests
- Integration tests with real dependencies
- Test repository implementations
- Test controllers with HTTP tests

## Migration Guide

### For Developers

1. **Finding Code**:
   - Business rules → `Domain/PWA/`
   - Use cases → `Application/UseCases/`
   - Controllers → `Infrastructure/Http/`
   - UI → `Presentation/Filament/`

2. **Adding Features**:
   - Start with domain (entities/value objects)
   - Create use case
   - Implement infrastructure
   - Add presentation

3. **Modifying Existing Code**:
   - Identify the layer
   - Check dependencies
   - Make changes respecting layer boundaries

## Best Practices

1. **Domain Layer**:
   - Keep it framework-agnostic
   - Use value objects for validation
   - Rich domain models

2. **Application Layer**:
   - One use case per feature
   - Thin services
   - Use DTOs for data transfer

3. **Infrastructure Layer**:
   - Implement domain interfaces
   - Framework-specific code only
   - Dependency injection

4. **Presentation Layer**:
   - Minimal logic
   - Delegate to use cases
   - UI concerns only

## References

- [Domain-Driven Design by Eric Evans](https://www.domainlanguage.com/ddd/)
- [Clean Architecture by Robert C. Martin](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)
- [Hexagonal Architecture](https://alistair.cockburn.us/hexagonal-architecture/)
