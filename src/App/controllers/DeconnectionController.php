<?php
class DeconnectionController
{
    public function processDeconnection()
    {
        session_destroy();
        header('Location: /');
    }
}
