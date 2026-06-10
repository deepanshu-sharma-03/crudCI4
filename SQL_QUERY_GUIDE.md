# JSON Notification System - SQL Query Guide

## Complete SQL Reference with Examples

---

## 1. Database Schema - Complete SQL

```sql
-- Create notifications table with JSON column
CREATE TABLE IF NOT EXISTS `notifications` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `icon` VARCHAR(100) DEFAULT 'ℹ️',

    `priority` ENUM('low', 'medium', 'high') DEFAULT 'medium',

    -- JSON Column: Per-user notification state
    `user_states` JSON NOT NULL DEFAULT JSON_OBJECT(
        'hidden', JSON_ARRAY(),
        'read', JSON_ARRAY(),
        'deleted', JSON_ARRAY(),
        'user_read_at', JSON_OBJECT(),
        'metadata', JSON_OBJECT()
    ),

    -- Status: 0=draft, 1=active, 2=archived
    `status` TINYINT DEFAULT 1,

    -- Timestamps
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Indexes for better performance
    INDEX `idx_status` (`status`),
    INDEX `idx_priority` (`priority`),
    INDEX `idx_created_at` (`created_at`),
    FULLTEXT INDEX `ft_search` (`title`, `description`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 2. Sample Data - Real-World Examples

### Insert Example 1: Fresh Notification (सभी users के लिए)

```sql
INSERT INTO notifications (title, description, priority, user_states, status)
VALUES (
    '🎉 Summer Mega Sale Started!',
    'Get 50% discount on all summer products. Limited time offer - 48 hours only!',
    'high',
    JSON_OBJECT(
        'hidden', JSON_ARRAY(),
        'read', JSON_ARRAY(),
        'deleted', JSON_ARRAY(),
        'user_read_at', JSON_OBJECT(),
        'metadata', JSON_OBJECT(
            'broadcast_at', NOW(),
            'campaign_id', 'SUMMER_2024',
            'target_group', 'all_users',
            'total_users_targeted', 5000
        )
    ),
    1
);

-- Query Result (ID: 1 generated)
-- Abhi JSON empty है: कोई user hidden नहीं, कोई read नहीं
```

### Insert Example 2: After Some Users Interact

```sql
-- मान लो 30 minutes के बाद:
-- - 3500 users ने notification open किया (read)
-- - 1200 users ने hide किया (✕ button)

UPDATE notifications
SET user_states = JSON_OBJECT(
    'hidden', JSON_ARRAY(2, 5, 8, 12, 14, 18, 21, 25, 30, 35, 40, 45, 50),  -- 13 users...
    'read', JSON_ARRAY(1, 3, 4, 6, 7, 9, 10, 11, 13, 15, 16, 17, 19, 20),    -- 14 users...
    'deleted', JSON_ARRAY(),
    'user_read_at', JSON_OBJECT(
        '1', '2024-06-01 10:30:00',
        '3', '2024-06-01 10:32:00',
        '4', '2024-06-01 10:35:00'
    ),
    'metadata', JSON_OBJECT(
        'broadcast_at', '2024-06-01 10:00:00',
        'campaign_id', 'SUMMER_2024',
        'total_users_read', 3500,
        'total_users_hidden', 1200
    )
)
WHERE id = 1;
```

---

## 3. JSON Query Examples - Common Operations

### Query 1: Get Visible Notifications for User 5

```sql
-- User 5 के लिए सभी visible notifications
-- (जो hidden नहीं हैं और deleted नहीं हैं)

SELECT
    id,
    title,
    description,
    priority,
    CASE
        WHEN JSON_CONTAINS(user_states, '5', '$.read') THEN TRUE
        ELSE FALSE
    END as is_read,
    created_at
FROM notifications
WHERE
    status = 1
    AND NOT JSON_CONTAINS(user_states, '5', '$.hidden')
    AND NOT JSON_CONTAINS(user_states, '5', '$.deleted')
    AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
