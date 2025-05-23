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
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Register</button>
    </form>
    <div id="registerMessage"></div>

    <script>
        document.getElementById("registerForm").addEventListener("submit", async function (e) {
    e.preventDefault();
    
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const messageEl = document.getElementById("registerMessage");
    
    // Clear previous messages
    messageEl.textContent = '';
    messageEl.className = '';
            function showMessage(message, type) {
            const messageEl = document.getElementById("registerMessage");
            messageEl.textContent = message;
            messageEl.className = type;
        }
    // try {
        const response = await fetch("/mvc-main/register", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ email, password })
        });
        
        // First check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            throw new Error(`Server returned: ${response.status} - ${text}`);
        }
        
        const data = await response.json();
        
        if (response.ok) {
            throw new Error(data.success || 'Registration Successfull');
        }
        
        if (data.token) {
            // Store token and user data
            localStorage.setItem("authToken", data.token);
            if (data.user) {
                localStorage.setItem("user", JSON.stringify(data.user));
            }
            
            // Show success message
            showMessage("Registration successful! Redirecting to dashboard...", "success");
            
            // Redirect after delay
            setTimeout(() => {
                window.location.href = "/mvc-main/dashboard";
            }, 1500);
        } else {
            throw new Error('Registration failed - no token received');
        }
    // } catch (error) {
    //     // Handle different error types
    //     let errorMsg = error.message;
        
    //     // If it's a SyntaxError from JSON parsing
    //     if (error instanceof SyntaxError) {
    //         errorMsg = "Server returned invalid response";
    //     }
        
    //     showMessage(errorMsg, "error");
    //     console.error("Registration error:", error);
    // }
});
    </script>
</body>
</html>