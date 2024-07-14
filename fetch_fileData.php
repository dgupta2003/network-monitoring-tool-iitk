<?php
function getFileName($protocol, $dataType) {
    $fileMap = [
        'ARP' => [
            'Timestamp' => 'arp_timestamp.txt',
            'Source IP' => 'arp_sourceIP.txt',
            'Receiver IP' => 'arp_receiverIP.txt',
            'Receiver MAC' => 'arp_receiverMAC.txt',
            'ARP Type' => 'arp_type.txt',
            'Payload Length' => 'arp_payloadLength.txt',
            'All ARP Data' => 'arp_all.txt'
        ],
        'TCP' => [
            'Timestamp' => 'tcp_timestamp.txt',
            'Source IP' => 'tcp_sourceIP.txt',
            'Source Port Number' => 'tcp_sourcePort.txt',
            'Destination IP' => 'tcp_destinationIP.txt',
            'Destination Port Number' => 'tcp_destinationPort.txt',
            'Flag Type' => 'tcp_flagType.txt',
            'Payload Length' => 'tcp_payloadLength.txt',
            'All TCP Data' => 'tcp_all.txt'
        ],
        'UDP' => [
            'Timestamp' => 'udp_timestamp.txt',
            'Source IP' => 'udp_sourceIP.txt',
            'Source Port Number' => 'udp_sourcePort.txt',
            'Destination IP' => 'udp_destinationIP.txt',
            'Destination Port Number' => 'udp_destinationPort.txt',
            'Payload Length' => 'udp_payloadLength.txt',
            'All UDP Data' => 'udp_all.txt'
        ]
    ];

    return $fileMap[$protocol][$dataType] ?? null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $protocol = $_POST['protocol'];
    $dataType = $_POST['data-type'];

    //error_log("Protocol: " . $protocol);
    //error_log("Data Type: " . $dataType);
    //echo "Protocol: " . htmlspecialchars($protocol) . "<br>";
    //echo "Data Type: " . htmlspecialchars($dataType) . "<br>";

    if ($protocol && $dataType) {
        $fileName = getFileName($protocol, $dataType);
        
        if ($fileName) {
        	$filePath = "/var/www/html/makingLogin/$fileName";	
        }
        
        if (file_exists($filePath)) {
            $data = file($filePath, FILE_IGNORE_NEW_LINES);
        } else {
            echo "<p>File not found.</p>";
            exit;
        }
    } else {
        echo "<p>Invalid input.</p>";
        exit;
    }
} else {
    echo "<p>Invalid request method.</p>";
    exit;
}


echo '<table border="1">';
if ($dataType === 'All ARP Data' || $dataType === 'All TCP Data' || $dataType === 'All UDP Data') {
    $headers = explode("\t", array_shift($data));
    echo '<tr>';
    foreach ($headers as $header) {
        echo '<th>' . htmlspecialchars($header) . '</th>';
    }
    echo '</tr>';

    foreach ($data as $line) {
        $columns = explode("\t", $line);
        echo '<tr>';
        foreach ($columns as $column) {
            echo '<td>' . htmlspecialchars($column) . '</td>';
        }
        echo '</tr>';
    }
} else {
    echo '<tr><th>' . htmlspecialchars($dataType) . '</th></tr>';
    foreach ($data as $line) {
        echo '<tr><td>' . htmlspecialchars($line) . '</td></tr>';
    }
}
echo '</table>';
?>