ORDER BY created_at DESC;
```

**Sample Output:**

```
id | title                    | priority | is_read | created_at
5  | Summer Mega Sale         | high     | 1       | 2024-06-01 10:00:00
3  | New Products Arrived     | medium   | 0       | 2024-05-31 14:20:00
1  | Welcome to Our Platform  | low      | 1       | 2024-05-30 09:15:00
```

---

### Query 2: Check if User Has Hidden a Specific Notification

```sql
-- क्या User 5 ने Notification 3 को hide किया?

SELECT
    id,
    title,
    JSON_EXTRACT(user_states, '$.hidden') as who_hidden
FROM notifications
WHERE
    id = 3
    AND JSON_CONTAINS(user_states, '5', '$.hidden');

-- अगर row return होता है: User 5 ने hide किया
-- अगर empty result: User 5 ने hide नहीं किया
```

---

### Query 3: Add User to Hidden Array (Hide Notification)

```sql
-- User 7 को Notification 5 से hide करो

UPDATE notifications
SET user_states = JSON_ARRAY_APPEND(
    user_states,
    '$.hidden',
    7
)
WHERE id = 5
AND NOT JSON_CONTAINS(user_states, '7', '$.hidden');

-- Explanation:
-- JSON_ARRAY_APPEND(column, path, value)
-- column: JSON column को update करो
-- path: '$.hidden' = hidden array में
-- value: 7 = user ID 7 को add करो
```

**Before:**

```json
{
  "hidden": [2, 5, 8],
  "read": [1, 3, 4]
}
```

**After:**

```json
{
  "hidden": [2, 5, 8, 7],
  "read": [1, 3, 4]
}
```

---

### Query 4: Mark Notification as Read (Add User to Read Array)

```sql
-- User 10 को Notification 5 को read mark करो

UPDATE notifications
SET
    user_states = JSON_ARRAY_APPEND(
        user_states,
        '$.read',
        10
    ),
    user_states = JSON_SET(
        user_states,
        CONCAT('$.user_read_at."', 10, '"'),
        NOW()
    )
WHERE id = 5
AND NOT JSON_CONTAINS(user_states, '10', '$.read');

-- Note: MySQL 5.7 में एक साथ दो updates नहीं हो सकते
-- इसलिए सही तरीका:

UPDATE notifications
SET
    user_states = JSON_MERGE_PATCH(
        user_states,
        JSON_OBJECT(
            'read', JSON_ARRAY_APPEND(
                JSON_EXTRACT(user_states, '$.read'),
                '$',
                10
            ),
            'user_read_at', JSON_SET(
                JSON_EXTRACT(user_states, '$.user_read_at'),
                CONCAT('."', 10, '"'),
                DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s')
            )
        )
    )
WHERE id = 5;
```

---

### Query 5: Count Unread Notifications for User 3

```sql
-- User 3 के लिए unread notification count करो

SELECT
    COUNT(*) as unread_count
FROM notifications
WHERE
    status = 1
    AND NOT JSON_CONTAINS(user_states, '3', '$.hidden')
    AND NOT JSON_CONTAINS(user_states, '3', '$.read');

-- Output: 5 (पाँच notifications unread हैं)
```

---

### Query 6: Get Engagement Statistics (Admin Dashboard)

```sql
-- हर notification के लिए statistics निकालो

SELECT
    id,
    title,
    priority,
    JSON_LENGTH(user_states, '$.read') as read_count,
    JSON_LENGTH(user_states, '$.hidden') as hidden_count,
    JSON_LENGTH(user_states, '$.deleted') as deleted_count,
    (
        SELECT COUNT(DISTINCT user_id) FROM users WHERE nf_status = 1
    ) as total_users,
    ROUND(
        (JSON_LENGTH(user_states, '$.read') /
        (SELECT COUNT(DISTINCT user_id) FROM users WHERE nf_status = 1)) * 100,
        2
    ) as read_percentage,
    created_at,
    updated_at
