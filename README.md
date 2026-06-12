<div align="center">
    <h1>OLX Adverts</h1>
    <p>
        This service automates the monitoring of advertisement prices on the OLX platform. It allows users to subscribe to specific OLX
        listings via email and receive instant notifications whenever the price drops or changes.
    </p>
    <img src="https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white" alt="PHP 8.4" />
    <img src="https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white" alt="Laravel 13" />
    <img src="https://img.shields.io/badge/MariaDB-11-003545?logo=mariadb&logoColor=white" alt="MariaDB 11" />
    <img src="https://img.shields.io/badge/Docker-ready-2496ED?logo=docker&logoColor=white" alt="Docker ready" />
</div>

### Workflow Diagram (Sequence)

```mermaid
sequenceDiagram
    autonumber
    actor User as Client/User
    participant API as SubscriptionController
    participant Service as SubscriptionService
    participant Job as SendVerificationEmailJob (Queue)
    participant OLX as OLX Platform (External)
    participant DB as Database (MariaDB)

    User->>API: POST /api/v1/subscriptions (email, url)
    API->>Service: makeSubscription(data)
    Service->>DB: Check if Advertisement already exists
    
    alt Advertisement is NEW
        Service->>OLX: Fetch HTML page (HTTP request)
        OLX-->>Service: Return Page Data
        Service->>DB: Create Advertisement
    end

    Service->>DB: firstOrCreate Subscription for Email
    
    alt Email is already verified before
        Service->>DB: Set status to 'active'
    else Email is NEW
        Service->>Job: Dispatch job to background queue
        Note over Job: Generates temporary signed URL (24h) & Sends Email
    end

    Service-->>API: Return SubscriptionResource
    API-->>User: HTTP 201 (Created) or 200 (OK)

    %% Потік підтвердження через пошту
    Note over User, API: User clicks Link in Email (with ?expires&signature)
    User->>API: GET /api/v1/subscriptions/{subscription}
    Note over API: Middleware validates 'signed' signature
    API->>DB: Update status to 'active'
    API-->>User: HTTP 200 (Returns SubscriptionResource)
```

## How to start
```bash
git clone https://github.com/DenMitter/olx-adverts.git
cd olx-adverts
docker compose up -d
```

Open: http://localhost:8080/