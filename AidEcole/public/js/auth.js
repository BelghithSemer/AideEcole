document.getElementById('login-form').addEventListener('submit', function (event) {
    event.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    fetch('/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email, password }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.token) {
            localStorage.setItem('jwt_token', data.token); // Save the JWT token
            console.log('Login successful');
        } else {
            console.error('Login failed:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});