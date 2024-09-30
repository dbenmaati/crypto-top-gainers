let max_items    = CpcprefCryptoGainer.max_items;
let text_color   = CpcprefCryptoGainer.text_color;
let item_padding = CpcprefCryptoGainer.item_padding;

// Fetch the data from CoinCap API
fetch('https://api.coincap.io/v2/assets?limit=200')
    .then(response => response.json())
    .then(data => {
        const assets = data.data;

        // Sort top gainers by changePercent24Hr (descending order)
        const topGainers = assets
            .filter(asset => parseFloat(asset.changePercent24Hr) > 0) // Only positive gains
            .sort((a, b) => parseFloat(b.changePercent24Hr) - parseFloat(a.changePercent24Hr))
            .slice(0, max_items); // Limit to top 5 gainers

        // Sort top losers by changePercent24Hr (ascending order)
        const topLosers = assets
            .filter(asset => parseFloat(asset.changePercent24Hr) < 0) // Only negative changes
            .sort((a, b) => parseFloat(a.changePercent24Hr) - parseFloat(b.changePercent24Hr))
            .slice(0, max_items); // Limit to top 5 losers

        // Function to generate list items for gainers or losers
        function createListItem(asset, index) {
            return `
                <li class="cpcpref-crypto-item" style="padding: ${item_padding}px 0;">
                    <span class="cpcpref-crypto-price" style="color: ${text_color};">${index + 1}</span>
                    
                    <img 
                        src="${CpcprefCryptoGainer.img_url}${asset.id}-logo.png" 
                        alt="${asset.name}" 
                        class="cpcpref-crypto-logo"
                        onerror="this.onerror=null; this.src='${CpcprefCryptoGainer.img_url}default.jpg';"
                    >
                    
                    <span class="cpcpref-crypto-name" style="color: ${text_color};">${asset.name}</span>
                    <span class="cpcpref-crypto-price" style="color: ${text_color};">$${parseFloat(asset.priceUsd).toLocaleString()}</span>
                    <span class="cpcpref-crypto-percent ${parseFloat(asset.changePercent24Hr) > 0 ? 'cpcpref-gain' : 'cpcpref-loss'}">${parseFloat(asset.changePercent24Hr).toFixed(0)}%</span>
                </li>
            `;
        }

        // Display top gainers in the list
        const gainersListElement = document.getElementById('cpcpref-top-gainers');
        if (gainersListElement) {
            topGainers.forEach((asset, index) => {
                gainersListElement.innerHTML += createListItem(asset, index);
            });
        }

        // Display top losers in the list
        const losersListElement = document.getElementById('cpcpref-top-losers');
        if (losersListElement) {
            topLosers.forEach((asset, index) => {
                losersListElement.innerHTML += createListItem(asset, index);
            });
        }

    })
    .catch(error => {
        console.error('Error fetching data:', error);
    });
