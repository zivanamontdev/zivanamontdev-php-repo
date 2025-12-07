-- Migration: Update registrations table for new form fields
-- Date: 2025-12-06

-- Add new columns
ALTER TABLE registrations ADD child_age NVARCHAR(50) NULL;
ALTER TABLE registrations ADD whatsapp NVARCHAR(20) NULL;

-- Copy existing data from old columns to new columns (if needed)
UPDATE registrations SET whatsapp = phone WHERE whatsapp IS NULL AND phone IS NOT NULL;

-- Make whatsapp required (after data migration)
ALTER TABLE registrations ALTER COLUMN whatsapp NVARCHAR(20) NOT NULL;

-- Optional: Drop old columns that are no longer used
-- ALTER TABLE registrations DROP COLUMN email;
-- ALTER TABLE registrations DROP COLUMN phone;
-- ALTER TABLE registrations DROP COLUMN message;