FROM notifications
WHERE status = 1
ORDER BY read_count DESC
LIMIT 10;
```

**Sample Output:**

```
id | title              | read_count | hidden_count | total_users | read_percentage
5  | Summer Sale        | 3500       | 1200         | 5000        | 70.00
3  | New Products       | 2100       | 950          | 5000        | 42.00
1  | Welcome            | 4800       | 150          | 5000        | 96.00
```

---

### Query 7: Get Users Who Read But Not Hidden (Engaged Users)

```sql
-- वह users जिन्होंने notification read तो किया पर hide नहीं किया

SELECT
    id,
    title,
    JSON_EXTRACT(user_states, '$.read') as engaged_users_read,
    JSON_LENGTH(user_states, '$.read') as total_engaged
FROM notifications
WHERE
    id = 5
    AND JSON_LENGTH(user_states, '$.read') > 0;

-- Output: Engaged users की list मिलेगी
```

---

### Query 8: Find Specific User in Hidden Array

```sql
-- Find करो: क्या User 25 Notification 7 को hide किया है?

SELECT
    id,
    title,
    IF(
        JSON_CONTAINS(user_states, '25', '$.hidden'),
        'USER_HIDDEN_NOTIFICATION',
        'USER_NOT_HIDDEN'
    ) as user_status
FROM notifications
WHERE id = 7;

-- Output:
-- id | title       | user_status
-- 7  | Sale!       | USER_HIDDEN_NOTIFICATION
```

---

### Query 9: Get Users Who Didn't Hide Notification (Active Users)

```sql
-- Notification 5 को कितने active (non-hidden) users को दिख रहा है?

SELECT
    COUNT(DISTINCT u.id) as active_users
FROM users u
WHERE
    u.nf_status = 1
    AND NOT JSON_CONTAINS(
        (SELECT user_states FROM notifications WHERE id = 5),
        CAST(u.id AS CHAR),
        '$.hidden'
    );

-- Output: 3800 active users को दिख रहा है
```

---

### Query 10: Search Notifications by Text

```sql
-- "Sale" या "Discount" वाले notifications खोजो

SELECT
    id,
    title,
    description,
    JSON_LENGTH(user_states, '$.read') as read_count
FROM notifications
WHERE
    status = 1
    AND (
        MATCH(title, description) AGAINST('sale discount' IN BOOLEAN MODE)
        OR title LIKE '%sale%'
        OR description LIKE '%discount%'
    )
ORDER BY created_at DESC;
```

---

## 4. Complex Scenarios - Real-World Queries

### Scenario 1: Broadcast Notification To All Users

```sql
-- Initial state: सभी users के लिए empty JSON

INSERT INTO notifications (title, description, priority, user_states)
VALUES (
    'Important Update',
    'System maintenance scheduled for tonight 11 PM - 1 AM',
    'high',
    JSON_OBJECT(
        'hidden', JSON_ARRAY(),
        'read', JSON_ARRAY(),
        'deleted', JSON_ARRAY(),
        'user_read_at', JSON_OBJECT(),
        'metadata', JSON_OBJECT(
            'type', 'system_maintenance',
            'maintenance_start', '2024-06-02 23:00:00',
            'maintenance_end', '2024-06-03 01:00:00'
        )
    )
);
```

---

### Scenario 2: Track When Each User Read Notification

```sql
-- Users को individual timestamps के साथ track करो

-- User 1 reads at 10:30
UPDATE notifications
SET user_states = JSON_SET(
    user_states,
    '$.user_read_at."1"',
    '2024-06-01 10:30:00'
)
WHERE id = 5;

-- User 2 reads at 10:35
UPDATE notifications
SET user_states = JSON_SET(
    user_states,
    '$.user_read_at."2"',
    '2024-06-01 10:35:00'
)
WHERE id = 5;

-- Query: सभी read timestamps
SELECT
    JSON_EXTRACT(user_states, '$.user_read_at') as all_read_times
FROM notifications
WHERE id = 5;

-- Output:
-- all_read_times
-- {"1":"2024-06-01 10:30:00","2":"2024-06-01 10:35:00"}
```

---

### Scenario 3: Permanent Deletion (Strong Hide)

```sql
-- User 5 notification को permanently delete करना है

