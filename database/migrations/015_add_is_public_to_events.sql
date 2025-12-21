-- Add is_public column to events table
ALTER TABLE events
ADD COLUMN is_public TINYINT(1) DEFAULT 0 AFTER url;
