<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>success page</title>
    <link rel="stylesheet" href="style.css">
   
</head>
<body>
	<div class="successhead">
	<h1><span>Network Monitoring Tool</span></h1>
   	</div>
	
	<form id="data-form">
	
   <div class="top-bar">
	
		<div class="radio-buttons">
		
                    <input type="radio" id="arpradio" name="protocol" value="ARP" onclick="populateDropdown('ARP')"><label for="arpradio">ARP</label>
                    
                    <input type="radio" id="tcpradio" name="protocol" value="TCP" onclick="populateDropdown('TCP')"><label for="tcpradio">TCP</label>
                
                    <input type="radio" id="udpradio" name="protocol" value="UDP" onclick="populateDropdown('UDP')"><label for="udpradio">UDP</label>
                
                </div>
	        
	        <select id="dropdown-menu" class="dropdown-menu" name="data-type">
	        	<option value="">Select Data Type</option>
	        </select>
		<button type="submit" class="btn-submit-button">Submit</button>
			            
            	</div>
        </form>    
    	<div id="table-container"></div>
            
            <div class="some-block">
           
            <a href="admin_page.php" class="btn">Back to Admin Page</a>
        </div>

    <script>
        

        
        document.addEventListener('DOMContentLoaded', function() {
            const protocolRadios = document.querySelectorAll('input[name="protocol"]');
            const dropdown = document.getElementById('dropdown-menu');
            const dataOptions = {
                ARP: ["Timestamp", "Source IP", "Receiver IP", "Receiver MAC", "ARP Type", "Payload Length", "All ARP Data"],
                TCP: ["Timestamp", "Source IP", "Source Port Number", "Destination IP", "Destination Port Number", "Flag Type", "Payload Length", "All TCP Data"],
                UDP: ["Timestamp", "Source IP", "Source Port Number", "Destination IP", "Destination Port Number", "Payload Length", "All UDP Data"]
            };

            protocolRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    populateDropdown(this.value);
                });
            });

            function populateDropdown(protocol) {
                dropdown.innerHTML = ''; // Clear existing options

                const defaultOption = document.createElement('option');
                defaultOption.value = "";
                defaultOption.textContent = "Select Data Type";
                dropdown.appendChild(defaultOption);

                if (dataOptions[protocol]) {
                    dataOptions[protocol].forEach(option => {
                        const opt = document.createElement('option');
                        opt.value = option;
                        opt.textContent = option;
                        dropdown.appendChild(opt);
                    });
                }
            }

            document.getElementById('data-form').addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(this);

                fetch('fetch_fileData.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('table-container').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
</body>
</html>