UPDATE notifications
SET user_states = JSON_OBJECT(
    'hidden', JSON_REMOVE(
        JSON_EXTRACT(user_states, '$.hidden'),
        JSON_SEARCH(
            JSON_EXTRACT(user_states, '$.hidden'),
            'one',
            5
        )
    ),
    'read', JSON_REMOVE(
        JSON_EXTRACT(user_states, '$.read'),
        JSON_SEARCH(
            JSON_EXTRACT(user_states, '$.read'),
            'one',
            5
        )
    ),
    'deleted', JSON_ARRAY_APPEND(
        JSON_EXTRACT(user_states, '$.deleted'),
        '$',
        5
    ),
    'user_read_at', JSON_REMOVE(
        JSON_EXTRACT(user_states, '$.user_read_at'),
        CONCAT('."', 5, '"')
    ),
    'metadata', JSON_EXTRACT(user_states, '$.metadata')
)
WHERE id = 7;
```

---

### Scenario 4: Archive Old Notifications (Cleanup)

```sql
-- 90 दिन से पुरानी notifications को archive करो
-- (JSON size को control में रखने के लिए)

UPDATE notifications
SET status = 2  -- 2 = archived
WHERE
    status = 1
    AND created_at < DATE_SUB(NOW(), INTERVAL 90 DAY)
    AND JSON_LENGTH(user_states, '$.read') > 0;  -- कुछ users पढ़ चुके हैं
```

---

### Scenario 5: Migration - Move Data to Analytics Table

```sql
-- Large JSON के लिए separate analytics table बनाओ

CREATE TABLE IF NOT EXISTS `notification_analytics` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `notification_id` INT,
    `user_id` INT,
    `action` ENUM('read', 'hidden', 'deleted'),
    `action_timestamp` TIMESTAMP,
    FOREIGN KEY (`notification_id`) REFERENCES notifications(id),
    INDEX `idx_notification_user` (`notification_id`, `user_id`)
);

-- JSON से analytics table में data transfer करो
INSERT INTO notification_analytics (notification_id, user_id, action, action_timestamp)
SELECT
    id,
    CAST(value AS UNSIGNED) as user_id,
    'read' as action,
    NOW() as action_timestamp
FROM notifications,
JSON_TABLE(
    user_states,
    '$.read[*]' COLUMNS (value VARCHAR(10) PATH '$')
) as jt
WHERE id = 5;
```

---

## 5. Performance Optimization Queries

### Optimization 1: Add Computed Columns

```sql
-- JSON_LENGTH को columns में pre-compute करो

ALTER TABLE notifications
ADD COLUMN read_count INT GENERATED ALWAYS AS
    (JSON_LENGTH(user_states, '$.read')) STORED;

ALTER TABLE notifications
ADD COLUMN hidden_count INT GENERATED ALWAYS AS
    (JSON_LENGTH(user_states, '$.hidden')) STORED;

-- Create indexes on computed columns
CREATE INDEX idx_read_count ON notifications(read_count);
CREATE INDEX idx_hidden_count ON notifications(hidden_count);

-- अब ये query बहुत faster होगी:
SELECT * FROM notifications
WHERE read_count > 1000
ORDER BY read_count DESC;
```

---

### Optimization 2: Batch Operations

```sql
-- 1000 users को एक बार में hide करो

DELIMITER $$

CREATE PROCEDURE HideManyUsers(
    IN p_notification_id INT,
    IN p_user_ids JSON
)
BEGIN
    DECLARE i INT DEFAULT 0;
    DECLARE user_count INT;
    DECLARE user_id INT;

    SET user_count = JSON_LENGTH(p_user_ids);

    WHILE i < user_count DO
        SET user_id = JSON_EXTRACT(p_user_ids, CONCAT('$[', i, ']'));

        UPDATE notifications
        SET user_states = JSON_ARRAY_APPEND(
            user_states,
            '$.hidden',
            user_id
        )
        WHERE id = p_notification_id
        AND NOT JSON_CONTAINS(user_states, user_id, '$.hidden');

        SET i = i + 1;
    END WHILE;
