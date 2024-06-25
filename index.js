document.getElementById('bmiForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const height = document.getElementById('height').value;
    const weight = document.getElementById('weight').value;

    fetch('http://43.205.143.131:5000/', {
        method: 'POST',
        mode: 'no-cors',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ height: height, weight: weight }),
    })
    .then(response => {
        // In no-cors mode, you can't access the response body directly.
        if (response.ok) {
            console.log('Request succeeded');
        } else {
            console.error('Request failed');
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
});
