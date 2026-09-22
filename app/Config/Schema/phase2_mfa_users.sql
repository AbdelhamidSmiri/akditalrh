-- Phase 2: MFA columns for users
-- Safe to re-run only if columns do not already exist.

ALTER TABLE users
  ADD COLUMN totp_secret VARCHAR(255) NULL DEFAULT NULL AFTER password,
  ADD COLUMN totp_enabled TINYINT(1) NOT NULL DEFAULT 0 AFTER totp_secret;
