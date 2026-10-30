-- Run ONCE after deploying the new youth flow.
-- Before this change, approving a resident under 30 auto-created a "bare"
-- tbl_youth row (name/age/sex only, no civil status). Those residents were
-- listed as SK youth without ever registering. This removes those leftover
-- rows so they are asked to register themselves on their next login.
-- Registered youth (civil_status filled in) are NOT touched.

-- 1) Preview what will be removed:
SELECT id_youth, lname, fname, age
FROM tbl_youth
WHERE civil_status IS NULL OR civil_status = '';

-- 2) Delete them (uncomment to run):
-- DELETE FROM tbl_youth WHERE civil_status IS NULL OR civil_status = '';
