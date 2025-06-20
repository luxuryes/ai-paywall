document.addEventListener('DOMContentLoaded', function () {
    // Find the placeholder div we created in our PHP file.
    const placeholder = document.getElementById('ai-paywall-content');

    // If the placeholder exists on the page...
    if (placeholder) {
        // Get the post ID from the 'data-postid' attribute.
        const postId = placeholder.getAttribute('data-postid');

        if (postId) {
            // Construct the full API URL using the data passed from PHP.
            const apiUrl = aiPaywall.rest_url + postId;

            // Use the fetch API to get the hidden content.
            fetch(apiUrl, {
                headers: {
                    // Send the nonce for security (optional but good practice).
                    'X-WP-Nonce': aiPaywall.nonce
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // If the fetch is successful, replace the placeholder's
                // content with the full content from the API.
                if (data.content) {
                    placeholder.innerHTML = data.content;
                } else {
                    placeholder.innerHTML = '<p><em>Error: Could not load the full article.</em></p>';
                }
            })
            .catch(error => {
                console.error('AI Paywall Error:', error);
                placeholder.innerHTML = '<p><em>Sorry, the rest of this article could not be loaded.</em></p>';
            });
        }
    }
});
