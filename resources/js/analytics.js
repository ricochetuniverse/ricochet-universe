const script = document.createElement('script');
script.async = true;
script.src = 'https://www.googletagmanager.com/gtag/js?id=UA-123412242-1';

const first = document.getElementsByTagName('script')[0];
first.parentNode.insertBefore(script, first);

// The rest...
window.dataLayer = window.dataLayer || [];
function gtag() {
    dataLayer.push(arguments);
}
gtag('js', new Date());

gtag('config', 'UA-123412242-1');
