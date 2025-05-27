<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        input {
            padding: 8px;
            font-size: 16px;
        }
        button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        #registerMessage {
            margin-top: 15px;
            padding: 10px;
            border-radius: 4px;
        }
        .error {
            background-color: #ffebee;
            color: #d32f2f;
        }
        .success {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
    </style>
</head>
<body>
    <h2>Register</h2>
    <form id="registerForm">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
        <button type="submit">Register</button>
    </form>
    <div id="message"></div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());
        
        // Client-side validation
        if (data.password !== data.confirm_password) {
            document.getElementById('message').innerText = 'Passwords do not match!';
            document.getElementById('message').style.color = 'red';
            return;
        }

        // Remove confirm_password before sending to server
        delete data.confirm_password;

        fetch('/mvc-main/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(async response => {
            const result = await response.json();
            if (response.ok) {
                document.getElementById('message').innerText = result.message;
                document.getElementById('message').style.color = 'green';
                setTimeout(() => {
                    window.location.href = result.redirect || '/mvc-main/login';
                }, 1500); // Show message for 1.5 sec before redirect
            } else {
                document.getElementById('message').innerText = result.error || 'Registration failed';
                document.getElementById('message').style.color = 'red';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('message').innerText = 'An error occurred during registration';
            document.getElementById('message').style.color = 'red';
        });
    }); 
    </script>
</body>
</html>