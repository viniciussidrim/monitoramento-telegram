<?php

date_default_timezone_set('America/Sao_Paulo');

require 'vendor/autoload.php';

use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Request;

$API_KEY = 'chave_aqui';
$chat_id = 'num chat';

$telegram = new Telegram($API_KEY);

//Captura dados
$memoryUsage = shell_exec("free -h | awk 'NR==2 {print \"Total: \" \$2 \"\\nUsado: \" \$3 \"\\nLivre: \" \$4}'");
$diskUsage = shell_exec("df -h | awk 'NR==2 {print \"Total: \" \$2 \"\\nUsado: \" \$3 \"\\nLivre: \" \$4}'");
$ioDiskLivre = shell_exec("iostat 1 1 | awk 'NR==4 {print \$6}'");
$cpuUsage = shell_exec("mpstat 1 1 | awk 'NR==4 {print \"Uso da CPU: \" 100 - \$12 \"%\"}'");
$loadAverage = shell_exec("uptime | awk -F'load average:' '{ print \"Carga Média: \" \$2 }'");
$cpuInfo = shell_exec("cat /proc/cpuinfo | grep 'model name' | uniq");
$uptime = shell_exec('uptime -p');
$hora = date('H:i:s');
$data = date('d/m/Y');

//Estrutura mensagens
$message = PHP_EOL;
$message .= "----- Dados Raspberry ----";
$message .= PHP_EOL;
$message .= "Data/Hora captura:";
$message .= PHP_EOL;
$message .= $hora . ' | ' . $data;
$message .= PHP_EOL;
$message .= PHP_EOL;
$message .= "Uso de Memória:" . PHP_EOL . $memoryUsage;
$message .= PHP_EOL;
$message .= "Espaço em Disco:" . PHP_EOL . $diskUsage;
$message .= PHP_EOL;
$message .= "Io Disco:" . PHP_EOL . trim($ioDiskLivre) . "%" . PHP_EOL;
$message .= PHP_EOL;
$message .= trim($cpuUsage). PHP_EOL;
$message .= PHP_EOL;
$message .= trim($loadAverage) . PHP_EOL;
$message .= PHP_EOL;
$message .= trim($cpuInfo) . PHP_EOL;
$message .= PHP_EOL;

// Enviar a mensagem
$result = Request::sendMessage([
    'chat_id' => $chat_id,
    'text'    => $message,
]);

if ($result->isOk()) {
    echo "Alerta enviado";
} else {
    echo "Error ao enviar alerta: " . $result->getDescription();
}
