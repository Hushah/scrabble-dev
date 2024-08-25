/*
    This function is called every single time the user types in the input field
    It sends a GET request to the server with the current value of the input field
    The server responds with a JSON object containing the cleaned rack (non alpha chars stripped) and the results table
    The cleaned rack is displayed in the rackDisplayContainer
    The results table is displayed in the results div
*/
function updateResults() {
    // Get the value of the input field
    const rack = document.getElementById('rackInput').value;

    // Create a new XMLHttpRequest object
    const xhr = new XMLHttpRequest();

    // Configure it: GET-request for the URL /index.php with the rack parameter
    xhr.open('GET', 'index.php?rack=' + encodeURIComponent(rack), true);

    // Set up a function to handle the response
    xhr.onload = function () {
        // If the request was successful
        if (xhr.status >= 200 && xhr.status < 300) {
            // Parse the JSON response
            const response = JSON.parse(xhr.responseText);

            // Variable to store the rackDisplay element
            const rackDisplayElement = document.querySelector('.rackDisplay'); 
            // Variable to store the tableDisplay element
            const tableDisplayElement = document.querySelector('.tableContainer');

            // If rack is not empty, display it
            if(response.cleanedRack){
                // convert rack to lowercase and assign to a new variable
                // Converting the rack to lowercase because the specific font used needs to be lowercase
                const cleanedRackLower = response.cleanedRack.toLowerCase();

                // Insert the cleaned rack in the rackDisplayContainer
                document.getElementById('rackDisplayContainer').innerHTML = '<p>RACK: <span class="scramble-font">' + cleanedRackLower + '</span></p>';

                
                // Add animation class to rackDisplay
                rackDisplayElement.classList.remove('popAway'); // Reset animation
                void rackDisplayElement.offsetWidth; // Trigger reflow
                rackDisplayElement.classList.add('popOut'); // Start animation

                // Make div visible by increasing the opacity
                document.querySelector('.rackDisplay').style.opacity = 1;

            } else { // If rack is empty, clear the rackDisplayContainer
                // Add animation class to rackDisplay
                rackDisplayElement.classList.remove('popOut'); // Reset animation
                void rackDisplayElement.offsetWidth; // Trigger reflow
                rackDisplayElement.classList.add('popAway'); // Start animation

                // Wait for 0.5s before clearing the rackDisplayContainer
                setTimeout(() => {
                    document.getElementById('rackDisplayContainer').innerHTML = '';
                }, 600);
                
                // Make div invisible by reducing the opacity
                rackDisplayElement.style.opacity = 0;
            }

            // Insert the results table into the results div
            document.getElementById('results').innerHTML = response.resultsTable;

            // Check if results table is empty
            if (document.getElementById('results').innerHTML === "") {
                /* !!! BUG:
                 * When the page loads for the first time, and the user inputs something into the rack
                 * the table seems to display the popOut animation, quickly followed by the popAway animation
                 * despite the table being empty. After this, it works as expected.
                 * Can't seem to find a fix for this.
                */

                // Add animation class to tableDisplay
                tableDisplayElement.classList.remove('popOut'); // Reset animation
                void tableDisplayElement.offsetWidth; // Trigger reflow
                tableDisplayElement.classList.add('popAway'); // Start animation
                
                // Wait for 0.6s for the animation to end before continuing
                setTimeout(() => {
                    // Make div invisible by reducing the opacity
                    tableDisplayElement.style.opacity = 0;
                }, 600);

            } else { // If results table is not empty
                // Add animation class to tableDisplay
                tableDisplayElement.classList.remove('popAway'); // Reset animation
                void tableDisplayElement.offsetWidth; // Trigger reflow
                tableDisplayElement.classList.add('popOut'); // Start animation

                // Wait for 0.6s for the animation to end before continuing
                setTimeout(() => {
                    // Make div invisible by reducing the opacity
                    tableDisplayElement.style.opacity = 1;
                }, 600);
            }

        } else { // If the request failed
            console.error('Request failed with status ' + xhr.status);
        }
    };

    // Send the request
    xhr.send();
}
