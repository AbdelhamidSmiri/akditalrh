<?php
$message = isset($params['message']) ? $params['message'] : $message;
?>

<script>
    window.FlashMessage.success(<?php echo json_encode($message); ?>, {
                timeout: 5000,
                progress: true
            });
</script>

