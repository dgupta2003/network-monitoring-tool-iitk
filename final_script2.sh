#!/bin/bash

# Capture 2000 packets from the network interface 'enp0s1' and save them in a pcap file
tcpdump -i enp0s1 -c 2000 -w "/var/www/html/makingLogin/all_capture.pcap"

# Read the pcap file and save the packet data to a text file with timestamps
tcpdump -r "/var/www/html/makingLogin/all_capture.pcap" -nn -tt > "/var/www/html/makingLogin/all_capture.txt"

# Filter the packets and save to respective files
grep -E " Flags| ack" "/var/www/html/makingLogin/all_capture.txt" > "/var/www/html/makingLogin/tcp_capture.txt"
grep "UDP" "/var/www/html/makingLogin/all_capture.txt" > "/var/www/html/makingLogin/udp_capture.txt"
grep "ARP" "/var/www/html/makingLogin/all_capture.txt" > "/var/www/html/makingLogin/arp_capture.txt"

echo "Captures saved in /var/www/html/makingLogin"

# creating the output directory
output_dir="/var/www/html/makingLogin"

# creating input files for each protocol
arp_input_file="$output_dir/arp_capture.txt"
udp_input_file="$output_dir/udp_capture.txt"
tcp_input_file="$output_dir/tcp_capture.txt"

# creating output files for UDP
udp_timestamp_file="$output_dir/udp_timestamp.txt"
udp_source_ip_file="$output_dir/udp_sourceIP.txt"
udp_source_port_file="$output_dir/udp_sourcePort.txt"
udp_dest_ip_file="$output_dir/udp_destinationIP.txt"
udp_dest_port_file="$output_dir/udp_destinationPort.txt"
udp_payload_length_file="$output_dir/udp_payloadLength.txt"
udp_all_info_file="$output_dir/udp_all.txt"

# creating output files for ARP
arp_timestamp_file="$output_dir/arp_timestamp.txt"
arp_type_file="$output_dir/arp_type.txt"
arp_source_ip_file="$output_dir/arp_sourceIP.txt"
arp_receiver_ip_file="$output_dir/arp_receiverIP.txt"
arp_receiver_mac_file="$output_dir/arp_receiverMAC.txt"
arp_payload_length_file="$output_dir/arp_payloadLength.txt"
arp_all_info_file="$output_dir/arp_all.txt"

# creating output files for TCP
tcp_timestamp_file="$output_dir/tcp_timestamp.txt"
tcp_source_ip_file="$output_dir/tcp_sourceIP.txt"
tcp_source_port_file="$output_dir/tcp_sourcePort.txt"
tcp_dest_ip_file="$output_dir/tcp_destinationIP.txt"
tcp_dest_port_file="$output_dir/tcp_destinationPort.txt"
tcp_flag_type_file="$output_dir/tcp_flagType.txt"
tcp_payload_length_file="$output_dir/tcp_payloadLength.txt"
tcp_all_info_file="$output_dir/tcp_all.txt"

# clearing the content of the output files
> "$udp_timestamp_file"
> "$udp_source_ip_file"
> "$udp_source_port_file"
> "$udp_dest_ip_file"
> "$udp_dest_port_file"
> "$udp_payload_length_file"
> "$udp_all_info_file"

# heading for the all info file
echo -e "Timestamp\tSource IP\tSource Port No.\tDestination IP\tDestination Port No.\tPayload Length" >> "$udp_all_info_file"

# while loop to process each line of the UDP input file
while IFS= read -r line; do
    # Extract the required fields using awk
    timestamp=$(echo "$line" | awk '{print $1}')
    source_ip=$(echo "$line" | awk '{print $3}' | cut -d'.' -f1-4)
    source_port=$(echo "$line" | awk '{print $3}' | cut -d'.' -f5 | tr -d ':')
    destination_ip=$(echo "$line" | awk '{print $5}' | cut -d'.' -f1-4)
    destination_port=$(echo "$line" | awk '{print $5}' | cut -d'.' -f5 | tr -d ':')
    payload_length=$(echo "$line" | awk '{print $NF}')

    # Save each field into the respective files
    echo "$timestamp" >> "$udp_timestamp_file"
    echo "$source_ip" >> "$udp_source_ip_file"
    echo "$source_port" >> "$udp_source_port_file"
    echo "$destination_ip" >> "$udp_dest_ip_file"
    echo "$destination_port" >> "$udp_dest_port_file"
    echo "$payload_length" >> "$udp_payload_length_file"
    
    # Save all information into the all_info_file
    echo -e "$timestamp\t$source_ip\t$source_port\t$destination_ip\t$destination_port\t$payload_length" >> "$udp_all_info_file"
