UPDATE users SET password='$2y$10$P0YRZdeRi39SCIUXO6FUQenWtX0ttpPrP3y2384R6.p.N3/DJ4pOS' WHERE id=135;
SELECT id, name, email, role, LENGTH(password) as pwd_len FROM users WHERE id=135;