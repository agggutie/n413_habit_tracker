$(document).ready(function() {
    // Function to load content based on hash
    function loadContent() {
        // Extract hash fragment from URL
        var hash = window.location.hash;
        console.log('Hash:', hash);

        // Default to home if no hash or hash is empty
        if (!hash || hash === '#') {
            hash = '#home';
        }

        // Remove the '#' symbol
        var target = hash.substring(1);

        // Load content based on hash
        console.log('Target:', target);

        // Define a mapping of hash values to corresponding file paths
        var pathMap = {
            'home': 'pages/home/',
            'login': 'pages/login/',
            'register': 'pages/register/',
            'display': 'pages/display/',
            'create': 'pages/create/',
            'progress': 'pages/progress/',
            // Add more mappings for other navigation links as needed
        };

        // Check if the target is in the mapping
        if (target in pathMap) {
            var pathToPage = pathMap[target];
            $('#content').load(pathToPage + target + '.html');
        } else {
            // Handle unknown hash values (e.g., show a 404 page)
            console.log('Unknown hash:', target);
        }
    }

    // Load content on initial page load
    loadContent();

    // Re-load content whenever the hash fragment changes in the URL
    $(window).on('hashchange', function() {
        // Load content based on new hash
        loadContent();
    });

});


