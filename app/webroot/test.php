<?php
/**
 * Web test runner intentionally disabled (Phase 0 hardening).
 */
header('HTTP/1.1 403 Forbidden');
header('Content-Type: text/plain; charset=UTF-8');
echo 'Forbidden';
exit;
