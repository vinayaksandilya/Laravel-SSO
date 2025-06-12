# Database Structure Overview

## SSO Auth Server

### OAuth-related Tables

#### oauth_auth_codes
- `id` (char, 80) - Primary key
- `user_id` (foreignId) - Indexed
- `client_id` (foreignUuid)
- `scopes` (text, nullable)
- `revoked` (boolean)
- `expires_at` (dateTime, nullable)

#### oauth_access_tokens
- `id` (char, 80) - Primary key
- `user_id` (foreignId, nullable) - Indexed
- `client_id` (foreignUuid)
- `name` (string, nullable)
- `scopes` (text, nullable)
- `revoked` (boolean)
- `created_at` (timestamp)
- `updated_at` (timestamp)
- `expires_at` (dateTime, nullable)

#### oauth_refresh_tokens
- `id` (char, 80) - Primary key
- `access_token_id` (char, 80) - Indexed
- `revoked` (boolean)
- `expires_at` (dateTime, nullable)

#### oauth_clients
- `id` (uuid) - Primary key
- `owner_id` (uuid, nullable)
- `owner_type` (string, nullable)
- `name` (string)
- `secret` (string, nullable)
- `provider` (string, nullable)
- `redirect_uris` (text)
- `grant_types` (text)
- `revoked` (boolean)
- `created_at` (timestamp)
- `updated_at` (timestamp)
- `password_client` (boolean)
- `personal_access_client` (boolean)
- `revoked` (boolean)

### Standard Tables

#### users
- `id` (bigint) - Primary key, auto-increment
- `name` (string)
- `email` (string) - Unique
- `email_verified_at` (timestamp, nullable)
- `password` (string)
- `remember_token` (string)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### password_reset_tokens
- `email` (string) - Primary key
- `token` (string)
- `created_at` (timestamp, nullable)

#### sessions
- `id` (string) - Primary key
- `user_id` (foreignId, nullable) - Indexed
- `ip_address` (string, 45, nullable)
- `user_agent` (text, nullable)
- `payload` (longText)
- `last_activity` (integer) - Indexed

## SSO Client App

### Standard Tables

These tables are the same as in the auth server, plus client-specific tables:

#### users
- `id` (bigint) - Primary key, auto-increment
- `name` (string)
- `email` (string) - Unique
- `email_verified_at` (timestamp, nullable)
- `password` (string)
- `remember_token` (string)
- `created_at` (timestamp)
- `updated_at` (timestamp)

#### password_reset_tokens
- `email` (string) - Primary key
- `token` (string)
- `created_at` (timestamp, nullable)

#### sessions
- `id` (string) - Primary key
- `user_id` (foreignId, nullable) - Indexed
- `ip_address` (string, 45, nullable)
- `user_agent` (text, nullable)
- `payload` (longText)
- `last_activity` (integer) - Indexed