done < "$udp_input_file"

echo "UDP Data extracted to files successfully."

# clearing the content of the output files
> "$arp_timestamp_file"
> "$arp_type_file"
> "$arp_source_ip_file"
> "$arp_receiver_ip_file"
> "$arp_receiver_mac_file"
> "$arp_payload_length_file"
> "$arp_all_info_file"

# heading for the all info file
echo -e "Timestamp\tType\tSource IP\tReceiver IP\tReceiver MAC\tPayload Length" >> "$arp_all_info_file"

# while loop to process each line of the ARP input file
while IFS= read -r line; do
    timestamp=$(echo "$line" | awk '{print $1}')
    type=$(echo "$line" | awk '{print $3}' | tr -d ',')
    source_ip=$(echo "$line" | awk -F'tell ' '{print $2}' | awk '{print $1}' | tr -d ',')
    receiver_ip=$(echo "$line" | awk -F'who-has ' '{print $2}' | awk '{print $1}' | tr -d ',')
    receiver_mac=$(echo "$line" | awk -F'(' '{print $2}' | awk -F')' '{print $1}' | tr -d ',')
    payload_length=$(echo "$line" | awk '{print $NF}')

    # Handle receiver IP and MAC for Replies
    if [[ "$type" == "Reply" ]]; then
        receiver_ip=$(echo "$line" | awk -F'Reply ' '{print $2}' | awk '{print $1}')
        receiver_mac=$(echo "$line" | awk -F'is-at ' '{print $2}' | awk -F',' '{print $1}')
    fi

    # Save each field into the respective files
    echo "$timestamp" >> "$arp_timestamp_file"
    echo "$type" >> "$arp_type_file"
    echo "$source_ip" >> "$arp_source_ip_file"
    echo "$receiver_ip" >> "$arp_receiver_ip_file"
    echo "$receiver_mac" >> "$arp_receiver_mac_file"
    echo "$payload_length" >> "$arp_payload_length_file"
    
    echo -e "$timestamp\t$type\t$source_ip\t$receiver_ip\t$receiver_mac\t$payload_length" >> "$arp_all_info_file"
done < "$arp_input_file"

echo "ARP Data extracted to files successfully."


# clearing the content of the output files
> "$tcp_timestamp_file"
> "$tcp_source_ip_file"
> "$tcp_source_port_file"
> "$tcp_dest_ip_file"
> "$tcp_dest_port_file"
> "$tcp_flag_type_file"
> "$tcp_payload_length_file"
> "$tcp_all_info_file"

# heading for the all info file
echo -e "Timestamp\tSource IP\tSource Port No.\tDestination IP\tDestination Port No.\tFlag Type\tPayload Length" >> "$tcp_all_info_file"

# while loop will process each line of the input TCP file
while IFS= read -r line; do
    
    timestamp=$(echo "$line" | awk '{print $1}')
    source_ip=$(echo "$line" | awk '{print $3}' | cut -d'.' -f1-4)
    source_port=$(echo "$line" | awk '{print $3}' | cut -d'.' -f5 | tr -d ':')
    destination_ip=$(echo "$line" | awk '{print $5}' | cut -d'.' -f1-4)
    destination_port=$(echo "$line" | awk '{print $5}' | cut -d'.' -f5 | tr -d ':')
    flag_type=$(echo "$line" | awk '{print $6}' | tr -d '[]')
    payload_length=$(echo "$line" | awk '{print $NF}')

	# Handle different format when the flag type is "Flags"
	if [[ "$flag_type" == "Flags" ]]; then
		flag_type=$(echo "$line" | awk '{print $7}' | tr -d '[],')
	fi

    # Save each field into the respective files
    echo "$timestamp" >> "$tcp_timestamp_file"
    echo "$source_ip" >> "$tcp_source_ip_file"
    echo "$source_port" >> "$tcp_source_port_file"
    echo "$destination_ip" >> "$tcp_dest_ip_file"
    echo "$destination_port" >> "$tcp_dest_port_file"
    echo "$flag_type" >> "$tcp_flag_type_file"
    echo "$payload_length" >> "$tcp_payload_length_file"
    
    # Save all information into the all_info_file
    echo -e "$timestamp\t$source_ip\t$source_port \t$destination_ip\t$destination_port\t$flag_type\t$payload_length" >> "$tcp_all_info_file"
done < "$tcp_input_file"

echo "TCP Data extracted to files successfully."