END$$

DELIMITER ;

-- Usage:
CALL HideManyUsers(5, JSON_ARRAY(1, 2, 3, 4, 5, 6, 7, 8, 9, 10));
```

---

### Optimization 3: Partial Index (MySQL 8.0+)

```sql
-- सिर्फ active notifications को index करो (status = 1)

CREATE INDEX idx_active_read ON notifications(
    (JSON_LENGTH(user_states, '$.read'))
)
WHERE status = 1;
```

---

## 6. Debugging & Monitoring Queries

### Check JSON Validity

```sql
-- JSON valid है या corrupt?

SELECT
    id,
    title,
    IF(
        JSON_VALID(user_states) = 1,
        'VALID',
        'CORRUPTED'
    ) as json_status
FROM notifications;
```

### Monitor JSON Size

```sql
-- हर notification का JSON size check करो

SELECT
    id,
    title,
    CHAR_LENGTH(user_states) as json_size_bytes,
    ROUND(CHAR_LENGTH(user_states) / 1024, 2) as json_size_kb,
    JSON_LENGTH(user_states, '$.hidden') as hidden_users,
    JSON_LENGTH(user_states, '$.read') as read_users
FROM notifications
ORDER BY json_size_bytes DESC
LIMIT 10;
```

### Find Heavy Notifications

```sql
-- कौनसे notifications बहुत बड़े हैं (performance issue)?

SELECT
    id,
    title,
    CHAR_LENGTH(user_states) as size_bytes,
    ROUND(CHAR_LENGTH(user_states) / 1024, 2) as size_kb,
    ROUND(CHAR_LENGTH(user_states) / (1024 * 1024), 2) as size_mb
FROM notifications
WHERE CHAR_LENGTH(user_states) > 1000000  -- 1 MB से ज्यादा
ORDER BY CHAR_LENGTH(user_states) DESC;
```

---

## 7. Performance Tips (MySQL JSON Best Practices)

| Tip                        | Why                                       | Example                                                    |
| -------------------------- | ----------------------------------------- | ---------------------------------------------------------- |
| **Use Generated Columns**  | Pre-computed values faster than functions | `read_count INT GENERATED ALWAYS AS (JSON_LENGTH(...))`    |
| **Index Computed Columns** | Speeds up filtering                       | `CREATE INDEX idx_read_count ON notifications(read_count)` |
| **Archive Old Data**       | Keeps JSON size manageable                | Move data > 90 days to archive table                       |
| **Batch Operations**       | Fewer database round trips                | Use JSON_ARRAY for bulk inserts                            |
| **Denormalization**        | Sometimes denormalize counts              | Store `read_count` separately                              |
| **Limit JSON Depth**       | Simplify structure for speed              | Avoid nested objects                                       |
| **Use Transactions**       | Ensure data consistency                   | `START TRANSACTION; ... COMMIT;`                           |
| **Monitor Size**           | Prevent gigantic JSON                     | Check `CHAR_LENGTH(user_states)`                           |

---

## 8. Troubleshooting Common Issues

### Issue: "JSON path expression" Error

```sql
-- ❌ Wrong:
SELECT * FROM notifications
WHERE JSON_CONTAINS(user_states, 5, '$.hidden');

-- ✅ Correct:
SELECT * FROM notifications
WHERE JSON_CONTAINS(user_states, '5', '$.hidden');  -- Quote the value!
```

### Issue: NULL Results on JSON Functions

```sql
-- ❌ Wrong: Null check
SELECT * FROM notifications WHERE JSON_EXTRACT(user_states, '$.hidden') IS NOT NULL;

-- ✅ Correct:
SELECT * FROM notifications WHERE JSON_EXTRACT(user_states, '$.hidden') IS NOT NULL
AND JSON_TYPE(JSON_EXTRACT(user_states, '$.hidden')) = 'ARRAY';
```

---
