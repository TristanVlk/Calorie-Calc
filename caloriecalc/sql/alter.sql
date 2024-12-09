
CREATE TABLE IF NOT EXISTS calorie_tracker (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    day_of_week ENUM('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun') NOT NULL,
    calories_consumed INT NOT NULL DEFAULT 0,
    date DATE NOT NULL,  -- The date of the entry
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE (user_id, day_of_week, date) -- Ensure unique records for each day
);
